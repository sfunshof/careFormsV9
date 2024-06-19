@extends('backoffice.layouts.layout')
@section('title')
    Building {{ $title }}
@endsection
@section('contents')
    <section class="section">
              
        <div class="row">
            <div class="col-md-12" >
                <div id="noQuesID" class="text-success" style="display:none">
                    <p>
                        There are currently no questionnaires on this form. You may use the 
                        <span class="fw-bold text-danger"> Reset All Questions </span > button to add system inputted questions
                    </p>    
                </div>
                <div class="form-check form-switch text-right">
                    <input class="form-check-input" type="checkbox" id="buildFormFrameID" 
                           onchange="hide_buildFormFrameFunc()">
                    <label class="form-check-label" for="buildFormFrameID">Remove Build Frame</label>
                </div>
                <div id="scrollableFrame">
                    <div class="accordion accordion-flush " id="accordionBodyID">
                        {{--  This is where the form details are written to by js --}}
                    </div>
                </div>
                {{--
                <hr class="bg-danger border-2 border-top border-danger">
                --}}
                <div class="d-flex justify-content-between mt-3">
                    <div>
                        <button class="btn btn-primary" type="button" onClick="add_quesFunc(1)">Add Question</button>
                        <button class="btn btn-primary" type="button" onClick="update_formFunc()">Update Form</button>
                    </div>
                    <div>
                        <button class="btn btn-danger" type="button" onClick="reset_quesFunc()">Reset All Questions</button>
                    </div>
                </div>
            </div>
        </div>
    </section>   
 
@endsection

@section('jscontents')
    @include('backoffice.fakecomponents.ques_component')
@endsection 
