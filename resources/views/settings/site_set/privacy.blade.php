@extends('layouts.master')
@section('title', 'Add CMS'.$slug.' Page')
@section('content')
<section class="gj_disc_cms_setting">
    <div class="row gj_row">
        <div class="col-md-3 col-sm-3 col-xs-12">
            @include('layouts.sidebar')
        </div>

        <div class="col-md-9 col-sm-9 col-xs-12">
            <!-- <div class="row">
                <div class="col-lg-12">
                    <ul class="breadcrumb">
                        <li class=""><a> Home  </a></li>
                        <li class="active"><a> Add CMS Disclaimers Page  </a></li>
                    </ul>
                </div>
            </div> -->

            <div class="gj_box dark">
                @if(Session::has('message'))
                    <p class="alert {{ Session::get('alert-class', 'alert-info') }}">{{ Session::get('message') }}</p>
                @endif
                <header>
                    <div class="gj_icons"><i class="fa fa-edit"></i></div>
                    <h5 class="gj_heading"> Add CMS {{$slug}} Page  </h5>
                </header>

                <div class="col-md-12">
                    {{ Form::open(array('url' => 'add_update_ettings/'.$slug,'class'=>'gj_disc_cms_form','files' => true,'method'=>'post')) }}
                        @if(isset($as))
                            @if($as)
                                {{ Form::hidden('a_id', $as->id, array('class' => 'form-control gj_a_id')) }}
                            @endif
                        @endif
                                {{ Form::hidden('page_datas',null, array('class' => 'form-control page_datas')) }}

                        <div class="form-group">
                            {{ Form::label('page_data', $slug.' Page Data') }}
                            <span class="error">* 
                                @if ($errors->has('page_data'))
                                    {{ $errors->first('page_data') }}
                                @endif
                            </span>

                            <textarea class="page_data" placeholder="Enter text ..." name="page_data" id="page_data">{{isset($as)?$as->value:''}}</textarea>
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
            if(isset($as)) {
                if($as->value) { 
                $ed = htmlentities($as->value, ENT_QUOTES);
                $ed = preg_replace( "/\r|\n/", "", $ed); ?>
            
                $("#page_data").Editor("setText", '<?php echo html_entity_decode($ed); ?>');  
            <?php } 
        } ?>
    });

    $('#update').on('click',function(){
        var id = 0;
        var page_data = 0;
        

        if($('#page_data').Editor("getText")) {
            page_data = $('#page_data').Editor("getText");
        }
       $('.page_datas').val(page_data);
       $('.gj_disc_cms_form').submit();
    });
</script>

@endsection
