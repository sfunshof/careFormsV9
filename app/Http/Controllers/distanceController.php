<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use GuzzleHttp\Client;

class distanceController extends Controller
{
    private $apiKey;

    public function __construct()
    {
        $this->apiKey = "123"; // config('care.here_api_key'); // Initialize the API key in the constructor
    }

    public function getDistances(Request $request)
    {
        $pairs = $request->input('pairs');
        $distances = [];

        foreach ($pairs as $pair) {
            list($postcode1, $postcode2) = $pair;

            // Check if the distance is already in the database
            $distance = DB::table('postcode_distances')
                ->where('postcode1', $postcode1)
                ->where('postcode2', $postcode2)
                ->value('distance');

            if ($distance === null) {
                // Fetch distance from HERE API
                $coord1 = $this->getCoordinates($postcode1);
                $coord2 = $this->getCoordinates($postcode2);

                if ($coord1 && $coord2) {
                    $distance = $this->fetchDistanceFromAPI($coord1, $coord2);
                    if ($distance !== '---') {
                        // Store distance in database
                        DB::table('postcode_distances')->insert([
                            'postcode1' => $postcode1,
                            'postcode2' => $postcode2,
                            'distance' => $distance,
                         ]);
                    }
                } else {
                    $distance = '---';
                }
            }

            $distances[] = $distance;
        }

        return response()->json(['distances' => $distances]);
    }

    public function getCoordinates($postcode)
    {
        $response = Http::get("https://geocode.search.hereapi.com/v1/geocode", [
            'q' => "{$postcode},UK",
            'apiKey' => $this->apiKey,
        ]);

        $data = $response->json();
        return $data['items'][0]['position'] ?? null;
    }

    public function fetchDistanceFromAPI($coord1, $coord2)
    {
        $response = Http::get("https://router.hereapi.com/v8/routes", [
            'transportMode' => 'car',
            'origin' => "{$coord1['lat']},{$coord1['lng']}",
            'destination' => "{$coord2['lat']},{$coord2['lng']}",
            'return' => 'summary',
            'apiKey' => $this->apiKey,
        ]);

        $data = $response->json();
        if (isset($data['routes'][0]['sections'][0]['summary']['length'])) {
            $distanceInMeters = $data['routes'][0]['sections'][0]['summary']['length'];
            $distanceInMiles = $distanceInMeters / 1609.34;
            return round($distanceInMiles, 2);
        } else {
            return '---';
        }
    }
    public function calculateTotalDistance($postcodes)
    {
        $totalDistance = 0;
        for ($i = 0; $i < count($postcodes) - 1; $i++) {
            $postcode1 = $postcodes[$i];
            $postcode2 = $postcodes[$i + 1];
            $distance = $this->getDistanceBetweenPostcodes($postcode1, $postcode2);
            $totalDistance += ($distance === '---' ? 0 : $distance);
        }
        return $totalDistance;
    }

    public function getDistanceBetweenPostcodes($postcode1, $postcode2)
    {
        $distance = DB::table('postcode_distances')
            ->where('postcode1', $postcode1)
            ->where('postcode2', $postcode2)
            ->value('distance');

        if ($distance === null) {
            $coord1 = $this->getCoordinates($postcode1);
            $coord2 = $this->getCoordinates($postcode2);

            if ($coord1 && $coord2) {
                $distance = $this->fetchDistanceFromAPI($coord1, $coord2);
                if ($distance !== '---') {
                    DB::table('postcode_distances')->insert([
                        'postcode1' => $postcode1,
                        'postcode2' => $postcode2,
                        'distance' => $distance,
                    ]);
                }
            } else {
                $distance = '---';
            }
        }

        return $distance;
    }
    
    public function validatePostcodes(Request $request)
    {
        $postcodes = $request->input('postCodes');
        $invalidPostcodes = [];

        foreach ($postcodes as $postcode) {
            $existsInDatabase = DB::table('postcode_distances')
                ->where('postcode1', $postcode)
                ->orWhere('postcode2', $postcode)
                ->exists();

            if (!$existsInDatabase) {
                $response = Http::get('https://geocode.search.hereapi.com/v1/geocode', [
                    'q' => $postcode,
                    'apiKey' => env('HERE_API_KEY')
                ]);

                if ($response->failed() || empty($response->json()['items'])) {
                    $invalidPostcodes[] = $postcode;
                }
            }
        }
        return response()->json(['invalid_postcodes' => $invalidPostcodes]);
    }

   public  function calculateMileagePayment($businessMiles) {
        $first10kRate = 0.45; // 45p per mile for the first 10,000 miles
        $after10kRate = 0.25; // 25p per mile after 10,000 miles
    
        if ($businessMiles <= 10000) {
            return $businessMiles * $first10kRate;
        } else {
            $first10kPayment = 10000 * $first10kRate;
            $remainingMiles = $businessMiles - 10000;
            $after10kPayment = $remainingMiles * $after10kRate;
    
            return $first10kPayment + $after10kPayment;
        }
    }


}
