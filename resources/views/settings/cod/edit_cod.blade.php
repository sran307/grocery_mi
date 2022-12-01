@extends('layouts.master')
@section('title', 'Edit COD')
@section('content')
<section class="gj_edt_cod_setting">
    <div class="row gj_row">
        <div class="col-md-3 col-sm-3 col-xs-12">
            @include('layouts.sidebar')
        </div>

        <div class="col-md-9 col-sm-9 col-xs-12">
            <!-- <div class="row">
                <div class="col-lg-12">
                    <ul class="breadcrumb">
                        <li class=""><a> Home  </a></li>
                        <li class="active"><a> Edit COD  </a></li>
                    </ul>
                </div>
            </div> -->

            <div class="gj_box dark">
                <header>
                    <div class="gj_icons"><i class="fa fa-edit"></i></div>
                    <h5 class="gj_heading"> Edit COD Setting  </h5>
                </header>

                <div class="col-md-12">
                    {{ Form::open(array('url' => 'edit_cod','class'=>'gj_cod_form','files' => true)) }}
                        @if($cod)
                            {{ Form::hidden('cod_id', $cod->id, array('class' => 'form-control gj_cod_id')) }}
                        @endif

                        <div class="form-group">
                            {{ Form::label('above_amount', 'COD Amount') }}
                            <span class="error">* 
                                @if ($errors->has('above_amount'))
                                    {{ $errors->first('above_amount') }}
                                @endif
                            </span>

                            {{ Form::text('above_amount', ($cod->above_amount ? $cod->above_amount : Input::old('above_amount')), array('class' => 'form-control gj_above_amount','placeholder' => 'Enter COD Amount')) }}
                            <p class="gj_ex_cod">Eg:2500 (This Amount is calculate for Net Amount Above 2500 and set to COD charges is particular COD Amount.)</p>
                        </div>

                        <div class="form-group">
                            {{ Form::label('cod_amount', 'COD Charge') }}
                            <span class="error">* 
                                @if ($errors->has('cod_amount'))
                                    {{ $errors->first('cod_amount') }}
                                @endif
                            </span>

                            {{ Form::text('cod_amount', ($cod->cod_amount ? $cod->cod_amount : Input::old('cod_amount')), array('class' => 'form-control gj_cod_amount','placeholder' => 'Enter COD Charge')) }}
                        </div>

                        <div class="form-group">
                            {{ Form::label('remarks', 'Remarks') }}
                            <span class="error"> 
                                @if ($errors->has('remarks'))
                                    {{ $errors->first('remarks') }}
                                @endif
                            </span>

                            {{ Form::textarea('remarks', ($cod->remarks ? $cod->remarks : Input::old('remarks')), array('class' => 'form-control gj_remarks', 'rows' => '5', 'placeholder' => 'Enter Remarks')) }}
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
        $('p.alert').delay(3000).slideUp(500);
</script>
@endsection
