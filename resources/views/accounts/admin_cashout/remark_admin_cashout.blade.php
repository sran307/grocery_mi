@extends('layouts.master')
@section('title', 'Remark Cashout Request')
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
                    <h5 class="gj_heading"> Remark Cashout Request  </h5>
                </header>

                <div class="col-md-12">
                    {{ Form::open(array('url' => 'remark_admin_cashout','class'=>'gj_recas_form','files' => true)) }}
                        <div class="form-group">
                            {{ Form::label('vendor_remarks', 'Vendor Remarks') }}
                            <span class="error">* 
                                @if ($errors->has('vendor_remarks'))
                                    {{ $errors->first('vendor_remarks') }}
                                @endif
                            </span>

                            @if($cashout)
                                {{ Form::hidden('cashout_id', $cashout->id, array('class' => 'form-control gj_cashout_id')) }}
                            @else 
                                {{ Form::hidden('cashout_id', null, array('class' => 'form-control gj_cashout_id')) }}
                            @endif

                            {{ Form::textarea('vendor_remarks', ($cashout->vendor_remarks ? $cashout->vendor_remarks : Input::old('vendor_remarks')), array('class' => 'form-control gj_vendor_remarks', 'rows' => '5', 'placeholder' => 'Enter Remarks')) }}
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
</script>
@endsection
