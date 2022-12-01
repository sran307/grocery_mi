@extends('layouts.master')
@section('title', 'E-Mail & Contact Settings')
@section('content')
<section class="gj_email_setting">
    <div class="row gj_row">
        <div class="col-md-3 col-sm-3 col-xs-12">
            @include('layouts.sidebar')
        </div>

        <div class="col-md-9 col-sm-9 col-xs-12">
            <div class="row">
                <div class="col-lg-12">
                    <!-- <ul class="breadcrumb">
                        <li class=""><a> Home  </a></li>
                        <li class="active"><a> E-Mail & Contact Settings  </a></li>
                    </ul> -->
                    @if(Session::has('message'))
                        <p class="alert gj_bk_alt {{ Session::get('alert-class', 'alert-info') }}">{{ Session::get('message') }}</p>
                    @endif
                </div>
            </div>

            <div class="gj_box dark">
                <header>
                    <div class="gj_icons"><i class="fa fa-edit"></i></div>
                    <h5 class="gj_heading"> E-Mail & Contact Settings  </h5>
                </header>

                <div class="col-md-12">
                    {{ Form::open(array('url' => 'email_setting','class'=>'gj_geneal_form')) }}
                        <div class="form-group">
                            {{ Form::label('contact_name', 'Contact Name') }}
                            <span class="error">* 
                                @if ($errors->has('contact_name'))
                                    {{ $errors->first('contact_name') }}
                                @endif
                            </span>
                            @if(isset($email))
                                {{ Form::hidden('id', ($email->id ? $email->id : ''), array('class' => 'form-control')) }}

                                {{ Form::text('contact_name', ($email->contact_name ? $email->contact_name : ''), array('class' => 'form-control')) }}
                            @else
                                {{ Form::hidden('id', Input::old('id'), array('class' => 'form-control')) }}

                                {{ Form::text('contact_name', Input::old('contact_name'), array('class' => 'form-control')) }}
                            @endif
                        </div>

                        <div class="form-group">
                            {{ Form::label('contact_email', 'Contact E-Mail') }}
                            <span class="error">* 
                                @if ($errors->has('contact_email'))
                                    {{ $errors->first('contact_email') }}
                                @endif
                            </span>

                            @if(isset($email))
                                {{ Form::email('contact_email', ($email->contact_email ? $email->contact_email : ''), array('class' => 'form-control')) }}
                            @else
                                {{ Form::email('contact_email', Input::old('contact_email'), array('class' => 'form-control')) }}
                            @endif
                        </div>

                        <div class="form-group">
                            {{ Form::label('skype_email', 'Skype E-Mail') }}
                            <span class="error"> 
                                @if ($errors->has('skype_email'))
                                    {{ $errors->first('skype_email') }}
                                @endif
                            </span>

                            @if(isset($email))
                                {{ Form::email('skype_email', ($email->skype_email ? $email->skype_email : ''), array('class' => 'form-control')) }}
                            @else
                                {{ Form::email('skype_email', Input::old('skype_email'), array('class' => 'form-control')) }}
                            @endif
                        </div>

                        <div class="form-group">
                            {{ Form::label('webmaster_email', 'Webmaster E-Mail') }}
                            <span class="error">* 
                                @if ($errors->has('webmaster_email'))
                                    {{ $errors->first('webmaster_email') }}
                                @endif
                            </span>

                            @if(isset($email))
                                {{ Form::email('webmaster_email', ($email->webmaster_email ? $email->webmaster_email : ''), array('class' => 'form-control')) }}
                            @else
                                {{ Form::email('webmaster_email', Input::old('webmaster_email'), array('class' => 'form-control')) }}
                            @endif
                        </div>

                        <div class="form-group">
                            {{ Form::label('site_no_reply_email', 'Site No Reply E-Mail') }}
                            <span class="error">* 
                                @if ($errors->has('site_no_reply_email'))
                                    {{ $errors->first('site_no_reply_email') }}
                                @endif
                            </span>

                            @if(isset($email))
                                {{ Form::email('site_no_reply_email', ($email->site_no_reply_email ? $email->site_no_reply_email : ''), array('class' => 'form-control')) }}
                            @else
                                {{ Form::email('site_no_reply_email', Input::old('site_no_reply_email'), array('class' => 'form-control')) }}
                            @endif
                        </div>

                        <div class="form-group">
                            {{ Form::label('contact_phone1', 'Contact Phone1') }}
                            <span class="error">* 
                                @if ($errors->has('contact_phone1'))
                                    {{ $errors->first('contact_phone1') }}
                                @endif
                            </span>

                            @if(isset($email))
                                {{ Form::text('contact_phone1', ($email->contact_phone1 ? $email->contact_phone1 : ''), array('class' => 'form-control')) }}
                            @else
                                {{ Form::text('contact_phone1', Input::old('contact_phone1'), array('class' => 'form-control')) }}
                            @endif
                            <p class="gj_ex_ph">Example: +91 123-4567-890</p>
                        </div>

                        <div class="form-group">
                            {{ Form::label('contact_phone2', 'Contact Phone 2') }}
                            <span class="error">* 
                                @if ($errors->has('contact_phone2'))
                                    {{ $errors->first('contact_phone2') }}
                                @endif
                            </span>

                            @if(isset($email))
                                {{ Form::text('contact_phone2', ($email->contact_phone2 ? $email->contact_phone2 : ''), array('class' => 'form-control')) }}
                            @else
                                {{ Form::text('contact_phone2', Input::old('contact_phone2'), array('class' => 'form-control')) }}
                            @endif
                        </div>

                        <div class="form-group">
                            {{ Form::label('address1', 'Address1') }}
                            <span class="error">* 
                                @if ($errors->has('address1'))
                                    {{ $errors->first('address1') }}
                                @endif
                            </span>
                            @if(isset($email))
                                {{ Form::text('address1', ($email->address1 ? $email->address1 : ''), array('class' => 'form-control')) }}
                            @else
                                {{ Form::text('address1', Input::old('address1'), array('class' => 'form-control')) }}
                            @endif
                        </div>

                        <div class="form-group">
                            {{ Form::label('address2', 'City') }}
                            <span class="error">* 
                                @if ($errors->has('address2'))
                                    {{ $errors->first('address2') }}
                                @endif
                            </span>
                            @if(isset($email))
                                {{ Form::text('address2', ($email->address2 ? $email->address2 : ''), array('class' => 'form-control')) }}
                            @else
                                {{ Form::text('address2', Input::old('address2'), array('class' => 'form-control')) }}
                            @endif
                        </div>

                        <div class="form-group">
                            {{ Form::label('pincode', 'Pincode') }}
                            <span class="error">* 
                                @if ($errors->has('pincode'))
                                    {{ $errors->first('pincode') }}
                                @endif
                            </span>
                            @if(isset($email))
                                {{ Form::text('pincode', ($email->pincode ? $email->pincode : ''), array('class' => 'form-control')) }}
                            @else
                                {{ Form::text('pincode', Input::old('pincode'), array('class' => 'form-control')) }}
                            @endif
                        </div>

                        <div class="form-group">
                            {{ Form::label('country', 'Select Country') }}
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
                                        if ($value->id == $email->country) {
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
                        </div>
                        <div class="form-group">
                            {{ Form::label('city', 'Google Map Iframe') }}
                            <span class="error">* 
                                @if ($errors->has('google_map'))
                                    {{ $errors->first('google_map') }}
                                @endif
                            </span>

                            @if(isset($email))
                                {{ Form::textarea('google_map', ($email->google_map ? $email->google_map : ''), array('class' => 'form-control')) }}
                            @else
                                {{ Form::textarea('google_map', Input::old('google_map'), array('class' => 'form-control')) }}
                            @endif
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
        $("#country").select2();
        $("#state").select2();
        $("#city").select2();

        var country = $('#country').select2('val');
        @if($email->state)
            var state = <?php echo $email->state; ?>;
        @else
            var state = 0;
        @endif

        @if($email->city)
            var city = <?php echo $email->city; ?>;
        @else
            var city = 0;
        @endif

        if(city) {
            city = city;          
        } else {
            city = 0;
        }

        if(country) {
            $.ajax({
                type: 'post',
                url: '{{url('/select_state')}}',
                data: {country: country, state: state, type: 'state'},
                success: function(data){
                    if(data){
                        $("#state").html(data);
                        $("#state").removeAttr("disabled");

                        var st = $('#state').val();
                        if(st) {
                            $.ajax({
                                type: 'post',
                                url: '{{url('/select_city')}}',
                                data: {st: st, city: city, type: 'city'},
                                success: function(data){
                                    if(data){
                                        $("#city").html(data);
                                        $("#city").removeAttr("disabled");
                                    } else {
                                        $.confirm({
                                            title: '',
                                            content: 'Please Select State!',
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
                                        $("#city").prop("disabled", true);
                                    }
                                }
                            });
                        } else {
                            $.confirm({
                                title: '',
                                content: 'Please Select State!',
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
                            content: 'Please Select Country!',
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
                        $("#state").prop("disabled", true);
                        $("#city").prop("disabled", true);
                    }
                }
            });
        } else {
            $.confirm({
                title: '',
                content: 'Please Select Country!',
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

    $('#country').on('change',function() {
        var country = $(this).val();
        if(country) {
            $.ajax({
                type: 'post',
                url: '{{url('/select_state')}}',
                data: {country: country, type: 'state'},
                success: function(data){
                    if(data){
                        $("#state").html(data);
                        $("#state").removeAttr("disabled");
                    } else {
                        $.confirm({
                            title: '',
                            content: 'Please Select Country!',
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
                        $("#state").prop("disabled", true);
                        $("#city").prop("disabled", true);
                    }
                }
            });
        } else {
            $.confirm({
                title: '',
                content: 'Please Select Country!',
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

    $('#state').on('change',function() {
        var st = $(this).val();
        if(st) {
            $.ajax({
                type: 'post',
                url: '{{url('/select_city')}}',
                data: {st: st, type: 'city'},
                success: function(data){
                    if(data){
                        $("#city").html(data);
                        $("#city").removeAttr("disabled");
                    } else {
                        $.confirm({
                            title: '',
                            content: 'Please Select State!',
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
                        $("#city").prop("disabled", true);
                    }
                }
            });
        } else {
            $.confirm({
                title: '',
                content: 'Please Select State!',
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

<script type="text/javascript">
    $('p.gj_bk_alt').delay(5000).slideUp(700);
</script>
@endsection