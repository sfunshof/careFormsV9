<div class="container" data-aos="fade-up">


    <div class="col-lg-6">
       @if (session('status'))
            <div class="alert alert-success" rol="alert"> 
                {{ session('status') }}
            </div>
       @endif
        <form action="{{route('verification.send') }}" method="post">
            <p>
                You must verify your email address. Please  check your email for a verification link 
            </p>
            @csrf

            <div class="mt-1 pt-1 mb-1 pb-1">
                <input name="login" id="login" class="btn btn-block login-btn btn-primary"  type="Submit" value="Resend Email" >
            </div>
        </form>
                  
    </div>      
</div>       