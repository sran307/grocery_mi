@extends('layouts.master')
@section('title', 'Add Attributes')
@section('content')
<section class="gj_attributes_setting">
    <div class="row gj_row">
        <div class="col-md-3 col-sm-3 col-xs-12">
            @include('layouts.sidebar')
        </div>

        <div class="col-md-9 col-sm-9 col-xs-12">
            <!-- <div class="row">
                <div class="col-lg-12">
                    <ul class="breadcrumb">
                        <li class=""><a> Home  </a></li>
                        <li class="active"><a> Add Attributes  </a></li>
                    </ul>
                </div>
            </div> -->

            <div class="gj_box dark">
                @if(Session::has('message'))
                    <p class="alert {{ Session::get('alert-class', 'alert-info') }}">{{ Session::get('message') }}</p>
                @endif
                <header>
                    <div class="gj_icons"><i class="fa fa-edit"></i></div>
                    <h5 class="gj_heading"> Add Attributes  </h5>
                </header>

                <div class="col-md-12">
                    {{ Form::open(array('url' => 'add_attributes','class'=>'gj_attributes_form','files' => true)) }}
                        <div class="form-group">
                            {{ Form::label('att_name', 'Attributes Name') }}
                            <span class="error">* 
                                @if ($errors->has('att_name'))
                                    {{ $errors->first('att_name') }}
                                @endif
                            </span>

                            <select name="att_name" id="att_name" class="gj_att_name form-control">
                                <option value="0">-- Select Attributes Name --</option>
                                @if(isset($atts) && count($atts) !=0 )
                                    @foreach ($atts as $key => $value)
                                        <option value="{{$value->id}}">{{$value->att_name}}</option>
                                    @endforeach
                                @endif
                            </select>
                        </div>

                        <div class="form-group">
                            {{ Form::label('att_value', 'Attributes Value') }}
                            <span class="error">* 
                                @if ($errors->has('att_value'))
                                    {{ $errors->first('att_value') }}
                                @endif
                            </span>

                            {{ Form::text('att_value', Input::old('att_value'), array('class' => 'form-control gj_att_value','placeholder' => 'Enter Attributes Value')) }}
                        </div>

                        <div class="form-group">
                            {{ Form::label('att_image', 'Upload Attributes Image') }}
                            <span class="error">* 
                                @if ($errors->has('att_image'))
                                    {{ $errors->first('att_image') }}
                                @endif
                            </span>
                            <!-- <p class="gj_not" style="color:red"><em>image size must be 250 x 200 pixels</em></p> -->

                            <input type="file" name="att_image" id="att_image" accept="image/*" class="gj_att_image">
                        </div>

                        <div class="form-group">
                            {{ Form::label('att_desc', 'Attributes Description') }}
                            <span class="error">* 
                                @if ($errors->has('att_desc'))
                                    {{ $errors->first('att_desc') }}
                                @endif
                            </span>

                            {{ Form::textarea('att_desc', Input::old('att_desc'), array('class' => 'form-control gj_att_desc', 'rows'=>'5','placeholder' => 'Enter Attributes Description')) }}
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
        $("#att_name").select2();
    });
</script>
@endsection