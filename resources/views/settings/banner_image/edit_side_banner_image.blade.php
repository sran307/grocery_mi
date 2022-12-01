@extends('layouts.master')
@section('title', 'Edit Banner Image')
@section('content')
<section class="gj_email_setting">
    <div class="row gj_row">
        <div class="col-md-3 col-sm-3 col-xs-12">
            @include('layouts.sidebar')
        </div>

        <div class="col-md-9 col-sm-9 col-xs-12">
            <div class="row">
                <div class="col-lg-12">
                    <ul class="breadcrumb">
                        <li class=""><a> Home  </a></li>
                        <li class="active"><a> Edit Side Banner Image  </a></li>
                    </ul>
                </div>
            </div>

            <div class="gj_box dark">
                <header>
                    <div class="gj_icons"><i class="fa fa-edit"></i></div>
                    <h5 class="gj_heading"> Edit Side Banner Image  </h5>
                </header>

                <div class="col-md-12">
                    {{ Form::open(array('url' => 'edit_side_banner_image','class'=>'gj_geneal_form','files' => true)) }}
                        @if($banner)
                            {{ Form::hidden('b_id', $banner->id, array('class' => 'form-control gj_b_id')) }}
                        @endif

                        <div class="form-group">
                            {{ Form::label('image_title', 'Image Title') }}
                            <span class="error">* 
                                @if ($errors->has('image_title'))
                                    {{ $errors->first('image_title') }}
                                @endif
                            </span>

                            {{ Form::text('image_title', ($banner->image_title ? $banner->image_title : Input::old('image_title')), array('class' => 'form-control gj_image_title','placeholder' => 'Banner Title in English')) }}
                        </div>

                       

                        <div class="gj_ban_img_whole">
                            <?php 
                            $file_path = 'images/banner_image';
                            ?>
                            @if(isset($banner))
                                @if($banner->banner_image !='')
                                <div class="form-group">
                                    {{ Form::label('current_noimage', 'Current Banner Image') }}
                                    <div class="gj_ni_div">
                                       <img src="{{ asset($file_path.'/'.$banner->banner_image)}}" class="img-responsive"> 
                                    </div>
                                    {{ Form::hidden('old_banner_image', ($banner->banner_image ? $banner->banner_image : ''), array('class' => 'form-control')) }}
                                </div>
                                @endif
                            @endif

                            <div class="form-group">
                                {{ Form::label('banner_image', 'Upload Banner Image') }}
                                <span class="error"> 
                                    @if ($errors->has('banner_image'))
                                        {{ $errors->first('banner_image') }}
                                    @endif
                                </span>
                                <p class="gj_not" style="color:red"><em>image size must be 270 x 220 pixels</em></p>

                                <input type="file" name="banner_image" id="banner_image" accept="image/*" class="gj_banner_image">
                            </div>
                        </div>

                        <div class="form-group">
                            {{ Form::label('redirect_url', 'Redirect URL') }}
                            <span class="error">* 
                                @if ($errors->has('redirect_url'))
                                    {{ $errors->first('redirect_url') }}
                                @endif
                            </span>

                            {{ Form::text('redirect_url', ($banner->redirect_url ? $banner->redirect_url : Input::old('redirect_url')), array('class' => 'form-control gj_redirect_url', 'placeholder' => 'Redirect URL')) }}
                            <p class="gj_ru_ex">Example : <b>http://www.google.com</b> or <b>#</b></p>
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
    });
</script>
@endsection
