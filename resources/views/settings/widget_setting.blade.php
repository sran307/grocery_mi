@extends('layouts.master')
@section('title', 'Widget Settings')
@section('content')
<section class="gj_widget_setting">
    <div class="row gj_row">
        <div class="col-md-3 col-sm-3 col-xs-12">
            @include('layouts.sidebar')
        </div>

        <div class="col-md-9 col-sm-9 col-xs-12">
            <div class="row">
                <div class="col-lg-12">
                    <!-- <ul class="breadcrumb">
                        <li class=""><a> Home  </a></li>
                        <li class="active"><a> Widget Settings  </a></li>
                    </ul> -->
                    @if(Session::has('message'))
                        <p class="alert gj_bk_alt {{ Session::get('alert-class', 'alert-info') }}">{{ Session::get('message') }}</p>
                    @endif
                </div>
            </div>

            <div class="gj_box dark">
                <header>
                    <div class="gj_icons"><i class="fa fa-edit"></i></div>
                    <h5 class="gj_heading"> Widget Settings  </h5>
                </header>

                <div class="col-md-12">
                    {{ Form::open(array('url' => 'widget_setting','class'=>'gj_widget_form','files' => true)) }}
                        <div class="form-group">
                            {{ Form::label('first_title', 'First Widget Title') }}
                            <span class="error">* 
                                @if ($errors->has('first_title'))
                                    {{ $errors->first('first_title') }}
                                @endif
                            </span>
                            @if(isset($widget))
                                {{ Form::hidden('id', ($widget->id ? $widget->id : ''), array('class' => 'form-control')) }}

                                {{ Form::text('first_title', ($widget->first_title ? $widget->first_title : ''), array('class' => 'form-control')) }}
                            @else
                                {{ Form::hidden('id', Input::old('id'), array('class' => 'form-control')) }}

                                {{ Form::text('first_title', Input::old('first_title'), array('class' => 'form-control')) }}
                            @endif
                        </div>

                        <div class="form-group">
                            {{ Form::label('first_content', 'First Widgte Content') }}
                            <span class="error">* 
                                @if ($errors->has('first_content'))
                                    {{ $errors->first('first_content') }}
                                @endif
                            </span>
                            @if(isset($widget))
                                {{ Form::text('first_content', ($widget->first_content ? $widget->first_content : ''), array('class' => 'form-control')) }}
                            @else
                                {{ Form::text('first_content', Input::old('first_content'), array('class' => 'form-control')) }}
                            @endif
                        </div>

                        <div class="form-group">
                            {{ Form::label('first_url', 'First Widget URL') }}
                            <span class="error">* 
                                @if ($errors->has('first_url'))
                                    {{ $errors->first('first_url') }}
                                @endif
                            </span>
                            @if(isset($widget))
                                {{ Form::text('first_url', ($widget->first_url ? $widget->first_url : ''), array('class' => 'form-control')) }}
                            @else
                                {{ Form::text('first_url', Input::old('first_url'), array('class' => 'form-control')) }}
                            @endif
                        </div>

                        <div class="form-group">
                            {{ Form::label('first_icon', 'First Widget Icon') }}
                            <span class="error">* 
                                @if ($errors->has('first_icon'))
                                    {{ $errors->first('first_icon') }}
                                @endif
                            </span>
                            @if(isset($widget))
                                {{ Form::text('first_icon', ($widget->first_icon ? $widget->first_icon : ''), array('class' => 'form-control')) }}
                            @else
                                {{ Form::text('first_icon', Input::old('first_icon'), array('class' => 'form-control')) }}
                            @endif

                            <p class="gj_lt_fa">View Icon Codes : <button type="button" class="gj_lt_icons" data-toggle="modal" data-target="#myModal">FontAwesome Icons</button></p>

                            <!-- Modal -->
                            <div class="modal fade" id="myModal" role="dialog">
                                <div class="modal-dialog">
                                    <!-- Modal content-->
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                                            <h4 class="modal-title">FontAwesome Icons</h4>
                                        </div>
                                        <div class="modal-body">
                                            @include('layouts.icons')
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            {{ Form::label('second_title', 'Second Widget Title') }}
                            <span class="error">* 
                                @if ($errors->has('second_title'))
                                    {{ $errors->first('second_title') }}
                                @endif
                            </span>
                            @if(isset($widget))
                                {{ Form::text('second_title', ($widget->second_title ? $widget->second_title : ''), array('class' => 'form-control')) }}
                            @else
                                {{ Form::text('second_title', Input::old('second_title'), array('class' => 'form-control')) }}
                            @endif
                        </div>

                        <div class="form-group">
                            {{ Form::label('second_content', 'Second Widgte Content') }}
                            <span class="error">* 
                                @if ($errors->has('second_content'))
                                    {{ $errors->first('second_content') }}
                                @endif
                            </span>
                            @if(isset($widget))
                                {{ Form::text('second_content', ($widget->second_content ? $widget->second_content : ''), array('class' => 'form-control')) }}
                            @else
                                {{ Form::text('second_content', Input::old('second_content'), array('class' => 'form-control')) }}
                            @endif
                        </div>

                        <div class="form-group">
                            {{ Form::label('second_url', 'Second Widget URL') }}
                            <span class="error">* 
                                @if ($errors->has('second_url'))
                                    {{ $errors->first('second_url') }}
                                @endif
                            </span>
                            @if(isset($widget))
                                {{ Form::text('second_url', ($widget->second_url ? $widget->second_url : ''), array('class' => 'form-control')) }}
                            @else
                                {{ Form::text('second_url', Input::old('second_url'), array('class' => 'form-control')) }}
                            @endif
                        </div>

                        <div class="form-group">
                            {{ Form::label('second_icon', 'Second Widget Icon') }}
                            <span class="error">* 
                                @if ($errors->has('second_icon'))
                                    {{ $errors->first('second_icon') }}
                                @endif
                            </span>
                            @if(isset($widget))
                                {{ Form::text('second_icon', ($widget->second_icon ? $widget->second_icon : ''), array('class' => 'form-control')) }}
                            @else
                                {{ Form::text('second_icon', Input::old('second_icon'), array('class' => 'form-control')) }}
                            @endif

                            <p class="gj_lt_fa">View Icon Codes : <button type="button" class="gj_lt_icons" data-toggle="modal" data-target="#myModal">FontAwesome Icons</button></p>
                        </div>

                        <div class="form-group">
                            {{ Form::label('third_title', 'Third Widget Title') }}
                            <span class="error">* 
                                @if ($errors->has('third_title'))
                                    {{ $errors->first('third_title') }}
                                @endif
                            </span>
                            @if(isset($widget))
                                {{ Form::text('third_title', ($widget->third_title ? $widget->third_title : ''), array('class' => 'form-control')) }}
                            @else
                                {{ Form::text('third_title', Input::old('third_title'), array('class' => 'form-control')) }}
                            @endif
                        </div>

                        <div class="form-group">
                            {{ Form::label('third_content', 'Third Widgte Content') }}
                            <span class="error">* 
                                @if ($errors->has('third_content'))
                                    {{ $errors->first('third_content') }}
                                @endif
                            </span>
                            @if(isset($widget))
                                {{ Form::text('third_content', ($widget->third_content ? $widget->third_content : ''), array('class' => 'form-control')) }}
                            @else
                                {{ Form::text('third_content', Input::old('third_content'), array('class' => 'form-control')) }}
                            @endif
                        </div>

                        <div class="form-group">
                            {{ Form::label('third_url', 'Third Widget URL') }}
                            <span class="error">* 
                                @if ($errors->has('third_url'))
                                    {{ $errors->first('third_url') }}
                                @endif
                            </span>
                            @if(isset($widget))
                                {{ Form::text('third_url', ($widget->third_url ? $widget->third_url : ''), array('class' => 'form-control')) }}
                            @else
                                {{ Form::text('third_url', Input::old('third_url'), array('class' => 'form-control')) }}
                            @endif
                        </div>

                        <div class="form-group">
                            {{ Form::label('third_icon', 'Third Widget Icon') }}
                            <span class="error">* 
                                @if ($errors->has('third_icon'))
                                    {{ $errors->first('third_icon') }}
                                @endif
                            </span>
                            @if(isset($widget))
                                {{ Form::text('third_icon', ($widget->third_icon ? $widget->third_icon : ''), array('class' => 'form-control')) }}
                            @else
                                {{ Form::text('third_icon', Input::old('third_icon'), array('class' => 'form-control')) }}
                            @endif

                            <p class="gj_lt_fa">View Icon Codes : <button type="button" class="gj_lt_icons" data-toggle="modal" data-target="#myModal">FontAwesome Icons</button></p>
                        </div>

                        <div class="form-group">
                            {{ Form::label('fourth_title', 'Fourth Widget Title') }}
                            <span class="error">* 
                                @if ($errors->has('fourth_title'))
                                    {{ $errors->first('fourth_title') }}
                                @endif
                            </span>
                            @if(isset($widget))
                                {{ Form::text('fourth_title', ($widget->fourth_title ? $widget->fourth_title : ''), array('class' => 'form-control')) }}
                            @else
                                {{ Form::text('fourth_title', Input::old('fourth_title'), array('class' => 'form-control')) }}
                            @endif
                        </div>

                        <div class="form-group">
                            {{ Form::label('fourth_content', 'Fourth Widgte Content') }}
                            <span class="error">* 
                                @if ($errors->has('fourth_content'))
                                    {{ $errors->first('fourth_content') }}
                                @endif
                            </span>
                            @if(isset($widget))
                                {{ Form::text('fourth_content', ($widget->fourth_content ? $widget->fourth_content : ''), array('class' => 'form-control')) }}
                            @else
                                {{ Form::text('fourth_content', Input::old('fourth_content'), array('class' => 'form-control')) }}
                            @endif
                        </div>

                        <div class="form-group">
                            {{ Form::label('fourth_url', 'Fourth Widget URL') }}
                            <span class="error">* 
                                @if ($errors->has('fourth_url'))
                                    {{ $errors->first('fourth_url') }}
                                @endif
                            </span>
                            @if(isset($widget))
                                {{ Form::text('fourth_url', ($widget->fourth_url ? $widget->fourth_url : ''), array('class' => 'form-control')) }}
                            @else
                                {{ Form::text('fourth_url', Input::old('fourth_url'), array('class' => 'form-control')) }}
                            @endif
                        </div>

                        <div class="form-group">
                            {{ Form::label('fourth_icon', 'Fourth Widget Icon') }}
                            <span class="error">* 
                                @if ($errors->has('fourth_icon'))
                                    {{ $errors->first('fourth_icon') }}
                                @endif
                            </span>
                            @if(isset($widget))
                                {{ Form::text('fourth_icon', ($widget->fourth_icon ? $widget->fourth_icon : ''), array('class' => 'form-control')) }}
                            @else
                                {{ Form::text('fourth_icon', Input::old('fourth_icon'), array('class' => 'form-control')) }}
                            @endif

                            <p class="gj_lt_fa">View Icon Codes : <button type="button" class="gj_lt_icons" data-toggle="modal" data-target="#myModal">FontAwesome Icons</button></p>
                        </div>

                        <div class="form-group">
                            {{ Form::label('fifth_title', 'Fifth Widget Title') }}
                            <span class="error">* 
                                @if ($errors->has('fifth_title'))
                                    {{ $errors->first('fifth_title') }}
                                @endif
                            </span>
                            @if(isset($widget))
                                {{ Form::text('fifth_title', ($widget->fifth_title ? $widget->fifth_title : ''), array('class' => 'form-control')) }}
                            @else
                                {{ Form::text('fifth_title', Input::old('fifth_title'), array('class' => 'form-control')) }}
                            @endif
                        </div>

                        <div class="form-group">
                            {{ Form::label('fifth_content', 'Fifth Widgte Content') }}
                            <span class="error">* 
                                @if ($errors->has('fifth_content'))
                                    {{ $errors->first('fifth_content') }}
                                @endif
                            </span>
                            @if(isset($widget))
                                {{ Form::text('fifth_content', ($widget->fifth_content ? $widget->fifth_content : ''), array('class' => 'form-control')) }}
                            @else
                                {{ Form::text('fifth_content', Input::old('fifth_content'), array('class' => 'form-control')) }}
                            @endif
                        </div>

                        <div class="form-group">
                            {{ Form::label('fifth_url', 'Fifth Widget URL') }}
                            <span class="error">* 
                                @if ($errors->has('fifth_url'))
                                    {{ $errors->first('fifth_url') }}
                                @endif
                            </span>
                            @if(isset($widget))
                                {{ Form::text('fifth_url', ($widget->fifth_url ? $widget->fifth_url : ''), array('class' => 'form-control')) }}
                            @else
                                {{ Form::text('fifth_url', Input::old('fifth_url'), array('class' => 'form-control')) }}
                            @endif
                        </div>

                        <div class="form-group">
                            {{ Form::label('fifth_icon', 'Fifth Widget Icon') }}
                            <span class="error">* 
                                @if ($errors->has('fifth_icon'))
                                    {{ $errors->first('fifth_icon') }}
                                @endif
                            </span>
                            @if(isset($widget))
                                {{ Form::text('fifth_icon', ($widget->fifth_icon ? $widget->fifth_icon : ''), array('class' => 'form-control')) }}
                            @else
                                {{ Form::text('fifth_icon', Input::old('fifth_icon'), array('class' => 'form-control')) }}
                            @endif

                            <p class="gj_lt_fa">View Icon Codes : <button type="button" class="gj_lt_icons" data-toggle="modal" data-target="#myModal">FontAwesome Icons</button></p>
                        </div>

                        <div class="gj_ban_img_whole">
                            <?php 
                            $file_path = 'images/widget';
                            ?>
                            @if(isset($widget))
                                @if($widget->provide_img != '')
                                <div class="form-group">
                                    {{ Form::label('current_provide_img', 'Current Provide Image') }}
                                    <div class="gj_mc_div">
                                       <img src="{{ asset($file_path.'/'.$widget->provide_img)}}" class="img-responsive"> 
                                    </div>
                                    {{ Form::hidden('old_provide_img', ($widget->provide_img ? $widget->provide_img : ''), array('class' => 'form-control')) }}
                                </div>
                                @endif
                            @endif

                            <div class="form-group">
                                {{ Form::label('provide_img', 'Upload Provide Image') }}
                                <span class="error">* 
                                    @if ($errors->has('provide_img'))
                                        {{ $errors->first('provide_img') }}
                                    @endif
                                </span>
                                <p class="gj_not" style="color:red"><em>image size must be 1650 x 100 pixels</em></p>

                                <input type="file" name="provide_img" id="provide_img" accept="image/*" class="gj_provide_img">
                            </div>
                        </div>

                        <div class="form-group">
                            {{ Form::label('provide_url', 'Provide URL') }}
                            <span class="error">* 
                                @if ($errors->has('provide_url'))
                                    {{ $errors->first('provide_url') }}
                                @endif
                            </span>
                            @if(isset($widget))
                                {{ Form::text('provide_url', ($widget->provide_url ? $widget->provide_url : ''), array('class' => 'form-control')) }}
                            @else
                                {{ Form::text('provide_url', Input::old('provide_url'), array('class' => 'form-control')) }}
                            @endif
                        </div>

                        <div class="gj_ban_img_whole">
                            <?php 
                            $file_path = 'images/widget';
                            ?>
                            @if(isset($widget))
                                @if($widget->footer_pay_img != '')
                                <div class="form-group">
                                    {{ Form::label('current_footer_pay_img', 'Current Footer Payment Image') }}
                                    <div class="gj_mc_div">
                                       <img src="{{ asset($file_path.'/'.$widget->footer_pay_img)}}" class="img-responsive"> 
                                    </div>
                                    {{ Form::hidden('old_footer_pay_img', ($widget->footer_pay_img ? $widget->footer_pay_img : ''), array('class' => 'form-control')) }}
                                </div>
                                @endif
                            @endif

                            <div class="form-group">
                                {{ Form::label('footer_pay_img', 'Upload Footer Payment Image') }}
                                <span class="error">* 
                                    @if ($errors->has('footer_pay_img'))
                                        {{ $errors->first('footer_pay_img') }}
                                    @endif
                                </span>
                                <p class="gj_not" style="color:red"><em>image size must be 373 x 25 pixels</em></p>

                                <input type="file" name="footer_pay_img" id="footer_pay_img" accept="image/*" class="gj_footer_pay_img">
                            </div>
                        </div>

                        <div class="form-group">
                            {{ Form::label('footer_pay_url', 'Footer Payment URL') }}
                            <span class="error">* 
                                @if ($errors->has('footer_pay_url'))
                                    {{ $errors->first('footer_pay_url') }}
                                @endif
                            </span>
                            @if(isset($widget))
                                {{ Form::text('footer_pay_url', ($widget->footer_pay_url ? $widget->footer_pay_url : ''), array('class' => 'form-control')) }}
                            @else
                                {{ Form::text('footer_pay_url', Input::old('footer_pay_url'), array('class' => 'form-control')) }}
                            @endif
                        </div>

                        <div class="form-group">
                            {{ Form::label('footer_hd1', 'Footer Heading-1') }}
                            <span class="error">* 
                                @if ($errors->has('footer_hd1'))
                                    {{ $errors->first('footer_hd1') }}
                                @endif
                            </span>
                            @if(isset($widget))
                                {{ Form::text('footer_hd1', ($widget->footer_hd1 ? $widget->footer_hd1 : ''), array('class' => 'form-control')) }}
                            @else
                                {{ Form::text('footer_hd1', Input::old('footer_hd1'), array('class' => 'form-control')) }}
                            @endif
                        </div>

                        <div class="form-group">
                            {{ Form::label('footer_hd2', 'Footer Heading-2') }}
                            <span class="error">* 
                                @if ($errors->has('footer_hd2'))
                                    {{ $errors->first('footer_hd2') }}
                                @endif
                            </span>
                            @if(isset($widget))
                                {{ Form::text('footer_hd2', ($widget->footer_hd2 ? $widget->footer_hd2 : ''), array('class' => 'form-control')) }}
                            @else
                                {{ Form::text('footer_hd2', Input::old('footer_hd2'), array('class' => 'form-control')) }}
                            @endif
                        </div>

                        <div class="form-group">
                            {{ Form::label('footer_hd3', 'Footer Heading-3') }}
                            <span class="error">* 
                                @if ($errors->has('footer_hd3'))
                                    {{ $errors->first('footer_hd3') }}
                                @endif
                            </span>
                            @if(isset($widget))
                                {{ Form::text('footer_hd3', ($widget->footer_hd3 ? $widget->footer_hd3 : ''), array('class' => 'form-control')) }}
                            @else
                                {{ Form::text('footer_hd3', Input::old('footer_hd3'), array('class' => 'form-control')) }}
                            @endif
                        </div>

                        <div class="form-group">
                            {{ Form::label('footer_hd4', 'Footer Heading-4') }}
                            <span class="error">* 
                                @if ($errors->has('footer_hd4'))
                                    {{ $errors->first('footer_hd4') }}
                                @endif
                            </span>
                            @if(isset($widget))
                                {{ Form::text('footer_hd4', ($widget->footer_hd4 ? $widget->footer_hd4 : ''), array('class' => 'form-control')) }}
                            @else
                                {{ Form::text('footer_hd4', Input::old('footer_hd4'), array('class' => 'form-control')) }}
                            @endif
                        </div>

                        <div class="form-group">
                            {{ Form::label('footer_hd5', 'Footer Heading-5') }}
                            <span class="error">* 
                                @if ($errors->has('footer_hd5'))
                                    {{ $errors->first('footer_hd5') }}
                                @endif
                            </span>
                            @if(isset($widget))
                                {{ Form::text('footer_hd5', ($widget->footer_hd5 ? $widget->footer_hd5 : ''), array('class' => 'form-control')) }}
                            @else
                                {{ Form::text('footer_hd5', Input::old('footer_hd5'), array('class' => 'form-control')) }}
                            @endif
                        </div>

                        <div class="form-group">
                            {{ Form::label('footer_nl_quotes', 'Footer News Letter Quotes') }}
                            <span class="error">* 
                                @if ($errors->has('footer_nl_quotes'))
                                    {{ $errors->first('footer_nl_quotes') }}
                                @endif
                            </span>
                            @if(isset($widget))
                                {{ Form::text('footer_nl_quotes', ($widget->footer_nl_quotes ? $widget->footer_nl_quotes : ''), array('class' => 'form-control')) }}
                            @else
                                {{ Form::text('footer_nl_quotes', Input::old('footer_nl_quotes'), array('class' => 'form-control')) }}
                            @endif
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
        $('p.alert').delay(5000).slideUp(700);
    });
</script>

<script type="text/javascript">
    $('p.gj_bk_alt').delay(5000).slideUp(700);
</script>
@endsection