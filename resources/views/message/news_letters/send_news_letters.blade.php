@extends('layouts.master')
@section('title', 'Send News Letters')
@section('content')
<section class="gj_send_news_letters_setting">
    <div class="row gj_row">
        <div class="col-md-3 col-sm-3 col-xs-12">
            @include('layouts.message_sidebar')
        </div>

        <div class="col-md-9 col-sm-9 col-xs-12">
            <!-- <div class="row">
                <div class="col-lg-12">
                    <ul class="breadcrumb">
                        <li class=""><a> Home  </a></li>
                        <li class="active"><a> Send News Letters  </a></li>
                    </ul>
                </div>
            </div> -->

            <div class="gj_box dark">
                @if(Session::has('message'))
                    <p class="alert {{ Session::get('alert-class', 'alert-info') }}">{{ Session::get('message') }}</p>
                @endif
                <header>
                    <div class="gj_icons"><i class="fa fa-edit"></i></div>
                    <h5 class="gj_heading"> Send News Letters  </h5>
                </header>

                <div class="col-md-12">
                    {{ Form::open(array('url' => 'send_news_letters','class'=>'gj_snl_form','files' => true)) }}
                        <div class="form-group">
                            {{ Form::label('email_to', 'E-Mail To') }}
                            <span class="error">* 
                                @if ($errors->has('email_to'))
                                    {{ $errors->first('email_to') }}
                                @endif
                            </span>

                            <div class="gj_py_ro_div">
                                <span class="gj_py_ro">
                                    <input type="radio" checked name="email_to" value="1"> All Subcriber
                                </span>
                                <span class="gj_py_ro">
                                    <input type="radio" name="email_to" value="2"> Particular Subcriber
                                </span>
                                <span class="gj_py_ro">
                                    <input type="radio" name="email_to" value="3"> All Enquiries Mail
                                </span>
                                <span class="gj_py_ro">
                                    <input type="radio" name="email_to" value="4"> Particular Enquiries Mail
                                </span>
                            </div>
                        </div>

                        <div class="form-group gj_part_hide">
                            {{ Form::label('part_subs', 'Select Particular Subcriber') }}
                            <span class="error">* 
                                @if ($errors->has('part_subs'))
                                    {{ $errors->first('part_subs') }}
                                @endif
                            </span>

                            @if ($subcribers && (count($subcribers) != 0))
                                <select class="gj_part_subs form-control" name="part_subs[]" multiple="multiple">
                                    @foreach ($subcribers as $key => $value)
                                        <option value="{{$value->id}}">{{$value->email}}</option>
                                    @endforeach
                                </select>
                            @endif
                        </div>

                        <div class="form-group gj_part_enq_hide">
                            {{ Form::label('part_enq', 'Select Particular Enquiries Mail') }}
                            <span class="error">* 
                                @if ($errors->has('part_enq'))
                                    {{ $errors->first('part_enq') }}
                                @endif
                            </span>

                            @if ($contacts && (count($contacts) != 0))
                                <select class="gj_part_enq form-control" name="part_enq[]" multiple="multiple">
                                    @foreach ($contacts as $key => $value)
                                        <option value="{{$value->id}}">{{$value->contact_email}}</option>
                                    @endforeach
                                </select>
                            @endif
                        </div>

                        <div class="form-group">
                            {{ Form::label('subject', 'Subject') }}
                            <span class="error">* 
                                @if ($errors->has('subject'))
                                    {{ $errors->first('subject') }}
                                @endif
                            </span>

                            {{ Form::text('subject', Input::old('subject'), array('class' => 'form-control gj_subject','placeholder' => 'Subject in English')) }}
                        </div>

                        <div class="form-group">
                            {{ Form::label('message', 'Message') }}
                            <span class="error">* 
                                @if ($errors->has('message'))
                                    {{ $errors->first('message') }}
                                @endif
                            </span>

                            <textarea class="message" placeholder="Message ..." name="message" id="message"></textarea>
                            <p>Eg : Thanks For Your Subcribe. We Will Contact you Soon.</p>
                        </div>

                        {{ Form::button('Update', array('class' => 'btn btn-primary', 'id'=>'update')) }}

                    {{ Form::close() }}
                </div>
            </div>
        </div>
    </div>
</section>


<link rel="stylesheet" type="text/css" href="{{ asset('css/editor.css')}}">
<script src="{{ asset('js/editor.js')}}"></script>

<script>
    $(document).ready(function() { 
        $('p.alert').delay(5000).slideUp(500); 
        $("#message").Editor();
        $(".gj_part_subs").select2();
        $(".gj_part_enq").select2();

        if($("input[name='email_to']").val() == 2) {
            $('.gj_part_hide').show();
            $('.gj_part_enq_hide').hide();
        } else if($("input[name='email_to']").val() == 3) {
            $('.gj_part_hide').hide();
            $('.gj_part_enq_hide').hide();
        } else if($("input[name='email_to']").val() == 4) {
            $('.gj_part_hide').hide();
            $('.gj_part_enq_hide').show();
        } else {
            $('.gj_part_hide').hide();
            $('.gj_part_enq_hide').hide();
        }
    });

    $("input[name='email_to']").on('change',function(){
        if($(this).val() == 2) {
            $('.gj_part_hide').show();
            $('.gj_part_enq_hide').hide();
        } else if($(this).val() == 3) {
            $('.gj_part_hide').hide();
            $('.gj_part_enq_hide').hide();
        } else if($(this).val() == 4) {
            $('.gj_part_hide').hide();
            $('.gj_part_enq_hide').show();
        } else {
            $('.gj_part_hide').hide();
            $('.gj_part_enq_hide').hide();
        } 
    });

    $('#update').on('click',function(){
        var email_to = 0;
        var subject = 0;
        var message = 0;
        var part_subs = "";
        var part_enqs = "";

        if($("input[name='email_to']:checked").val()) {
            email_to = $("input[name='email_to']:checked").val();
        }

        if($('#subject').val()) {
            subject = $('#subject').val();
        }

        if($('.gj_part_subs').val()) {
            part_subs = $('.gj_part_subs').val();
        }

        if($('.gj_part_enq').val()) {
            part_enqs = $('.gj_part_enq').val();
        }

        if($('#message').Editor("getText")) {
            message = $('#message').Editor("getText");
        }
        if((subject != 0) && (message != 0) && (email_to != 0)) {
            $.ajax({
                type: 'post',
                url: '{{url('/send_news_letters')}}',
                data: {subject: subject, part_subs: part_subs, part_enqs: part_enqs, message: message, email_to: email_to, type: 'send'},
                success: function(data){
                    if(data == 0){
                        window.location.href = "{{route('send_news_letters')}}";
                    } else {
                        $.confirm({
                            title: '',
                            content: 'No Action Performed!',
                            icon: 'fa fa-exclamation',
                            theme: 'modern',
                            closeIcon: true,
                            animation: 'scale',
                            type: 'purple',
                            buttons: {
                                Ok: function(){
                                    window.location.reload();
                                }
                            }
                        });
                    }
                }
            });            
        } else {
            $.confirm({
                title: '',
                content: 'Please Enter Correct Details!',
                icon: 'fa fa-exclamation',
                theme: 'modern',
                closeIcon: true,
                animation: 'scale',
                type: 'red',
                buttons: {
                    Ok: function(){
                    }
                }
            });                               
        }
    });
</script>

@endsection
