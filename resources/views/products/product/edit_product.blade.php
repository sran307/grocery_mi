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

                                    <p class="gj_not" style="color:red"><em>Note : Just give the content format here as you wish to display in the frontend. (Eg: Paragraph should be given if needed)</em></p>
                                </div>

                                <!-- <div class="form-group">
                                    {{ Form::label('product_weight', 'Product Weight (gram)') }}
                                    <span class="error">* 
                                        @if ($errors->has('product_weight'))
                                            {{ $errors->first('product_weight') }}
                                        @endif
                                    </span>

                                    {{ Form::text('product_weight', ($product->product_weight ? $product->product_weight : Input::old('product_weight')), array('class' => 'form-control gj_product_weight','placeholder' => 'Enter Product Weight in Gram (eg:150)')) }}
                                </div>

                                <div class="form-group">
                                    {{ Form::label('product_length', 'Product Length (cm)') }}
                                    <span class="error">* 
                                        @if ($errors->has('product_length'))
                                            {{ $errors->first('product_length') }}
                                        @endif
                                    </span>

                                    {{ Form::text('product_length', ($product->product_length ? $product->product_length : Input::old('product_length')), array('class' => 'form-control gj_product_length','placeholder' => 'Enter Product Length in Centimeter (eg:10)')) }}
                                </div>

                                <div class="form-group">
                                    {{ Form::label('product_width', 'Product Width (cm)') }}
                                    <span class="error">* 
                                        @if ($errors->has('product_width'))
                                            {{ $errors->first('product_width') }}
                                        @endif
                                    </span>

                                    {{ Form::text('product_width', ($product->product_width ? $product->product_width : Input::old('product_width')), array('class' => 'form-control gj_product_width','placeholder' => 'Enter Product Width in Centimeter (eg:10)')) }}
                                </div>

                                <div class="form-group">
                                    {{ Form::label('product_height', 'Product Height (cm)') }}
                                    <span class="error">* 
                                        @if ($errors->has('product_height'))
                                            {{ $errors->first('product_height') }}
                                        @endif
                                    </span>

                                    {{ Form::text('product_height', ($product->product_height ? $product->product_height : Input::old('product_height')), array('class' => 'form-control gj_product_height','placeholder' => 'Enter Product Height in Centimeter (eg:10)')) }}
                                </div> -->
                                

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
                                    {{ Form::label('varient', 'Varient') }}
                                    <span class="error"> 
                                        @if ($errors->has('varient'))
                                            {{ $errors->first('varient') }}
                                        @endif
                                    </span>

                                    {{ Form::text('varient', ($product->varient ? $product->varient : Input::old('varient')), array('class' => 'form-control gj_varient','placeholder' => 'Enter Varient')) }}
                                </div>

                                <div class="form-group">
                                    {{ Form::label('unit', 'Select Measurement Unit') }}
                                    <span class="error">* 
                                        @if ($errors->has('unit'))
                                            {{ $errors->first('unit') }}
                                        @endif
                                    </span>

                                    <select id="measurement_unit" name="unit" class="form-control">
                                        <option value="0">Select A Unit</option>
                                        <?php $units=App\MeasurementUnits::all();?>
                                        @foreach ($units as $unit)

                                        <option value='{{$unit->id}}' <?php if($unit->id==$product->measurement_unit){echo "selected";}?>>{{$unit->unit_name}}</option>";
                                        @endforeach
                                    </select>
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
                                        <option value="" selected disabled>Select Main Category</option>
                                        <?php echo $opt; ?>
                                    </select>
                                </div>

                                <div class="form-group">
                                    {{ Form::label('sub_cat_name', 'Select Sub Category Name') }}
                                    <span class="error">* 
                                        @if ($errors->has('sub_cat_name'))
                                            {{ $errors->first('sub_cat_name') }}
                                        @endif
                                    </span>

                                    <select id="sub_cat_name" name="sub_cat_name" disabled class="form-control">
                                        <option value="" selected disabled>Select Sub Category Name</option>
                                    </select>
                                </div>

                                <!--<div class="form-group">
                                    {{ Form::label('sub_sub_cat_name', 'Select Sub Sub Category Name') }}
                                    <span class="error">*
                                        @if ($errors->has('sub_sub_cat_name'))
                                            {{ $errors->first('sub_sub_cat_name') }}
                                        @endif
                                    </span>

                                    <select id="sub_sub_cat_name" name="sub_sub_cat_name" disabled class="form-control">
                                        <option value="" selected disabled>Select Sub Sub Category Name</option>
                                    </select>
                                </div>-->

                               
                                <div class="form-group">
                                    {{ Form::label('tags', 'Select Tags') }}
                                    <span class="error"> 
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
                                    {{ Form::label('original_price', 'Original Product Cost') }}
                                    <span class="error">* 
                                        @if ($errors->has('original_price'))
                                            {{ $errors->first('original_price') }}
                                        @endif
                                    </span>

                                    {{ Form::text('original_price', ($product->original_price ? $product->original_price : Input::old('original_price')), array('class' => 'form-control gj_original_price','placeholder' => 'Enter Original Price')) }}
                                </div>

                                <div class="form-group">
                                    {{ Form::label('tax', 'Tax (%)') }}
                                    <span class="error">* 
                                        @if ($errors->has('tax'))
                                            {{ $errors->first('tax') }}
                                        @endif
                                    </span>

                                    <!-- <input class="form-control gj_tax" placeholder="Enter Tax in percentage" name="h_tax" disabled type="text" id="h_tax" value="<?php if($product->tax) { echo $product->tax; } else { echo 0; } ?>"> -->
                                    {{ Form::text('tax', ($product->tax ? $product->tax : Input::old('tax')), array('class' => 'form-control gj_tax','placeholder' => 'Enter Tax in percentage')) }}
                                </div>
                            <div class="form-group">
                                    {{ Form::label('discounted_price', 'MRP Price') }}
                                    <span class="error">* 
                                        @if ($errors->has('mrp_price_retailer'))
                                            {{ $errors->first('mrp_price_retailer') }}
                                        @endif
                                    </span>

                                    {{ Form::text('mrp_price_retailer', $product->original_retailer_price?$product->original_retailer_price:Input::old('mrp_price_retailer'), array('class' => 'form-control','placeholder' => 'Enter MRP Price','id' => 'gj_discounted_price1')) }}
                                </div>
                                 
                                <div class="form-group">
                                    {{ Form::label('discounted_price', 'MRP Price+Tax') }}
                                    <span class="error">* 
                                        @if ($errors->has('discounted_price'))
                                            {{ $errors->first('discounted_price') }}
                                        @endif
                                    </span>

                                    {{ Form::text('discounted_price', ($product->discounted_price ? $product->discounted_price : Input::old('discounted_price')), array('class' => 'form-control gj_discounted_price','placeholder' => 'Enter MRP Price','id' => 'gj_discounted_price10','readonly'=>'true')) }}
                                </div>
                                    
                                <!--<div class="form-group">
                                    {{ Form::label('tax_amount', 'Tax Amount') }}
                                    <span class="error">* 
                                        @if ($errors->has('tax_amount'))
                                            {{ $errors->first('tax_amount') }}
                                        @endif
                                    </span>

                                    {{ Form::text('h_tax_amount', ($product->tax_amount ? $product->tax_amount : Input::old('h_tax_amount')), array('class' => 'form-control gj_h_tax_amount','placeholder' => 'Enter Tax in Amount','disabled')) }}

                                    {{ Form::hidden('tax_amount', ($product->tax_amount ? $product->tax_amount : Input::old('tax_amount')), array('class' => 'form-control gj_tax_amount','placeholder' => 'Enter Tax in Amount')) }}
                                </div>

                                <div class="form-group">
                                    {{ Form::label('product_cost', 'Product Cost') }}
                                    <span class="error">* 
                                        @if ($errors->has('product_cost'))
                                            {{ $errors->first('product_cost') }}
                                        @endif
                                    </span>

                                    {{ Form::text('h_product_cost', ($product->product_cost ? $product->product_cost : Input::old('h_product_cost')), array('class' => 'form-control gj_h_product_cost','placeholder' => 'Enter Product Cost','disabled')) }}

                                    {{ Form::hidden('product_cost', ($product->product_cost ? $product->product_cost : Input::old('product_cost')), array('class' => 'form-control gj_product_cost','placeholder' => 'Enter Product Cost')) }}
                                </div>-->

                                <div class="form-group">
                                    {{ Form::label('service_charge', 'Service Charge') }}
                                    <span class="error"> 
                                        @if ($errors->has('service_charge'))
                                            {{ $errors->first('service_charge') }}
                                        @endif
                                    </span>

                                    {{ Form::text('service_charge', ($product->service_charge ? $product->service_charge : 0), array('class' => 'form-control gj_service_charge','placeholder' => 'Enter Service Charge')) }}
                                </div>

                                <div class="form-group">
                                    {{ Form::label('tax_type', 'Select Shipping Type') }}
                                    <span class="error">* 
                                        @if ($errors->has('original_price'))
                                            {{ $errors->first('original_price') }}
                                        @endif
                                    </span>

                                    <select id="tax_type" name="tax_type" class="form-control">
                                        <option value="0" @if($product->tax_type == 0 || $product->tax_type == '') {{'selected'}} @endif>Select Shipping Type</option>
                                        <option value="1" @if($product->tax_type == 1) {{'selected'}} @endif>Inclusive</option>
                                        <option value="2" @if($product->tax_type == 2) {{'selected'}} @endif>Exclusive</option>
                                    </select>
                                </div>

                                <!--<div class="form-group">
                                    {{ Form::label('shiping_charge', 'Shiping Charge') }}
                                    <span class="error">* 
                                        @if ($errors->has('shiping_charge'))
                                            {{ $errors->first('shiping_charge') }}
                                        @endif
                                    </span>

                                    {{ Form::text('shiping_charge', ($product->shiping_charge ? $product->shiping_charge : 0), array('class' => 'form-control gj_shiping_charge','placeholder' => 'Enter Shiping Charge')) }}
                                </div>-->

                                <div class="form-group">
                                    {{ Form::label('onhand_qty', 'On Hand Quantity') }}
                                    <span class="error">* 
                                        @if ($errors->has('onhand_qty'))
                                            {{ $errors->first('onhand_qty') }}
                                        @endif
                                    </span>

                                    {{ Form::text('h_onhand_qty', ($product->onhand_qty ? $product->onhand_qty : Input::old('onhand_qty')), array('disabled','class' => 'form-control gj_onhand_qty','placeholder' => 'Enter On Hand Quantity')) }}
                                    {{ Form::hidden('onhand_qty', ($product->onhand_qty ? $product->onhand_qty : Input::old('onhand_qty')), array('class' => 'form-control gj_onhand_qty','placeholder' => 'Enter On Hand Quantity')) }}
                                </div>

                                
 
                                  
                                <div class="form-group">
                                    {{ Form::label('features', 'Features') }}
                                    <span class="error">* 
                                        @if ($errors->has('features'))
                                            {{ $errors->first('features') }}
                                        @endif
                                    </span>

                                    {{ Form::textarea('features', ($product->features ? $product->features : Input::old('features')), array('class' => 'form-control gj_features','placeholder' => 'Enter Features','rows'=>'5')) }}

                                    <p class="gj_not" style="color:red"><em>Note : Just give the content format here as you wish to display in the frontend. (Eg: Paragraph should be given if needed)</em></p>
                                </div>

                                <div class="form-group">
                                    {{ Form::label('shiping_policy', 'Shipping & Return Policy') }}
                                    <span class="error">* 
                                        @if ($errors->has('shiping_policy'))
                                            {{ $errors->first('shiping_policy') }}
                                        @endif
                                    </span>

                                    {{ Form::textarea('shiping_policy', ($product->shiping_policy ? $product->shiping_policy : Input::old('shiping_policy')), array('class' => 'form-control gj_shiping_policy','placeholder' => 'Enter Shipping & Return Policy','rows'=>'5')) }}

                                    <p class="gj_not" style="color:red"><em>Note : Just give the content format here as you wish to display in the frontend. (Eg: Paragraph should be given if needed)</em></p>
                                </div>
 <div class="form-group">
                                    {{ Form::label('is_related_product', 'Have related products?') }}
                                    <span class="error">* 
                                        @if ($errors->has('is_related_product'))
                                            {{ $errors->first('is_related_product') }}
                                        @endif
                                    </span>

                                    <div class="gj_py_ro_div">
                                        <span class="gj_py_ro">
                                            <input type="radio" name="is_related_product" class="related_pdt" value="1"
                                            <?php if($product->is_related_product == 1) { echo "checked"; } ?>> Yes
                                        </span>
                                        <span class="gj_py_ro">
                                            <input type="radio" name="is_related_product" class="related_pdt" value="0"
                                            <?php if($product->is_related_product == 0) { echo "checked"; } ?>> No
                                        </span>
                                    </div>
                                </div>
                                <div class="form-group" id="check_al">
                                    {{ Form::label('related_pdts', 'Choose Related Products') }}
                                    <span class="error">* 
                                        @if ($errors->has('related_pdts'))
                                            {{ $errors->first('related_pdts') }}
                                        @endif
                                    </span>

                                    <?php 
                                        $opt = '';
                                        $measure1 = \DB::table('products')->where('is_block',1)->where('id','!=',$product->id)->get();
                                        if(($measure1) && (count($measure1) != 0)){
                                            foreach ($measure1 as $key => $value) {
                                                $undo=\DB::table('related_products')->where('product_id',$product->id)
                                                ->where('related_product_id',$value->id)
                                                ->count();
                                                if($undo >0)
                                                $opt.='<option value="'.$value->id.'" selected>'.$value->product_title.'</option>';
                                                else
                                                 $opt.='<option value="'.$value->id.'">'.$value->product_title.'</option>';

                                            }
                                        } 
                                    ?>

                                    <select id="related_pdts" name="related_pdts[]" class="form-control js-example-basic-multiple" multiple="multiple">
                                        <?php echo $opt; ?>
                                    </select>
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
                                    {{ Form::label('best_seller_flag', 'Best Seller') }}
                                    <span class="error">* 
                                        @if ($errors->has('best_seller_flag'))
                                            {{ $errors->first('best_seller_flag') }}
                                        @endif
                                    </span>

                                    <div class="gj_py_ro_div">
                                        <span class="gj_py_ro">
                                            <input type="radio" <?php if($product->best_seller_flag == 1) { echo "checked"; } ?> name="best_seller_flag" value="1"> Yes
                                        </span>
                                        <span class="gj_py_ro">
                                            <input type="radio" <?php if($product->best_seller_flag == 0) { echo "checked"; } ?> name="best_seller_flag" value="0"> No
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
                                    <span class="error"> 
                                        @if ($errors->has('store_name'))
                                            {{ $errors->first('store_name') }}
                                        @endif
                                    </span>

                                    <?php 
                                        $opt = '';
                                        // $store = \DB::table('stores')->where('is_block',1)->get();
                                        if((isset($store)) && (count($store) != 0)){
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
                                        @if ($product->store == $value->id) 
                                            <option value="0" Selected>Select Stores</option>
                                        @else 
                                            <option value="0">Select Stores</option>
                                        @endif
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

                                        @if ($errors->has('att_value'))
                                            <p class="error"> 
                                                {{ $errors->first('att_value') }}
                                            </p>
                                        @endif

                                        @if ($errors->has('att_description'))
                                            <p class="error"> 
                                                {{ $errors->first('att_description') }}
                                            </p>
                                        @endif

                                        @if ($errors->has('att_price'))
                                            <p class="error"> 
                                                {{ $errors->first('att_price') }}
                                            </p>
                                        @endif

                                        @if ($errors->has('att_tax_amount'))
                                            <p class="error"> 
                                                {{ $errors->first('att_tax_amount') }}
                                            </p>
                                        @endif

                                        @if ($errors->has('att_cost'))
                                            <p class="error"> 
                                                {{ $errors->first('att_cost') }}
                                            </p>
                                        @endif

                                        @if ($errors->has('att_qty'))
                                            <p class="error"> 
                                                {{ $errors->first('att_qty') }}
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
                                                    <th>Default</th>
                                                    <th>Attribute Name</th>
                                                    <th class="gj_att_values">Value</th>
                                                    <th>Cost</th>
                                                    <th>Tax</th>
                                                    <th>Price</th>
                                                    <th>Qty</th>
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
                                                            <?php $atts_flds = ""; ?>
                                                            <tr id="gj_tr_att_{{$keys+1}}">
                                                                <td>
                                                                    <input type="radio" name="att_default[]" class="gj_att_default" <?php if($values->att_default == 1) { echo "checked"; } ?> value="{{$values->att_default}}">
                                                                    <input type="hidden" class="v_att_default" name="v_att_default[]" value="{{$values->att_default}}">
                                                                </td>
                                                                <td>
                                                                    <select id="attribute_name_1" name="attribute_name[]" class="form-control gj_att_name">
                                                                        <option value="0" selected>Select Attribute</option>
                                                                        @if(isset($attributes) && count($attributes) != 0)
                                                                            @foreach ($attributes as $att_key => $att_val)
                                                                                @if($att_val->id == $values->attribute_name)
                                                                                    <option selected value="{{$att_val->id}}">{{$att_val->att_name}}</option>
                                                                                @else
                                                                                    <option value="{{$att_val->id}}">{{$att_val->att_name}}</option>
                                                                                @endif
                                                                                <?php $atts_flds.='<option value="'.$att_val->id.'">'.$att_val->att_name.'</option>'; ?>
                                                                            @endforeach
                                                                            <?php echo $atts_flds; ?>
                                                                        @endif
                                                                    </select>
                                                                </td>
                                                                <td>
                                                                    <input type="hidden" name="oldatt_value[]" class="gj_old_attr_values" value="{{$values->attribute_values}}">
                                                                    <select id="attvalue_1" name="att_value[]" class="form-control gj_attr_values">
                                                                        <option value="0" selected>Select Attributes Value</option>
                                                                    </select>
                                                                </td>

                                                                <td>
                                                                    <input type="text" class="form-control gj_att_price" placeholder="Enter Price" name="att_price[]" id="cost_{{$keys+1}}"  value="{{$values->att_price}}">
                                                                </td>

                                                                <td>
                                                                    <input type="text" class="form-control gj_att_h_tax_amount" placeholder="Enter Tax Amount" name="att_h_tax_amount[]" id="h_tax_amount_{{$keys+1}}"  value="{{$values->att_tax_amount}}" disabled>

                                                                    <input type="hidden" class="form-control gj_att_tax_amount" placeholder="Enter Tax Amount" name="att_tax_amount[]" id="tax_amount_{{$keys+1}}"  value="{{$values->att_tax_amount}}">
                                                                </td>

                                                                <td>
                                                                    <input type="text" class="form-control gj_att_h_cost" placeholder="Enter cost" name="att_h_cost[]" id="att_h_cost_{{$keys+1}}"  value="{{$values->att_cost}}" disabled>

                                                                    <input type="hidden" class="form-control gj_att_cost" placeholder="Enter cost" name="att_cost[]" id="cost_{{$keys+1}}"  value="{{$values->att_cost}}">
                                                                </td>

                                                                <td>
                                                                    <input type="text" class="form-control gj_att_qty" placeholder="Enter Quantity" name="att_qty[]" id="att_qty_{{$keys+1}}" value="{{$values->att_qty}}" readonly>
                                                                </td>
                                                                <td>
                                                                    <textarea class="form-control gj_att_description" placeholder="Enter description" name="att_description[]" id="description_{{$keys+1}}" rows="1">{{$values->description}}</textarea>
                                                                </td>
                                                                <td>
                                                                    <?php  
                                                                        $att_file_path = 'images/attributes';
                                                                    ?>
                                                                    @if($values->image)
                                                                        <div class="gj_aimg_div">
                                                                            <img src="{{ asset($att_file_path.'/'.$values->image)}}" class="img-responsive gj_old_att_img">
                                                                            {{ Form::hidden('old_att_image[]', $values->image, array('class' => 'form-control')) }}
                                                                        </div>
                                                                    @endif
                                                                    <input type="file" name="att_image[]" id="att_image_{{$keys+1}}" accept="image/*" class="gj_att_image gj_edit_att_image form-control">
                                                                </td>
                                                                <td>
                                                                    <button type='button' id='removeButton_{{$keys+1}}' class="gj_att_rem"><i class="fa fa-trash"></i></button>
                                                                </td>
                                                            </tr>
                                                        <?php }
                                                    } else { ?>
                                                        <?php $atts_flds = ""; ?>
                                                        <tr id="gj_tr_att_1">
                                                            <td>
                                                                <input type="radio" name="att_default[]" class="gj_att_default" value="2">
                                                                <input type="hidden" class="v_att_default" name="v_att_default[]" value="2">
                                                            </td>
                                                            <td>
                                                                <select id="attribute_name_1" name="attribute_name[]" class="form-control gj_att_name">
                                                                    <option value="0" selected>Select Attribute</option>
                                                                    @if(isset($attributes) && count($attributes) != 0)
                                                                        @foreach ($attributes as $att_key => $att_val)
                                                                            <option value="{{$att_val->id}}">{{$att_val->att_name}}</option>
                                                                            <?php $atts_flds.='<option value="'.$att_val->id.'">'.$att_val->att_name.'</option>'; ?>
                                                                        @endforeach
                                                                    @endif
                                                                </select>
                                                            </td>
                                                            <td>
                                                                <select id="attvalue_1" name="att_value[]" class="form-control gj_attr_values">
                                                                    <option value="0" selected>Select Attributes Value</option>
                                                                </select>
                                                            </td>
                                                            
                                                            <td>
                                                                <input type="text" class="form-control gj_att_price" placeholder="Enter price" name="att_price[]" id="price_1">
                                                            </td>

                                                            <td>
                                                                <input type="text" class="form-control gj_att_h_tax_amount" placeholder="Enter Tax Amount" name="att_h_tax_amount[]" id="h_tax_amount_1" disabled>

                                                                <input type="hidden" class="form-control gj_att_tax_amount" placeholder="Enter Tax Amount" name="att_tax_amount[]" id="tax_amount_1">
                                                            </td>

                                                            <td>
                                                                <input type="text" class="form-control gj_att_h_cost" placeholder="Enter cost" name="att_h_cost[]" id="att_h_cost_1" disabled>

                                                                <input type="hidden" class="form-control gj_att_cost" placeholder="Enter cost" name="att_cost[]" id="cost_1">
                                                            </td>

                                                            <td>
                                                                <input type="text" class="form-control gj_att_qty" placeholder="Enter Quantity" name="att_qty[]" id="att_qty_1">
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
                                                    <?php $atts_flds = ""; ?>
                                                    <tr id="gj_tr_att_1">
                                                        <td>
                                                            <input type="radio" name="att_default[]" class="gj_att_default" value="2">
                                                            <input type="hidden" class="v_att_default" name="v_att_default[]" value="2">
                                                        </td>
                                                        <td>
                                                            <select id="attribute_name_1" name="attribute_name[]" class="form-control gj_att_name">
                                                                <option value="0" selected>Select Attribute</option>
                                                                @if(isset($attributes) && count($attributes) != 0)
                                                                    @foreach ($attributes as $att_key => $att_val)
                                                                        <option value="{{$att_val->id}}">{{$att_val->att_name}}</option>
                                                                        <?php $atts_flds.='<option value="'.$att_val->id.'">'.$att_val->att_name.'</option>'; ?>
                                                                    @endforeach
                                                                @endif
                                                            </select>
                                                        </td>
                                                        <td>
                                                            <select id="attvalue_1" name="att_value[]" class="form-control gj_attr_values">
                                                                <option value="0" selected>Select Attributes Value</option>
                                                            </select>
                                                        </td>
                                                        
                                                        <td>
                                                            <input type="text" class="form-control gj_att_price" placeholder="Enter price" name="att_price[]" id="price_1">
                                                        </td>

                                                        <td>
                                                            <input type="text" class="form-control gj_att_h_tax_amount" placeholder="Enter Tax Amount" name="att_h_tax_amount[]" id="h_tax_amount_1" disabled>

                                                            <input type="hidden" class="form-control gj_att_tax_amount" placeholder="Enter Tax Amount" name="att_tax_amount[]" id="tax_amount_1">
                                                        </td>

                                                        <td>
                                                            <input type="text" class="form-control gj_att_h_cost" placeholder="Enter cost" name="att_h_cost[]" id="att_h_cost_1" disabled>

                                                            <input type="hidden" class="form-control gj_att_cost" placeholder="Enter cost" name="att_cost[]" id="cost_1">
                                                        </td>

                                                        <td>
                                                            <input type="text" class="form-control gj_att_qty" placeholder="Enter Quantity" name="att_qty[]" id="att_qty_1">
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
                                                                        <div class="gj_aimg_div">
                                                                            <img src="{{ asset($pimg_file_path.'/'.$values->image)}}" class="img-responsive gj_old_prod_img"> 
                                                                            {{ Form::hidden('old_p_image[]', $values->image, array('class' => 'form-control')) }}
                                                                        </div>
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
check_dimension();
    function gj_round(value, decPlaces) {
        var val = value * Math.pow(10, decPlaces);
        var fraction = (Math.round((val - parseInt(val)) * 10) / 10);

        // -342.055 => -342.06
        if (fraction == -0.5) fraction = -0.6;

        val = Math.round(parseInt(val) + fraction) / Math.pow(10, decPlaces);
        return val;
    }
    function check_dimension()
{
    
    $('.table_tr').hide();
     $('#table_tr'+$('#package_dimension').val()).show();
    
}

    $(document).ready(function() { 
        $('p.alert').delay(5000).slideUp(500); 
        $("#main_cat_name").select2();
        $("#sub_cat_name").select2();
    $("#package_dimension").select2();
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

                        /*$.ajax({
                            type: 'post',
                            url: '{{url('/get_tax')}}',
                            data: {main_cat: main_cat, type: 'get_tax'},
                            success: function(data){
                                if(data != 'error'){
                                    $(".gj_tax").val(data);
                                } else {
                                    $.confirm({
                                        title: '',
                                        content: 'Tax Not Available, Please Add Tax!',
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
                                    $(".gj_tax").val('');
                                }
                            }
                        });*/
                        
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

    /*$('#discounted_price').on('change',function() {
        if($(this).val()) {
            var dp = parseFloat($(this).val());
            var mrp = 0;
            var gst = 0;
            if($('#original_price').val()) {
                mrp = parseFloat($('#original_price').val());
            }

            if($('#tax').val()) {
                gst = parseFloat($('#tax').val());
            }

            if(mrp!= 0 && gst != 0) {
                var tpp = dp * (gst/100);
                var tp = dp + tpp;
                if(mrp <= tp) {
                    $.confirm({
                        title: '',
                        content: 'Include GST Price is ' + tp + ', This Price is more than Original Price!',
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
                    $('#discounted_price').val('');
                }
            } else {
                $.confirm({
                    title: '',
                    content: 'Please Enter Original price And Category or Add Tax!',
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
        } else {
            $.confirm({
                title: '',
                content: 'Please Enter Discounted price!',
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
    });*/
     var checkedValue = $('.related_pdt:checked').val();

    if(checkedValue ==1)
    {
        
    $('#check_al').show();
    $('.js-example-basic-multiple').select2();

       }
       else
       {
         $('#check_al').hide();

       }
    $('.related_pdt').click(function() { 
       if($(this).val()==1)
       {
                     $('#check_al').show();
                       $('.js-example-basic-multiple').select2();

       }
       else
       {
                      $('#check_al').hide();

       }
});
    $('#gj_discounted_price1').on('change',function() {
        if($(this).val()) {
            var dp = parseFloat($(this).val());
            var mrp = 0;
            var gst = 0;
            if($('#original_price').val()) {
                mrp = parseFloat($('#original_price').val());
            }

            if($('#tax').val()) {
                gst = parseFloat($('#tax').val());
            }

            if(mrp!= 0 && gst != 0) {
                if(mrp > dp) {
                    var tpp = (dp * gst)/(100);
                    var tp = dp - tpp;
                    tpp = gj_round(tpp, 2);
                    tp = gj_round(tp, 2);
                    var pc = gj_round(pc, 2);
                    pc = tp + tpp;
                    if(mrp <= tp) {
                        $.confirm({
                            title: '',
                            content: 'Include GST Price is ' + tp + ', This Price is more than Original Price!',
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
                        $('#product_cost').val('');
                        $('.gj_h_product_cost').val('');
                        $('#gj_discounted_price10').val('');
                        $('.gj_h_tax_amount').val('');
                        $(this).val('');
                    } else {
                        $('#product_cost').val(tp);
                        $('.gj_h_product_cost').val(tp);
                        $('#gj_discounted_price10').val(tpp+dp);
                        $('.gj_h_tax_amount').val(tpp);
                        $('#discounted_price').val(pc);
                    }
                } else {
                    $.confirm({
                        title: '',
                        content: 'Please Enter Retailer Price Less Than Original price!',
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
                    $('#product_cost').val('');
                    $('.gj_h_product_cost').val('');
                    $('#gj_discounted_price10').val(tpp+dp);
                    $('.gj_h_tax_amount').val('');
                    $(this).val('');
                }
            } else {
                $.confirm({
                    title: '',
                    content: 'Please Enter Original price And Add Tax!',
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

                $('#product_cost').val('');
                $('.gj_h_product_cost').val('');
                $('#gj_discounted_price10').val('');
                $('.gj_h_tax_amount').val('');
                $(this).val('');
            }
        } else {
            $.confirm({
                title: '',
                content: 'Please Enter Discounted Price!',
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

            $('#product_cost').val('');
            $('.gj_h_product_cost').val('');
            $('#gj_discounted_price10').val('');
            $('.gj_h_tax_amount').val('');
            $(this).val('');
        }
    });
    $('#discount_price_dealer').on('change',function() {
        if($(this).val()) {
            var dp = parseFloat($(this).val());
            var mrp = 0;
            var gst = 0;
            if($('#original_price').val()) {
                mrp = parseFloat($('#original_price').val());
            }

            if($('#tax').val()) {
                gst = parseFloat($('#tax').val());
            }

            if(mrp!= 0 && gst != 0) {
                if(mrp > dp) {
                    var tpp = (dp * gst)/(100);
                    var tp = dp - tpp;
                    tpp = gj_round(tpp, 2);
                    tp = gj_round(tp, 2);
                    var pc = gj_round(pc, 2);
                    pc = tp + tpp;
                    if(mrp <= tp) {
                        $.confirm({
                            title: '',
                            content: 'Include GST Price is ' + tp + ', This Price is more than Original Price!',
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
                        $('#product_cost').val('');
                        $('.gj_h_product_cost').val('');
                        $('#discount_price_dealer10').val('');
                        $('.gj_h_tax_amount').val('');
                        $(this).val('');
                    } else {
                        $('#product_cost').val(tp);
                        $('.gj_h_product_cost').val(tp);
                        $('#discount_price_dealer10').val(tpp+dp);
                        $('.gj_h_tax_amount').val(tpp);
                        // $('#gj_discounted_price1').val(pc);
                    }
                } else {
                    $.confirm({
                        title: '',
                        content: 'Please Enter Discounted Price Less Than Original price!',
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
                    $('#product_cost').val('');
                    $('.gj_h_product_cost').val('');
                    $('#discount_price_dealer10').val('');
                    $('.gj_h_tax_amount').val('');
                    $(this).val('');
                }
            } else {
                $.confirm({
                    title: '',
                    content: 'Please Enter Original price And Add Tax!',
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

                $('#product_cost').val('');
                $('.gj_h_product_cost').val('');
                $('#discount_price_dealer10').val('');
                $('.gj_h_tax_amount').val('');
                $(this).val('');
            }
        } else {
            $.confirm({
                title: '',
                content: 'Please Enter Discounted Price!',
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

            $('#product_cost').val('');
            $('.gj_h_product_cost').val('');
            $('#discount_price_dealer10').val('');
            $('.gj_h_tax_amount').val('');
            $(this).val('');
        }
    });

    $('body').on('change','.gj_att_price',function() {
        if($(this).val()) {
            var dp = parseFloat($(this).val());
            var mrp = 0;
            var gst = 0;
            if($('#original_price').val()) {
                mrp = parseFloat($('#original_price').val());
            }

            if($('#tax').val()) {
                gst = parseFloat($('#tax').val());
            }

            if(mrp!= 0 && gst != 0) {
                if(mrp > dp) {
                    var tpp = (dp * gst)/(100 + gst);
                    var tp = dp - tpp;
                    tpp = gj_round(tpp, 2);
                    tp = gj_round(tp, 2);
                    var pc = tp + tpp;
                    pc = gj_round(pc, 2);
                    if(mrp <= tp) {
                        $.confirm({
                            title: '',
                            content: 'Include GST Price is ' + tp + ', This Price is more than Original Price!',
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
                        $(this).closest('tr').find('.gj_att_h_cost').val('');
                        $(this).closest('tr').find('.gj_att_cost').val('');
                        $(this).closest('tr').find('.gj_att_h_tax_amount').val('');
                        $(this).closest('tr').find('.gj_att_tax_amount').val('');
                        $(this).val('');
                    } else {
                        $(this).closest('tr').find('.gj_att_h_cost').val(tp);
                        $(this).closest('tr').find('.gj_att_cost').val(tp);
                        $(this).closest('tr').find('.gj_att_h_tax_amount').val(tpp);
                        $(this).closest('tr').find('.gj_att_tax_amount').val(tpp);
                        $(this).closest('tr').find('.gj_att_price').val(pc);
                    }
                } else {
                    $.confirm({
                        title: '',
                        content: 'Please Enter Attribute Cost Less Than Original price!',
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
                    $(this).closest('tr').find('.gj_att_h_cost').val('');
                    $(this).closest('tr').find('.gj_att_cost').val('');
                    $(this).closest('tr').find('.gj_att_h_tax_amount').val('');
                    $(this).closest('tr').find('.gj_att_tax_amount').val('');
                    $(this).val('');
                }
            } else {
                $.confirm({
                    title: '',
                    content: 'Please Enter Original price And Add Tax!',
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

                $(this).closest('tr').find('.gj_att_h_cost').val('');
                $(this).closest('tr').find('.gj_att_cost').val('');
                $(this).closest('tr').find('.gj_att_h_tax_amount').val('');
                $(this).closest('tr').find('.gj_att_tax_amount').val('');
                $(this).val('');
            }
        } else {
            $.confirm({
                title: '',
                content: 'Please Enter Product Cost!',
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

            $(this).closest('tr').find('.gj_att_h_cost').val('');
            $(this).closest('tr').find('.gj_att_cost').val('');
            $(this).closest('tr').find('.gj_att_h_tax_amount').val('');
            $(this).closest('tr').find('.gj_att_tax_amount').val('');
            $(this).val('');
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

                        /*$.ajax({
                            type: 'post',
                            url: '{{url('/get_tax')}}',
                            data: {main_cat: main_cat, type: 'get_tax'},
                            success: function(data){
                                if(data != 'error'){
                                    $(".gj_tax").val(data);
                                } else {
                                    $.confirm({
                                        title: '',
                                        content: 'Tax Not Available, Please Add Tax!',
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
                                    $(".gj_tax").val('');
                                }
                            }
                        });*/
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

   /* $('#sub_cat_name').on('change',function() {
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
    });*/
</script>

<script type="text/javascript">
    $(document).ready(function(){
        var att_n = 0;
        if($("input[name='attributes_flag']:checked").val() == 1) {
            $.each($(".gj_att_name option:selected"), function(){            
                if ($(this).val()) {
                    att_n = $(this).val();
                }
                var old_id = $(this).closest('tr').find('.gj_old_attr_values').val();

                var ths = $(this);

                $.ajax({
                    type: 'post',
                    url: '{{url('/select_att_vals')}}',
                    data: {id: att_n, old_id: old_id, type: 'select_att_vals'},
                    success: function(data){
                        if(data != 0){
                            ths.closest('tr').find('.gj_attr_values').html(data);
                        } else {
                            $.confirm({
                                title: '',
                                content: 'Select Another Attributes!',
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
                            // window.location.reload();
                        }
                    }
                });
            });
        }

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

        $('.gj_att_default').each(function () {
            if (this.checked) {
                $(this).val('1');
                $(this).closest('tr').find('.v_att_default').val('1');
            } else {
                $(this).val('2');
                $(this).closest('tr').find('.v_att_default').val('2');
            }
        });

        $('body').on('change','.gj_att_default',function() {
            if ($(this).prop("checked")){
                $('.gj_att_default').val('2');
                $('.v_att_default').val('2');
                $(this).val('1');
                $(this).closest('tr').find('.v_att_default').val('1');
            } else {
                $(this).val('2');
                $('.v_att_default').val('2');
            }
        });

        $('body').on('change','.gj_att_name',function() {
            var att_n = 0;
            if ($(this).val()) {
                att_n = $(this).val();
            }
            var ths = $(this);

            $.ajax({
                type: 'post',
                url: '{{url('/select_att_vals')}}',
                data: {id: att_n, type: 'select_att_vals'},
                success: function(data){
                    if(data != 0){
                        ths.closest('tr').find('.gj_attr_values').html(data);
                    } else {
                        $.confirm({
                            title: '',
                            content: 'Select Another Attributes!',
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
                        // window.location.reload();
                    }
                }
            });
        });

        var counter = <?php echo count($atts) + 1;?>;
        $("#addButton").click(function () {
            var newTextBoxDiv = $(document.createElement('tr')).attr("id", 'gj_tr_att_' + counter);
            newTextBoxDiv.after().html('<td><input type="radio" name="att_default[]" class="gj_att_default" value="2"><input type="hidden" class="v_att_default" name="v_att_default[]" value="2"></td><td><select id="attribute_name_' + counter + '" name="attribute_name[]" class="form-control gj_att_name"><option value="0" selected>Select Attribute</option><?php echo $atts_flds; ?></select></td><td><select id="attvalue_' + counter + '" name="att_value[]" class="form-control gj_attr_values"><option value="0" selected>Select Attributes Value</option></select></td><td><input type="text" class="form-control gj_att_price" placeholder="Enter Price" name="att_price[]" id="price_' + counter + '"></td><td><input type="text" class="form-control gj_att_h_tax_amount" placeholder="Enter Tax Amount" name="att_h_tax_amount[]" id="h_tax_amount_' + counter + '" disabled><input type="hidden" class="form-control gj_att_tax_amount" placeholder="Enter Tax Amount" name="att_tax_amount[]" id="tax_amount_' + counter + '"></td><td><input type="text" class="form-control gj_att_h_cost" placeholder="Enter Cost" name="att_h_cost[]" id="att_h_cost_' + counter + '" disabled><input type="hidden" class="form-control gj_att_cost" placeholder="Enter Cost" name="att_cost[]" id="cost_' + counter + '"></td><td><input type="text" class="form-control gj_att_qty" placeholder="Enter Quantity" name="att_qty[]" id="att_qty_' + counter + '"></td><td><textarea class="form-control gj_att_description" placeholder="Enter description" name="att_description[]" id="description_' + counter + '" rows="1"></textarea></td><td><input type="file" name="att_image[]" id="att_image_' + counter + '" accept="image/*" class="gj_att_image form-control"></td><td><button type="button" id="removeButton_' + counter + '" class="gj_att_rem"><i class="fa fa-trash"></i></button></td>');
            // newTextBoxDiv.appendTo("#TextBoxesGroup");
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

<!-- Editor Script Start -->
    <script src="https://cdn.ckeditor.com/4.11.4/standard/ckeditor.js"></script>

    <script>
        CKEDITOR.replace( 'product_desc' );
        CKEDITOR.replace( 'features' );
        CKEDITOR.replace( 'shiping_policy' );
        $('.gj_product_wattage').keypress(function(evt) {
  evt = (evt) ? evt : window.event;
  var charCode = (evt.which) ? evt.which : evt.keyCode;
  if (charCode == 8 || charCode == 37) {
    return true;
  } else if (charCode == 46 && $(this).val().indexOf('.') != -1) {
    return false;
  } else if (charCode > 31 && charCode != 46 && (charCode < 48 || charCode > 57)) {
    return false;
  }
  return true;
});
    </script>
<!-- Editor Script End -->
@endsection