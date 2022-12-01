@extends('layouts.master')
@section('title', 'Add Tax CutOff')
@section('content')
<section class="gj_add_cotax_setting">
    <div class="row gj_row">
        <div class="col-md-3 col-sm-3 col-xs-12">
            @include('layouts.sidebar')
        </div>

        <div class="col-md-9 col-sm-9 col-xs-12">
            <!-- <div class="row">
                <div class="col-lg-12">
                    <ul class="breadcrumb">
                        <li class=""><a> Home  </a></li>
                        <li class="active"><a> Add Tax CutOff  </a></li>
                    </ul>
                </div>
            </div> -->

            <div class="gj_box dark">
                @if(Session::has('message'))
                    <p class="alert {{ Session::get('alert-class', 'alert-info') }}">{{ Session::get('message') }}</p>
                @endif
                <header>
                    <div class="gj_icons"><i class="fa fa-edit"></i></div>
                    <h5 class="gj_heading"> Add Tax CutOff  </h5>
                </header>

                <div class="col-md-12">
                    {{ Form::open(array('url' => 'add_cutoff','class'=>'gj_cutoff_form','files' => true)) }}
                        <div class="form-group">
                            {{ Form::label('above_amount', 'Cut-Off Amount') }}
                            <span class="error">* 
                                @if ($errors->has('above_amount'))
                                    {{ $errors->first('above_amount') }}
                                @endif
                            </span>

                            {{ Form::text('above_amount', Input::old('above_amount'), array('class' => 'form-control gj_above_amount','placeholder' => 'Enter Cut-Off Amount')) }}
                            <p class="gj_ex_cutoff">Eg:2500 (This Amount is calculate for Cutoff Above 2500 in total value to set shiping charges particular rate.)</p>
                        </div>

                        <div class="form-group">
                            {{ Form::label('shiping_amount', 'Shiping Amount') }}
                            <span class="error">* 
                                @if ($errors->has('shiping_amount'))
                                    {{ $errors->first('shiping_amount') }}
                                @endif
                            </span>

                            {{ Form::text('shiping_amount', Input::old('shiping_amount'), array('class' => 'form-control gj_shiping_amount','placeholder' => 'Enter Shiping Amount')) }}
                        </div>

                        <div class="form-group">
                            {{ Form::label('remarks', 'Remarks') }}
                            <span class="error"> 
                                @if ($errors->has('remarks'))
                                    {{ $errors->first('remarks') }}
                                @endif
                            </span>

                            {{ Form::textarea('remarks', Input::old('remarks'), array('class' => 'form-control gj_remarks', 'rows' => '5', 'placeholder' => 'Enter Remarks')) }}
                        </div>

                        {{ Form::submit('Save', array('class' => 'btn btn-primary')) }}

                    {{ Form::close() }}
                </div>
            </div>
        </div>
    </div>
</section>

<script>
    $(document).ready(function() { 
        $('p.alert').delay(3000).slideUp(500); 
    });
</script>
@endsection
