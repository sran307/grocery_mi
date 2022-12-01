@extends('layouts.master')
@section('title', 'Manage About Us Widget 1')
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
                        <li class="active"><a> Add CMS About Us Page  </a></li>
                    </ul>
                </div>
            </div> -->

            <div class="gj_box dark">
                @if(Session::has('message'))
                    <p class="alert {{ Session::get('alert-class', 'alert-info') }}">{{ Session::get('message') }}</p>
                @endif
                <header>
                    <div class="gj_icons"><i class="fa fa-edit"></i></div>
                    <h5 class="gj_heading"> Manage CMS About Us Widget 1 </h5>
                </header>

                <div class="col-md-12">
                    {{ Form::open(array('route' => ['update_widget_1', $data->id],'class'=>'gj_about_cms_form','files' => true)) }}
                        <input type="hidden" name="page_id" value="{{$data->id}}">
                        <div class="form-group">
                            {{ Form::label('value', 'Add The Value') }}
                            <span class="error">* 
                                @if ($errors->has('value'))
                                    {{ $errors->first('value') }}
                                @endif
                            </span>

                        <!--{{ Form::text('value', $data->value, Input::old('value'), array('class' => 'form-control','placeholder' => 'Enter the value')) }}-->
                            <input type="text" name="value" class="form-control" value="{{$data->value}}" placeholder="Enter the value">
                        </div>

                        <div class="form-group">
                            {{ Form::label('description', 'Add Widget 1 Description') }}
                            <span class="error">* 
                                @if ($errors->has('description'))
                                    {{ $errors->first('description') }}
                                @endif
                            </span>

                            <textarea cols="100"  class='form-control' placeholder="Enter text ..." name="description" id="description">{{$data->description}}</textarea>
                        </div>

                        <div class="form-group">
                            {{ Form::label('Widget1_image', 'Upload Widget 1 Image') }}
                            <span class="error">* 
                                @if ($errors->has('widget_1_image'))
                                    {{ $errors->first('widget_1_image') }}
                                @endif
                            </span>
                            <p class="gj_not" style="color:red"><em>image size must be 60 x 60 pixels</em></p>

                            <input type="file" name="widget_1_img" id="widget_1_img" accept="image/*" class="sr_about_image">
                            <img src="{{asset('images/site_img/'.$data->image)}}" width="50px" height="50px" class="sr_about_img" alt="widget_1_img" id="widget_1_img">
                        </div>
                        {{ Form::submit('Update', array('class' => 'btn btn-primary', 'type'=>'submit')) }}

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
        $("#description").Editor("setText", "ht");
    });
</script>

@endsection
