@extends('layouts.master')
@section('title', 'Edit Attributes')
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
                        <li class="active"><a> Edit Attributes  </a></li>
                    </ul>
                </div>
            </div> -->

            <div class="gj_box dark">
                <header>
                    <div class="gj_icons"><i class="fa fa-edit"></i></div>
                    <h5 class="gj_heading"> Edit Attributes  </h5>
                </header>

                <div class="col-md-12">
                    {{ Form::open(array('url' => 'edit_att_fields','class'=>'gj_att_fields_form','files' => true)) }}
                        @if($att_fields)
                            {{ Form::hidden('att_fields_id', $att_fields->id, array('class' => 'form-control gj_att_fields_id')) }}
                        @endif

                        <div class="form-group">
                            {{ Form::label('att_name', 'Attributes Name') }}
                            <span class="error">* 
                                @if ($errors->has('att_name'))
                                    {{ $errors->first('att_name') }}
                                @endif
                            </span>

                            {{ Form::text('att_name', ($att_fields->att_name ? $att_fields->att_name : Input::old('att_name')), array('class' => 'form-control gj_att_name','placeholder' => 'Attributes Name')) }}
                        </div>

                        <div class="form-group">
                            {{ Form::label('att_desc', 'Attributes Description') }}
                            <span class="error">* 
                                @if ($errors->has('att_desc'))
                                    {{ $errors->first('att_desc') }}
                                @endif
                            </span>

                            {{ Form::textarea('att_desc', ($att_fields->att_desc ? $att_fields->att_desc : Input::old('att_desc')), array('class' => 'form-control gj_att_desc', 'rows'=>'5','placeholder' => 'Attributes Description')) }}
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
        $("#att_fields").select2(); 
    });
</script>
@endsection
