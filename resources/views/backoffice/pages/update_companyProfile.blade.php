@extends('backoffice.layouts.layout')
@section('title')
    Update company profile
@endsection
@section('contents')
    <section class="section">
        <div class="row">
            <div class="col-lg-9">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">
                            <span class="muted"> All fields are required </span>
                        </h5>
                        {{-- Floating Labels Form --}}
                        <form id= "addnew_formID"  class="row g-3 needs-validation" >
                            @csrf
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-floating">
                                        <input name="companyName"  type="text" class="form-control" id="companyNameID" placeholder="Company Name" value="{{ $companyProfile->companyName}}" required>
                                        <label for="companyNameID">Company Name</label>
                                    </div>
                                    <span class="text-danger companyName_err"></span>   
                                </div>
                                
                                <div class="col-md-6">
                                    <div class="form-floating">
                                        <input name="contactEmail"  type="text" class="form-control" id="contactEmailID" placeholder="Contact Email" value="{{ $companyProfile->contactEmail}}" required>
                                        <label for="contactEmailID">Contact Email</label>
                                    </div>
                                    <span class="text-danger contactEmail_err"></span>   
                                </div>
                            </div>
                            
                            <hr class="m-1 p-0 border-1 border-light">
                            
                            <div class="row">
                                <div class="col-md-5">
                                    <div class="form-floating">
                                        <input name="smsName"  type="text" class="form-control" id="smsNameID" placeholder="SMS Name" value="{{ $companyProfile->smsName}}" required>
                                        <label for="smsNameID">SMS Name</label>
                                    </div>
                                    <span class="text-danger smsName_err"></span>   
                                </div>
                                
                               <div class="col-md-7">
                                    <div class="form-floating">
                                        <textarea  name="smsPreText" class="form-control" placeholder="SMS Pre Text" id="smsPreTextID" style="height: 120px;"> {{ $companyProfile->smsPreText}}</textarea>
                                        <label for="smsPreText">SMS Pre Text</label>
                                    </div>
                                    <span class="text-danger smsPreText_err"></span>   
                                </div>
                            </div>
                            <hr class="bg-danger border-2 border-top border-primary">
                            <div class="text-right">
                                <button type="button" class="btn btn-primary"  onClick="update_companyProfileFunc()" >Update</button>
                            </div>
                        </form><!-- End floating Labels Form -->
                    </div>   
                
                </div> 
            </div>
        </div>
    </section>        
    <script>
        let token = "{{ csrf_token() }}";
        let update_companyProfileURL= "{{ url('backoffice/upate_companyprofile')}}"; 
    </script>   
@endsection    

@section('jscontents')
  {{--  This js file is used to add and update so it is callled save --}} 
  <script src="{{asset('custom/js/backoffice/update_companyProfile.js')}}"></script>
  
@endsection   
