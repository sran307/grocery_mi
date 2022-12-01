@extends('layouts.master')
@section('title', 'Add Size')
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
                    <h5 class="gj_heading"> Add Package Dimension  </h5>
                </header>

                <div class="col-md-12">
                    {{ Form::open(array('url' => 'store_package_dimension','class'=>'gj_size_form','files' => true)) }}
                        <div class="form-group">
                            {{ Form::label('size', 'Package Title') }}
                            <span class="error">* 
                                @if ($errors->has('title'))
                                    {{ $errors->first('title') }}
                                @endif
                            </span>

                            {{ Form::text('title', Input::old('title'), array('class' => 'form-control gj_size','placeholder' => 'Title')) }}
                        </div>
                        <div class="form-group">
                            {{ Form::label('size', 'Package Type') }}
                            <span class="error">* 
                                @if ($errors->has('type'))
                                    {{ $errors->first('type') }}
                                @endif
                            </span>

                            {{ Form::text('type', Input::old('type'), array('class' => 'form-control gj_size','placeholder' => 'Type')) }}
                        </div>
                        <div class="form-group">
                            {{ Form::label('size', 'Height') }}
                            <span class="error">* 
                                @if ($errors->has('height'))
                                    {{ $errors->first('height') }}
                                @endif
                            </span>

                            {{ Form::number('height', Input::old('height'), array('class' => 'form-control gj_size','placeholder' => 'Height')) }}
                        </div>
                        <div class="form-group">
                            {{ Form::label('size', 'Width') }}
                            <span class="error">* 
                                @if ($errors->has('width'))
                                    {{ $errors->first('width') }}
                                @endif
                            </span>

                            {{ Form::number('width', Input::old('width'), array('class' => 'form-control gj_size','placeholder' => 'Width')) }}
                        </div>
                        <div class="form-group">
                            {{ Form::label('size', 'Length') }}
                            <span class="error">* 
                                @if ($errors->has('length'))
                                    {{ $errors->first('length') }}
                                @endif
                            </span>

                            {{ Form::number('length', Input::old('length'), array('class' => 'form-control gj_size','placeholder' => 'Length')) }}
                        </div>
                        <div class="form-group">
                            {{ Form::label('size', 'Price') }}
                            <span class="error">* 
                                @if ($errors->has('price'))
                                    {{ $errors->first('price') }}
                                @endif
                            </span>

                            {{ Form::number('price', Input::old('price'), array('class' => 'form-control gj_size','placeholder' => 'Price')) }}
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
