@extends('layouts.master')
@section('title', 'Add Store')
@section('content')
<section class="gj_email_setting">
    <div class="row gj_row">
        <div class="col-md-3 col-sm-3 col-xs-12">
            @include('layouts.merchant_sidebar')
        </div>

        <div class="col-md-9 col-sm-9 col-xs-12">
            <!-- <div class="row">
                <div class="col-lg-12">
                    <ul class="breadcrumb">
                        <li class=""><a> Home  </a></li>
                        <li class="active"><a> Add Store  </a></li>
                    </ul>
                </div>
            </div> -->

            <div class="gj_box dark">
                @if(Session::has('message'))
                    <p class="alert {{ Session::get('alert-class', 'alert-info') }}">{{ Session::get('message') }}</p>
                @endif
                <header>
                    <div class="gj_icons"><i class="fa fa-edit"></i></div>
                    <h5 class="gj_heading"> Add Store  </h5>
                </header>

                <div class="col-md-12">
                    {{ Form::open(array('url' => 'add_store','class'=>'gj_store_form','files' => true)) }}
                        <div class="gj_box dark gj_inside_box">
                            <header>
                                <h5 class="gj_heading"> Store Details  </h5>
                            </header>

                            <div class="col-md-12">
                                <div class="form-group">
                                    {{ Form::label('merchant', 'Merchant Name') }}
                                    <span class="error">* 
                                        @if ($errors->has('merchant'))
                                            {{ $errors->first('merchant') }}
                                        @endif
                                    </span>

                                    {{ Form::text('h_merchant', ($merchants->first_name ? $merchants->first_name : Input::old('first_name')), array('class' => 'form-control gj_h_merchant', 'disabled' ,'placeholder' => 'Enter Merchants')) }}
                                    {{ Form::hidden('merchant', ($merchants->id ? $merchants->id : 0), array('class' => 'form-control gj_merchant','placeholder' => 'Enter Merchants')) }}
                                </div>

                                <div class="form-group">
                                    {{ Form::label('store_name', 'Store Name') }}
                                    <span class="error">* 
                                        @if ($errors->has('store_name'))
                                            {{ $errors->first('store_name') }}
                                        @endif
                                    </span>

                                    {{ Form::text('store_name', Input::old('store_name'), array('class' => 'form-control gj_store_name','placeholder' => 'Enter Store Name in English')) }}
                                </div>

                                <div class="form-group">
                                    {{ Form::label('store_phone', 'Phone') }}
                                    <span class="error">* 
                                        @if ($errors->has('store_phone'))
                                            {{ $errors->first('store_phone') }}
                                        @endif
                                    </span>

                                    {{ Form::text('store_phone', Input::old('store_phone'), array('class' => 'form-control gj_store_phone','placeholder' => 'Enter Store Phone Number')) }}
                                </div>

                                <div class="form-group">
                                    {{ Form::label('store_address1', 'Address') }}
                                    <span class="error">* 
                                        @if ($errors->has('store_address1'))
                                            {{ $errors->first('store_address1') }}
                                        @endif
                                    </span>

                                    {{ Form::text('store_address1', Input::old('store_address1'), array('class' => 'form-control gj_store_address1','placeholder' => 'Enter Store Address')) }}
                                </div>

                                <div class="form-group">
                                    {{ Form::label('store_address2', 'City') }}
                                    <span class="error">* 
                                        @if ($errors->has('store_address2'))
                                            {{ $errors->first('store_address2') }}
                                        @endif
                                    </span>

                                    {{ Form::text('store_address2', Input::old('store_address2'), array('class' => 'form-control gj_store_address2','placeholder' => 'Enter Store City')) }}
                                </div>

                                <div class="form-group">
                                    {{ Form::label('store_country', 'Select Store Country') }}
                                    <span class="error">* 
                                        @if ($errors->has('store_country'))
                                            {{ $errors->first('store_country') }}
                                        @endif
                                    </span>

                                    <?php 
                                        $opt = '';
                                        $ctys = \DB::table('countries_managements')->where('is_block',1)->get();
                                        if(($ctys) && (count($ctys) != 0)){
                                            foreach ($ctys as $key => $value) {
                                                $opt.='<option value="'.$value->id.'">'.$value->country_name.'</option>';
                                            }
                                        } 
                                    ?>
                                    <select id="store_country" name="store_country" class="form-control">
                                        <option value="0" selected disabled>Select Country</option>
                                        <?php echo $opt; ?>
                                    </select>
                                </div>

                                <div class="form-group">
                                    {{ Form::label('store_state', 'Select Store State') }}
                                    <span class="error">* 
                                        @if ($errors->has('store_state'))
                                            {{ $errors->first('store_state') }}
                                        @endif
                                    </span>

                                    <select id="store_state" name="store_state" disabled class="form-control">
                                        <option value="0" selected disabled>Select State</option>
                                    </select>
                                </div>

                                <div class="form-group">
                                    {{ Form::label('store_city', 'Select Store District') }}
                                    <span class="error">* 
                                        @if ($errors->has('store_city'))
                                            {{ $errors->first('store_city') }}
                                        @endif
                                    </span>

                                    <select id="store_city" name="store_city" disabled class="form-control">
                                        <option value="0" selected disabled>Select District</option>
                                    </select>
                                </div>

                                <div class="form-group">
                                    {{ Form::label('store_zipcode', 'Store Zipcode') }}
                                    <span class="error">* 
                                        @if ($errors->has('store_zipcode'))
                                            {{ $errors->first('store_zipcode') }}
                                        @endif
                                    </span>

                                    {{ Form::number('store_zipcode', Input::old('store_zipcode'), array('class' => 'form-control gj_store_zipcode','placeholder' => 'Enter Store Zipcode')) }}
                                </div>

                                <div class="form-group">
                                    {{ Form::label('meta_keyword', 'Meta Keyword') }}
                                    <span class="error"> 
                                        @if ($errors->has('meta_keyword'))
                                            {{ $errors->first('meta_keyword') }}
                                        @endif
                                    </span>

                                    <textarea name="meta_keyword" id="meta_keyword" class="form-control gj_meta_keyword" placeholder="Enter Meta Keyword in English" rows="3">{{Input::old('meta_keyword')}}</textarea>
                                </div>

                                <div class="form-group">
                                    {{ Form::label('meta_description', 'Meta Description') }}
                                    <span class="error"> 
                                        @if ($errors->has('meta_description'))
                                            {{ $errors->first('meta_description') }}
                                        @endif
                                    </span>

                                    <textarea name="meta_description" id="meta_description" class="form-control gj_meta_description" placeholder="Enter Meta Description in English" rows="3">{{Input::old('meta_description')}}</textarea>
                                </div>

                                <!-- <div class="form-group">
                                    {{ Form::label('website', 'Website') }}
                                    <span class="error"> 
                                        @if ($errors->has('website'))
                                            {{ $errors->first('website') }}
                                        @endif
                                    </span>

                                    {{ Form::text('website', Input::old('website'), array('class' => 'form-control gj_website','placeholder' => 'http://www.example.com')) }}
                                </div> -->

                                <div class="form-group">
                                    {{ Form::label('slogan', 'Slogan') }}
                                    <span class="error">* 
                                        @if ($errors->has('slogan'))
                                            {{ $errors->first('slogan') }}
                                        @endif
                                    </span>

                                    {{ Form::text('slogan', Input::old('slogan'), array('class' => 'form-control gj_slogan','placeholder' => 'Enter Slogan')) }}
                                </div>

                                <div class="form-group">
                                    {{ Form::label('stores_image', 'Upload Store Image') }}
                                    <span class="error">* 
                                        @if ($errors->has('stores_image'))
                                            {{ $errors->first('stores_image') }}
                                        @endif
                                    </span>
                                    <p class="gj_not" style="color:red"><em>image size must be 455 x 378 pixels</em></p>

                                    <input type="file" name="stores_image" id="stores_image" accept="image/*" class="gj_stores_image">
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
        $('p.alert').delay(5000).slideUp(500); 
        $("#store_country").select2();
        $("#store_city").select2();
    });
    
    $('#store_country').on('change',function() {
        var country = $(this).val();
        if(country) {
            $.ajax({
                type: 'post',
                url: '{{url('/select_state')}}',
                data: {country: country, type: 'store_state'},
                success: function(data){
                    if(data){
                        $("#store_state").html(data);
                        $("#store_state").removeAttr("disabled");
                    } else {
                        $.confirm({
                            title: '',
                            content: 'Please Select Store Country!',
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
                        $("#store_state").prop("disabled", true);
                        $("#store_city").prop("disabled", true);
                    }
                }
            });
        } else {
            $.confirm({
                title: '',
                content: 'Please Select Store Country!',
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

    $('#store_state').on('change',function() {
        var st = $(this).val();
        if(st) {
            $.ajax({
                type: 'post',
                url: '{{url('/select_city')}}',
                data: {st: st, type: 'store_city'},
                success: function(data){
                    if(data){
                        $("#store_city").html(data);
                        $("#store_city").removeAttr("disabled");
                    } else {
                        $.confirm({
                            title: '',
                            content: 'Please Select Store State!',
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
                        $("#store_city").prop("disabled", true);
                    }
                }
            });
        } else {
            $.confirm({
                title: '',
                content: 'Please Select Store State!',
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
</script>
@endsection