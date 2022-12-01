@extends('layouts.master')
@section('title', 'Manage CMS About Us Page')
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
                        <li class="active"><a> Add CMS About Us Page  </a></li>
                    </ul>
                </div>
            </div> -->

            <div class="gj_box dark">
                @if(Session::has('message'))
                    <p class="alert {{ Session::get('alert-class', 'alert-info') }}">{{ Session::get('message') }}</p>
                @endif
                <header>
                    <div class="gj_icons"><i class="fa fa-edit"></i></div>
                    <h5 class="gj_heading"> Manage CMS About Us Page </h5>
                </header>

                <div class="col-md-12">
                    {{ Form::open(array('url' => 'add_about_page','class'=>'gj_about_cms_form','files' => true)) }}
                        @if(isset($about_page))
                            @if($about_page)
                                {{ Form::hidden('id', $about_page->id, array('class' => 'form-control gj_a_id')) }}
                            @endif
                        @endif
                        <input type="hidden" name="page_old" id="page_old">
                        <div class="form-group">
                            {{ Form::label('page_heading', 'Add The Heading') }}
                            <span class="error">* 
                                @if ($errors->has('page_heading'))
                                    {{ $errors->first('page_heading') }}
                                @endif
                            </span>

                            {{ Form::text('page_heading', Input::old('page_heading'), array('class' => 'form-control sr_page_heading','placeholder' => 'Page Heading In English')) }}
                        </div>

                        <div class="form-group">
                            {{ Form::label('page_data', 'Add About Us Page Data') }}
                            <span class="error">* 
                                @if ($errors->has('page_data'))
                                    {{ $errors->first('page_data') }}
                                @endif
                            </span>

                            <textarea class="page_data" placeholder="Enter text ..." name="page_data" id="page_data"></textarea>
                        </div>

                        <div class="form-group">
                            {{ Form::label('about_page_image', 'Upload About Page Image') }}
                            <span class="error">* 
                                @if ($errors->has('featured_product_img'))
                                    {{ $errors->first('featured_product_img') }}
                                @endif
                            </span>
                            <p class="gj_not" style="color:red"><em>image size must be 400 x 400 pixels</em></p>

                            <input type="file" name="about_image" id="about_image" accept="image/*" class="sr_about_image">
                            <img src="" width="50px" height="50px" class="sr_about_img" alt="about_image" id="about_img_field">
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
            if(isset($about_page)) {
                if($about_page->page_data) { 
                    $ed = htmlentities($about_page->page_data, ENT_QUOTES);
                    $ed = preg_replace( "/\r|\n/", "", $ed); ?>
                
                    $("#page_data").Editor("setText", '<?php echo html_entity_decode($ed); ?>');  
                <?php } 

                if($about_page->heading) { 
                     ?>

                    $("#page_heading").val('<?php echo $about_page->heading; ?>');  
                <?php } 

                if($about_page->image) { 
                    ?>

                $("#about_img_field").attr('src', '<?php echo asset('images/site_img/'.$about_page->image); ?>');  
                <?php } 
               
        } ?>
    });

    $('#update').on('click',function(){
        var id = 0;
        var page_data = 0;
        var page_heading = 0;

        if($('.gj_a_id').val()) {
            id = $('.gj_a_id').val();
        }

        if($('#page_data').Editor("getText")) {
            page_data = $('#page_data').Editor("getText");
        }

        if($('.sr_page_heading').val()) {
            page_heading = $('.sr_page_heading').val();
        }
            console.log(page_heading);
            $('#page_old').val(page_data);
        if((page_data != 0 && page_heading != 0)) {

            $('.gj_about_cms_form').submit();
            // $.ajax({
            //     type: 'post',
            //     url: '{{url('/add_about_page')}}',
            //     data: {id: id, page_data: page_data, page_heading: page_heading, type: 'add'},
            //     success: function(data){
            //         if(data == 0){
            //             window.location.reload();
            //         } else {
            //             $.confirm({
            //                 title: '',
            //                 content: 'No Action Performed!',
            //                 icon: 'fa fa-exclamation',
            //                 theme: 'modern',
            //                 closeIcon: true,
            //                 animation: 'scale',
            //                 type: 'purple',
            //                 buttons: {
            //                     Ok: function(){
            //                         window.location.reload();
            //                     }
            //                 }
            //             });
            //         }
            //     }
            // });            
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
