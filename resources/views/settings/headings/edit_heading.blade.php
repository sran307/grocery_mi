@extends('layouts.master')
@section('title', 'Edit Heading')
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
                        <li class="active"><a> Edit CMS Page  </a></li>
                    </ul>
                </div>
            </div> -->

            <div class="gj_box dark">
                <header>
                    <div class="gj_icons"><i class="fa fa-edit"></i></div>
                    <h5 class="gj_heading"> Edit Heading  </h5>
                </header>

                @foreach($headings as $heading)
                <div class="col-md-12">
                {{ Form::open(array('route' => ['update_heading', $heading->id],'action' => 'HeadingController@update_heading', 'class'=>'gj_cms_page_form','files' => true)) }}
                        @if($heading)
                            {{ Form::hidden('heading_id', $heading->id, array('class' => 'form-control gj_cms_page_id')) }}
                        @endif
                       <div class="form-group">
                            {{ Form::label('page_name', 'Heading') }}
                            <span class="error">* 
                                @if ($errors->has('page_name'))
                                    {{ $errors->first('page_name') }}
                                @endif
                            </span>

                            {{ Form::text('page_name', ($heading->heading ? $heading->heading : Input::old('heading')), array('class' => 'form-control gj_page_name','placeholder' => 'Heading')) }}
                        </div>

                        {{ Form::button('Update', array('class' => 'btn btn-primary', 'type'=>'submit', 'id'=>'update')) }}

                    {{ Form::close() }}
                   </form>
 
                </div>
                @endforeach
            </div>
        </div>
    </div>
</section>

<link rel="stylesheet" type="text/css" href="{{ asset('css/editor.css')}}">
<script src="{{ asset('js/editor.js')}}"></script>

<script>
    $(document).ready(function() {
        $('p.alert').delay(2000).slideUp(300); 
    });
   /* $('#update').on('click',function(){
        var page_name = 0;
        var cms_page_id = 0;
        if($('#page_name').val()) {
            page_name = $('#page_name').val();
        }

        if($('.gj_cms_page_id').val()) {
            cms_page_id = $('.gj_cms_page_id').val();
        }
        console.log(page_name);
        console.log(cms_page_id);
        if((page_name == 0) && (cms_page_id == 0)) {
            $.ajax({
                type: 'post',
                url: '{{route('update_heading')}}',
                data: {page_name: page_name, cms_page_id: cms_page_id, type: 'update'},
               
                success: function(data){
                    if(data == 0){
                        window.location.href = "{{route('manage_cms_page')}}";
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
                type: 'red',
                buttons: {
                    Ok: function(){
                        window.location.reload();
                    }
                }
            });                        
        }*/
    });
</script>
@endsection
