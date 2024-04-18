@extends('mobilespotcheck.layouts.layout')
@section('title')
    Spot Check Login    
@endsection
@section('contents')
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-body">
                        
                        <!-- Show spinner when form is submitted -->
                        <div id="spinner" style="display: none;">
                            <div class="text-center">
                                <div class="spinner-border" role="status">
                                    <span class="visually-hidden">Loading...</span>
                                </div>
                            </div>
                        </div>

                        <h3 class="card-title text-center">Login</h3>
                        <h6 class="text-center">Spot Checks</h6>
                        <form method="POST" action="{{ route('spotcheckloginlogic') }}" id="loginFormID">
                            @csrf
                            <div class="mb-3">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email') }}">
                                @error('email')
                                    <div class="invalid-feedback" role="alert">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label for="password" class="form-label">Password</label>
                                <input type="password" class="form-control @error('password') is-invalid @enderror" id="password" name="password">
                                @error('password')
                                    <div class="invalid-feedback" role="alert">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            @error('validates')
                                <div class="alert alert-danger" role="alert">
                                    {{ $message }} <!-- Display custom error message for email and password mismatch -->
                                </div>
                            @enderror
                           
                            <button type="submit" class="btn btn-primary w-100">Login</button>
                        </form>
                        <p class="mt-4 fs-6  fst-italic  fw-lighter  text-end"> Powered by CareTrail.co.uk</p>
                        {{--  IOS modal prompt --}}
                        @include('backoffice.inc.modal') 
                        
                        {{--  Adroid modal prompt  --}}
                        <div id="custom-info-bar" style="display: none;"  class="border border-success rounded p-2">
                            <p class='fs-5'>  Install this application on your home screen for quick and easy access when you are on the go </p>
                            <button   class="btn btn-success w-100"   id="custom-install-button">Install</button>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
       if (document.querySelector('.alert-danger')) {
            const errorMessage = document.querySelector('.alert-danger');
            setTimeout(function() {
                errorMessage.style.display = 'none';
            }, 2000); // Hide the error message after 2 seconds (2000 milliseconds)
        }
    </script>
@endsection

@push('scripts')
    <script>
        document.getElementById('loginFormID').addEventListener('submit', function () {
            document.getElementById('spinner').style.display = 'block';
        });
    </script>
    
    <script>
        var PWA_name="Spot Check" 
    </script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootbox.js/6.0.0/bootbox.min.js" integrity="sha512-oVbWSv2O4y1UzvExJMHaHcaib4wsBMS5tEP3/YkMP6GmkwRJAa79Jwsv+Y/w7w2Vb/98/Xhvck10LyJweB8Jsw==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script  src="{{ asset('custom/js/pwa/install.js')}}"></script>  
@endpush   

