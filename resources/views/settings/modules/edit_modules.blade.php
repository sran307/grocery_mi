@extends('layouts.master')
@section('title', 'Edit Modules')
@section('content')
<section class="gj_e_modu_setting">
    <div class="row gj_row">
        <div class="col-md-3 col-sm-3 col-xs-12">
            @include('layouts.sidebar')
        </div>

        <div class="col-md-9 col-sm-9 col-xs-12">
            <!-- <div class="row">
                <div class="col-lg-12">
                    <ul class="breadcrumb">
                        <li class=""><a> Home  </a></li>
                        <li class="active"><a> Edit Modules  </a></li>
                    </ul>
                </div>
            </div> -->

            <div class="gj_box dark">
                <header>
                    <div class="gj_icons"><i class="fa fa-edit"></i></div>
                    <h5 class="gj_heading"> Edit Modules  </h5>
                </header>

                <div class="col-md-12">
                    {{ Form::open(array('url' => 'edit_modules','class'=>'gj_modules_form','files' => true)) }}
                        @if($roles)
                            {{ Form::hidden('modules_id', $roles->id, array('class' => 'form-control gj_modules_id')) }}
                        @endif

                        <div class="form-group">
                            {{ Form::label('module_name', 'Module Name') }}
                            <span class="error">* 
                                @if ($errors->has('module_name'))
                                    {{ $errors->first('module_name') }}
                                @endif
                            </span>

                            {{ Form::text('module_name', ($roles->module_name ? $roles->module_name : Input::old('module_name')), array('class' => 'form-control gj_module_name','placeholder' => 'Module Name')) }}
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
        $('p.alert').delay(5000).slideUp(800); 
        $("#modules").select2(); 
    });
</script>
@endsection
