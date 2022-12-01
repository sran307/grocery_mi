@extends('layouts.master')
@section('title', 'Logo Settings')
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
                        <li class="active"><a> Logo Settings  </a></li>
                    </ul>
                </div>
            </div> -->

            <div class="gj_box dark">
                @if(Session::has('message'))
                    <p class="alert {{ Session::get('alert-class', 'alert-info') }}">{{ Session::get('message') }}</p>
                @endif
                <header>
                    <div class="gj_icons"><i class="fa fa-edit"></i></div>
                    <h5 class="gj_heading"> Logo Settings  </h5>
                </header>

                <div class="col-md-12">
                    {{ Form::open(array('url' => 'logo_setting','class'=>'gj_logo_form','files' => true)) }}
                        @if(isset($logo))
                            {{ Form::hidden('id', ($logo->id ? $logo->id : ''), array('class' => 'form-control')) }}
                            <?php 
                            $file_path = 'images/logo';
                            ?>
                            @if($logo->logo_image !='')
                            <div class="form-group">
                                {{ Form::label('current_logo', 'Current Logo') }}
                                <div class="gj_cl_div">
                                   <img src="{{ asset($file_path.'/'.$logo->logo_image)}}" class="img-responsive"> 
                                </div>
                                {{ Form::hidden('old_logo_image', ($logo->logo_image ? $logo->logo_image : ''), array('class' => 'form-control')) }}
                            </div>
                            @endif
                        @else
                            {{ Form::hidden('id', Input::old('id'), array('class' => 'form-control')) }}
                        @endif

                        <div class="form-group">
                            {{ Form::label('logo_image', 'Upload Logo Image') }}
                            <span class="error">* 
                                @if ($errors->has('logo_image'))
                                    {{ $errors->first('logo_image') }}
                                @endif
                            </span>
                            <p class="gj_not" style="color:red"><em>image size must be 180 x 45 pixels</em></p>

                            @if(isset($logo))
                                <input type="file" name="logo_image" id="logo_image" accept="image/*" class="gj_logo_image">
                            @else
                                <input type="file" name="logo_image" id="logo_image" accept="image/*" class="gj_logo_image">
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
