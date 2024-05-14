<?php

namespace App\Listeners;
 
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\DB;

class AfterRegistration
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function handle($event)
    {
        //
        $user = $event->user;
        //dd($user);

        $companyID = $user->id; // Assuming the user id is the companyID
       
        // Start a database transaction
        DB::transaction(function () use ($user, $companyID) {
            // Update the companyID in tableA
            DB::table('userstable')
                ->where('id', $user->id)
                ->update(['companyID' => $companyID]);

            // Insert data into tableB
            DB::table('companyprofiletable')->insert([
                'companyName' => $user->name,
                'companyID' => $companyID,
                'contactEmail' => $user->email,
                'smsName' => substr($user->name, 0, 11), // Get the first 11 characters of the name using MySQL's LEFT function
            ]);
        });
    }
}
