<!DOCTYPE html>
    <html lang="en">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Carer Spot Check</title>
            <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
            <link href="{{asset('custom/css/mobilespotcheck/fnon.min.css')}}"  rel="stylesheet">
            <link href="{{asset('custom/css/mobilespotcheck/check_employee_spotcheck.css')}}"  rel="stylesheet">
        </head>
        <body>
            <div class="bg-primary text-white text-center py-2" style="position: fixed; top: 0; width: 100%; z-index: 1030;">
                <span>{{ $companyName}} Spot Check </span>
            </div>
            <main class="container mt-5">
                <div id="mainID">
                    @if ($status==0)
                        <div class="alert alert-danger" role="alert">
                            Error: Records cannot be found
                        </div>
                    @elseif ($status==1)
                        @include('backoffice.pages.check_employee_spotcheck_component')
                        
                        <!-- Spinner element -->
                        <div class="spinner-container">
                            <div id="spinner" class="spinner-border text-primary" role="status" style="display: none;">
                                <span class="visually-hidden">Loading...</span>
                            </div>
                        </div>
                        
                        <p> 
                            This is the end of the spot check. Plese make your comments and submit it
                        </p>    
                                                
                        <div class="mb-3">
                            <label for="commentsID" class="form-label">Care giver's comments</label>
                            <textarea class="form-control"   name="comments" id="commentsID" rows="3"></textarea>
                        </div>
                        <button type="btn" class="btn btn-primary mb-3 w-100" onClick="submitComments();return false">Submit</button>

                    @endif
                </div>   
            </main>

            <footer class="container-fluid mt-5 py-3 text-center bg-light">
                <p>&copy; <?php echo date("Y") ?> CareTrail</p>
            </footer>
            <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
            <script src="{{asset('custom/js/mobilespotcheck/fnon.min.js')}}"></script>
            <script src="{{asset('custom/js/mobilespotcheck/check_employee_spotcheck.js')}}"></script>
            <script>
                let keyID={{ $keyID}};
                let token = "{{ csrf_token() }}"; 
                let check_employee_spotcheckSaveURL="{{url('spotcheck/checksave')}}"; 
            </script>   
        </body>
    </html>