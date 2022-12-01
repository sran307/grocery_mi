@extends('layouts.master')
@section('title', 'Add CMS Page')
@section('content')
<section class="gj_email_setting">
    <div class="row gj_row">
        <div class="col-md-3 col-sm-3 col-xs-12">
            @include('layouts.sidebar')
        </div>

        <div class="col-md-9 col-sm-9 col-xs-12">
            <!-- <div class="row">
                <div class="col-lg-12">
                    <ul class="breadcrumb">
                        <li class=""><a> Home  </a></li>
                        <li class="active"><a> Add CMS Page  </a></li>
                    </ul>
                </div>
            </div> -->

            <div class="gj_box dark">
                @if(Session::has('message'))
                    <p class="alert {{ Session::get('alert-class', 'alert-info') }}">{{ Session::get('message') }}</p>
                @endif
                <header>
                    <div class="gj_icons"><i class="fa fa-edit"></i></div>
                    <h5 class="gj_heading"> Add CMS Page  </h5>
                </header>

                <div class="col-md-12">
                    {{ Form::open(array('url' => 'add_cms_page','class'=>'gj_cms_form','files' => true)) }}
                        <div class="form-group">
                            {{ Form::label('page_name', 'Page Title') }}
                            <span class="error">* 
                                @if ($errors->has('page_name'))
                                    {{ $errors->first('page_name') }}
                                @endif
                            </span>

                            {{ Form::text('page_name', Input::old('page_name'), array('class' => 'form-control gj_page_name','placeholder' => 'Page Title in English')) }}
                        </div>

                        <div class="form-group">
                            {{ Form::label('page_description', 'Page Description') }}
                            <span class="error">* 
                                @if ($errors->has('page_description'))
                                    {{ $errors->first('page_description') }}
                                @endif
                            </span>

                            <textarea class="page_description" placeholder="Enter text ..." name="page_description" id="page_description"></textarea>
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
        $('p.alert').delay(2000).slideUp(300); 
        $("#size").select2();
        $("#page_description").Editor();
    });

    $('#update').on('click',function(){
        var page_name = 0;
        var page_description = 0;
        if($('#page_name').val()) {
            page_name = $('#page_name').val();
        }

        if($('#page_description').Editor("getText")) {
            page_description = $('#page_description').Editor("getText");
        }
        if((page_name != 0) && (page_description != 0)) {
            $.ajax({
                type: 'post',
                url: '{{url('/add_cms_page')}}',
                data: {page_name: page_name, page_description: page_description, type: 'add'},
                success: function(data){
                    if(data == 0){
                        window.location.href = "{{route('manage_cms_page')}}";
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
                        window.location.reload();
                    }
                }
            });                           
        }
    });
</script>

@endsection
