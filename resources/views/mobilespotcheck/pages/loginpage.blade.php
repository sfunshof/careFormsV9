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
                        <p class="mt-4 fs-6  fst-italic  fw-lighter  text-end"> Powered by Metricsart.com</p>
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
        //when you click the back on browser stop the spiiner
        function stopSpinner(){
            document.getElementById('spinner').style.display = 'none'; 
        }
        setTimeout(stopSpinner, 3000);
    </script>
@endpush   