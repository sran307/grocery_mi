@extends('layouts.master')
@section('title', 'Edit Brands')
@section('content')
<section class="gj_brands_setting">
    <div class="row gj_row">
        <div class="col-md-3 col-sm-3 col-xs-12">
            @include('layouts.product_sidebar')
        </div>

        <div class="col-md-9 col-sm-9 col-xs-12">
            <!-- <div class="row">
                <div class="col-lg-12">
                    <ul class="breadcrumb">
                        <li class=""><a> Home  </a></li>
                        <li class="active"><a> Edit Brands  </a></li>
                    </ul>
                </div>
            </div> -->

            <div class="gj_box dark">
                <header>
                    <div class="gj_icons"><i class="fa fa-edit"></i></div>
                    <h5 class="gj_heading"> Edit Brands  </h5>
                </header>

                <div class="col-md-12">
                    {{ Form::open(array('url' => 'edit_brands','class'=>'gj_brands_form','files' => true)) }}
                        @if($brands)
                            {{ Form::hidden('brands_id', $brands->id, array('class' => 'form-control gj_brands_id')) }}
                        @endif

                        <div class="form-group">
                            {{ Form::label('brand_name', 'Brand Name') }}
                            <span class="error">* 
                                @if ($errors->has('brand_name'))
                                    {{ $errors->first('brand_name') }}
                                @endif
                            </span>

                            {{ Form::text('brand_name', ($brands->brand_name ? $brands->brand_name : Input::old('brand_name')), array('class' => 'form-control gj_brand_name','placeholder' => 'Brand Name')) }}
                        </div>

                        <div class="form-group">
                            {{ Form::label('country_origin', 'Country Of Origin') }}
                            <span class="error">* 
                                @if ($errors->has('country_origin'))
                                    {{ $errors->first('country_origin') }}
                                @endif
                            </span>

                            {{ Form::text('country_origin', ($brands->country_origin ? $brands->country_origin : Input::old('country_origin')), array('class' => 'form-control gj_country_origin','placeholder' => 'Country Of Origin')) }}
                        </div>

                        <!-- <div class="form-group">
                            {{ Form::label('address', 'Address') }}
                            <span class="error">* 
                                @if ($errors->has('address'))
                                    {{ $errors->first('address') }}
                                @endif
                            </span>

                            {{ Form::text('address', ($brands->address ? $brands->address : Input::old('address')), array('class' => 'form-control gj_address','placeholder' => 'Address')) }}
                        </div>

                        <div class="form-group">
                            {{ Form::label('pincode', 'Pincode') }}
                            <span class="error">* 
                                @if ($errors->has('pincode'))
                                    {{ $errors->first('pincode') }}
                                @endif
                            </span>

                            {{ Form::number('pincode', ($brands->pincode ? $brands->pincode : Input::old('pincode')), array('class' => 'form-control gj_pincode','placeholder' => 'Pincode')) }}
                        </div>

                        <div class="form-group">
                            {{ Form::label('country', 'Country') }}
                            <span class="error">* 
                                @if ($errors->has('country'))
                                    {{ $errors->first('country') }}
                                @endif
                            </span>

                            <?php 
                                $opt = '';
                                $ctys = \DB::table('countries_managements')->where('is_block',1)->get();
                                if(($ctys) && (count($ctys) != 0)){
                                    foreach ($ctys as $key => $value) {
                                        if($brands->country == $value->id) {
                                            $opt.='<option selected value="'.$value->id.'">'.$value->country_name.'</option>';
                                        } else {
                                            $opt.='<option value="'.$value->id.'">'.$value->country_name.'</option>';
                                        }
                                    }
                                } 
                            ?>
                            <select id="country" name="country" class="form-control">
                                <option value="0" selected disabled>Select Country</option>
                                <?php echo $opt; ?>
                            </select>
                        </div> 

                        <div class="form-group">
                            {{ Form::label('state', 'Select State') }}
                            <span class="error">* 
                                @if ($errors->has('state'))
                                    {{ $errors->first('state') }}
                                @endif
                            </span>

                            <select id="state" name="state" disabled class="form-control">
                                <option value="0" selected disabled>Select State</option>
                            </select>
                        </div>

                        <div class="form-group">
                            {{ Form::label('city', 'Select District') }}
                            <span class="error">* 
                                @if ($errors->has('city'))
                                    {{ $errors->first('city') }}
                                @endif
                            </span>

                            <select id="city" name="city" disabled class="form-control">
                                <option value="0" selected disabled>Select District</option>
                            </select>
                        </div>    -->

                        <div class="gj_ban_img_whole">
                            <?php 
                            $file_path = 'images/brands';
                            ?>
                            @if(isset($brands))
                                @if($brands->brand_image != '')
                                <div class="form-group">
                                    {{ Form::label('current_brand_image', 'Current Brand Image') }}
                                    <div class="gj_mc_div">
                                       <img src="{{ asset($file_path.'/'.$brands->brand_image)}}" class="img-responsive"> 
                                    </div>
                                    {{ Form::hidden('old_brand_image', ($brands->brand_image ? $brands->brand_image : ''), array('class' => 'form-control')) }}
                                </div>
                                @endif
                            @endif

                            <div class="form-group">
                                {{ Form::label('brand_image', 'Upload Brand Image') }}
                                <span class="error">* 
                                    @if ($errors->has('brand_image'))
                                        {{ $errors->first('brand_image') }}
                                    @endif
                                </span>
                                <p class="gj_not" style="color:red"><em>image size must be 140 x 73 pixels</em></p>

                                <input type="file" name="brand_image" id="brand_image" accept="image/*" class="gj_brand_image">
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
        $("#country").select2();
        $("#state").select2();
        $("#city").select2();

        /*var country = $('#country').val();
        @if($brands->state)
            var state = <?php echo $brands->state; ?>;
        @else
            var state = 0;
        @endif
        if(country) {
            $.ajax({
                type: 'post',
                url: '{{url('/state_details')}}',
                data: {country: country, state: state, type: 'state'},
                success: function(data){
                    if(data){
                        $("#state").html(data);
                        $("#state").removeAttr("disabled");

                        var states = $('#state').val();
                        @if($brands->city)
                            var city = <?php echo $brands->city; ?>;
                        @else
                            var city = 0;
                        @endif
                        if(states) {
                            $.ajax({
                                type: 'post',
                                url: '{{url('/city_details')}}',
                                data: {state: states, city: city, type: 'city'},
                                success: function(data){
                                    if(data){
                                        $("#city").html(data);
                                        $("#city").removeAttr("disabled");
                                    } else {
                                        $.confirm({
                                            title: '',
                                            content: 'Please Select Another City!',
                                            icon: 'fa fa-exclamation',
                                            theme: 'modern',
                                            closeIcon: true,
                                            animation: 'scale',
                                            type: 'purple',
                                            buttons: {
                                                Ok: function(){
                                                }
                                            }
                                        });
                                    }
                                }
                            });
                        } else {
                            $.confirm({
                                title: '',
                                content: 'Please Select Another City!',
                                icon: 'fa fa-exclamation',
                                theme: 'modern',
                                closeIcon: true,
                                animation: 'scale',
                                type: 'purple',
                                buttons: {
                                    Ok: function(){
                                    }
                                }
                            });
                        }
                    } else {
                        $.confirm({
                            title: '',
                            content: 'Please Select Another Country!',
                            icon: 'fa fa-exclamation',
                            theme: 'modern',
                            closeIcon: true,
                            animation: 'scale',
                            type: 'purple',
                            buttons: {
                                Ok: function(){
                                }
                            }
                        });
                    }
                }
            });
        } else {
            $.confirm({
                title: '',
                content: 'Please Select Another Country!',
                icon: 'fa fa-exclamation',
                theme: 'modern',
                closeIcon: true,
                animation: 'scale',
                type: 'purple',
                buttons: {
                    Ok: function(){
                    }
                }
            });
        }*/
    });

    $('#country').on('change',function() {
        var country = $(this).val();
        if(country) {
            $.ajax({
                type: 'post',
                url: '{{url('/state_details')}}',
                data: {country: country, type: 'state'},
                success: function(data){
                    if(data){
                        $("#state").html(data);
                        $("#state").removeAttr("disabled");
                    } else {
                        $.confirm({
                            title: '',
                            content: 'Please Select Another Country!',
                            icon: 'fa fa-exclamation',
                            theme: 'modern',
                            closeIcon: true,
                            animation: 'scale',
                            type: 'purple',
                            buttons: {
                                Ok: function(){
                                }
                            }
                        });
                    }
                }
            });
        }
    });

    $('#state').on('change',function() {
        var state = $(this).val();
        if(state) {
            $.ajax({
                type: 'post',
                url: '{{url('/city_details')}}',
                data: {state: state, type: 'city'},
                success: function(data){
                    if(data){
                        $("#city").html(data);
                        $("#city").removeAttr("disabled");
                    } else {
                        $.confirm({
                            title: '',
                            content: 'Please Select Another City!',
                            icon: 'fa fa-exclamation',
                            theme: 'modern',
                            closeIcon: true,
                            animation: 'scale',
                            type: 'purple',
                            buttons: {
                                Ok: function(){
                                }
                            }
                        });
                    }
                }
            });
        }
    });
</script>
@endsection
