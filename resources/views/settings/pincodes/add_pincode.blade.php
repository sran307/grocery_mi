@extends('layouts.master')
@section('title', 'Add Pincode')
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
                        <li class="active"><a> Add Size  </a></li>
                    </ul>
                </div>
            </div> -->

            <div class="gj_box dark">
                @if(Session::has('message'))
                    <p class="alert {{ Session::get('alert-class', 'alert-info') }}">{{ Session::get('message') }}</p>
                @endif
                <header>
                    <div class="gj_icons"><i class="fa fa-edit"></i></div>
                    <h5 class="gj_heading"> Add Pincode  </h5>
                </header>

                <div class="col-md-12">
                    {{ Form::open(array('url' => 'store_pincode','class'=>'gj_size_form','files' => true)) }}
                        <div class="form-group">
                            {{ Form::label('size', 'Pincode') }}
                            <span class="error">* 
                                @if ($errors->has('pincode'))
                                    {{ $errors->first('pincode') }}
                                @endif
                            </span>

                            {{ Form::number('pincode', Input::old('pincode'), array('class' => 'form-control gj_size','placeholder' => 'Pincode','maxlength'=>'6')) }}
                        </div>
                        <div class="form-group">
                            {{ Form::label('size', 'Division Name') }}
                            <span class="error">* 
                                @if ($errors->has('divisionname'))
                                    {{ $errors->first('divisionname') }}
                                @endif
                            </span>

                            {{ Form::text('divisionname', Input::old('divisionname'), array('class' => 'form-control gj_size','placeholder' => 'Division Name')) }}
                        </div>
                        <div class="form-group">
                            {{ Form::label('size', 'Region Name') }}
                            <span class="error">* 
                                @if ($errors->has('regionname'))
                                    {{ $errors->first('regionname') }}
                                @endif
                            </span>

                            {{ Form::text('regionname', Input::old('regionname'), array('class' => 'form-control gj_size','placeholder' => 'Region Name')) }}
                        </div>
                         <div class="form-group">
                            {{ Form::label('size', 'Circle Name') }}
                            <span class="error">* 
                                @if ($errors->has('circlename'))
                                    {{ $errors->first('circlename') }}
                                @endif
                            </span>

                            {{ Form::text('circlename', Input::old('circlename'), array('class' => 'form-control gj_size','placeholder' => 'Circle Name')) }}
                        </div>
                        <div class="form-group">
                            {{ Form::label('size', 'Taluk Name') }}
                            <span class="error">* 
                                @if ($errors->has('taluk'))
                                    {{ $errors->first('taluk') }}
                                @endif
                            </span>

                            {{ Form::text('taluk', Input::old('taluk'), array('class' => 'form-control gj_size','placeholder' => 'Taluk Name')) }}
                        </div>
                         <div class="form-group">
                            {{ Form::label('size', 'District Name') }}
                            <span class="error">* 
                                @if ($errors->has('districtname'))
                                    {{ $errors->first('districtname') }}
                                @endif
                            </span>

                            {{ Form::text('districtname', Input::old('districtname'), array('class' => 'form-control gj_size','placeholder' => 'District Name')) }}
                        </div>
                        <div class="form-group">
                            {{ Form::label('size', 'State Name') }}
                            <span class="error">* 
                                @if ($errors->has('statename'))
                                    {{ $errors->first('statename') }}
                                @endif
                            </span>

                            {{ Form::text('statename', Input::old('statename'), array('class' => 'form-control gj_size','placeholder' => 'State Name')) }}
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
        // $("#size").select2();
    });
</script>
@endsection
