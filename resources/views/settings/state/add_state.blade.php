@extends('layouts.master')
@section('title', 'Add State')
@section('content')
<section class="gj_state_setting">
    <div class="row gj_row">
        <div class="col-md-3 col-sm-3 col-xs-12">
            @include('layouts.sidebar')
        </div>

        <div class="col-md-9 col-sm-9 col-xs-12">
            <!-- <div class="row">
                <div class="col-lg-12">
                    <ul class="breadcrumb">
                        <li class=""><a> Home  </a></li>
                        <li class="active"><a> Add State  </a></li>
                    </ul>
                </div>
            </div> -->

            <div class="gj_box dark">
                @if(Session::has('message'))
                    <p class="alert {{ Session::get('alert-class', 'alert-info') }}">{{ Session::get('message') }}</p>
                @endif
                <header>
                    <div class="gj_icons"><i class="fa fa-edit"></i></div>
                    <h5 class="gj_heading"> Add State  </h5>
                </header>

                <div class="col-md-12">
                    {{ Form::open(array('url' => 'add_state','class'=>'gj_state_form','files' => true)) }}
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
                                        $opt.='<option value="'.$value->id.'">'.$value->country_name.'</option>';
                                    }
                                } 
                            ?>
                            <select id="country" name="country" class="form-control">
                                <option value="0" selected disabled>Select Country</option>
                                <?php echo $opt; ?>
                            </select>
                        </div>                        

                        <div class="form-group">
                            {{ Form::label('state', 'State') }}
                            <span class="error">* 
                                @if ($errors->has('state'))
                                    {{ $errors->first('state') }}
                                @endif
                            </span>

                            {{ Form::text('state', Input::old('state'), array('class' => 'form-control gj_state','placeholder' => 'Enter State in English')) }}
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
    });
</script>
@endsection
