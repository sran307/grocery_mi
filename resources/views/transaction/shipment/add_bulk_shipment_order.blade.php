@extends('layouts.master')
@section('title', 'Add Shipments')
@section('content')
<section class="gj_add_shipments_setting">
    <div class="row gj_row">
        <div class="col-md-3 col-sm-3 col-xs-12">
            @include('layouts.courier_sidebar')
        </div>

        <div class="col-md-9 col-sm-9 col-xs-12">
            <!-- <div class="row">
                <div class="col-lg-12">
                    <ul class="breadcrumb">
                        <li class=""><a> Home  </a></li>
                        <li class="active"><a> Add Shipments  </a></li>
                    </ul>
                </div>
            </div> -->

            <div class="gj_box dark">
                @if(Session::has('message'))
                    <p class="alert {{ Session::get('alert-class', 'alert-info') }}">{{ Session::get('message') }}</p>
                @endif
                <header>
                    <div class="gj_icons"><i class="fa fa-edit"></i></div>
                    <h5 class="gj_heading"> Add Shipments  </h5>
                </header>

                <div class="col-md-12">
                    <div class="gj_instuct">
                        <p class="gj_ins_para">Download the Sample Data and its same format using to Bulk Upload.</p>
                        <p class="gj_sam_dl">
                            <button type="button" onClick="window.location.href='{{ asset('backend/shipment.xlsx')}}'"><i class="fa fa-file-excel-o "></i>Download</button>
                        </p>
                    </div>
                    {{ Form::open(array('url' => 'add_bulk_shipment_order','class'=>'gj_shipodrs_form','files' => true)) }}
                        <div class="form-group">
                            {{ Form::label('bulk_upload', 'Bulk Upload') }}
                            <span class="error">* 
                                @if ($errors->has('bulk_upload'))
                                    {{ $errors->first('bulk_upload') }}
                                @endif
                            </span>

                            <input name="bulk_upload" type="file" id="bulk_upload" class="">
                        </div>

                        {{ Form::submit('Import', array('class' => 'btn btn-primary')) }}

                    {{ Form::close() }}
                </div>
            </div>
        </div>
    </div>
</section>

<script>
    $(document).ready(function() { 
        $('p.alert').delay(5000).slideUp(300);
    });
</script>
@endsection
