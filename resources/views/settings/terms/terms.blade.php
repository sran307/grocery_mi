@extends('layouts.master')
@section('title', 'Add CMS Terms Page')
@section('content')
<section class="gj_terms_setting">
    <div class="row gj_row">
        <div class="col-md-3 col-sm-3 col-xs-12">
            @include('layouts.sidebar')
        </div>

        <div class="col-md-9 col-sm-9 col-xs-12">
            <!-- <div class="row">
                <div class="col-lg-12">
                    <ul class="breadcrumb">
                        <li class=""><a> Home  </a></li>
                        <li class="active"><a> Add CMS Terms Page  </a></li>
                    </ul>
                </div>
            </div> -->

            <div class="gj_box dark">
                @if(Session::has('message'))
                    <p class="alert {{ Session::get('alert-class', 'alert-info') }}">{{ Session::get('message') }}</p>
                @endif
                <header>
                    <div class="gj_icons"><i class="fa fa-edit"></i></div>
                    <h5 class="gj_heading"> Add CMS Terms Page  </h5>
                </header>

                <div class="col-md-12">
                    {{ Form::open(array('url' => 'terms','class'=>'gj_terms_form','files' => true)) }}
                        @if(isset($terms))
                            @if($terms)
                                {{ Form::hidden('t_id', $terms->id, array('class' => 'form-control gj_t_id')) }}
                            @endif
                        @endif

                        <div class="form-group">
                            {{ Form::label('page_data', 'Add Terms & Condition Page Data') }}
                            <span class="error">* 
                                @if ($errors->has('page_data'))
                                    {{ $errors->first('page_data') }}
                                @endif
                            </span>

                            <textarea class="page_data" placeholder="Enter text ..." name="page_data" id="page_data"></textarea>
                        </div>

                        {{ Form::button('Update', array('class' => 'btn btn-primary', 'id'=>'update')) }}

                    {{ Form::close() }}
                </div>
            </div>
        </div>
    </div>
</section>


<link rel="stylesheet" type="text/css" href="{{ asset('css/editor.css')}}">
<script src="{{ asset('js/editor.js')}}"></script>

<script>
    $(document).ready(function() { 
        $('p.alert').delay(2000).slideUp(300); 
        $("#size").select2();
        $("#page_data").Editor();

        <?php 
        if(isset($terms)) {
            if($terms->page_data) { 
                $ed = htmlentities($terms->page_data, ENT_QUOTES);
                $ed = preg_replace( "/\r|\n/", "", $ed); ?>
                $("#page_data").Editor("setText", '<?php echo html_entity_decode($ed); ?>'); 
            <?php } 
        } ?>
    });

    $('#update').on('click',function(){
        var id = 0;
        var page_data = 0;
        if($('.gj_t_id').val()) {
            id = $('.gj_t_id').val();
        }

        if($('#page_data').Editor("getText")) {
            page_data = $('#page_data').Editor("getText");
        }
        if((page_data != 0)) {
            $.ajax({
                type: 'post',
                url: '{{url('/terms')}}',
                data: {id: id, page_data: page_data, type: 'add'},
                success: function(data){
                    if(data == 0){
                        window.location.reload();
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
        } else {
            $.confirm({
                title: '',
                content: 'Please Enter Correct Details!',
                icon: 'fa fa-exclamation',
                theme: 'modern',
                closeIcon: true,
                animation: 'scale',
                type: 'blue',
                buttons: {
                    Ok: function(){
                    }
                }
            });                           
        }
    });
</script>

@endsection
