@extends('layouts.master')
@section('title', 'Add Cashout Request')
@section('content')

<?php $log = session()->get('user'); ?>

<section class="gj_chreq_setting">
    <div class="row gj_row">
        <div class="col-md-3 col-sm-3 col-xs-12">
            @include('layouts.account_sidebar')
        </div>
        <div class="col-md-9 col-sm-9 col-xs-12">
            <!-- <div class="row">
                <div class="col-lg-12">
                    <ul class="breadcrumb">
                        <li class=""><a> Home  </a></li>
                        <li class="active"><a> Add Cashout Request  </a></li>
                    </ul>
                </div>
            </div> -->

            <div class="gj_box dark">
                @if(Session::has('message'))
                    <p class="alert {{ Session::get('alert-class', 'alert-info') }}">{{ Session::get('message') }}</p>
                @endif
                <header>
                    <div class="gj_icons"><i class="fa fa-edit"></i></div>
                    <h5 class="gj_heading"> Add Cashout Request  </h5>
                </header>

                <div class="col-md-12">
                    {{ Form::open(array('url' => 'add_admin_cashout','class'=>'gj_adcas_form','files' => true)) }}
                        <div class="form-group">
                            {{ Form::label('vendor', 'Select Vendors') }}
                            <span class="error">* 
                                @if ($errors->has('vendor'))
                                    {{ $errors->first('vendor') }}
                                @endif
                            </span>

                            <select name="vendor" id="vendor" class="form-control gj_chvendor">
                                <option value="">Select Vendor</option>
                                @if(isset($vendors) && sizeof($vendors) != 0)
                                    @foreach($vendors as $vk => $vv)
                                        <option value="{{$vv->id}}">{{$vv->first_name.' '.$vv->last_name}}</option>
                                    @endforeach
                                @endif 
                            </select>
                        </div>

                        <div class="gj_ven_dets"></div>

                        <div class="form-group">
                            {{ Form::label('process_type', 'Process Type') }}
                            <span class="error">* 
                                @if ($errors->has('process_type'))
                                    {{ $errors->first('process_type') }}
                                @endif
                            </span>

                            <div class="gj_py_ro_div">
                                <span class="gj_py_ro">
                                    <input type="radio" checked name="process_type" value="Deduction"> Deduction
                                </span>
                                <span class="gj_py_ro">
                                    <input type="radio" name="process_type" value="Addition"> Addition
                                </span>
                            </div>
                        </div>

                        <div class="form-group">
                            {{ Form::label('amount', 'Amount') }}
                            <span class="error">* 
                                @if ($errors->has('amount'))
                                    {{ $errors->first('amount') }}
                                @endif
                            </span>

                            {{ Form::text('amount', Input::old('amount'), array('class' => 'form-control gj_amount', 'placeholder' => 'Enter Amount')) }}
                        </div>

                        <div class="form-group">
                            {{ Form::label('reasons', 'Reasons') }}
                            <span class="error">* 
                                @if ($errors->has('reasons'))
                                    {{ $errors->first('reasons') }}
                                @endif
                            </span>

                            {{ Form::textarea('reasons', Input::old('reasons'), array('class' => 'form-control gj_reasons', 'rows' => '5', 'placeholder' => 'Enter Your Reasons')) }}
                        </div>

                        <div class="form-group">
                            {{ Form::label('credit_note', 'Select Credit Notes') }}
                            <span class="error">* 
                                @if ($errors->has('credit_note'))
                                    {{ $errors->first('credit_note') }}
                                @endif
                            </span>

                            <select name="credit_note" id="credit_note" class="form-control gj_chcredit_note">
                                <option value="">Select Credit Notes</option>
                                @if(isset($notes) && sizeof($notes) != 0)
                                    @foreach($notes as $nk => $nv)
                                        <option value="{{$nv->id}}">{{$nv->cn_code}}</option>
                                    @endforeach
                                @endif 
                            </select>
                        </div>

                        <div class="gj_cn_dets"></div>

                        <div class="form-group">
                            {{ Form::label('others', 'Other') }}
                            <span class="error"> 
                                @if ($errors->has('others'))
                                    {{ $errors->first('others') }}
                                @endif
                            </span>

                            {{ Form::textarea('others', Input::old('others'), array('class' => 'form-control gj_others', 'rows' => '5', 'placeholder' => 'Others')) }}
                        </div>

                        <div class="form-group">
                            {{ Form::label('remarks', 'Remarks') }}
                            <span class="error"> 
                                @if ($errors->has('remarks'))
                                    {{ $errors->first('remarks') }}
                                @endif
                            </span>

                            {{ Form::textarea('remarks', Input::old('remarks'), array('class' => 'form-control gj_remarks', 'rows' => '5', 'placeholder' => 'Remarks')) }}
                        </div>

                        {{ Form::submit('Save', array('class' => 'btn btn-primary', 'id'=>'gj_save_req')) }}

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

        $('p.alert').delay(5000).slideUp(500);
    });

    $(document).ready(function () {
        /*check all script start*/
        $("#ckbCheckAll").click(function () {
            $(".checkBoxClass").prop('checked', $(this).prop('checked'));
        });
        
        $(".checkBoxClass").change(function(){
            if (!$(this).prop("checked")){
                $("#ckbCheckAll").prop("checked",false);
            }
        });
        /*check all script end*/

        /*Select Vendor Script Start*/
        $('.gj_chvendor').on('change',function(){
            var id = 0;
            if($(this).val()) {
                id = $(this).val();

                $.ajax({
                    type: 'post',
                    url: '{{url('/select_vendor')}}',
                    data: {id: id, type: 'select_vendor'},
                    success: function(response){
                        if(response != 1){
                            $('.gj_ven_dets').html(response);
                        } else {
                            $.confirm({
                                title: '',
                                content: 'No Action Performed!',
                                icon: 'fa fa-exclamation',
                                theme: 'modern',
                                closeIcon: true,
                                animation: 'scale',
                                type: 'purple',
                                buttons: {
                                    Ok: function(){
                                        window.location.reload();
                                    }
                                }
                            });
                        }
                    }
                });
            } else {
                $.confirm({
                    title: '',
                    content: 'Please Select Vendor!',
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
        /*Select Vendor Script End*/

        /*Select Credit Notes Script Start*/
        $('.gj_chcredit_note').on('change',function(){
            var id = 0;
            if($(this).val()) {
                id = $(this).val();

                $.ajax({
                    type: 'post',
                    url: '{{url('/select_credit_note')}}',
                    data: {id: id, type: 'select_credit_note'},
                    success: function(response){
                        if(response != 1){
                            $('.gj_cn_dets').html(response);
                        } else {
                            $.confirm({
                                title: '',
                                content: 'No Action Performed!',
                                icon: 'fa fa-exclamation',
                                theme: 'modern',
                                closeIcon: true,
                                animation: 'scale',
                                type: 'purple',
                                buttons: {
                                    Ok: function(){
                                        window.location.reload();
                                    }
                                }
                            });
                        }
                    }
                });
            } else {
                $.confirm({
                    title: '',
                    content: 'Please Select Credit Notes!',
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
        /*Select Credit Notes Script End*/
    });
</script>
@endsection
