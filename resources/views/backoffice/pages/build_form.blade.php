@extends('backoffice.layouts.layout')
@section('title')
    Building {{ $title }}
@endsection
@section('contents')
    <section class="section">
              
        <div class="row">
            <div class="col-md-12" > 
                <div class="accordion accordion-flush " id="accordionBodyID">
                     {{--  This is where the form details are written to by js --}}
                </div>

                <hr class="bg-danger border-2 border-top border-danger">
                <div class="text-left">
                    <button class="btn btn-primary" type="button" onClick="add_quesFunc(1)">Add Question</button>
                    <button class="btn btn-primary" type="button" onClick="update_formFunc()"  >Update Form</button>
                </div>
            </div>
        </div>
    </section>   
    <script>
        const accordionBody = document.getElementById('accordionBodyID');
        const cqcOptionsArray=@json($cqcArray);
        const quesOptionsArray=@json($quesArray);
        const formDetails= @json($forms); 
        const optionsArray=@json($options);
        let formTitle=@json($title); 
        let respTypeID={{$respTypeID}}; //This is for the different forms
        let companyID={{ $company_settings[0]->companyID }}; //No need any more can be used in the controller
        let token = "{{ csrf_token() }}";
        let update_formURL= "{{ url('buildforms/update_form')}}"; 
    </script>       
@endsection

@section('jscontents')
    <script src="{{asset('custom/js/backoffice/build_formsTemplates.js')}}"></script>
    <script src="{{asset('custom/js/backoffice/build_form.js')}}"></script>
@endsection 
