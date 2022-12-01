@extends('layouts.master')
@section('title', 'Edit Measurement Unit')
@section('content')
<section class="gj_email_setting">
    <div class="row gj_row">
        <div class="col-md-3 col-sm-3 col-xs-12">
            @include('layouts.product_sidebar')
        </div>

        <div class="col-md-9 col-sm-9 col-xs-12">
            <!-- <div class="row">
                <div class="col-lg-12">
                    <ul class="breadcrumb">
                        <li class=""><a> Home  </a></li>
                        <li class="active"><a> Edit Measurement Unit  </a></li>
                    </ul>
                </div>
            </div> -->

            <div class="gj_box dark">
                <header>
                    <div class="gj_icons"><i class="fa fa-edit"></i></div>
                    <h5 class="gj_heading"> Edit Measurement Unit  </h5>
                </header>

                <div class="col-md-12">
                    {{ Form::open(array('url' => 'edit_measurement','class'=>'gj_measurement_form','files' => true)) }}
                        @if($measurement)
                            {{ Form::hidden('measurement_id', $measurement->id, array('class' => 'form-control gj_measurement_id')) }}
                        @endif

                        <div class="form-group">
                            {{ Form::label('unit_name', 'Unit Name') }}
                            <span class="error">* 
                                @if ($errors->has('unit_name'))
                                    {{ $errors->first('unit_name') }}
                                @endif
                            </span>

                            {{ Form::text('unit_name', ($measurement->unit_name ? $measurement->unit_name : Input::old('unit_name')), array('class' => 'form-control gj_unit_name','placeholder' => 'Enter Unit Name in English')) }}
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
        $("#measurement").select2(); 
    });
</script>
@endsection
