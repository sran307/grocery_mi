@extends('layouts.master')
@section('title', 'Edit Bank Details')
@section('content')
<section class="gj_bank_setting">
    <div class="row gj_row">
        <div class="col-md-3 col-sm-3 col-xs-12">
            @include('layouts.account_sidebar')
        </div>

        <div class="col-md-9 col-sm-9 col-xs-12">
            <!-- <div class="row">
                <div class="col-lg-12">
                    <ul class="breadcrumb">
                        <li class=""><a> Home  </a></li>
                        <li class="active"><a> Edit Bank Details  </a></li>
                    </ul>
                </div>
            </div> -->

            <div class="gj_box dark">
                <header>
                    <div class="gj_icons"><i class="fa fa-edit"></i></div>
                    <h5 class="gj_heading"> Edit Bank Details  </h5>
                </header>

                <div class="col-md-12">
                    {{ Form::open(array('url' => 'edit_bank_details','class'=>'gj_bnk_det_form','files' => true)) }}
                        @if($banks)
                            {{ Form::hidden('banks_id', $banks->id, array('class' => 'form-control gj_banks_id')) }}
                        @endif

                        <div class="form-group">
                            {{ Form::label('ac_no', 'A/C No') }}
                            <span class="error">* 
                                @if ($errors->has('ac_no'))
                                    {{ $errors->first('ac_no') }}
                                @endif
                            </span>

                            {{ Form::text('ac_no', ($banks->ac_no ? $banks->ac_no : Input::old('ac_no')), array('class' => 'form-control gj_ac_no','placeholder' => 'Enter Bank A/C No')) }}
                        </div>

                        <div class="form-group">
                            {{ Form::label('ac_name', 'A/C Holder Name') }}
                            <span class="error">* 
                                @if ($errors->has('ac_name'))
                                    {{ $errors->first('ac_name') }}
                                @endif
                            </span>

                            {{ Form::text('ac_name', ($banks->ac_name ? $banks->ac_name : Input::old('ac_name')), array('class' => 'form-control gj_ac_name','placeholder' => 'Enter Bank A/C Holder Name')) }}
                        </div>

                        <div class="form-group">
                            {{ Form::label('ac_type', 'A/C Type') }}
                            <span class="error">* 
                                @if ($errors->has('ac_type'))
                                    {{ $errors->first('ac_type') }}
                                @endif
                            </span>

                            {{ Form::text('ac_type', ($banks->ac_type ? $banks->ac_type : Input::old('ac_type')), array('class' => 'form-control gj_ac_type','placeholder' => 'Enter Bank A/C Type')) }}
                        </div>  

                        <div class="form-group">
                            {{ Form::label('bank_name', 'Bank Name') }}
                            <span class="error">* 
                                @if ($errors->has('bank_name'))
                                    {{ $errors->first('bank_name') }}
                                @endif
                            </span>

                            {{ Form::text('bank_name', ($banks->bank_name ? $banks->bank_name : Input::old('bank_name')), array('class' => 'form-control gj_bank_name','placeholder' => 'Enter Bank Name')) }}
                        </div> 

                        <div class="form-group">
                            {{ Form::label('bank_branch', 'Branch Name') }}
                            <span class="error">* 
                                @if ($errors->has('bank_branch'))
                                    {{ $errors->first('bank_branch') }}
                                @endif
                            </span>

                            {{ Form::text('bank_branch', ($banks->bank_branch ? $banks->bank_branch : Input::old('bank_branch')), array('class' => 'form-control gj_bank_branch','placeholder' => 'Enter Bank Branch Name')) }}
                        </div>

                        <div class="form-group">
                            {{ Form::label('bank_ifsc', 'IFSC Code') }}
                            <span class="error">* 
                                @if ($errors->has('bank_ifsc'))
                                    {{ $errors->first('bank_ifsc') }}
                                @endif
                            </span>

                            {{ Form::text('bank_ifsc', ($banks->bank_ifsc ? $banks->bank_ifsc : Input::old('bank_ifsc')), array('class' => 'form-control gj_bank_ifsc','placeholder' => 'Enter Bank IFSC Code')) }}
                        </div> 

                        <div class="form-group">
                            {{ Form::label('remarks', 'Remarks') }}
                            <span class="error">* 
                                @if ($errors->has('remarks'))
                                    {{ $errors->first('remarks') }}
                                @endif
                            </span>
                            {{ Form::hidden('default', ($banks->default ? $banks->default : 0), array('class' => 'form-control gj_default','placeholder' => 'Enter Default')) }}

                            {{ Form::textarea('remarks', ($banks->remarks ? $banks->remarks : Input::old('remarks')), array('class' => 'form-control gj_remarks', 'rows'=>'5','placeholder' => 'Enter Remarks')) }}
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
    });
</script>
@endsection
