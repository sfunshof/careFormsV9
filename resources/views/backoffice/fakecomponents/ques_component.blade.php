<!-- This a trick. we need the js files . The html here does not render any view but gets 
    all the php variables  The div renders nothing but then the js are loaded which is important here
-->     
<div hidden id="reset_formID"></div>     

<script>
    
    let accordionBody = document.getElementById('accordionBodyID');
    let cqcOptionsArray=@json($cqcArray);
    let quesOptionsArray=@json($quesArray);
    let formDetails= @json($forms); 
    let optionsArray=@json($options);
    let formTitle=@json($title); 
    let respTypeID={{$respTypeID}}; // formsController  This is for the different forms
    let companyID={{ $company_settings[0]->companyID }}; //No need any more can be used in the controller
    let token = "{{ csrf_token() }}";
    let update_formURL= "{{ url('buildforms/update_form')}}"; 
    let reset_formURL= "{{ url('buildforms/reset_form')}}";
     
</script>       
<script src="{{asset('custom/js/backoffice/build_formsTemplates.js')}}"></script>
<script src="{{asset('custom/js/backoffice/build_form.js')}}"></script>