@extends('layouts.master')
@section('title', 'Edit Products')
@section('content')
<section class="gj_email_setting">
    <div class="row gj_row">
        <div class="col-md-3 col-sm-3 col-xs-12">
            @include('layouts.product_sidebar')
        </div>

        <div class="col-md-9 col-sm-9 col-xs-12">
            <!-- <div class="row">
                <div class="col-lg-12">
                    <ul class="breadcrumb">
                        <li class=""><a> Home  </a></li>
                        <li class="active"><a> Edit Products  </a></li>
                    </ul>
                </div>
            </div> -->

            <div class="gj_box dark">
                <header>
                    <div class="gj_icons"><i class="fa fa-edit"></i></div>
                    <h5 class="gj_heading"> Edit Products  </h5>
                </header>

                <div class="col-md-12">
                    {{ Form::open(array('url' => 'edit_product','class'=>'gj_product_form','files' => true)) }}
                        @if($product)
                            {{ Form::hidden('product_id', $product->id, array('class' => 'form-control gj_product_id')) }}
                        @endif

                        <div class="gj_box dark gj_inside_box">
                            <header>
                                <h5 class="gj_heading"> Products Details  </h5>
                            </header>
                            
                            <div class="col-md-12">
                                <div class="form-group">
                                    {{ Form::label('product_title', 'product Title') }}
                                    <span class="error">* 
                                        @if ($errors->has('product_title'))
                                            {{ $errors->first('product_title') }}
                                        @endif
                                    </span>

                                    {{ Form::text('product_title', ($product->product_title ? $product->product_title : Input::old('product_title')), array('class' => 'form-control gj_product_title','placeholder' => 'Enter product Title in English')) }}
                                </div>

                                <div class="form-group">
                                    {{ Form::label('product_desc', 'product Description') }}
                                    <span class="error">* 
                                        @if ($errors->has('product_desc'))
                                            {{ $errors->first('product_desc') }}
                                        @endif
                                    </span>

                                    {{ Form::textarea('product_desc', ($product->product_desc ? $product->product_desc : Input::old('product_desc')), array('class' => 'form-control gj_product_desc', 'rows' => '5', 'placeholder' => 'Enter product Description in English')) }}
                                </div>

                                <div class="form-group">
                                    {{ Form::label('brand', 'Select Brands') }}
                                    <span class="error"> 
                                        @if ($errors->has('brand'))
                                            {{ $errors->first('brand') }}
                                        @endif
                                    </span>

                                    <?php 
                                        $opt = '';
                                        $brand = \DB::table('brands')->where('is_block',1)->get();
                                        if(($brand) && (count($brand) != 0)){
                                            foreach ($brand as $key => $value) {
                                                if($product->brand == $value->id) {
                                                    $opt.='<option selected value="'.$value->id.'">'.$value->brand_name.'</option>';
                                                } else {
                                                    $opt.='<option value="'.$value->id.'">'.$value->brand_name.'</option>';
                                                }
                                            }
                                        } 
                                    ?>
                                    <select id="brand" name="brand" class="form-control">
                                        <option value="0" selected>Select Brands</option>
                                        <?php echo $opt; ?>
                                    </select>
                                </div>

                                <div class="form-group">
                                    {{ Form::label('model_no', 'Model No') }}
                                    <span class="error">
                                        @if ($errors->has('model_no'))
                                            {{ $errors->first('model_no') }}
                                        @endif
                                    </span>

                                    {{ Form::text('model_no', ($product->model_no ? $product->model_no : Input::old('model_no')), array('class' => 'form-control gj_model_no','placeholder' => 'Enter Model No')) }}
                                </div>

                                <div class="form-group">
                                    {{ Form::label('varient', 'Varient') }}
                                    <span class="error"> 
                                        @if ($errors->has('varient'))
                                            {{ $errors->first('varient') }}
                                        @endif
                                    </span>

                                    {{ Form::text('varient', ($product->varient ? $product->varient : Input::old('varient')), array('class' => 'form-control gj_varient','placeholder' => 'Enter Varient')) }}
                                </div>

                                <div class="form-group">
                                    {{ Form::label('main_cat_name', 'Select Main Category Name') }}
                                    <span class="error">* 
                                        @if ($errors->has('main_cat_name'))
                                            {{ $errors->first('main_cat_name') }}
                                        @endif
                                    </span>

                                    <?php 
                                        $opt = '';
                                        $main = \DB::table('category_management_settings')->where('is_block',1)->get();
                                        if(($main) && (count($main) != 0)){
                                            foreach ($main as $key => $value) {
                                                if($value->id == $product->main_cat_name) {
                                                    $opt.='<option selected value="'.$value->id.'">'.$value->main_cat_name.'</option>';
                                                } else {
                                                    $opt.='<option value="'.$value->id.'">'.$value->main_cat_name.'</option>';
                                                }
                                            }
                                        } 
                                    ?>
                                    <select id="main_cat_name" name="main_cat_name" class="form-control">
                                        <option value="0" selected disabled>Select Main Category</option>
                                        <?php echo $opt; ?>
                                    </select>
                                </div>

                                <div class="form-group">
                                    {{ Form::label('sub_cat_name', 'Select Sub Category Name') }}
                                    <span class="error"> 
                                        @if ($errors->has('sub_cat_name'))
                                            {{ $errors->first('sub_cat_name') }}
                                        @endif
                                    </span>

                                    <select id="sub_cat_name" name="sub_cat_name" disabled class="form-control">
                                        <option value="0" selected disabled>Select Sub Category Name</option>
                                    </select>
                                </div>

                                <div class="form-group">
                                    {{ Form::label('sub_sub_cat_name', 'Select Sub Sub Category Name') }}
                                    <span class="error">
                                        @if ($errors->has('sub_sub_cat_name'))
                                            {{ $errors->first('sub_sub_cat_name') }}
                                        @endif
                                    </span>

                                    <select id="sub_sub_cat_name" name="sub_sub_cat_name" disabled class="form-control">
                                        <option value="0" selected disabled>Select Sub Sub Category Name</option>
                                    </select>
                                </div>

                                <div class="form-group">
                                    {{ Form::label('manufacturer', 'Manufacturer') }}
                                    <span class="error">* 
                                        @if ($errors->has('manufacturer'))
                                            {{ $errors->first('manufacturer') }}
                                        @endif
                                    </span>

                                    {{ Form::text('manufacturer', ($product->manufacturer ? $product->manufacturer : Input::old('manufacturer')), array('class' => 'form-control gj_manufacturer','placeholder' => 'Enter Manufacturer')) }}
                                </div>

                                <div class="form-group">
                                    {{ Form::label('tags', 'Select Tags') }}
                                    <span class="error">* 
                                        @if ($errors->has('tags'))
                                            {{ $errors->first('tags') }}
                                        @endif
                                    </span>

                                    <?php 
                                        $opt = '';
                                        $tag = \DB::table('tags')->where('is_block',1)->get();
                                        if(($tag) && (count($tag) != 0)){
                                            foreach ($tag as $key => $value) {
                                                if($product->tags) {
                                                    $tags = json_decode($product->tags);
                                                    foreach ($tags as $keys => $values) {
                                                        if($values == $value->id) {
                                                            $opt.='<option selected value="'.$value->id.'">'.$value->tag_title.'</option>';
                                                        } else {
                                                            $opt.='<option value="'.$value->id.'">'.$value->tag_title.'</option>';
                                                        }            
                                                    }              
                                                } else {
                                                    $opt.='<option value="'.$value->id.'">'.$value->tag_title.'</option>';
                                                }
                                            }
                                        } 
                                    ?>

                                    <select id="tags" name="tags[]" class="form-control" multiple="multiple">
                                        <?php echo $opt; ?>
                                    </select>
                                </div>

                                <div class="form-group">
                                    {{ Form::label('original_price', 'Original Price') }}
                                    <span class="error">* 
                                        @if ($errors->has('original_price'))
                                            {{ $errors->first('original_price') }}
                                        @endif
                                    </span>

                                    {{ Form::text('original_price', ($product->original_price ? $product->original_price : Input::old('original_price')), array('class' => 'form-control gj_original_price','placeholder' => 'Enter Original Price')) }}
                                </div>

                                <div class="form-group">
                                    {{ Form::label('tax_type', 'Select Tax Type') }}
                                    <span class="error">* 
                                        @if ($errors->has('original_price'))
                                            {{ $errors->first('original_price') }}
                                        @endif
                                    </span>

                                    <select id="tax_type" name="tax_type" class="form-control">
                                        <option value="0" @if($product->tax_type == 0 || $product->tax_type == '') {{'selected'}} @endif>Select Tax Type</option>
                                        <option value="1" @if($product->tax_type == 1) {{'selected'}} @endif>Inclusive</option>
                                        <option value="2" @if($product->tax_type == 2) {{'selected'}} @endif>Exclusive</option>
                                    </select>
                                </div>

                                <div class="form-group">
                                    {{ Form::label('tax', 'Tax (%)') }}
                                    <span class="error">* 
                                        @if ($errors->has('tax'))
                                            {{ $errors->first('tax') }}
                                        @endif
                                    </span>

                                    <input class="form-control gj_tax" placeholder="Enter Tax in percentage" name="h_tax" disabled type="text" id="h_tax" value="<?php if($product->tax) { echo $product->tax; } else { echo 0; } ?>">
                                    {{ Form::hidden('tax', ($product->tax ? $product->tax : Input::old('tax')), array('class' => 'form-control gj_tax','placeholder' => 'Enter Tax in percentage')) }}
                                </div>

                                <div class="form-group">
                                    {{ Form::label('service_charge', 'Service Charge') }}
                                    <span class="error">* 
                                        @if ($errors->has('service_charge'))
                                            {{ $errors->first('service_charge') }}
                                        @endif
                                    </span>

                                    {{ Form::text('service_charge', ($product->service_charge ? $product->service_charge : Input::old('service_charge')), array('class' => 'form-control gj_service_charge','placeholder' => 'Enter Service Charge in percentage')) }}
                                </div>

                                <div class="form-group">
                                    {{ Form::label('discounted_price', 'Discounted Price') }}
                                    <span class="error">* 
                                        @if ($errors->has('discounted_price'))
                                            {{ $errors->first('discounted_price') }}
                                        @endif
                                    </span>

                                    {{ Form::text('discounted_price', ($product->discounted_price ? $product->discounted_price : Input::old('discounted_price')), array('class' => 'form-control gj_discounted_price','placeholder' => 'Enter Discounted Price')) }}
                                </div>

                                <div class="form-group">
                                    {{ Form::label('onhand_qty', 'On Hand Quantity') }}
                                    <span class="error">* 
                                        @if ($errors->has('onhand_qty'))
                                            {{ $errors->first('onhand_qty') }}
                                        @endif
                                    </span>

                                    {{ Form::number('onhand_qty', ($product->onhand_qty ? $product->onhand_qty : Input::old('onhand_qty')), array('class' => 'form-control gj_onhand_qty','placeholder' => 'Enter On Hand Quantity')) }}
                                </div>

                                <div class="form-group">
                                    {{ Form::label('measurement_unit', 'Select Measurement Units') }}
                                    <span class="error">* 
                                        @if ($errors->has('measurement_unit'))
                                            {{ $errors->first('measurement_unit') }}
                                        @endif
                                    </span>

                                    <?php 
                                        $opt = '';
                                        $measure = \DB::table('measurement_units')->where('is_block',1)->get();
                                        if(($measure) && (count($measure) != 0)){
                                            foreach ($measure as $key => $value) {
                                                if ($product->measurement_unit == $value->id) {
                                                    $opt.='<option selected value="'.$value->id.'">'.$value->unit_name.'</option>';
                                                } else {
                                                    $opt.='<option value="'.$value->id.'">'.$value->unit_name.'</option>';
                                                }
                                            }
                                        } 
                                    ?>

                                    <select id="measurement_unit" name="measurement_unit" class="form-control">
                                        <option value="0" selected disabled>Select Measurement Units</option>
                                        <?php echo $opt; ?>
                                    </select>
                                </div>

                                <div class="form-group">
                                    {{ Form::label('features', 'Features') }}
                                    <span class="error">* 
                                        @if ($errors->has('features'))
                                            {{ $errors->first('features') }}
                                        @endif
                                    </span>

                                    {{ Form::textarea('features', ($product->features ? $product->features : Input::old('features')), array('class' => 'form-control gj_features','placeholder' => 'Enter Features','rows'=>'5')) }}
                                </div>

                                <div class="form-group">
                                    {{ Form::label('offers_flag', 'Set Offers') }}
                                    <span class="error">* 
                                        @if ($errors->has('offers_flag'))
                                            {{ $errors->first('offers_flag') }}
                                        @endif
                                    </span>

                                    <div class="gj_py_ro_div">
                                        <span class="gj_py_ro">
                                            <input type="radio" <?php if($product->offers_flag == 1) { echo "checked"; } ?> name="offers_flag" value="1"> Active
                                        </span>
                                        <span class="gj_py_ro">
                                            <input type="radio" <?php if($product->offers_flag == 0) { echo "checked"; } ?> name="offers_flag" value="0"> Deactive
                                        </span>
                                    </div>
                                </div>

                                <div class="form-group">
                                    {{ Form::label('featuredproduct_flag', 'Featured Products') }}
                                    <span class="error">* 
                                        @if ($errors->has('featuredproduct_flag'))
                                            {{ $errors->first('featuredproduct_flag') }}
                                        @endif
                                    </span>

                                    <div class="gj_py_ro_div">
                                        <span class="gj_py_ro">
                                            <input type="radio" <?php if($product->featuredproduct_flag == 1) { echo "checked"; } ?> name="featuredproduct_flag" value="1"> Yes
                                        </span>
                                        <span class="gj_py_ro">
                                            <input type="radio" <?php if($product->featuredproduct_flag == 0) { echo "checked"; } ?> name="featuredproduct_flag" value="0"> No
                                        </span>
                                    </div>
                                </div>

                                <div class="form-group">
                                    {{ Form::label('toprated_flag', 'Top Rated') }}
                                    <span class="error">* 
                                        @if ($errors->has('toprated_flag'))
                                            {{ $errors->first('toprated_flag') }}
                                        @endif
                                    </span>

                                    <div class="gj_py_ro_div">
                                        <span class="gj_py_ro">
                                            <input type="radio" <?php if($product->toprated_flag == 1) { echo "checked"; } ?> name="toprated_flag" value="1"> Yes
                                        </span>
                                        <span class="gj_py_ro">
                                            <input type="radio" <?php if($product->toprated_flag == 0) { echo "checked"; } ?> name="toprated_flag" value="0"> No
                                        </span>
                                    </div>
                                </div>

                                <div class="form-group">
                                    {{ Form::label('delivery', 'Delivery') }}
                                    <span class="error"> 
                                        @if ($errors->has('delivery'))
                                            {{ $errors->first('delivery') }}
                                        @endif
                                    </span>

                                    {{ Form::number('delivery', ($product->delivery ? $product->delivery : Input::old('delivery')), array('class' => 'form-control gj_delivery','placeholder' => 'Enter Delivery in Days')) }}
                                </div>

                                <div class="form-group">
                                    {{ Form::label('store_name', 'Select Stores') }}
                                    <span class="error">* 
                                        @if ($errors->has('store_name'))
                                            {{ $errors->first('store_name') }}
                                        @endif
                                    </span>

                                    <?php 
                                        $opt = '';
                                        $store = \DB::table('stores')->where('is_block',1)->get();
                                        if(($store) && (count($store) != 0)){
                                            foreach ($store as $key => $value) {
                                                if ($product->store == $value->id) {
                                                    $opt.='<option selected value="'.$value->id.'">'.$value->store_name.'</option>';
                                                } else {
                                                    $opt.='<option value="'.$value->id.'">'.$value->store_name.'</option>';
                                                }
                                            }
                                        } 
                                    ?>

                                    <select id="store_name" name="store_name" class="form-control">
                                        <option value="0" selected disabled>Select Stores</option>
                                        <?php echo $opt; ?>
                                    </select>
                                </div>

                                <div class="gj_ban_img_whole">
                                    <?php 
                                    $file_path = 'images/featured_products';
                                    ?>
                                    @if(isset($product))
                                        @if($product->featured_product_img != '')
                                        <div class="form-group">
                                            {{ Form::label('current_featured_product_img', 'Current product Featured Image') }}
                                            <div class="gj_mc_div">
                                               <img src="{{ asset($file_path.'/'.$product->featured_product_img)}}" class="img-responsive"> 
                                            </div>
                                            {{ Form::hidden('old_featured_product_img', ($product->featured_product_img ? $product->featured_product_img : ''), array('class' => 'form-control')) }}
                                        </div>
                                        @endif
                                    @endif

                                    <div class="form-group">
                                        {{ Form::label('featured_product_img', 'Upload Featured Product Image') }}
                                        <span class="error">* 
                                            @if ($errors->has('featured_product_img'))
                                                {{ $errors->first('featured_product_img') }}
                                            @endif
                                        </span>
                                        <p class="gj_not" style="color:red"><em>image size must be 800 x 800 pixels</em></p>

                                        <input type="file" name="featured_product_img" id="featured_product_img" accept="image/*" class="gj_featured_product_img">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="gj_box dark gj_inside_box">
                            <header>
                                <h5 class="gj_heading"> Products Attributes  </h5>
                            </header>
                            
                            <div class="col-md-12">
                                <div class="gj_p_att_div">
                                    <div class="gj_tot_err">
                                        @if ($errors->has('attribute_name'))
                                            <p class="error"> 
                                                {{ $errors->first('attribute_name') }}
                                            </p>
                                        @endif

                                        @if ($errors->has('colors'))
                                            <p class="error"> 
                                                {{ $errors->first('colors') }}
                                            </p>
                                        @endif

                                        @if ($errors->has('sizes'))
                                            <p class="error"> 
                                                {{ $errors->first('sizes') }}
                                            </p>
                                        @endif

                                        @if ($errors->has('capacity'))
                                            <p class="error"> 
                                                {{ $errors->first('capacity') }}
                                            </p>
                                        @endif

                                        @if ($errors->has('att_price'))
                                            <p class="error"> 
                                                {{ $errors->first('att_price') }}
                                            </p>
                                        @endif

                                        @if ($errors->has('att_description'))
                                            <p class="error"> 
                                                {{ $errors->first('att_description') }}
                                            </p>
                                        @endif

                                        @if ($errors->has('att_image'))
                                            <p class="error"> 
                                                {{ $errors->first('att_image') }}
                                            </p>
                                        @endif
                                    </div>

                                    <div class="form-group">
                                        {{ Form::label('attributes_flag', 'Set Attributes') }}
                                        <span class="error">* 
                                            @if ($errors->has('attributes_flag'))
                                                {{ $errors->first('attributes_flag') }}
                                            @endif
                                        </span>

                                        <div class="gj_py_ro_div">
                                            <span class="gj_py_ro">
                                                <input type="radio" <?php if($product->attributes_flag == 1) { echo "checked"; } ?> name="attributes_flag" value="1"> Active
                                            </span>
                                            <span class="gj_py_ro">
                                                <input type="radio" <?php if($product->attributes_flag == 0) { echo "checked"; } ?> name="attributes_flag" value="0"> Deactive
                                            </span>
                                        </div>
                                    </div>

                                    <div class="gj_p_att_resp table-responsive">
                                        <table class="table table-stripped table-bordered gj_tab_att">
                                            <thead>
                                                <tr>
                                                    <th>Attribute Name</th>
                                                    <th class="gj_att_color">Color</th>
                                                    <th class="gj_att_size">Size</th>
                                                    <th class="gj_att_capacity">Capacity</th>
                                                    <th>Price</th>
                                                    <th>Description</th>
                                                    <th>Attribute Image</th>
                                                    <th>#</th>
                                                </tr>
                                            </thead>
                                            <tbody id="gj_att_bdy">
                                                <?php 
                                                $atts = "";
                                                if($product) {
                                                    $atts = \DB::table('products_attributes')->where('product_id', $product->id)->where('is_block',1)->get();

                                                    if(($atts) && (count($atts) != 0)){
                                                        foreach ($atts as $keys => $values) { ?>
                                                            <tr id="gj_tr_att_{{$keys+1}}">
                                                                <td>
                                                                    <select id="attribute_name_{{$keys+1}}" name="attribute_name[]" class="form-control gj_att_name">
                                                                        <option <?php if($values->attribute_name == 0){ echo "selected"; } ?> value="0">Select Attribute</option>
                                                                        <option <?php if($values->attribute_name == 1){ echo "selected"; } ?> value="1">Color</option>
                                                                        <option <?php if($values->attribute_name == 2){ echo "selected"; } ?> value="2">Size</option>
                                                                        <option <?php if($values->attribute_name == 3){ echo "selected"; } ?> value="3">Capacity</option>
                                                                    </select>
                                                                </td>
                                                                <td class="gj_att_color">
                                                                    <?php 
                                                                        $opt1 = '';
                                                                        $clr = \DB::table('color_settings')->where('is_block',1)->get();
                                                                        if(($clr) && (count($clr) != 0)){
                                                                            foreach ($clr as $key => $value) {
                                                                                if($value->id == $values->colors) {
                                                                                    $opt1.='<option selected value="'.$value->id.'">'.$value->color_name.'</option>';
                                                                                } else {
                                                                                    $opt1.='<option value="'.$value->id.'">'.$value->color_name.'</option>';
                                                                                }
                                                                            }
                                                                        } 
                                                                    ?>

                                                                    <select id="colors_{{$keys+1}}" name="colors[]" class="form-control gj_att_colors">
                                                                        <option value="0" selected>Select Colors</option>
                                                                        <?php echo $opt1; ?>
                                                                    </select>
                                                                </td>
                                                                <td class="gj_att_size">
                                                                    <?php 
                                                                        $opt2 = '';
                                                                        $size = \DB::table('size_settings')->where('is_block',1)->get();
                                                                        if(($size) && (count($size) != 0)){
                                                                            foreach ($size as $key => $value) {
                                                                                if($value->id == $values->sizes) {
                                                                                    $opt2.='<option selected value="'.$value->id.'">'.$value->size.'</option>';

                                                                                } else {
                                                                                    $opt2.='<option value="'.$value->id.'">'.$value->size.'</option>';
                                                                                }                       
                                                                            }
                                                                        } 
                                                                    ?>

                                                                    <select id="sizes_{{$keys+1}}" name="sizes[]" class="form-control gj_att_sizes">
                                                                        <option value="0" selected>Select Size</option>
                                                                        <?php echo $opt2; ?>
                                                                    </select>
                                                                </td>
                                                                <td class="gj_att_capacity">
                                                                    <?php 
                                                                        $opt3 = '';
                                                                        $capacity = \DB::table('capacity_settings')->where('is_block',1)->get();
                                                                        if(($capacity) && (count($capacity) != 0)){
                                                                            foreach ($capacity as $key => $value) {
                                                                                if($value->id == $values->capacity) {
                                                                                    $opt3.='<option selected value="'.$value->id.'">'.$value->capacity.'</option>';
                                                                                } else {
                                                                                    $opt3.='<option value="'.$value->id.'">'.$value->capacity.'</option>';
                                                                                }
                                                                            }
                                                                        } 
                                                                    ?>

                                                                    <select id="capacity_{{$keys+1}}" name="capacity[]" class="form-control gj_att_capacity">
                                                                        <option value="0" selected>Select Capacity</option>
                                                                        <?php echo $opt3; ?>
                                                                    </select>
                                                                </td>
                                                                <td>
                                                                    <input type="text" class="form-control gj_att_price" placeholder="Enter Price" name="att_price[]" id="price_{{$keys+1}}" value="{{$values->att_price}}">
                                                                </td>
                                                                <td>
                                                                    <textarea class="form-control gj_att_description" placeholder="Enter description" name="att_description[]" id="description_{{$keys+1}}" rows="1">{{$values->description}}</textarea>
                                                                </td>
                                                                <td>
                                                                    <?php  
                                                                        $att_file_path = 'images/attributes';
                                                                    ?>
                                                                    @if($values->image)
                                                                        <img src="{{ asset($att_file_path.'/'.$values->image)}}" class="img-responsive gj_old_att_img">
                                                                        {{ Form::hidden('old_att_image[]', $values->image, array('class' => 'form-control')) }}
                                                                    @endif
                                                                    <input type="file" name="att_image[]" id="att_image_{{$keys+1}}" accept="image/*" class="gj_att_image gj_edit_att_image form-control">
                                                                </td>
                                                                <td>
                                                                    <button type='button' id='removeButton_{{$keys+1}}' class="gj_att_rem"><i class="fa fa-trash"></i></button>
                                                                </td>
                                                            </tr>
                                                        <?php }
                                                    } else { ?>
                                                        <tr id="gj_tr_att_1">
                                                            <td>
                                                                <select id="attribute_name_1" name="attribute_name[]" class="form-control gj_att_name">
                                                                    <option value="0" selected>Select Attribute</option>
                                                                    <option value="1">Color</option>
                                                                    <option value="2">Size</option>
                                                                    <option value="3">Capacity</option>
                                                                </select>
                                                            </td>
                                                            <td class="gj_att_color">
                                                                <?php 
                                                                    $opt1 = '';
                                                                    $clr = \DB::table('color_settings')->where('is_block',1)->get();
                                                                    if(($clr) && (count($clr) != 0)){
                                                                        foreach ($clr as $key => $value) {
                                                                            $opt1.='<option value="'.$value->id.'">'.$value->color_name.'</option>';
                                                                        }
                                                                    } 
                                                                ?>

                                                                <select id="colors_1" name="colors[]" class="form-control gj_att_colors">
                                                                    <option value="0" selected>Select Color</option>
                                                                    <?php echo $opt1; ?>
                                                                </select>
                                                            </td>
                                                            <td class="gj_att_size">
                                                                <?php 
                                                                    $opt2 = '';
                                                                    $size = \DB::table('size_settings')->where('is_block',1)->get();
                                                                    if(($size) && (count($size) != 0)){
                                                                        foreach ($size as $key => $value) {
                                                                            $opt2.='<option value="'.$value->id.'">'.$value->size.'</option>';
                                                                        }
                                                                    } 
                                                                ?>

                                                                <select id="sizes_1" name="sizes[]" class="form-control gj_att_sizes">
                                                                    <option value="0" selected>Select Size</option>
                                                                    <?php echo $opt2; ?>
                                                                </select>
                                                            </td>
                                                            <td class="gj_att_capacity">
                                                                <?php 
                                                                    $opt3 = '';
                                                                    $capacity = \DB::table('capacity_settings')->where('is_block',1)->get();
                                                                    if(($capacity) && (count($capacity) != 0)){
                                                                        foreach ($capacity as $key => $value) {
                                                                            $opt3.='<option value="'.$value->id.'">'.$value->capacity.'</option>';
                                                                        }
                                                                    } 
                                                                ?>

                                                                <select id="capacity_1" name="capacity[]" class="form-control gj_att_capacity">
                                                                    <option value="0" selected>Select Capacity</option>
                                                                    <?php echo $opt3; ?>
                                                                </select>
                                                            </td>
                                                            <td>
                                                                <input type="text" class="form-control gj_att_price" placeholder="Enter Price" name="att_price[]" id="price_1">
                                                            </td>
                                                            <td>
                                                                <textarea class="form-control gj_att_description" placeholder="Enter description" name="att_description[]" id="description_1" rows="1"></textarea>
                                                            </td>
                                                            <td>
                                                                <input type="file" name="att_image[]" id="att_image_1" accept="image/*" class="gj_att_image form-control">
                                                            </td>
                                                            <td>
                                                                <button type='button' id='removeButton_1' class="gj_att_rem"><i class="fa fa-trash"></i></button>
                                                            </td>
                                                        </tr>
                                                    <?php } 
                                                } else { ?>
                                                    <tr id="gj_tr_att_1">
                                                        <td>
                                                            <select id="attribute_name_1" name="attribute_name[]" class="form-control gj_att_name">
                                                                <option value="0" selected>Select Attribute</option>
                                                                <option value="1">Color</option>
                                                                <option value="2">Size</option>
                                                                <option value="3">Capacity</option>
                                                            </select>
                                                        </td>
                                                        <td class="gj_att_color">
                                                            <?php 
                                                                $opt1 = '';
                                                                $clr = \DB::table('color_settings')->where('is_block',1)->get();
                                                                if(($clr) && (count($clr) != 0)){
                                                                    foreach ($clr as $key => $value) {
                                                                        $opt1.='<option value="'.$value->id.'">'.$value->color_name.'</option>';
                                                                    }
                                                                } 
                                                            ?>

                                                            <select id="colors_1" name="colors[]" class="form-control gj_att_colors">
                                                                <option value="0" selected>Select Color</option>
                                                                <?php echo $opt1; ?>
                                                            </select>
                                                        </td>
                                                        <td class="gj_att_size">
                                                            <?php 
                                                                $opt2 = '';
                                                                $size = \DB::table('size_settings')->where('is_block',1)->get();
                                                                if(($size) && (count($size) != 0)){
                                                                    foreach ($size as $key => $value) {
                                                                        $opt2.='<option value="'.$value->id.'">'.$value->size.'</option>';
                                                                    }
                                                                } 
                                                            ?>

                                                            <select id="sizes_1" name="sizes[]" class="form-control gj_att_sizes">
                                                                <option value="0" selected>Select Size</option>
                                                                <?php echo $opt2; ?>
                                                            </select>
                                                        </td>
                                                        <td class="gj_att_capacity">
                                                            <?php 
                                                                $opt3 = '';
                                                                $capacity = \DB::table('capacity_settings')->where('is_block',1)->get();
                                                                if(($capacity) && (count($capacity) != 0)){
                                                                    foreach ($capacity as $key => $value) {
                                                                        $opt3.='<option value="'.$value->id.'">'.$value->capacity.'</option>';
                                                                    }
                                                                } 
                                                            ?>

                                                            <select id="capacity_1" name="capacity[]" class="form-control gj_att_capacity">
                                                                <option value="0" selected>Select Capacity</option>
                                                                <?php echo $opt3; ?>
                                                            </select>
                                                        </td>
                                                        <td>
                                                            <input type="text" class="form-control gj_att_price" placeholder="Enter Price" name="att_price[]" id="price_1">
                                                        </td>
                                                        <td>
                                                            <textarea class="form-control gj_att_description" placeholder="Enter description" name="att_description[]" id="description_1" rows="1"></textarea>
                                                        </td>
                                                        <td>
                                                            <input type="file" name="att_image[]" id="att_image_1" accept="image/*" class="gj_att_image form-control">
                                                        </td>
                                                        <td>
                                                            <button type='button' id='removeButton_1' class="gj_att_rem"><i class="fa fa-trash"></i></button>
                                                        </td>
                                                    </tr>
                                                <?php } ?>
                                            </tbody>
                                        </table>

                                        <input type='button' value='Add Button' id='addButton'>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="gj_box dark gj_inside_box">
                            <header>
                                <h5 class="gj_heading"> Products Images  </h5>
                            </header>
                            
                            <div class="col-md-12">
                                <div class="gj_p_img_div">
                                    <div class="gj_tot_err">
                                        @if ($errors->has('p_name'))
                                            <p class="error"> 
                                                {{ $errors->first('p_name') }}
                                            </p>
                                        @endif

                                        @if ($errors->has('p_image'))
                                            <p class="error"> 
                                                {{ $errors->first('p_image') }}
                                            </p>
                                        @endif
                                    </div>
                                    <div class="gj_p_img_resp table-responsive">
                                        <table class="table table-stripped table-bordered gj_tab_pimg">
                                            <thead>
                                                <tr>
                                                    <th>Product Image Name</th>
                                                    <th>Product Image</th>
                                                    <th>#</th>
                                                </tr>
                                            </thead>
                                            <tbody id="gj_pimg_bdy">
                                                <?php  
                                                $p_imgs = "";
                                                if($product) {
                                                    $p_imgs = \DB::table('products_images')->where('product_id', $product->id)->where('is_block',1)->get();

                                                    if(($p_imgs) && (count($p_imgs) != 0)){
                                                        foreach ($p_imgs as $keys => $values) { ?>
                                                            <tr id="gj_tr_pimg_{{$keys+1}}">
                                                                <td>
                                                                    <input class="form-control gj_p_name" placeholder="Enter Product Name" name="p_name[]" type="text" id="p_name_{{$keys+1}}" value="{{$values->p_name}}">
                                                                </td>
                                                                <td>
                                                                    <?php  
                                                                        $pimg_file_path = 'images/products';
                                                                    ?>
                                                                    @if($values->image)
                                                                        <img src="{{ asset($pimg_file_path.'/'.$values->image)}}" class="img-responsive gj_old_prod_img"> 
                                                                        {{ Form::hidden('old_p_image[]', $values->image, array('class' => 'form-control')) }}
                                                                    @endif
                                                                    <input type="file" name="p_image[]" id="p_image_{{$keys+1}}" accept="image/*" class="gj_p_image gj_edit_p_image form-control">
                                                                </td>
                                                                <td>
                                                                    <button type='button' id='img_removeButton_{{$keys+1}}' class="gj_pimg_rem"><i class="fa fa-trash"></i></button>
                                                                </td>
                                                            </tr>
                                                        <?php }
                                                    } else { ?>
                                                        <tr id="gj_tr_pimg_1">
                                                            <td>
                                                                <input class="form-control gj_p_name" placeholder="Enter Product Name" name="p_name[]" type="text" id="p_name_1">
                                                            </td>
                                                            <td>
                                                                <input type="file" name="p_image[]" id="p_image_1" accept="image/*" class="gj_p_image form-control">
                                                            </td>
                                                            <td>
                                                                <button type='button' id='img_removeButton_1' class="gj_pimg_rem"><i class="fa fa-trash"></i></button>
                                                            </td>
                                                        </tr>
                                                    <?php }
                                                } else { ?>
                                                    <tr id="gj_tr_pimg_1">
                                                        <td>
                                                            <input class="form-control gj_p_name" placeholder="Enter Product Name" name="p_name[]" type="text" id="p_name_1">
                                                        </td>
                                                        <td>
                                                            <input type="file" name="p_image[]" id="p_image_1" accept="image/*" class="gj_p_image form-control">
                                                        </td>
                                                        <td>
                                                            <button type='button' id='img_removeButton_1' class="gj_pimg_rem"><i class="fa fa-trash"></i></button>
                                                        </td>
                                                    </tr>
                                                <?php } ?>
                                            </tbody>
                                        </table>

                                        <input type='button' value='Add Button' id='img_addButton'>
                                    </div>
                                </div>
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
        $("#sub_cat_name").select2();
        $("#sub_sub_cat_name").select2();
        $("#tags").select2();
        $("#measurement_unit").select2();
        $("#store_name").select2();

        var main_cat = $('#main_cat_name').val();
        if(main_cat) {
            <?php if($product) { ?>
                <?php if(isset($product->sub_cat_name)) { ?>
                    var sub_cat = <?php echo $product->sub_cat_name; ?>;
                <?php } ?>
            <?php } ?>
            $.ajax({
                type: 'post',
                url: '{{url('/select_sub_cat')}}',
                data: {main_cat: main_cat, sub_cat: sub_cat, type: 'sub_cat'},
                success: function(data){
                    if(data){
                        $("#sub_cat_name").html(data);
                        $("#sub_cat_name").removeAttr("disabled");

                        $.ajax({
                            type: 'post',
                            url: '{{url('/get_tax')}}',
                            data: {main_cat: main_cat, type: 'get_tax'},
                            success: function(data){
                                if(data != 1){
                                    $(".gj_tax").val(data);
                                } else {
                                    $.confirm({
                                        title: '',
                                        content: 'Tax Not Available!',
                                        icon: 'fa fa-exclamation',
                                        theme: 'modern',
                                        closeIcon: true,
                                        animation: 'scale',
                                        type: 'blue',
                                        buttons: {
                                            Ok: function(){
                                            }
                                        }
                                    });
                                    $(".gj_tax").val(0);
                                }
                            }
                        });
                        
                        var sub_cat = $('#sub_cat_name').val();
                        if(sub_cat) {
                            <?php if($product) { ?>
                                <?php if(isset($product->sub_sub_cat_name)) { ?>
                                    var sub_sub_cat = <?php echo $product->sub_sub_cat_name; ?>;
                                <?php } ?>
                            <?php } ?>
                            $.ajax({
                                type: 'post',
                                url: '{{url('/select_sub_sub_cat')}}',
                                data: {sub_cat: sub_cat, sub_sub_cat: sub_sub_cat, type: 'sub_sub_cat'},
                                success: function(data){
                                    if(data){
                                        $("#sub_sub_cat_name").html(data);
                                        $("#sub_sub_cat_name").removeAttr("disabled");
                                    } else {
                                        $.confirm({
                                            title: '',
                                            content: 'No Sub Sub Category Available for this Sub Category!',
                                            icon: 'fa fa-exclamation',
                                            theme: 'modern',
                                            closeIcon: true,
                                            animation: 'scale',
                                            type: 'blue',
                                            buttons: {
                                                Ok: function(){
                                                }
                                            }
                                        });
                                        $("#sub_sub_cat_name").html(data);
                                    }
                                }
                            });
                        }
                    } else {
                        $.confirm({
                            title: '',
                            content: 'No Sub Category Available for this Main Category!',
                            icon: 'fa fa-exclamation',
                            theme: 'modern',
                            closeIcon: true,
                            animation: 'scale',
                            type: 'blue',
                            buttons: {
                                Ok: function(){
                                }
                            }
                        });
                        $("#sub_cat_name").html(data);
                    }
                }
            });
        }
    });

    $('#main_cat_name').on('change',function() {
        var main_cat = $(this).val();
        if(main_cat) {
            $.ajax({
                type: 'post',
                url: '{{url('/select_sub_cat')}}',
                data: {main_cat: main_cat, type: 'sub_cat'},
                success: function(data){
                    if(data){
                        $("#sub_cat_name").html(data);
                        $("#sub_cat_name").removeAttr("disabled");

                        $.ajax({
                            type: 'post',
                            url: '{{url('/get_tax')}}',
                            data: {main_cat: main_cat, type: 'get_tax'},
                            success: function(data){
                                if(data != 1){
                                    $(".gj_tax").val(data);
                                } else {
                                    $.confirm({
                                        title: '',
                                        content: 'Tax Not Available!',
                                        icon: 'fa fa-exclamation',
                                        theme: 'modern',
                                        closeIcon: true,
                                        animation: 'scale',
                                        type: 'blue',
                                        buttons: {
                                            Ok: function(){
                                            }
                                        }
                                    });
                                    $(".gj_tax").val(0);
                                }
                            }
                        });
                    } else {
                        $.confirm({
                            title: '',
                            content: 'No Sub Category Available for this Main Category!',
                            icon: 'fa fa-exclamation',
                            theme: 'modern',
                            closeIcon: true,
                            animation: 'scale',
                            type: 'blue',
                            buttons: {
                                Ok: function(){
                                }
                            }
                        });
                        $("#sub_cat_name").html(data);
                    }
                }
            });
        }
    });

    $('#sub_cat_name').on('change',function() {
        var sub_cat = $(this).val();
        if(sub_cat) {
            $.ajax({
                type: 'post',
                url: '{{url('/select_sub_sub_cat')}}',
                data: {sub_cat: sub_cat, type: 'sub_sub_cat'},
                success: function(data){
                    if(data){
                        $("#sub_sub_cat_name").html(data);
                        $("#sub_sub_cat_name").removeAttr("disabled");
                    } else {
                        $.confirm({
                            title: '',
                            content: 'No Sub Sub Category Available for this Sub Category!',
                            icon: 'fa fa-exclamation',
                            theme: 'modern',
                            closeIcon: true,
                            animation: 'scale',
                            type: 'blue',
                            buttons: {
                                Ok: function(){
                                }
                            }
                        });
                        $("#sub_sub_cat_name").html(data);
                    }
                }
            });
        }
    });
</script>

<script type="text/javascript">

    $(document).ready(function(){
        var att_n = 0;
        $.each($(".gj_att_name option:selected"), function(){            
            if ($(this).val()) {
                att_n = $(this).val();
            }

            if(att_n == 1) {
                $(this).closest('tr').find('.gj_att_size').hide();
                $('thead').find('.gj_att_size').hide();

                $(this).closest('tr').find('.gj_att_capacity').hide();
                $('thead').find('.gj_att_capacity').hide();

                $(this).closest('tr').find('.gj_att_color').show();
                $('thead').find('.gj_att_color').show();
            } else if (att_n == 2) {
                $(this).closest('tr').find('.gj_att_color').hide();
                $('thead').find('.gj_att_color').hide();

                $(this).closest('tr').find('.gj_att_capacity').hide();
                $('thead').find('.gj_att_capacity').hide();

                $(this).closest('tr').find('.gj_att_size').show();
                $('thead').find('.gj_att_size').show();
            }  else if (att_n == 3) {
                $(this).closest('tr').find('.gj_att_color').hide();
                $('thead').find('.gj_att_color').hide();

                $(this).closest('tr').find('.gj_att_size').hide();
                $('thead').find('.gj_att_size').hide();

                $(this).closest('tr').find('.gj_att_capacity').show();
                $('thead').find('.gj_att_capacity').show();
            } else {
                $.confirm({
                    title: '',
                    content: 'Please Select Attribute Name!',
                    icon: 'fa fa-exclamation',
                    theme: 'modern',
                    closeIcon: true,
                    animation: 'scale',
                    type: 'blue',
                    buttons: {
                        Ok: function(){
                        }
                    }
                });
            }
        });

        if($("input[name='attributes_flag']:checked").val() == 1) {
            $('.gj_p_att_resp').show();
        } else {
            $('.gj_p_att_resp').hide();
        }

        $('body').on('change','input[name="attributes_flag"]',function() {
            if($("input[name='attributes_flag']:checked").val() == 1) {
                $('.gj_p_att_resp').show();
            } else {
                $('.gj_p_att_resp').hide();
            }
        });
        
        $('body').on('change','.gj_att_name',function() {
            var att_n = 0;
            if ($(this).val()) {
                att_n = $(this).val();
            }

            if(att_n == 1) {
                $(this).closest('tr').find('.gj_att_size').hide();
                $('thead').find('.gj_att_size').hide();

                $(this).closest('tr').find('.gj_att_capacity').hide();
                $('thead').find('.gj_att_capacity').hide();

                $(this).closest('tr').find('.gj_att_color').show();
                $('thead').find('.gj_att_color').show();
            } else if (att_n == 2) {
                $(this).closest('tr').find('.gj_att_color').hide();
                $('thead').find('.gj_att_color').hide();

                $(this).closest('tr').find('.gj_att_capacity').hide();
                $('thead').find('.gj_att_capacity').hide();

                $(this).closest('tr').find('.gj_att_size').show();
                $('thead').find('.gj_att_size').show();
            }  else if (att_n == 3) {
                $(this).closest('tr').find('.gj_att_color').hide();
                $('thead').find('.gj_att_color').hide();

                $(this).closest('tr').find('.gj_att_size').hide();
                $('thead').find('.gj_att_size').hide();

                $(this).closest('tr').find('.gj_att_capacity').show();
                $('thead').find('.gj_att_capacity').show();
            } else {
                $.confirm({
                    title: '',
                    content: 'Please Select Attribute Name!',
                    icon: 'fa fa-exclamation',
                    theme: 'modern',
                    closeIcon: true,
                    animation: 'scale',
                    type: 'blue',
                    buttons: {
                        Ok: function(){
                        }
                    }
                });
            }
        });

        var counter = <?php echo count($atts) + 1;?>;
        $("#addButton").click(function () {
            var newTextBoxDiv = $(document.createElement('tr')).attr("id", 'gj_tr_att_' + counter);
            newTextBoxDiv.after().html('<td><select id="attribute_name_' + counter + '" name="attribute_name[]" class="form-control gj_att_name"><option value="0" selected>Select Attribute</option><option value="1">Color</option><option value="2">Size</option><option value="3">Capacity</option></select></td><td class="gj_att_color"><select id="colors_' + counter + '" name="colors[]" class="form-control gj_att_colors"><option value="0" selected>Select Color</option><?php echo $opt1; ?></select></td><td class="gj_att_size"><select id="sizes_' + counter + '" name="sizes[]" class="form-control gj_att_sizes"><option value="0" selected>Select Size</option><?php echo $opt2; ?></select></td><td class="gj_att_capacity"><select id="capacity_' + counter + '" name="capacity[]" class="form-control gj_att_capacity"><option value="0" selected>Select Capacity</option><?php echo $opt3; ?></select></td><td><input type="text" class="form-control gj_att_price" placeholder="Enter Price" name="att_price[]" id="price_' + counter + '"></td><td><textarea class="form-control gj_att_description" placeholder="Enter description" name="att_description[]" id="description_' + counter + '" rows="1"></textarea></td><td><input type="file" name="att_image[]" id="att_image_' + counter + '" accept="image/*" class="gj_att_image form-control"></td><td><button type="button" id="removeButton_' + counter + '" class="gj_att_rem"><i class="fa fa-trash"></i></button></td>');
            newTextBoxDiv.appendTo("#gj_att_bdy");
            counter++;
        });

        $('body').on('click','.gj_att_rem',function() {
            if(counter==1){
                $.confirm({
                    title: '',
                    content: 'No more textbox to remove!',
                    icon: 'fa fa-ban',
                    theme: 'modern',
                    closeIcon: true,
                    animation: 'scale',
                    type: 'red',
                    buttons: {
                        Ok: function(){
                        }
                    }
                });
                return false;
            }   
        
            counter--;
            $(this).closest('tr').remove();
        });

        var cnt = <?php echo count($p_imgs) + 1;?>;
        $("#img_addButton").click(function () {
            var newTextBoxDiv = $(document.createElement('tr')).attr("id", 'gj_tr_pimg_' + cnt);
            newTextBoxDiv.after().html('<td><input class="form-control gj_p_name" placeholder="Enter Product Name" name="p_name[]" type="text" id="p_name_' + cnt + '"></td><td><input type="file" name="p_image[]" id="p_image_' + cnt + '" accept="image/*" class="gj_p_image form-control"></td><td><button type="button" id="img_removeButton_' + cnt + '" class="gj_pimg_rem"><i class="fa fa-trash"></i></button></td>');
            newTextBoxDiv.appendTo("#gj_pimg_bdy");
            cnt++;
        });

        $('body').on('click','.gj_pimg_rem',function() {
            if(cnt==1){
                $.confirm({
                    title: '',
                    content: 'No more textbox to remove!',
                    icon: 'fa fa-ban',
                    theme: 'modern',
                    closeIcon: true,
                    animation: 'scale',
                    type: 'red',
                    buttons: {
                        Ok: function(){
                        }
                    }
                });
                return false;
            }   
        
            cnt--;
            $(this).closest('tr').remove();
        });
    });
</script>
@endsection
