<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mileage Calculator</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="{{asset('custom/css/mileage/mycss.css')}}" rel="stylesheet">
</head>
<body>
    <div class="container-fluid mt-3">
        <header class="fixed-header">
            {{-- 
               <img src="{{asset('home/assets/img/logo.png')}}" alt="Logo" class="logo">
            --}} 
           
            <span class="logo d-flex align-items-center scrollto me-auto me-lg-0">
                <!-- Uncomment the line below if you also wish to use an image logo -->
                <img src="{{asset('home/assets/img/logo.png')}}" alt=""> 
                <h3>Care Trail<span>.</span></h3>
            </span>
            <h2 class="text-center text-secondary">Careworker's Mileage Calculator</h2>
        </header>

        <div class="row">
            <div class="col-md-6 left-half">
                @include('mileage.inc.postCodesPage')
            </div>
            <div class="col-md-6 ml-auto right-half">
                @include('mileage.inc.distancesPage')
            </div>
        </div>
    </div>
    <script>
        let hereApiKey = "{{ config('care.here_api_key') }}";
    </script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.16.9/xlsx.full.min.js"></script>
    <script src="{{asset('custom/js/mileage/myjs.js')}}"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
