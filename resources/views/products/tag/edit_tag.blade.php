@extends('layouts.master')
@section('title', 'Edit Tag')
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
                        <li class="active"><a> Edit Tag  </a></li>
                    </ul>
                </div>
            </div> -->

            <div class="gj_box dark">
                <header>
                    <div class="gj_icons"><i class="fa fa-edit"></i></div>
                    <h5 class="gj_heading"> Edit Tag  </h5>
                </header>

                <div class="col-md-12">
                    {{ Form::open(array('url' => 'edit_tag','class'=>'gj_tag_form','files' => true)) }}
                        @if($tag)
                            {{ Form::hidden('tag_id', $tag->id, array('class' => 'form-control gj_tag_id')) }}
                        @endif

                        <div class="form-group">
                            {{ Form::label('tag_title', 'Tag Title') }}
                            <span class="error">* 
                                @if ($errors->has('tag_title'))
                                    {{ $errors->first('tag_title') }}
                                @endif
                            </span>

                            {{ Form::text('tag_title', ($tag->tag_title ? $tag->tag_title : Input::old('tag_title')), array('class' => 'form-control gj_tag_title','placeholder' => 'Enter Tag Title in English')) }}
                        </div>

                        <div class="form-group">
                            {{ Form::label('tag_description', 'Tag Description') }}
                            <span class="error">* 
                                @if ($errors->has('tag_description'))
                                    {{ $errors->first('tag_description') }}
                                @endif
                            </span>

                            {{ Form::textarea('tag_description', ($tag->tag_description ? $tag->tag_description : Input::old('tag_description')), array('class' => 'form-control gj_tag_description', 'rows' => '5', 'placeholder' => 'Enter Tag Description in English')) }}
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
        $("#tag").select2(); 
    });
</script>
@endsection
