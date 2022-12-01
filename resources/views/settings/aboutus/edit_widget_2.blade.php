@extends('layouts.master')
@section('title', 'Manage About Us Widget 2')
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
                    <h5 class="gj_heading"> Manage CMS About Us Widget 2 </h5>
                </header>

                <div class="col-md-12">
                    {{ Form::open(array('route' => ['update_widget_2', $data->id],'class'=>'gj_about_cms_form','files' => true)) }}
                        <input type="hidden" name="page_id" value="{{$data->id}}">
                        <div class="form-group">
                            {{ Form::label('value', 'Content') }}
                            <span class="error">* 
                                @if ($errors->has('value'))
                                    {{ $errors->first('value') }}
                                @endif
                            </span>

                            <!--{{ Form::text('content', $data->contents, Input::old('value'), array('class' => 'form-control','placeholder' => 'Enter the value')) }}-->
                            <input type="text" class="form-control" name="content" value="{{$data->contents}}" placeholder="Enter the content">
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
