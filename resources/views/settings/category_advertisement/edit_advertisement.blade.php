@extends('layouts.master')
@section('title', 'Edit Advertisement')
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
                        <li class="active"><a> Edit Advertisement  </a></li>
                    </ul>
                </div>
            </div> -->

            <div class="gj_box dark">
                <header>
                    <div class="gj_icons"><i class="fa fa-edit"></i></div>
                    <h5 class="gj_heading"> Edit Advertisement  </h5>
                </header>

                <div class="col-md-12">
                    {{ Form::open(array('url' => 'edit_advertisement','class'=>'gj_ca_form','files' => true)) }}
                        @if($cat_ads)
                            {{ Form::hidden('ca_id', $cat_ads->id, array('class' => 'form-control gj_ca_id')) }}
                        @endif

                        <div class="form-group">
                            {{ Form::label('ad_title', 'Advertisement Title') }}
                            <span class="error">* 
                                @if ($errors->has('ad_title'))
                                    {{ $errors->first('ad_title') }}
                                @endif
                            </span>

                            {{ Form::text('ad_title', ($cat_ads->ad_title ? $cat_ads->ad_title : Input::old('ad_title')), array('class' => 'form-control gj_ad_title','placeholder' => 'Advertisement Title')) }}
                        </div>

                        <div class="form-group">
                            {{ Form::label('ad_website', 'Website URL') }}
                            <span class="error">* 
                                @if ($errors->has('ad_website'))
                                    {{ $errors->first('ad_website') }}
                                @endif
                            </span>

                            {{ Form::text('ad_website', ($cat_ads->ad_website ? $cat_ads->ad_website : Input::old('ad_website')), array('class' => 'form-control gj_ad_website','placeholder' => 'Website URL')) }}
                        </div>

                        <div class="form-group">
                            {{ Form::label('ad_start_date', 'Start Date') }}
                            <span class="error">* 
                                @if ($errors->has('ad_start_date'))
                                    {{ $errors->first('ad_start_date') }}
                                @endif
                            </span>

                            {{ Form::date('ad_start_date', ($cat_ads->ad_start_date ? date('Y-m-d', strtotime($cat_ads->ad_start_date)) : Input::old('ad_start_date')), array('class' => 'form-control gj_ad_start_date','placeholder' => 'Start Date')) }}
                        </div>

                        <div class="form-group">
                            {{ Form::label('ad_end_date', 'End Date') }}
                            <span class="error">* 
                                @if ($errors->has('ad_end_date'))
                                    {{ $errors->first('ad_end_date') }}
                                @endif
                            </span>

                            {{ Form::date('ad_end_date', ($cat_ads->ad_end_date ? date('Y-m-d', strtotime($cat_ads->ad_end_date)) : Input::old('ad_end_date')), array('class' => 'form-control gj_ad_end_date','placeholder' => 'End Date')) }}
                        </div>

                        <div class="form-group">
                            {{ Form::label('cust_name', 'Customer Name') }}
                            <span class="error">* 
                                @if ($errors->has('cust_name'))
                                    {{ $errors->first('cust_name') }}
                                @endif
                            </span>

                            {{ Form::text('cust_name', ($cat_ads->cust_name ? $cat_ads->cust_name : Input::old('cust_name')), array('class' => 'form-control gj_cust_name','placeholder' => 'Customer Name')) }}
                        </div>

                        <div class="form-group">
                            {{ Form::label('cust_no', 'Customer Number') }}
                            <span class="error">* 
                                @if ($errors->has('cust_no'))
                                    {{ $errors->first('cust_no') }}
                                @endif
                            </span>

                            {{ Form::text('cust_no', ($cat_ads->cust_no ? $cat_ads->cust_no : Input::old('cust_no')), array('class' => 'form-control gj_cust_no','placeholder' => 'Customer Number')) }}
                        </div>

                        <div class="form-group">
                            {{ Form::label('amount', 'Amount') }}
                            <span class="error">* 
                                @if ($errors->has('amount'))
                                    {{ $errors->first('amount') }}
                                @endif
                            </span>

                            {{ Form::text('amount', ($cat_ads->amount ? $cat_ads->amount : Input::old('amount')), array('class' => 'form-control gj_amount','placeholder' => 'Amount')) }}
                        </div>

                        <div class="form-group">
                            {{ Form::label('payment_status', 'Payment Status') }}
                            <span class="error">* 
                                @if ($errors->has('payment_status'))
                                    {{ $errors->first('payment_status') }}
                                @endif
                            </span>

                            <select id="payment_status" name="payment_status" class="form-control">
                                <option value="0" {{($cat_ads->payment_status == 0 ? 'selected' : '')}}>Pending</option>
                                <option value="1" {{($cat_ads->payment_status == 1 ? 'selected' : '')}}>Paid</option>
                                <option value="2" {{($cat_ads->payment_status == 2 ? 'selected' : '')}}>UnPaid</option>
                            </select>
                        </div>

                        <div class="form-group">
                            {{ Form::label('page', 'Page') }}
                            <span class="error">* 
                                @if ($errors->has('page'))
                                    {{ $errors->first('page') }}
                                @endif
                            </span>

                            <select id="page" name="page" class="form-control">
                                <option value="0" {{($cat_ads->page == 0 ? 'selected' : '')}}>Select Page</option>
                                <option value="Home Page" {{($cat_ads->page == 'Home Page' ? 'selected' : '')}}>Home Page</option>
                                <option value="Home Category-1" {{($cat_ads->page == 'Home Category-1' ? 'selected' : '')}}>Home Category-1</option>
                                <!--<option value="Home Category-2" {{($cat_ads->page == 'Home Category-2' ? 'selected' : '')}}>Home Category-2</option>-->
                                <!--<option value="Home Category-3" {{($cat_ads->page == 'Home Category-3' ? 'selected' : '')}}>Home Category-3</option>-->
                                <!--<option value="Header" {{($cat_ads->page == 'Header' ? 'selected' : '')}}>Header</option>-->
                                <!--<option value="Footer" {{($cat_ads->page == 'Footer' ? 'selected' : '')}}>Footer</option>-->
                                <option value="All Product Page" {{($cat_ads->page == "All Product Page" ? 'selected' : '')}}>All Product Page</option>
                                <!--<option value="Category Page" {{($cat_ads->page == "Category Page" ? 'selected' : '')}}>Category Page</option>-->
                                <!--<option value="View Product Page" {{($cat_ads->page == "View Product Page" ? 'selected' : '')}}>View Product Page</option>-->
                            </select>
                        </div>

                        <div class="form-group">
                            {{ Form::label('position', 'Position') }}
                            <span class="error">* 
                                @if ($errors->has('position'))
                                    {{ $errors->first('position') }}
                                @endif
                            </span>

                            <select id="position" name="position" class="form-control">
                                <option value="0" {{($cat_ads->position == 0 ? 'selected' : '')}}>Select Position</option>
                                <!--<option value="Top Left" {{($cat_ads->position == "Top Left" ? 'selected' : '')}}>Top Left</option>-->
                                <option value="Top Middle" {{($cat_ads->position == "Top Middle" ? 'selected' : '')}}>Top Middle</option>
                                <!--<option value="Top Right" {{($cat_ads->position == "Top Right" ? 'selected' : '')}}>Top Right</option>-->
                                <option value="Bottom Left" {{($cat_ads->position == "Bottom Left" ? 'selected' : '')}}>Bottom Left</option>
                                <!--<option value="Bottom Middle" {{($cat_ads->position == "Bottom Middle" ? 'selected' : '')}}>Bottom Middle</option>-->
                                <!--<option value="Bottom Right" {{($cat_ads->position == "Bottom Right" ? 'selected' : '')}}>Bottom Right</option>-->
                                <!--<option value="Left" {{($cat_ads->position == "Left" ? 'selected' : '')}}>Left</option>-->
                                <option value="Middle" {{($cat_ads->position == "Middle" ? 'selected' : '')}}>Middle</option>
                                <!--<option value="Right" {{($cat_ads->position == "Right" ? 'selected' : '')}}>Right</option>-->
                                <option value="Below Recent Products"  {{($cat_ads->position == "Below Recent Products" ? 'selected' : '')}}>Below Recent Products</option>
                                <option value="Below Featured Products"  {{($cat_ads->position == "Below Featured Products" ? 'selected' : '')}}>Below Featured Products</option>

                            </select>
                        </div>

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
                                        if($value->id == $cat_ads->main_cat_name) {
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
                        <!-- ($cat_ads->image_title ? $cat_ads->image_title : Input::old('image_title')) -->

                        <div class="gj_ban_img_whole">
                            <?php 
                            $file_path = 'images/category_advertisement';
                            ?>
                            @if(isset($cat_ads))
                                @if($cat_ads->ad_image != '')
                                <div class="form-group">
                                    {{ Form::label('current_ad_image', 'Current Advertisement Image') }}
                                    <div class="gj_mc_div">
                                       <img src="{{ asset($file_path.'/'.$cat_ads->ad_image)}}" class="img-responsive"> 
                                    </div>
                                    {{ Form::hidden('old_ad_image', ($cat_ads->ad_image ? $cat_ads->ad_image : ''), array('class' => 'form-control')) }}
                                </div>
                                @endif
                            @endif

                            <div class="form-group">
                                {{ Form::label('ad_image', 'Upload Advertisement Image') }}
                                <span class="error">* 
                                    @if ($errors->has('ad_image'))
                                        {{ $errors->first('ad_image') }}
                                    @endif
                                </span>

                                <input type="file" name="ad_image" id="ad_image" accept="image/*" class="gj_ad_image">
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
