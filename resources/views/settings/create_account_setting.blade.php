@extends('layouts.master')
@section('title', 'Account Settings')
@section('content')
<section class="gj_account_setting">
    <div class="row gj_row">
        <div class="col-md-3 col-sm-3 col-xs-12">
            @include('layouts.sidebar')
        </div>

        <div class="col-md-9 col-sm-9 col-xs-12">
            <!-- <div class="row">
                <div class="col-lg-12">
                    <ul class="breadcrumb">
                        <li class=""><a> Home  </a></li>
                        <li class="active"><a> Account Settings  </a></li>
                    </ul>
                </div>
            </div> -->

            <div class="gj_box dark">
                <header>
                    <div class="gj_icons"><i class="fa fa-edit"></i></div>
                    <h5 class="gj_heading"> Account Settings  </h5>
                </header>

                <div class="col-md-12">
                    {{ Form::open(array('url' => 'account_setting','class'=>'gj_account_form')) }}
                        <div class="form-group">
                            {{ Form::label('is_taxable', 'Is Taxable') }}
                            <span class="error">* 
                                @if ($errors->has('is_taxable'))
                                    {{ $errors->first('is_taxable') }}
                                @endif
                            </span>
                            @if(isset($account))
                                {{ Form::hidden('id', ($account->id ? $account->id : ''), array('class' => 'form-control')) }}

                                {{ Form::hidden('user_id', ($account->user_id ? $account->user_id : ''), array('class' => 'form-control')) }}

                                <div class="gj_py_ro_div">
                                    <span class="gj_py_ro">
                                        <input type="radio" <?php if($account->is_taxable == 1) { echo "checked"; } ?> name="is_taxable" value="1q"> Yes
                                    </span>
                                    <span class="gj_py_ro">
                                        <input type="radio" <?php if($account->is_taxable == 0) { echo "checked"; } ?> name="is_taxable" value="0"> No
                                    </span>
                                </div>
                            @else
                                {{ Form::hidden('id', Input::old('id'), array('class' => 'form-control')) }}

                                {{ Form::hidden('user_id', Input::old('user_id'), array('class' => 'form-control')) }}

                                <div class="gj_py_ro_div">
                                    <span class="gj_py_ro">
                                        <input type="radio" name="is_taxable" value="1"> Yes
                                    </span>
                                    <span class="gj_py_ro">
                                        <input type="radio" name="is_taxable" value="0"> No
                                    </span>
                                </div>
                            @endif
                        </div>

                        <div class="form-group gj_vat_gst">
                            {{ Form::label('vat_gst_no', 'Vat/GST Number') }}
                            <span class="error">* 
                                @if ($errors->has('vat_gst_no'))
                                    {{ $errors->first('vat_gst_no') }}
                                @endif
                            </span>

                            @if(isset($account))
                                {{ Form::text('vat_gst_no', ($account->vat_gst_no ? $account->vat_gst_no : ''), array('class' => 'form-control')) }}
                            @else
                                {{ Form::text('vat_gst_no', Input::old('vat_gst_no'), array('class' => 'form-control')) }}
                            @endif
                        </div>

                        <div class="form-group">
                            {{ Form::label('primary_acc_type', 'Bank Account Type') }}
                            <span class="error">* 
                                @if ($errors->has('primary_acc_type'))
                                    {{ $errors->first('primary_acc_type') }}
                                @endif
                            </span>

                            @if(isset($account))
                                <div class="gj_py_ro_div">
                                    <span class="gj_py_ro">
                                        <input type="radio" <?php ($account->primary_acc_type == 'Savings' ? 'checked' : '') ?> name="primary_acc_type" value="Savings"> Savings
                                    </span>
                                    <span class="gj_py_ro">
                                        <input type="radio" <?php ($account->primary_acc_type == 'Current' ? 'checked' : '') ?> name="primary_acc_type" value="Current"> Current
                                    </span>
                                </div>
                            @else
                                <div class="gj_py_ro_div">
                                    <span class="gj_py_ro">
                                        <input type="radio" name="primary_acc_type" value="Savings"> Savings
                                    </span>
                                    <span class="gj_py_ro">
                                        <input type="radio" name="primary_acc_type" value="Current"> Current
                                    </span>
                                </div>
                            @endif
                        </div>

                        <div class="form-group">
                            {{ Form::label('primary_acc_no', 'Bank Account Number') }}
                            <span class="error">* 
                                @if ($errors->has('primary_acc_no'))
                                    {{ $errors->first('primary_acc_no') }}
                                @endif
                            </span>

                            @if(isset($account))
                                {{ Form::number('primary_acc_no', ($account->primary_acc_no ? $account->primary_acc_no : ''), array('class' => 'form-control')) }}
                            @else
                                {{ Form::number('primary_acc_no', Input::old('primary_acc_no'), array('class' => 'form-control')) }}
                            @endif
                        </div>

                        <div class="form-group">
                            {{ Form::label('primary_acc_holder_name', 'Bank Account Holder Name') }}
                            <span class="error">* 
                                @if ($errors->has('primary_acc_holder_name'))
                                    {{ $errors->first('primary_acc_holder_name') }}
                                @endif
                            </span>

                            @if(isset($account))
                                {{ Form::text('primary_acc_holder_name', ($account->primary_acc_holder_name ? $account->primary_acc_holder_name : ''), array('class' => 'form-control')) }}
                            @else
                                {{ Form::text('primary_acc_holder_name', Input::old('primary_acc_holder_name'), array('class' => 'form-control')) }}
                            @endif
                        </div>

                        <div class="form-group">
                            {{ Form::label('primary_acc_bank', 'Bank Name') }}
                            <span class="error">* 
                                @if ($errors->has('primary_acc_bank'))
                                    {{ $errors->first('primary_acc_bank') }}
                                @endif
                            </span>

                            @if(isset($account))
                                {{ Form::text('primary_acc_bank', ($account->primary_acc_bank ? $account->primary_acc_bank : ''), array('class' => 'form-control')) }}
                            @else
                                {{ Form::text('primary_acc_bank', Input::old('primary_acc_bank'), array('class' => 'form-control')) }}
                            @endif
                        </div>

                        <div class="form-group">
                            {{ Form::label('primary_acc_branch', 'Branch Name') }}
                            <span class="error">* 
                                @if ($errors->has('primary_acc_branch'))
                                    {{ $errors->first('primary_acc_branch') }}
                                @endif
                            </span>

                            @if(isset($account))
                                {{ Form::text('primary_acc_branch', ($account->primary_acc_branch ? $account->primary_acc_branch : ''), array('class' => 'form-control')) }}
                            @else
                                {{ Form::text('primary_acc_branch', Input::old('primary_acc_branch'), array('class' => 'form-control')) }}
                            @endif
                        </div>

                        <div class="form-group">
                            {{ Form::label('primary_acc_ifsc', 'IFSC Code') }}
                            <span class="error">*
                                @if ($errors->has('primary_acc_ifsc'))
                                    {{ $errors->first('primary_acc_ifsc') }}
                                @endif
                            </span>

                            @if(isset($account))
                                {{ Form::text('primary_acc_ifsc', ($account->primary_acc_ifsc ? $account->primary_acc_ifsc : ''), array('class' => 'form-control')) }}
                            @else
                                {{ Form::text('primary_acc_ifsc', Input::old('primary_acc_ifsc'), array('class' => 'form-control')) }}
                            @endif
                        </div>

                        <div class="form-group">
                            {{ Form::label('optional_acc_type', 'Another Bank Account Type') }}
                            <span class="error"> 
                                @if ($errors->has('optional_acc_type'))
                                    {{ $errors->first('optional_acc_type') }}
                                @endif
                            </span>

                            @if(isset($account))
                                <div class="gj_py_ro_div">
                                    <span class="gj_py_ro">
                                        <input type="radio" <?php ($account->optional_acc_type == 'Savings' ? 'checked' : '') ?> name="optional_acc_type" value="Savings"> Savings
                                    </span>
                                    <span class="gj_py_ro">
                                        <input type="radio" <?php ($account->optional_acc_type == 'Current' ? 'checked' : '') ?> name="optional_acc_type" value="Current"> Current
                                    </span>
                                </div>
                            @else
                                <div class="gj_py_ro_div">
                                    <span class="gj_py_ro">
                                        <input type="radio" name="optional_acc_type" value="Savings"> Savings
                                    </span>
                                    <span class="gj_py_ro">
                                        <input type="radio" name="optional_acc_type" value="Current"> Current
                                    </span>
                                </div>
                            @endif
                        </div>

                        <div class="form-group">
                            {{ Form::label('optional_acc_no', 'Another Bank Account Number') }}
                            <span class="error"> 
                                @if ($errors->has('optional_acc_no'))
                                    {{ $errors->first('optional_acc_no') }}
                                @endif
                            </span>

                            @if(isset($account))
                                {{ Form::number('optional_acc_no', ($account->optional_acc_no ? $account->optional_acc_no : ''), array('class' => 'form-control')) }}
                            @else
                                {{ Form::number('optional_acc_no', Input::old('optional_acc_no'), array('class' => 'form-control')) }}
                            @endif
                        </div>

                        <div class="form-group">
                            {{ Form::label('optional_acc_holder_name', 'Another Bank Account Holder Name') }}
                            <span class="error"> 
                                @if ($errors->has('optional_acc_holder_name'))
                                    {{ $errors->first('optional_acc_holder_name') }}
                                @endif
                            </span>

                            @if(isset($account))
                                {{ Form::text('optional_acc_holder_name', ($account->optional_acc_holder_name ? $account->optional_acc_holder_name : ''), array('class' => 'form-control')) }}
                            @else
                                {{ Form::text('optional_acc_holder_name', Input::old('optional_acc_holder_name'), array('class' => 'form-control')) }}
                            @endif
                        </div>

                        <div class="form-group">
                            {{ Form::label('optional_acc_bank', 'Another Bank Name') }}
                            <span class="error"> 
                                @if ($errors->has('optional_acc_bank'))
                                    {{ $errors->first('optional_acc_bank') }}
                                @endif
                            </span>

                            @if(isset($account))
                                {{ Form::text('optional_acc_bank', ($account->optional_acc_bank ? $account->optional_acc_bank : ''), array('class' => 'form-control')) }}
                            @else
                                {{ Form::text('optional_acc_bank', Input::old('optional_acc_bank'), array('class' => 'form-control')) }}
                            @endif
                        </div>

                        <div class="form-group">
                            {{ Form::label('optional_acc_branch', 'Another Branch Name') }}
                            <span class="error"> 
                                @if ($errors->has('optional_acc_branch'))
                                    {{ $errors->first('optional_acc_branch') }}
                                @endif
                            </span>

                            @if(isset($account))
                                {{ Form::text('optional_acc_branch', ($account->optional_acc_branch ? $account->optional_acc_branch : ''), array('class' => 'form-control')) }}
                            @else
                                {{ Form::text('optional_acc_branch', Input::old('optional_acc_branch'), array('class' => 'form-control')) }}
                            @endif
                        </div>

                        <div class="form-group">
                            {{ Form::label('optional_acc_ifsc', 'Another IFSC Code') }}
                            <span class="error">
                                @if ($errors->has('optional_acc_ifsc'))
                                    {{ $errors->first('optional_acc_ifsc') }}
                                @endif
                            </span>

                            @if(isset($account))
                                {{ Form::text('optional_acc_ifsc', ($account->optional_acc_ifsc ? $account->optional_acc_ifsc : ''), array('class' => 'form-control')) }}
                            @else
                                {{ Form::text('optional_acc_ifsc', Input::old('optional_acc_ifsc'), array('class' => 'form-control')) }}
                            @endif
                        </div>



                        <div class="form-group">
                            {{ Form::label('initial_credits', 'Initial Credits') }}
                            <span class="error">
                                @if ($errors->has('initial_credits'))
                                    {{ $errors->first('initial_credits') }}
                                @endif                                
                            </span>

                            @if(isset($account))
                                {{ Form::text('initial_credits', ($account->initial_credits ? $account->initial_credits : ''), array('class' => 'form-control')) }}
                            @else
                                {{ Form::text('initial_credits', Input::old('initial_credits'), array('class' => 'form-control')) }}
                            @endif
                        </div>

                        {{ Form::submit('Update', array('class' => 'btn btn-primary')) }}

                    {{ Form::close() }}
                </div>
            </div>
        </div>
    </div>
</section>

<script type="text/javascript">
    $(document).ready(function() {
        $('.gj_vat_gst').hide();
    });

    $('body').on('change', "input[name='is_taxable']", function() {
        if($("input[name='is_taxable']:checked").val() == '0') {
            $('.gj_vat_gst').hide();
        } else {
            $('.gj_vat_gst').show();
        }
    });
</script>
@endsection
