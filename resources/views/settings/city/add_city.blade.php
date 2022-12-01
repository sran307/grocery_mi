@extends('layouts.master')
@section('title', 'Add District')
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
                        <li class="active"><a> Add District  </a></li>
                    </ul>
                </div>
            </div> -->

            <div class="gj_box dark">
                @if(Session::has('message'))
                    <p class="alert {{ Session::get('alert-class', 'alert-info') }}">{{ Session::get('message') }}</p>
                @endif
                <header>
                    <div class="gj_icons"><i class="fa fa-edit"></i></div>
                    <h5 class="gj_heading"> Add District  </h5>
                </header>

                <div class="col-md-12">
                    {{ Form::open(array('url' => 'add_city','class'=>'gj_city_form','files' => true)) }}
                        <div class="form-group">
                            {{ Form::label('country_name', 'Country Name') }}
                            <span class="error">* 
                                @if ($errors->has('country_name'))
                                    {{ $errors->first('country_name') }}
                                @endif
                            </span>

                            <?php 
                                $opt = '';
                                $ctys = \DB::table('countries_managements')->where('is_block',1)->where("country_name", "India")->get();
                                if(($ctys) && (count($ctys) != 0)){
                                    foreach ($ctys as $key => $value) {
                                        $opt.='<option value="'.$value->id.'">'.$value->country_name.'</option>';
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

                            {{ Form::text('city_name', Input::old('city_name'), array('class' => 'form-control gj_city_name','placeholder' => 'Enter District Name in English')) }}
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
