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
                    {{ Form::open(array('url' => 'edit_attributes','class'=>'gj_attributes_form','files' => true)) }}
                        @if($attributes)
                            {{ Form::hidden('attributes_id', $attributes->id, array('class' => 'form-control gj_attributes_id')) }}
                        @endif

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
                                        @if($value->id == $attributes->att_name)
                                            <option selected value="{{$value->id}}">{{$value->att_name}}</option>
                                        @else
                                            <option value="{{$value->id}}">{{$value->att_name}}</option>
                                        @endif
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

                            {{ Form::text('att_value', ($attributes->att_value ? $attributes->att_value : Input::old('att_value')), array('class' => 'form-control gj_att_value','placeholder' => 'Enter Attributes Value')) }}
                        </div>

                        <div class="gj_ban_img_whole">
                            <?php 
                            $file_path = 'images/attributes';
                            ?>
                            @if(isset($attributes))
                                @if($attributes->att_image != '')
                                <div class="form-group">
                                    {{ Form::label('current_att_image', 'Current Attributes Image') }}
                                    <div class="gj_cbni_div">
                                       <img src="{{ asset($file_path.'/'.$attributes->att_image)}}" class="img-responsive"> 
                                    </div>
                                    {{ Form::hidden('old_att_image', ($attributes->att_image ? $attributes->att_image : ''), array('class' => 'form-control')) }}
                                </div>
                                @endif
                            @endif

                            <div class="form-group">
                                {{ Form::label('att_image', 'Upload Attributes Image') }}
                                <span class="error">* 
                                    @if ($errors->has('att_image'))
                                        {{ $errors->first('att_image') }}
                                    @endif
                                </span>
                                <p class="gj_not" style="color:red"><em>image size must be 250 x 200 pixels</em></p>

                                <input type="file" name="att_image" id="att_image" accept="image/*" class="gj_att_image">
                            </div>
                        </div>

                        <div class="form-group">
                            {{ Form::label('att_desc', 'Attributes Value') }}
                            <span class="error">* 
                                @if ($errors->has('att_desc'))
                                    {{ $errors->first('att_desc') }}
                                @endif
                            </span>

                            {{ Form::textarea('att_desc', ($attributes->att_desc ? $attributes->att_desc : Input::old('att_desc')), array('class' => 'form-control gj_att_desc', 'rows'=>'5','placeholder' => 'Enter Attributes Value')) }}
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
        $("#att_name").select2(); 
    });
</script>
@endsection
