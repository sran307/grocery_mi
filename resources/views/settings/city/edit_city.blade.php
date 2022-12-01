@extends('layouts.master')
@section('title', 'Edit District')
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
                        <li class="active"><a> Edit District  </a></li>
                    </ul>
                </div>
            </div> -->

            <div class="gj_box dark">
                <header>
                    <div class="gj_icons"><i class="fa fa-edit"></i></div>
                    <h5 class="gj_heading"> Edit District  </h5>
                </header>

                <div class="col-md-12">
                    {{ Form::open(array('url' => 'edit_city','class'=>'gj_city_form','files' => true)) }}
                        @if($city)
                            {{ Form::hidden('city_id', $city->id, array('class' => 'form-control gj_city_id')) }}
                        @endif

                        <div class="form-group">
                            {{ Form::label('country_name', 'Country Name') }}
                            <span class="error">* 
                                @if ($errors->has('country_name'))
                                    {{ $errors->first('country_name') }}
                                @endif
                            </span>

                            <?php 
                                $opt = '';
                                $ctys = \DB::table('countries_managements')->where('is_block',1)->get();
                                if(($ctys) && (count($ctys) != 0)){
                                    foreach ($ctys as $key => $value) {
                                        if($value->id == $city->country_name) {
                                            $opt.='<option selected value="'.$value->id.'">'.$value->country_name.'</option>';
                                        } else {
                                            $opt.='<option value="'.$value->id.'">'.$value->country_name.'</option>';
                                        }
                                    }
                                } 
                            ?>
                            <select id="country_name" name="country_name" class="form-control">
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
                            {{ Form::label('city_name', 'District Name') }}
                            <span class="error">* 
                                @if ($errors->has('city_name'))
                                    {{ $errors->first('city_name') }}
                                @endif
                            </span>

                            {{ Form::text('city_name', ($city->city_name ? $city->city_name : Input::old('city_name')), array('class' => 'form-control gj_city_name','placeholder' => 'Enter District Name in English')) }}
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
        $("#country_name").select2(); 
        $("#state").select2(); 

        var country = $('#country_name').select2('val');
        
        @if($city->state)
            var state = {{$city->state}};
        @else
            var state = 0;
        @endif
        if(state) {
            state = state;          
        } else {
            state = 0;
        }

        if(country) {
            $.ajax({
                type: 'post',
                url: '{{url('/state_details')}}',
                data: {country: country, state: state, type: 'state'},
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
                            type: 'blue',
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
                type: 'blue',
                buttons: {
                    Ok: function(){
                    }
                }
            });
        }
    });

    $('#country_name').on('change',function() {
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
                            type: 'blue',
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
