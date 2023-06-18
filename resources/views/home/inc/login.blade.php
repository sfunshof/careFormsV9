<div class="container" data-aos="fade-up">
    <div class="section-header">
        <h2></h2>
    </div>
    
    <div class="col-lg-6">
        <p>
            Sign in with the data you registered with during registration
        </p>
            
        <form action="{{route('login') }}" method="post">
            @csrf
            <div class="form-floating mb-3">
                <input type="email" class="form-control noShadow   @error('email') is-invalid @enderror  " name="email"   value="{{old('email')}}"   id="email" placeholder="email@yourcompany.com">
                <label for="email">Username</label>
                @error('email')
                    <span class="invalid-feedback is-invalid" role="alert">
                        <strong> {{ $message}} </strong>
                    </span>   
                @enderror
            </div>
            <div class="form-floating mb-3">
                <input type="password" class="form-control noShadow   @error('password') is-invalid @enderror  " name="password"  required autocomplete="new-password"    id="password" placeholder="password">
                <label for="password">Password</label>
                @error('password')
                    <span class="invalid-feedback" role="alert">
                        <strong> {{ $message}} </strong>
                    </span>   
                @enderror
            </div>
            
            <div class="form-check mb-3">
                <input class="form-check-input" type="checkbox" id="remember_me" name="remember" >
                <label class="form-check-label" for="remember_me">  Remember me</label>
            </div>

            <p class="small">  <a class="text-primary fw-bold"  href="{{ route('password.request') }}">Forgot your password?</a>          </p>

            <div class="mt-1 pt-1 mb-1 pb-1">
                <input name="login" id="login" class="btn btn-block login-btn btn-primary"  type="Submit" value="Sign In" >
            </div>

        </form>
        
        <div class="mt-1 pt-1 mb-1 pb-1">
            <p class="mb-0  text-center">Don't have an account? <a href="{{ url('/register') }}"
                class="text-primary fw-bold">Register</a></p>
        </div>
          
    </div>   

</div>       

