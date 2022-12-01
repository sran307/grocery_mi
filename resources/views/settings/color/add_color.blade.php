@extends('layouts.master')
@section('title', 'Add Color')
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
                        <li class="active"><a> Add Color  </a></li>
                    </ul>
                </div>
            </div> -->

            <div class="gj_box dark">
                @if(Session::has('message'))
                    <p class="alert {{ Session::get('alert-class', 'alert-info') }}">{{ Session::get('message') }}</p>
                @endif
                <header>
                    <div class="gj_icons"><i class="fa fa-edit"></i></div>
                    <h5 class="gj_heading"> Add Color  </h5>
                </header>

                <div class="col-md-12">
                    {{ Form::open(array('url' => 'add_color','class'=>'gj_color_form','files' => true)) }}

                    <div class="gj_ban_img_whole row">
                        <div class="form-group">
                            {{ Form::label('colour', 'Colour') }}
                            <span class="error">* 
                                @if ($errors->has('color_code'))
                                    {{ $errors->first('color_code') }}
                                @endif
                                @if ($errors->has('color_name'))
                                    {{ $errors->first('color_name') }}
                                @endif
                            </span>

                            <div id="ntc" class="gj_color_picker">
                                <div id="picker"></div>
                                <div id="colortag">
                                    <h2 id="colorname"></h2>
                                    <div id="colorpick"></div>
                                    <div id="colorbox">
                                        <div id="colorsolid"></div>
                                    </div>
                                    <div id="colorpanel">
                                        <div id="colorhex">Your Color:</div>
                                        <input type="text" name="color_code" id="colorinp" class="inputbox" value="" maxlength="10">
                                        <div id="colorrgb"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{ Form::button('Update', array('class' => 'btn btn-primary', 'id'=>'update')) }}

                    {{ Form::close() }}
                </div>
            </div>
        </div>
    </div>
</section>

<script type="text/javascript" src="{{ asset('color/farbtastic.js')}}"></script>
<script type="text/javascript" src="{{ asset('color/ntc.js')}}"></script>
<script type="text/javascript" src="{{ asset('color/ntc_main.js')}}"></script>
<link type="text/css" rel="stylesheet" href="{{ asset('color/farbtastic.css')}}">
<script>
    $(document).ready(function() { 
        $('p.alert').delay(2000).slideUp(300); 
        // $("#colorop").select2();
    });

    $('#update').on('click',function(){
        var cn = 0;
        var cc = 0;
        var avoid1 ="<sup>approx.</sup>";
        var avoid2 ='<sup id="solid">solid</sup>';
        if($('#colorname').html()) {
            cn = $('#colorname').html();
            cn = cn.replace(avoid1,'');
            cn = cn.replace(avoid2,'');
        }

        if($('#colorinp').val()) {
            cc = $('#colorinp').val();
        }
        // alert(cn+" "+cc);
        if((cn == 0) && (cc == 0)) {
            $.confirm({
                title: '',
                content: 'Please Select Correct Colour!',
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
        } else {
            $.ajax({
                type: 'post',
                url: '{{url('/add_color')}}',
                data: {cn: cn, cc: cc, type: 'add'},
                success: function(data){
                    if(data == 0){
                        window.location.href = "{{route('manage_color')}}";
                    } else {
                        $.confirm({
                            title: '',
                            content: 'No Action Performed!',
                            icon: 'fa fa-exclamation',
                            theme: 'modern',
                            closeIcon: true,
                            animation: 'scale',
                            type: 'purple',
                            buttons: {
                                Ok: function(){
                                    window.location.reload();
                                }
                            }
                        });
                    }
                }
            });            
        }
    });
</script>
@endsection
