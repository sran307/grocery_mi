@extends('layouts.master')
@section('title', 'Edit Category Banner')
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
                        <li class="active"><a> Edit Category Banner  </a></li>
                    </ul>
                </div>
            </div> -->

            <div class="gj_box dark">
                <header>
                    <div class="gj_icons"><i class="fa fa-edit"></i></div>
                    <h5 class="gj_heading"> Edit Category Banner  </h5>
                </header>

                <div class="col-md-12">
                    {{ Form::open(array('url' => 'edit_category_banner','class'=>'gj_ca_form','files' => true)) }}
                        @if($cat_bans)
                            {{ Form::hidden('cb_id', $cat_bans->id, array('class' => 'form-control gj_ca_id')) }}
                        @endif

                        <div class="form-group">
                            {{ Form::label('main_cat_name', 'Main Category') }}
                            <span class="error">* 
                                @if ($errors->has('main_cat_name'))
                                    {{ $errors->first('main_cat_name') }}
                                @endif
                            </span>

                            <?php 
                                $opt = '';
                                if(($mains) && (count($mains) != 0)){
                                    foreach ($mains as $key => $value) {
                                        if($value->id == $cat_bans->main_cat_name) {
                                            $opt.='<option selected value="'.$value->id.'">'.$value->main_cat_name.'</option>';
                                        } else {
                                            $opt.='<option value="'.$value->id.'">'.$value->main_cat_name.'</option>';
                                        }
                                    }
                                } 
                            ?>
                            <select id="main_cat_name" name="main_cat_name" class="form-control">
                                <option value="0" selected disabled>Select Category</option>
                                <?php echo $opt; ?>
                            </select>
                        </div>

                        <div class="gj_ban_img_whole">
                            <?php 
                            $file_path = 'images/category_banner';
                            ?>
                            @if(isset($cat_bans))
                                @if(($cat_bans->images) && count($cat_bans->images) != 0)
                                <div class="form-group">
                                    {{ Form::label('current_c_banner_image', 'Current Category Advertisement Image') }}
                                    <div class="gj_ca_div">
                                        @foreach ($cat_bans->images as $imgs)
                                            <img src="{{ asset($file_path.'/'.$imgs->images)}}" class="img-responsive"> 
                                        @endforeach
                                    </div>
                                    {{ Form::hidden('old_c_banner_image', 1, array('class' => 'form-control')) }}
                                </div>
                                @else
                                    {{ Form::hidden('old_c_banner_image', 0, array('class' => 'form-control')) }}
                                @endif
                            @endif

                            <div class="form-group">
                                {{ Form::label('c_banner_image', 'Upload Category Advertisement Image') }}
                                <span class="error"> 
                                    @if ($errors->has('c_banner_image'))
                                        {{ $errors->first('c_banner_image') }}
                                    @endif
                                </span>
                                <p class="gj_not" style="color:red"><em>image size must be 250 x 200 pixels</em></p>

                                <input type="file" name="c_banner_image[]" id="c_banner_image" multiple accept="image/*" class="gj_c_banner_image">
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
        $('p.alert').delay(2000).slideUp(300); 
        $("#main_cat_name").select2(); 
    });
</script>
@endsection
