@extends('layouts.master')
@section('title', 'Social Media Settings')
@section('content')
<section class="gj_social_setting">
    <div class="row gj_row">
        <div class="col-md-3 col-sm-3 col-xs-12">
            @include('layouts.sidebar')
        </div>

        <div class="col-md-9 col-sm-9 col-xs-12">
            <div class="row">
                <div class="col-lg-12">
                    <!-- <ul class="breadcrumb">
                        <li class=""><a> Home  </a></li>
                        <li class="active"><a> Social Media Settings  </a></li>
                    </ul> -->
                    @if(Session::has('message'))
                        <p class="alert gj_bk_alt {{ Session::get('alert-class', 'alert-info') }}">{{ Session::get('message') }}</p>
                    @endif
                </div>
            </div>

            <div class="gj_box dark">
                <header>
                    <div class="gj_icons"><i class="fa fa-edit"></i></div>
                    <h5 class="gj_heading"> Social Media Settings  </h5>
                </header>

                <div class="col-md-12">
                    {{ Form::open(array('url' => 'social_media_setting','class'=>'gj_geneal_form')) }}
                        <div class="form-group">
                            {{ Form::label('facebook_app_id', 'Facebook App ID') }}
                            <span class="error">* 
                                @if ($errors->has('facebook_app_id'))
                                    {{ $errors->first('facebook_app_id') }}
                                @endif
                            </span>
                            @if(isset($social))
                                {{ Form::hidden('id', ($social->id ? $social->id : ''), array('class' => 'form-control')) }}

                                {{ Form::text('facebook_app_id', ($social->facebook_app_id ? $social->facebook_app_id : ''), array('class' => 'form-control')) }}
                            @else
                                {{ Form::hidden('id', Input::old('id'), array('class' => 'form-control')) }}

                                {{ Form::text('facebook_app_id', Input::old('facebook_app_id'), array('class' => 'form-control')) }}
                            @endif
                        </div>

                        <div class="form-group">
                            {{ Form::label('facebook_secrect_key', 'Facebook Secrect Key') }}
                            <span class="error">* 
                                @if ($errors->has('facebook_secrect_key'))
                                    {{ $errors->first('facebook_secrect_key') }}
                                @endif
                            </span>

                            @if(isset($social))
                                {{ Form::text('facebook_secrect_key', ($social->facebook_secrect_key ? $social->facebook_secrect_key : ''), array('class' => 'form-control')) }}
                            @else
                                {{ Form::text('facebook_secrect_key', Input::old('facebook_secrect_key'), array('class' => 'form-control')) }}
                            @endif
                        </div>

                        <div class="form-group">
                            {{ Form::label('facebook_page_url', 'Facebook Page Url') }}
                            <span class="error"> 
                                @if ($errors->has('facebook_page_url'))
                                    {{ $errors->first('facebook_page_url') }}
                                @endif
                            </span>

                            @if(isset($social))
                                {{ Form::text('facebook_page_url', ($social->facebook_page_url ? $social->facebook_page_url : ''), array('class' => 'form-control')) }}
                            @else
                                {{ Form::text('facebook_page_url', Input::old('facebook_page_url'), array('class' => 'form-control')) }}
                            @endif
                        </div>

                        <div class="form-group">
                            {{ Form::label('facebook_like_url', 'Facebook Like Box Url') }}
                            <span class="error"> 
                                @if ($errors->has('facebook_like_url'))
                                    {{ $errors->first('facebook_like_url') }}
                                @endif
                            </span>

                            @if(isset($social))
                                {{ Form::text('facebook_like_url', ($social->facebook_like_url ? $social->facebook_like_url : ''), array('class' => 'form-control')) }}
                            @else
                                {{ Form::text('facebook_like_url', Input::old('facebook_like_url'), array('class' => 'form-control')) }}
                            @endif
                        </div>

                        <div class="form-group">
                            {{ Form::label('twitter_page_url', 'Twitter Page Url') }}
                            <span class="error"> 
                                @if ($errors->has('twitter_page_url'))
                                    {{ $errors->first('twitter_page_url') }}
                                @endif
                            </span>

                            @if(isset($social))
                                {{ Form::text('twitter_page_url', ($social->twitter_page_url ? $social->twitter_page_url : ''), array('class' => 'form-control')) }}
                            @else
                                {{ Form::text('twitter_page_url', Input::old('twitter_page_url'), array('class' => 'form-control')) }}
                            @endif
                        </div>

                        <div class="form-group">
                            {{ Form::label('twitter_app_id', 'Twitter App ID') }}
                            <span class="error">* 
                                @if ($errors->has('twitter_app_id'))
                                    {{ $errors->first('twitter_app_id') }}
                                @endif
                            </span>

                            @if(isset($social))
                                {{ Form::text('twitter_app_id', ($social->twitter_app_id ? $social->twitter_app_id : ''), array('class' => 'form-control')) }}
                            @else
                                {{ Form::text('twitter_app_id', Input::old('twitter_app_id'), array('class' => 'form-control')) }}
                            @endif
                        </div>

                        <div class="form-group">
                            {{ Form::label('twitter_secrect_key', 'Twitter Secrect Key') }}
                            <span class="error">* 
                                @if ($errors->has('twitter_secrect_key'))
                                    {{ $errors->first('twitter_secrect_key') }}
                                @endif
                            </span>

                            @if(isset($social))
                                {{ Form::text('twitter_secrect_key', ($social->twitter_secrect_key ? $social->twitter_secrect_key : ''), array('class' => 'form-control')) }}
                            @else
                                {{ Form::text('twitter_secrect_key', Input::old('twitter_secrect_key'), array('class' => 'form-control')) }}
                            @endif
                        </div>

                        <div class="form-group">
                            {{ Form::label('linkedin_page_url', 'Linkedin Page Url') }}
                            <span class="error"> 
                                @if ($errors->has('linkedin_page_url'))
                                    {{ $errors->first('linkedin_page_url') }}
                                @endif
                            </span>

                            @if(isset($social))
                                {{ Form::text('linkedin_page_url', ($social->linkedin_page_url ? $social->linkedin_page_url : ''), array('class' => 'form-control')) }}
                            @else
                                {{ Form::text('linkedin_page_url', Input::old('linkedin_page_url'), array('class' => 'form-control')) }}
                            @endif
                        </div>

                        <div class="form-group">
                            {{ Form::label('youtube_url', 'Youtube Url') }}
                            <span class="error"> 
                                @if ($errors->has('youtube_url'))
                                    {{ $errors->first('youtube_url') }}
                                @endif
                            </span>

                            @if(isset($social))
                                {{ Form::text('youtube_url', ($social->youtube_url ? $social->youtube_url : ''), array('class' => 'form-control')) }}
                            @else
                                {{ Form::text('youtube_url', Input::old('youtube_url'), array('class' => 'form-control')) }}
                            @endif
                        </div>

                        <div class="form-group">
                            {{ Form::label('instagram_url', 'Instagram Url') }}
                            <span class="error"> 
                                @if ($errors->has('instagram_url'))
                                    {{ $errors->first('instagram_url') }}
                                @endif
                            </span>

                            @if(isset($social))
                                {{ Form::text('instagram_url', ($social->instagram_url ? $social->instagram_url : ''), array('class' => 'form-control')) }}
                            @else
                                {{ Form::text('instagram_url', Input::old('instagram_url'), array('class' => 'form-control')) }}
                            @endif
                        </div>

                        <div class="form-group">
                            {{ Form::label('pinterest_url', 'Pinterest Url') }}
                            <span class="error"> 
                                @if ($errors->has('pinterest_url'))
                                    {{ $errors->first('pinterest_url') }}
                                @endif
                            </span>

                            @if(isset($social))
                                {{ Form::text('pinterest_url', ($social->pinterest_url ? $social->pinterest_url : ''), array('class' => 'form-control')) }}
                            @else
                                {{ Form::text('pinterest_url', Input::old('pinterest_url'), array('class' => 'form-control')) }}
                            @endif
                        </div>

                        <div class="form-group">
                            {{ Form::label('gmap_app_key', 'Gmap App Key') }}
                            <span class="error">* 
                                @if ($errors->has('gmap_app_key'))
                                    {{ $errors->first('gmap_app_key') }}
                                @endif
                            </span>

                            @if(isset($social))
                                {{ Form::text('gmap_app_key', ($social->gmap_app_key ? $social->gmap_app_key : ''), array('class' => 'form-control')) }}
                            @else
                                {{ Form::text('gmap_app_key', Input::old('gmap_app_key'), array('class' => 'form-control')) }}
                            @endif
                        </div>

                        <div class="form-group">
                            {{ Form::label('analytics_code', 'Analytics Code') }}
                            <span class="error">* 
                                @if ($errors->has('analytics_code'))
                                    {{ $errors->first('analytics_code') }}
                                @endif
                            </span>

                            @if(isset($social))
                                {{ Form::textarea('analytics_code', ($social->analytics_code ? $social->analytics_code : ''), array('class' => 'form-control')) }}
                            @else
                                {{ Form::textarea('analytics_code', Input::old('analytics_code'), array('class' => 'form-control', 'rows' => 8)) }}
                            @endif
                        </div>

                        {{ Form::submit('Update', array('class' => 'btn btn-primary')) }}

                    {{ Form::close() }}
                </div>
            </div>
        </div>
    </div>
</section>

<script type="text/javascript">
    $('p.gj_bk_alt').delay(3000).slideUp(700);
</script>
@endsection
