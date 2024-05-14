<div class="container" data-aos="fade-up">

    <div class="section-header">
      <p>
        Getting Started is free. Simply register by entering your details below
      </p>
    </div>

    <div class="col-lg-6">
      <form action="{{route('register') }}" method="post" onsubmit="showPreloader();">
          @csrf
          <div class="form-floating mb-3">
              <input type="text" class="form-control noShadow  @error('name') is-invalid @enderror  "  name="name"  value="{{old('name')}}"    id="name" placeholder="Your Comaony Name">
              <label for="name">Company Name</label>
              @error('name')
                  <span class="invalid-feedback" role="alert">
                      <strong> {{ $message}} </strong>
                  </span>   
              @enderror
          </div>
          <div class="form-floating mb-3">
              <input type="email" class="form-control noShadow   @error('email') is-invalid @enderror  " name="email"   value="{{old('email')}}"   id="email" placeholder="email@yourcompany.com">
              <label for="email">Username</label>
              @error('email')
                  <span class="invalid-feedback" role="alert">
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
          <div class="form-floating mb-3">
              <input type="password" class="form-control noShadow" name="password_confirmation"  id="password_confirmation"    required autocomplete="new-password"   placeholder="confirm password">
              <label for="password_confirmation">Confirm Password</label>
          </div>
          <input name="register" id="register" class="btn btn-block login-btn btn-primary"  type="Submit" value="Register" >
          {{--
          <div class="text-center"><button type="submit" class= "btn btn-primary">Register</button></div>
          --}}
      </form>
  </div>      
</div>       