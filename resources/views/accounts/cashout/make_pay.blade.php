@extends('layouts.master')
@section('title', 'Make Payment')
@section('content')

<?php $log = session()->get('user'); ?>

<section class="gj_mke_setting">
    <div class="row gj_row">
        <div class="col-md-3 col-sm-3 col-xs-12">
            @include('layouts.account_sidebar')
        </div>
        <div class="col-md-9 col-sm-9 col-xs-12">
            <!-- <div class="row">
                <div class="col-lg-12">
                    <ul class="breadcrumb">
                        <li class=""><a> Home  </a></li>
                        <li class="active"><a> Make Payment  </a></li>
                    </ul>
                </div>
            </div> -->

            <div class="gj_box dark">
                @if(Session::has('message'))
                    <p class="alert {{ Session::get('alert-class', 'alert-info') }}">{{ Session::get('message') }}</p>
                @endif
                <header>
                    <div class="gj_icons"><i class="fa fa-edit"></i></div>
                    <h5 class="gj_heading"> Make Payment  </h5>
                </header>

                <div class="col-md-12">
                    {{ Form::open(array('url' => 'process_pay','class'=>'gj_prspay_form','files' => true)) }}
                        <div class="form-group">
                            {{ Form::label('bank', 'Select Bank') }}
                            <span class="error">* 
                                @if ($errors->has('bank'))
                                    {{ $errors->first('bank') }}
                                @endif
                            </span>

                            {{ Form::hidden('cash_id', ($cash->id ? $cash->id : ''), array('class' => 'form-control gj_cash_id', 'placeholder' => 'Cash ID')) }}

                            <?php 
                                $opt = '<option value="0">Select Bank</option>';
                                if(($cash->banks) && (sizeof($cash->banks) != 0)){
                                    foreach ($cash->banks as $key => $value) {
                                        if($value->default == 1) {
                                            $opt.='<option selected value="'.$value->id.'">'.$value->bank_name.'</option>';
                                        } else {
                                           $opt.='<option value="'.$value->id.'">'.$value->bank_name.'</option>';
                                        }
                                    }
                                } 
                            ?>
                            <select id="bank" name="bank" class="form-control">
                                <?php echo $opt; ?>
                            </select>
                        </div>

                        <div class="form-group">
                            {{ Form::label('bal_amount_tranfer', 'Amount To Be Transfer') }}
                            <span class="error">* 
                                @if ($errors->has('bal_amount_tranfer'))
                                    {{ $errors->first('bal_amount_tranfer') }}
                                @endif
                            </span>

                            {{ Form::text('bal_d_amount_tranfer', ($cash->balance ? $cash->balance : 0.00), array('class' => 'form-control bal_gj_d_amount_tranfer', 'disabled','placeholder' => 'Enter Request Amount in rupees')) }}

                            {{ Form::hidden('bal_amount_tranfer', ($cash->balance ? $cash->balance : 0.00), array('class' => 'form-control bal_gj_amount_tranfer', 'placeholder' => 'Enter Request Amount in rupees')) }}
                        </div>

                        <div class="form-group">
                            {{ Form::label('amount_paid', 'Amount Transfer') }}
                            <span class="error">* 
                                @if ($errors->has('amount_paid'))
                                    {{ $errors->first('amount_paid') }}
                                @endif
                            </span>

                            {{ Form::text('amount_paid', Input::old('amount_paid'), array('class' => 'form-control gj_amount_paid', 'placeholder' => 'Enter Transfer Amount in rupees')) }}
                        </div>

                        <div class="form-group">
                            {{ Form::label('balance', 'Balance Amount') }}
                            <span class="error">* 
                                @if ($errors->has('balance'))
                                    {{ $errors->first('balance') }}
                                @endif
                            </span>

                            {{ Form::text('d_balance', 0.00, array('class' => 'form-control gj_d_balance', 'disabled','placeholder' => 'Balance Amount in rupees')) }}

                            {{ Form::hidden('balance', 0.00, array('class' => 'form-control gj_balance', 'placeholder' => 'Balance Amount in rupees')) }}
                        </div>

                        <div class="form-group">
                            {{ Form::label('remarks', 'Remarks') }}
                            <span class="error">* 
                                @if ($errors->has('remarks'))
                                    {{ $errors->first('remarks') }}
                                @endif
                            </span>

                            {{ Form::textarea('remarks', Input::old('remarks'), array('class' => 'form-control gj_remarks', 'rows' => '5', 'placeholder' => 'Enter Your Remarks')) }}
                        </div>

                        <div class="form-group">
                            {{ Form::label('pay_mode', 'Payment Mode') }}
                            <span class="error">* 
                                @if ($errors->has('pay_mode'))
                                    {{ $errors->first('pay_mode') }}
                                @endif
                            </span>

                            <div class="gj_py_ro_div">
                                <span class="gj_py_ro">
                                    <input type="radio" checked name="pay_mode" value="1"> Cheque
                                </span>
                                <span class="gj_py_ro">
                                    <input type="radio" name="pay_mode" value="2"> Bank Transfer
                                </span>
                            </div>
                        </div>

                        <div class="gj_cheque_dets">
                            <div class="form-group">
                                {{ Form::label('cheque_no', 'Cheque Number') }}
                                <span class="error">* 
                                    @if ($errors->has('cheque_no'))
                                        {{ $errors->first('cheque_no') }}
                                    @endif
                                </span>

                                {{ Form::text('cheque_no', Input::old('cheque_no'), array('class' => 'form-control gj_cheque_no', 'placeholder' => 'Enter Cheque Number')) }}
                            </div>

                            <div class="form-group">
                                {{ Form::label('bank_name', 'Bank Name') }}
                                <span class="error">* 
                                    @if ($errors->has('bank_name'))
                                        {{ $errors->first('bank_name') }}
                                    @endif
                                </span>

                                {{ Form::text('bank_name', Input::old('bank_name'), array('class' => 'form-control gj_bank_name', 'placeholder' => 'Enter Bank Name')) }}
                            </div>

                            <div class="form-group">
                                {{ Form::label('branch_name', 'Branch Name') }}
                                <span class="error">* 
                                    @if ($errors->has('branch_name'))
                                        {{ $errors->first('branch_name') }}
                                    @endif
                                </span>

                                {{ Form::text('branch_name', Input::old('branch_name'), array('class' => 'form-control gj_branch_name', 'placeholder' => 'Enter Branch Name')) }}
                            </div>

                            <div class="form-group">
                                {{ Form::label('cheque_img', 'Upload Cheque Image') }}
                                <span class="error"> 
                                    @if ($errors->has('cheque_img'))
                                        {{ $errors->first('cheque_img') }}
                                    @endif
                                </span>

                                <input type="file" name="cheque_img" id="cheque_img" accept="image/*" class="gj_cheque_img">
                            </div>
                        </div>

                        <div class="gj_bnktfr_dets">
                            <div class="form-group">
                                {{ Form::label('receipt', 'Upload Receipt') }}
                                <span class="error">* 
                                    @if ($errors->has('receipt'))
                                        {{ $errors->first('receipt') }}
                                    @endif
                                </span>

                                <input type="file" name="receipt" id="receipt" class="gj_receipt">
                            </div>
                        </div>

                        {{ Form::submit('Process Payment', array('class' => 'btn btn-primary', 'id'=>'gj_save_pay')) }}

                    {{ Form::close() }}
                </div>
            </div>
        </div>
    </div>
</section>

<script>
    $(document).ready(function() { 
        $('#gj_all_cash_table').dataTable({
            "paginate": true,
            "searching": true,
            "bInfo" : false,
            "sort": true
        });
        $('#bank').select2();

        $('p.alert').delay(5000).slideUp(500);
    });

    $(document).ready(function () {
        /*pay mode change script start*/
        if ($("input[type=radio][name='pay_mode']:checked").val() == 1) {
            $('.gj_cheque_dets').slideDown();
            $('.gj_bnktfr_dets').slideUp();
        } else if ($("input[type=radio][name='pay_mode']:checked").val() == 2) {
            $('.gj_bnktfr_dets').slideDown();
            $('.gj_cheque_dets').slideUp();
        } else {
            $('.gj_bnktfr_dets').hide();
            $('.gj_cheque_dets').hide();
        }

        $('input[type=radio][name=pay_mode]').on('click',function(){
            if ($("input[type=radio][name='pay_mode']:checked").val() == 1) {
                $('.gj_cheque_dets').slideDown();
                $('.gj_bnktfr_dets').slideUp();
            } else if ($("input[type=radio][name='pay_mode']:checked").val() == 2) {
                $('.gj_bnktfr_dets').slideDown();
                $('.gj_cheque_dets').slideUp();
            } else {
                $('.gj_bnktfr_dets').hide();
                $('.gj_cheque_dets').hide();
            }
        });
        /*pay mode change Script End*/

        /*Transfer Balance change Script Start*/
        $('#amount_paid').on('change',function() {
            var am1 = 0;
            var am2 = 0;
            var bal = 0;

            if ($('#bal_amount_tranfer').val()) {
                if (!$.isNumeric($('#bal_amount_tranfer').val())) {
                    am1 = 0;
                    $('#bal_amount_tranfer').val(am1);
                    $('#bal_d_amount_tranfer').val(am1);
                    $.confirm({
                        title: '',
                        content: 'Value must be numeric!',
                        icon: 'fa fa-exclamation',
                        theme: 'modern',
                        closeIcon: true,
                        animation: 'scale',
                        type: 'purple',
                        buttons: {
                            Ok: function(){
                                // window.location.reload();
                            }
                        }
                    });
                } else {
                    am1 = parseFloat($('#bal_amount_tranfer').val());
                }
            }

            if ($(this).val()) {
                if (!$.isNumeric($(this).val())) {
                    am2 = 0;
                    $(this).val(am2);
                    $.confirm({
                        title: '',
                        content: 'Paid Amount is must be a number!',
                        icon: 'fa fa-exclamation',
                        theme: 'modern',
                        closeIcon: true,
                        animation: 'scale',
                        type: 'purple',
                        buttons: {
                            Ok: function(){
                                // window.location.reload();
                            }
                        }
                    });
                } else {
                    am2 = parseFloat($(this).val());
                }
            }

            if(am1 != 0 && am2 != 0) {
                if(am1 >= am2) {
                    bal = am1 - am2;
                    bal = (bal).toFixed(2);

                    $('.gj_d_balance').val(bal);
                    $('#balance').val(bal);
                } else {
                    $.confirm({
                        title: '',
                        content: 'Paid Amount value is too big on amout to be transfer!',
                        icon: 'fa fa-exclamation',
                        theme: 'modern',
                        closeIcon: true,
                        animation: 'scale',
                        type: 'purple',
                        buttons: {
                            Ok: function(){
                                // window.location.reload();
                            }
                        }
                    });
                    $('#balance').val(0);
                    $('.gj_d_balance').val(0);
                    $(this).val(0);
                }
            } else {
                $.confirm({
                    title: '',
                    content: 'Please Enter Paid Amount!',
                    icon: 'fa fa-exclamation',
                    theme: 'modern',
                    closeIcon: true,
                    animation: 'scale',
                    type: 'purple',
                    buttons: {
                        Ok: function(){
                            // window.location.reload();
                        }
                    }
                });
            }
        });
        /*Transfer Balance change Script End*/
    });
</script>
@endsection
