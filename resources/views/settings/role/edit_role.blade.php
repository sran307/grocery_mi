@extends('layouts.master')
@section('title', 'Edit Roles')
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
                        <li class="active"><a> Edit Roles  </a></li>
                    </ul>
                </div>
            </div> -->

            <div class="gj_box dark">
                <header>
                    <div class="gj_icons"><i class="fa fa-edit"></i></div>
                    <h5 class="gj_heading"> Edit Roles  </h5>
                </header>

                <div class="col-md-12">
                    {{ Form::open(array('url' => 'edit_role','class'=>'gj_role_form','files' => true)) }}
                        @if($role)
                            {{ Form::hidden('role_id', $role->id, array('class' => 'form-control gj_role_id')) }}
                        @endif

                        <div class="form-group">
                            {{ Form::label('role', 'Role') }}
                            <span class="error">* 
                                @if ($errors->has('role'))
                                    {{ $errors->first('role') }}
                                @endif
                            </span>

                            {{ Form::text('role', ($role->role ? $role->role : Input::old('role')), array('class' => 'form-control gj_role','placeholder' => 'Enter Roles in English')) }}
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
        $("#role").select2(); 
    });
</script>
@endsection
