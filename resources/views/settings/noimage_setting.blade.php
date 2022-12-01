@extends('layouts.master')
@section('title', 'Noimage Settings')
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
                        <li class="active"><a> Noimage Settings  </a></li>
                    </ul>
                </div>
            </div> -->

            <div class="gj_box dark">
                @if(Session::has('message'))
                    <p class="alert {{ Session::get('alert-class', 'alert-info') }}">{{ Session::get('message') }}</p>
                @endif
                <header>
                    <div class="gj_icons"><i class="fa fa-edit"></i></div>
                    <h5 class="gj_heading"> Noimage Settings  </h5>
                </header>

                <div class="col-md-12">
                    <?php 
                    $date = date('M-Y');
                    $file_path = 'images/noimage';
                    ?>
                    {{ Form::open(array('url' => 'noimage_setting','class'=>'gj_noimage_form','files' => true)) }}
                        <div class="gj_noimage_whole">
                            @if(isset($noimage))
                                {{ Form::hidden('id', ($noimage->id ? $noimage->id : ''), array('class' => 'form-control')) }}
                                @if($noimage->no_image !='')
                                <div class="form-group">
                                    {{ Form::label('current_noimage', 'Current No Image') }}
                                    <div class="gj_ni_div">
                                       <img src="{{ asset($file_path.'/'.$noimage->no_image)}}" class="img-responsive"> 
                                    </div>
                                    {{ Form::hidden('old_no_image', ($noimage->no_image ? $noimage->no_image : ''), array('class' => 'form-control')) }}
                                </div>
                                @endif
                            @else
                                {{ Form::hidden('id', Input::old('id'), array('class' => 'form-control')) }}
                            @endif

                            <div class="form-group">
                                {{ Form::label('no_image', 'Upload No Image') }}
                                <span class="error">* 
                                    @if ($errors->has('no_image'))
                                        {{ $errors->first('no_image') }}
                                    @endif
                                </span>
                                <p class="gj_not" style="color:red"><em>image size must be 381 x 215 pixels</em></p>

                                @if(isset($no_image))
                                    <input type="file" name="no_image" id="no_image" accept="image/*" class="gj_noimage">
                                @else
                                    <input type="file" name="no_image" id="no_image" accept="image/*" class="gj_noimage">
                                @endif
                            </div>
                        </div>

                        <div class="gj_noimage_whole">
                            @if(isset($noimage))
                                @if($noimage->profile_no_img !='')
                                <div class="form-group">
                                    {{ Form::label('current_profile_no_img', 'Current Profile No Image') }}
                                    <div class="gj_pni_div">
                                       <img src="{{ asset($file_path.'/'.$noimage->profile_no_img)}}" class="img-responsive"> 
                                    </div>
                                    {{ Form::hidden('old_profile_no_img', ($noimage->profile_no_img ? $noimage->profile_no_img : ''), array('class' => 'form-control')) }}
                                </div>
                                @endif
                            @endif

                            <div class="form-group">
                                {{ Form::label('profile_no_img', 'Upload Profile No Image') }}
                                <span class="error">* 
                                    @if ($errors->has('profile_no_img'))
                                        {{ $errors->first('profile_no_img') }}
                                    @endif
                                </span>
                                <p class="gj_not" style="color:red"><em>image size must be 250 x 200 pixels</em></p>

                                @if(isset($profile_no_img))
                                    <input type="file" name="profile_no_img" id="profile_no_img" accept="image/*" class="gj_noimage">
                                @else
                                    <input type="file" name="profile_no_img" id="profile_no_img" accept="image/*" class="gj_noimage">
                                @endif
                            </div>
                        </div>

                        <div class="gj_noimage_whole">
                            @if(isset($noimage))
                                @if($noimage->product_no_image !='')
                                <div class="form-group">
                                    {{ Form::label('current_product_no_image', 'Current Product No Image') }}
                                    <div class="gj_cbni_div">
                                       <img src="{{ asset($file_path.'/'.$noimage->product_no_image)}}" class="img-responsive"> 
                                    </div>
                                    {{ Form::hidden('old_product_no_image', ($noimage->product_no_image ? $noimage->product_no_image : ''), array('class' => 'form-control')) }}
                                </div>
                                @endif
                            @endif

                            <div class="form-group">
                                {{ Form::label('product_no_image', 'Upload Product No Image') }}
                                <span class="error">* 
                                    @if ($errors->has('product_no_image'))
                                        {{ $errors->first('product_no_image') }}
                                    @endif
                                </span>
                                <p class="gj_not" style="color:red"><em>image size must be 800 x 800 pixels</em></p>

                                @if(isset($product_no_image))
                                    <input type="file" name="product_no_image" id="product_no_image" accept="image/*" class="gj_noimage">
                                @else
                                    <input type="file" name="product_no_image" id="product_no_image" accept="image/*" class="gj_noimage">
                                @endif
                            </div>
                        </div>

                        <div class="gj_noimage_whole">
                            @if(isset($noimage))
                                @if($noimage->deal_no_image !='')
                                <div class="form-group">
                                    {{ Form::label('current_deal_no_image', 'Current Deal No Image') }}
                                    <div class="gj_pni_div">
                                       <img src="{{ asset($file_path.'/'.$noimage->deal_no_image)}}" class="img-responsive"> 
                                    </div>
                                    {{ Form::hidden('old_deal_no_image', ($noimage->deal_no_image ? $noimage->deal_no_image : ''), array('class' => 'form-control')) }}
                                </div>
                                @endif
                            @endif

                            <div class="form-group">
                                {{ Form::label('deal_no_image', 'Upload Deal No Image') }}
                                <span class="error">* 
                                    @if ($errors->has('deal_no_image'))
                                        {{ $errors->first('deal_no_image') }}
                                    @endif
                                </span>
                                <p class="gj_not" style="color:red"><em>image size must be 800 x 800 pixels</em></p>

                                @if(isset($deal_no_image))
                                    <input type="file" name="deal_no_image" id="deal_no_image" accept="image/*" class="gj_noimage">
                                @else
                                    <input type="file" name="deal_no_image" id="deal_no_image" accept="image/*" class="gj_noimage">
                                @endif
                            </div>
                        </div>

                        <div class="gj_noimage_whole">
                            @if(isset($noimage))
                                @if($noimage->stores_no_image !='')
                                <div class="form-group">
                                    {{ Form::label('current_stores_no_image', 'Current Stores No Image') }}
                                    <div class="gj_sni_div">
                                       <img src="{{ asset($file_path.'/'.$noimage->stores_no_image)}}" class="img-responsive"> 
                                    </div>
                                    {{ Form::hidden('old_stores_no_image', ($noimage->stores_no_image ? $noimage->stores_no_image : ''), array('class' => 'form-control')) }}
                                </div>
                                @endif
                            @endif

                            <div class="form-group">
                                {{ Form::label('stores_no_image', 'Upload Stores No Image') }}
                                <span class="error">* 
                                    @if ($errors->has('stores_no_image'))
                                        {{ $errors->first('stores_no_image') }}
                                    @endif
                                </span>
                                <p class="gj_not" style="color:red"><em>image size must be 455 x 378 pixels</em></p>

                                @if(isset($stores_no_image))
                                    <input type="file" name="stores_no_image" id="stores_no_image" accept="image/*" class="gj_noimage">
                                @else
                                    <input type="file" name="stores_no_image" id="stores_no_image" accept="image/*" class="gj_noimage">
                                @endif
                            </div>
                        </div>

                        <div class="gj_noimage_whole">
                            @if(isset($noimage))
                                @if($noimage->blog_banner_no_image !='')
                                <div class="form-group">
                                    {{ Form::label('current_blog_banner_no_image', 'Current Blog Banner No Image') }}
                                    <div class="gj_bbni_div">
                                       <img src="{{ asset($file_path.'/'.$noimage->blog_banner_no_image)}}" class="img-responsive"> 
                                    </div>
                                    {{ Form::hidden('old_blog_banner_no_image', ($noimage->blog_banner_no_image ? $noimage->blog_banner_no_image : ''), array('class' => 'form-control')) }}
                                </div>
                                @endif
                            @endif

                            <div class="form-group">
                                {{ Form::label('blog_banner_no_image', 'Upload Blog Banner No Image') }}
                                <span class="error">* 
                                    @if ($errors->has('blog_banner_no_image'))
                                        {{ $errors->first('blog_banner_no_image') }}
                                    @endif
                                </span>
                                <p class="gj_not" style="color:red"><em>image size must be 320 x 190 pixels</em></p>

                                @if(isset($blog_banner_no_image))
                                    <input type="file" name="blog_banner_no_image" id="blog_banner_no_image" accept="image/*" class="gj_noimage">
                                @else
                                    <input type="file" name="blog_banner_no_image" id="blog_banner_no_image" accept="image/*" class="gj_noimage">
                                @endif
                            </div>
                        </div>

                        <div class="gj_noimage_whole">
                            @if(isset($noimage))
                                @if($noimage->banner_no_image !='')
                                <div class="form-group">
                                    {{ Form::label('current_banner_no_image', 'Current Banner No Image') }}
                                    <div class="gj_bni_div">
                                       <img src="{{ asset($file_path.'/'.$noimage->banner_no_image)}}" class="img-responsive"> 
                                    </div>
                                    {{ Form::hidden('old_banner_no_image', ($noimage->banner_no_image ? $noimage->banner_no_image : ''), array('class' => 'form-control')) }}
                                </div>
                                @endif
                            @endif

                            <div class="form-group">
                                {{ Form::label('banner_no_image', 'Upload Banner No Image') }}
                                <span class="error">* 
                                    @if ($errors->has('banner_no_image'))
                                        {{ $errors->first('banner_no_image') }}
                                    @endif
                                </span>
                                <p class="gj_not" style="color:red"><em>image size must be 845 x 500 pixels</em></p>

                                @if(isset($banner_no_image))
                                    <input type="file" name="banner_no_image" id="banner_no_image" accept="image/*" class="gj_noimage">
                                @else
                                    <input type="file" name="banner_no_image" id="banner_no_image" accept="image/*" class="gj_noimage">
                                @endif
                            </div>
                        </div>

                        <div class="gj_noimage_whole">
                            @if(isset($noimage))
                                @if($noimage->category_banner_no_image !='')
                                <div class="form-group">
                                    {{ Form::label('current_category_banner_no_image', 'Current Category Banner No Image') }}
                                    <div class="gj_cbni_div">
                                       <img src="{{ asset($file_path.'/'.$noimage->category_banner_no_image)}}" class="img-responsive"> 
                                    </div>
                                    {{ Form::hidden('old_category_banner_no_image', ($noimage->category_banner_no_image ? $noimage->category_banner_no_image : ''), array('class' => 'form-control')) }}
                                </div>
                                @endif
                            @endif

                            <div class="form-group">
                                {{ Form::label('category_banner_no_image', 'Upload Category Banner No Image') }}
                                <span class="error">* 
                                    @if ($errors->has('category_banner_no_image'))
                                        {{ $errors->first('category_banner_no_image') }}
                                    @endif
                                </span>
                                <p class="gj_not" style="color:red"><em>image size must be 250 x 200 pixels</em></p>

                                @if(isset($category_banner_no_image))
                                    <input type="file" name="category_banner_no_image" id="category_banner_no_image" accept="image/*" class="gj_noimage">
                                @else
                                    <input type="file" name="category_banner_no_image" id="banner_no_image" accept="image/*" class="gj_noimage">
                                @endif
                            </div>
                        </div>

                        <div class="gj_noimage_whole">
                            @if(isset($noimage))
                                @if($noimage->ads_no_image !='')
                                <div class="form-group">
                                    {{ Form::label('current_ads_no_image', 'Current Ads No Image') }}
                                    <div class="gj_ani_div">
                                       <img src="{{ asset($file_path.'/'.$noimage->ads_no_image)}}" class="img-responsive"> 
                                    </div>
                                    {{ Form::hidden('old_ads_no_image', ($noimage->ads_no_image ? $noimage->ads_no_image : ''), array('class' => 'form-control')) }}
                                </div>
                                @endif
                            @endif

                            <div class="form-group">
                                {{ Form::label('ads_no_image', 'Upload Ads No Image') }}
                                <span class="error">* 
                                    @if ($errors->has('ads_no_image'))
                                        {{ $errors->first('ads_no_image') }}
                                    @endif
                                </span>
                                <p class="gj_not" style="color:red"><em>image size must be 800 x 400 pixels</em></p>

                                @if(isset($ads_no_image))
                                    <input type="file" name="ads_no_image" id="ads_no_image" accept="image/*" class="gj_noimage">
                                @else
                                    <input type="file" name="ads_no_image" id="banner_no_image" accept="image/*" class="gj_noimage">
                                @endif
                            </div>
                        </div>

                        <div class="gj_noimage_whole">
                            @if(isset($noimage))
                                @if($noimage->category_no_image !='')
                                <div class="form-group">
                                    {{ Form::label('current_category_no_image', 'Current Category No Image') }}
                                    <div class="gj_cni_div">
                                       <img src="{{ asset($file_path.'/'.$noimage->category_no_image)}}" class="img-responsive"> 
                                    </div>
                                    {{ Form::hidden('old_category_no_image', ($noimage->category_no_image ? $noimage->category_no_image : ''), array('class' => 'form-control')) }}
                                </div>
                                @endif
                            @endif

                            <div class="form-group">
                                {{ Form::label('category_no_image', 'Upload Category No Image') }}
                                <span class="error">* 
                                    @if ($errors->has('category_no_image'))
                                        {{ $errors->first('category_no_image') }}
                                    @endif
                                </span>
                                <p class="gj_not" style="color:red"><em>image size must be 200 x 200 pixels</em></p>

                                @if(isset($category_no_image))
                                    <input type="file" name="category_no_image" id="category_no_image" accept="image/*" class="gj_noimage">
                                @else
                                    <input type="file" name="category_no_image" id="banner_no_image" accept="image/*" class="gj_noimage">
                                @endif
                            </div>
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
