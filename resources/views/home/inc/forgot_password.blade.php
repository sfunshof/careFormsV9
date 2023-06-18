<div class="container" data-aos="fade-up">

    <div class="section-header">
      <h2></h2>
      <p>Ea vitae aspernatur deserunt voluptatem impedit deserunt magnam occaecati dssumenda quas ut ad dolores adipisci aliquam.</p>
    </div>
  
    <div class="col-lg-6">
      <p> Forgot Password </p>  
       @if (session('status'))
            <div class="alert alert-success" rol="alert"> 
                {{ session('status') }}
            </div>
       @endif
       <form action="{{route('password.email')}}" method="post">
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
          <input name="login" id="login" class="btn btn-block login-btn btn-primary"  type="Submit" value="Send Password Resent link" >
          {{--
          <div class="text-center"><button type="submit" class= "btn btn-primary">Register</button></div>
          --}}
      </form>
      <div class="mt-1 pt-1 mb-1 pb-1">
        <p class="mb-0  text-center">Don't have an account? <a href="{{ url('/register') }}"
            class="text-primary fw-bold">Register</a></p>
      </div>
    
  </div>      
</div>       