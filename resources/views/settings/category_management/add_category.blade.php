@extends('layouts.master')
@section('title', 'Add Main Category')
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
                        <li class="active"><a> Add Main Category  </a></li>
                    </ul>
                </div>
            </div> -->

            <div class="gj_box dark">
                @if(Session::has('message'))
                    <p class="alert {{ Session::get('alert-class', 'alert-info') }}">{{ Session::get('message') }}</p>
                @endif
                <header>
                    <div class="gj_icons"><i class="fa fa-edit"></i></div>
                    <h5 class="gj_heading"> Add Main Category  </h5>
                </header>

                <div class="col-md-12">
                    {{ Form::open(array('url' => 'add_category','class'=>'gj_geneal_form','files' => true)) }}

                        <div class="form-group">
                            {{ Form::label('main_cat_name', 'Main Category Name') }}
                            <span class="error">* 
                                @if ($errors->has('main_cat_name'))
                                    {{ $errors->first('main_cat_name') }}
                                @endif
                            </span>

                            {{ Form::text('main_cat_name', Input::old('main_cat_name'), array('class' => 'form-control gj_main_cat_name','placeholder' => 'Enter Category Name In English')) }}
                        </div>

                        <div class="form-group">
                            {{ Form::label('main_cat_icon', 'Main Category Icon') }}
                            <span class="error">
                                @if ($errors->has('main_cat_icon'))
                                    {{ $errors->first('main_cat_icon') }}
                                @endif
                            </span>
                            <p class="gj_ex_ph">Example: fa-user </p>

                            {{ Form::text('main_cat_icon', Input::old('main_cat_icon'), array('class' => 'form-control gj_main_cat_icon','placeholder' => 'Enter Category Icon In Under the List')) }}
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
                            {{ Form::label('is_block', 'Category Staus') }}
                            <span class="error">* 
                                @if ($errors->has('is_block'))
                                    {{ $errors->first('is_block') }}
                                @endif
                            </span>

                            <div class="gj_py_ro_div">
                                <span class="gj_py_ro">
                                    <input type="radio" checked name="is_block" value="1"> Active
                                </span>
                                <span class="gj_py_ro">
                                    <input type="radio" name="is_block" value="0"> Deactive
                                </span>
                            </div>
                        </div>

                        <div class="form-group">
                            {{ Form::label('main_cat_image', 'Upload Main Category Image') }}
                            <span class="error">* 
                                @if ($errors->has('main_cat_image'))
                                    {{ $errors->first('main_cat_image') }}
                                @endif
                            </span>
                            <p class="gj_not" style="color:red"><em>image size must be 200 x 200 pixels</em></p>

                            <input type="file" name="main_cat_image" id="main_cat_image" accept="image/*" class="gj_main_cat_image">
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
        $("#country_name").select2();
        $('p.alert').delay(1000).slideUp(300); 
    });

    $('#country_name').on('change',function(){
        var c_id = $(this).select2('val');

        $.ajax({
            type: 'post',
            url: '{{url('/country_details')}}',
            data: {c_id: c_id, type: 'details'},
            success: function(data){
                if(data != ""){
                    var data = $.parseJSON(data);
                    $('.gj_h_country_name').val(data.name);
                    $('.gj_country_code').val(data.code);
                    $('.gj_currency_symbol').val(data.currency_symbol);
                    $('.gj_currency_code').val(data.currency_code);
                } else {
                    $.confirm({
                        title: '',
                        content: 'No More Data Here!',
                        icon: 'fa fa-exclamation',
                        theme: 'modern',
                        closeIcon: true,
                        animation: 'scale',
                        type: 'purple',
                        buttons: {
                            Ok: function(){
                            }
                        }
                    });
                }
            }
        });
    });
</script>
@endsection
