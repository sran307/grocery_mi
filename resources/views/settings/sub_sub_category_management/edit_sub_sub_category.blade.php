@extends('layouts.master')
@section('title', 'Edit Sub Category')
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
                        <li class="active"><a> Edit Sub Category  </a></li>
                    </ul>
                </div>
            </div> -->

            <div class="gj_box dark">
                <header>
                    <div class="gj_icons"><i class="fa fa-edit"></i></div>
                    <h5 class="gj_heading"> Edit Sub Category  </h5>
                </header>

                <div class="col-md-12">
                    {{ Form::open(array('url' => 'edit_sub_sub_category','class'=>'gj_geneal_form','files' => true)) }}
                        @if($sub_sub_cats)
                            {{ Form::hidden('mssc_id', $sub_sub_cats->sub_sub_cat_id, array('class' => 'form-control gj_b_id')) }}
                        @endif

                        <div class="form-group">
                            {{ Form::label('main_cat_name', 'Main Category Name') }}
                            <span class="error">* 
                                @if ($errors->has('main_cat_name'))
                                    {{ $errors->first('main_cat_name') }}
                                @endif
                            </span>

                            {{ Form::text('h_main_cat_name', ($sub_sub_cats->cat_name ? $sub_sub_cats->cat_name : Input::old('main_cat_name')), array('class' => 'form-control gj_h_main_cat_name', 'disabled' ,'placeholder' => 'Enter Category Name In English')) }}
                            {{ Form::hidden('main_cat_name', ($sub_sub_cats->main_cat_name ? $sub_sub_cats->main_cat_name : 0), array('class' => 'form-control gj_main_cat_name','placeholder' => 'Enter Category Name In English')) }}
                        </div>

                        <div class="form-group">
                            {{ Form::label('sub_cat_name', 'Sub Category Name') }}
                            <span class="error">* 
                                @if ($errors->has('sub_cat_name'))
                                    {{ $errors->first('sub_cat_name') }}
                                @endif
                            </span>

                            {{ Form::text('h_sub_cat_name', ($sub_sub_cats->s_cat_name ? $sub_sub_cats->s_cat_name : Input::old('sub_cat_name')), array('class' => 'form-control gj_h_sub_cat_name', 'disabled' ,'placeholder' => 'Enter Sub Category Name In English')) }}
                            {{ Form::hidden('sub_cat_name', ($sub_sub_cats->sub_cat_name ? $sub_sub_cats->sub_cat_name : 0), array('class' => 'form-control gj_main_cat_name','placeholder' => 'Enter Sub Category Name In English')) }}
                        </div>

                        <div class="form-group">
                            {{ Form::label('sub_sub_cat_name', 'Sub Sub Category Name') }}
                            <span class="error">* 
                                @if ($errors->has('sub_sub_cat_name'))
                                    {{ $errors->first('sub_sub_cat_name') }}
                                @endif
                            </span>

                            {{ Form::text('sub_sub_cat_name', ($sub_sub_cats->sub_sub_cat_name ? $sub_sub_cats->sub_sub_cat_name : Input::old('sub_sub_cat_name')), array('class' => 'form-control gj_sub_sub_cat_name','placeholder' => 'Enter Sub Sub Category Name In English')) }}
                        </div>

                        <div class="form-group">
                            {{ Form::label('is_block', 'Category Staus') }}
                            <span class="error">* 
                                @if ($errors->has('is_block'))
                                    {{ $errors->first('is_block') }}
                                @endif
                            </span>

                            <div class="gj_py_ro_div">
                                <span class="gj_py_ro">
                                    <input type="radio" <?php if($sub_sub_cats->is_block == 1) { echo "checked"; } ?> name="is_block" value="1"> Active
                                </span>
                                <span class="gj_py_ro">
                                    <input type="radio" <?php if($sub_sub_cats->is_block == 0) { echo "checked"; } ?> name="is_block" value="0"> Deactive
                                </span>
                            </div>
                        </div>

                        <div class="gj_ban_img_whole">
                            <?php 
                            $file_path = 'images/sub_sub_cat_image';
                            ?>
                            @if(isset($sub_sub_cats))
                                @if($sub_sub_cats->sub_sub_cat_image !='')
                                <div class="form-group">
                                    {{ Form::label('current_sub_sub_cat_image', 'Current Sub Sub Category Image') }}
                                    <div class="gj_mc_div">
                                       <img src="{{ asset($file_path.'/'.$sub_sub_cats->sub_sub_cat_image)}}" class="img-responsive"> 
                                    </div>
                                    {{ Form::hidden('old_sub_sub_cat_image', ($sub_sub_cats->sub_sub_cat_image ? $sub_sub_cats->sub_sub_cat_image : ''), array('class' => 'form-control')) }}
                                </div>
                                @endif
                            @endif

                            <div class="form-group">
                                {{ Form::label('sub_sub_cat_image', 'Upload Sub Sub Category Image') }}
                                <span class="error"> 
                                    @if ($errors->has('sub_sub_cat_image'))
                                        {{ $errors->first('sub_sub_cat_image') }}
                                    @endif
                                </span>
                                <p class="gj_not" style="color:red"><em>image size must be 200 x 200 pixels</em></p>

                                <input type="file" name="sub_sub_cat_image" id="sub_sub_cat_image" accept="image/*" class="gj_sub_sub_cat_image">
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
    });
</script>
@endsection
