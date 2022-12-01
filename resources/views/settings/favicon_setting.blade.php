@extends('layouts.master')
@section('title', 'Favicon Settings')
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
                        <li class="active"><a> Favicon Settings  </a></li>
                    </ul>
                </div>
            </div> -->

            <div class="gj_box dark">
                @if(Session::has('message'))
                    <p class="alert {{ Session::get('alert-class', 'alert-info') }}">{{ Session::get('message') }}</p>
                @endif
                <header>
                    <div class="gj_icons"><i class="fa fa-edit"></i></div>
                    <h5 class="gj_heading"> Favicon Settings  </h5>
                </header>

                <div class="col-md-12">
                    {{ Form::open(array('url' => 'favicon_setting','class'=>'gj_favicon_form','files' => true)) }}
                        @if(isset($favicon))
                            {{ Form::hidden('id', ($favicon->id ? $favicon->id : ''), array('class' => 'form-control')) }}
                            <?php 
                            $file_path = 'images/favicon';
                            ?>
                            @if($favicon->favicon_image !='')
                            <div class="form-group">
                                {{ Form::label('current_favicon', 'Current Favicon') }}
                                <div class="gj_cf_div">
                                   <img src="{{ asset($file_path.'/'.$favicon->favicon_image)}}" class="img-responsive"> 
                                </div>
                                {{ Form::hidden('old_favicon_image', ($favicon->favicon_image ? $favicon->favicon_image : ''), array('class' => 'form-control')) }}
                            </div>
                            @endif
                        @else
                            {{ Form::hidden('id', Input::old('id'), array('class' => 'form-control')) }}
                        @endif

                        <div class="form-group">
                            {{ Form::label('favicon_image', 'Upload favicon Image') }}
                            <span class="error">* 
                                @if ($errors->has('favicon_image'))
                                    {{ $errors->first('favicon_image') }}
                                @endif
                            </span>
                            <p class="gj_not" style="color:red"><em>image size must be 16 x 16 pixels</em></p>

                            @if(isset($favicon))
                                <input type="file" name="favicon_image" id="favicon_image" accept="image/*" class="gj_favicon_image">
                            @else
                                <input type="file" name="favicon_image" id="favicon_image" accept="image/*" class="gj_favicon_image">
                            @endif
                        </div>

                        {{ Form::submit('Update', array('class' => 'btn btn-primary')) }}

                    {{ Form::close() }}
                </div>
            </div>
        </div>
    </div>
</section>
<script>
    $(document).ready(function() { 
        $("#country_name").select2();
        $('p.alert').delay(1000).slideUp(300); 
    });
</script>
@endsection
