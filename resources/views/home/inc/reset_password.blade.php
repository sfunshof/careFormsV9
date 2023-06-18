<div class="container" data-aos="fade-up">

    <div class="section-header">
      <h2></h2>
      <p>Ea vitae aspernatur deserunt voluptatem impedit deserunt magnam occaecati dssumenda quas ut ad dolores adipisci aliquam.</p>
    </div>
  
    <div class="col-lg-6">
       
      <p> Reset Password </p>  
      <form action="{{route('password.update')}}" method="post">
          @csrf
          <input type="hidden"  name="token"   value="{{ $request->route('token') }}" >
          <div class="form-floating mb-3">
              <input type="email" class="form-control noShadow   @error('email') is-invalid @enderror  " name="email"    value="{{ old('email', $request->email) }}" required autofocus />
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
        <div class="form-floating mb-3">
            <input type="password" class="form-control noShadow" name="password_confirmation"  id="password_confirmation"    required autocomplete="new-password"   placeholder="confirm password">
            <label for="password_confirmation">Confirm Password</label>
        </div>
          <!--
           <input name="login" id="login" class="btn btn-block login-btn btn-primary"  type="Submit" value="Update" >
          -->
          <div class="text-left">
              <button type="submit" class= "btn btn-primary">Reset Password</button>
           </div>
          
      </form>
  </div>      
</div>       