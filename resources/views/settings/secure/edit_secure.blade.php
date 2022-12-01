@extends('layouts.master')
@section('title', 'Edit Login Security')
@section('content')
<section class="gj_secure_edt_setting">
    <div class="row gj_row">
        <div class="col-md-3 col-sm-3 col-xs-12">
            @include('layouts.sidebar')
        </div>

        <div class="col-md-9 col-sm-9 col-xs-12">
            <!-- <div class="row">
                <div class="col-lg-12">
                    <ul class="breadcrumb">
                        <li class=""><a> Home  </a></li>
                        <li class="active"><a> Edit Login Security  </a></li>
                    </ul>
                </div>
            </div> -->

            <div class="gj_box dark">
                <header>
                    <div class="gj_icons"><i class="fa fa-edit"></i></div>
                    <h5 class="gj_heading"> Edit Login Security  </h5>
                </header>

                <div class="col-md-12">
                    {{ Form::open(array('url' => 'edit_secure','class'=>'gj_secure_form','files' => true)) }}
                        @if($secure)
                            {{ Form::hidden('secure_id', $secure->id, array('class' => 'form-control gj_secure_id')) }}
                        @endif

                        <div class="form-group">
                            {{ Form::label('question', 'Security Question') }}
                            <span class="error">* 
                                @if ($errors->has('question'))
                                    {{ $errors->first('question') }}
                                @endif
                            </span>

                            {{ Form::text('question', ($secure->question ? $secure->question : Input::old('question')), array('class' => 'form-control gj_question','placeholder' => 'Enter Security Question in English')) }}
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
        $('p.alert').delay(7000).slideUp(500); 
    });
</script>
@endsection
