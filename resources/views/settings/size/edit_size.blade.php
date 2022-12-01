@extends('layouts.master')
@section('title', 'Edit Size')
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
                        <li class="active"><a> Edit Size  </a></li>
                    </ul>
                </div>
            </div> -->

            <div class="gj_box dark">
                <header>
                    <div class="gj_icons"><i class="fa fa-edit"></i></div>
                    <h5 class="gj_heading"> Edit Size  </h5>
                </header>

                <div class="col-md-12">
                    {{ Form::open(array('url' => 'edit_size','class'=>'gj_size_form','files' => true)) }}
                        @if($size)
                            {{ Form::hidden('size_id', $size->id, array('class' => 'form-control gj_size_id')) }}
                        @endif

                        <div class="form-group">
                            {{ Form::label('size', 'Size') }}
                            <span class="error">* 
                                @if ($errors->has('size'))
                                    {{ $errors->first('size') }}
                                @endif
                            </span>

                            {{ Form::text('size', ($size->size ? $size->size : Input::old('size')), array('class' => 'form-control gj_size','placeholder' => 'Size')) }}
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
        $("#size").select2(); 
    });
</script>
@endsection
