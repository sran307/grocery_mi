@extends('layouts.master')
@section('title', 'Build PC Settings')
@section('content')
<section class="gj_general_setting">
    <div class="row gj_row">
        <div class="col-md-3 col-sm-3 col-xs-12">
            @include('layouts.sidebar')
        </div>

        <div class="col-md-9 col-sm-9 col-xs-12">
            <div class="row">
                <div class="col-lg-12">
                    <!-- <ul class="breadcrumb">
                        <li class=""><a> Home  </a></li>
                        <li class="active"><a> General Settings  </a></li>
                    </ul> -->
                    
                </div>
            </div>

            <div class="gj_box dark">
                @if(Session::has('message'))
                    <p class="alert {{ Session::get('alert-class', 'alert-info') }}">{{ Session::get('message') }}</p>
                @endif
                <header>
                    <div class="gj_icons"><i class="fa fa-edit"></i></div>
                    <h5 class="gj_heading">Build PC Settings  </h5>
                </header>

                <div class="col-md-12">
                    {{ Form::open(array('url' => 'build_pc_save','class'=>'gj_geneal_form')) }}
                        <div class="form-group">
                            {{ Form::label('title', 'Title') }}
                            <span class="error">* 
                                @if ($errors->has('title'))
                                    {{ $errors->first('title') }}
                                @endif
                            </span>
                            @if(isset($general))
                                {{ Form::hidden('id', ($general->id ? $general->id : ''), array('class' => 'form-control')) }}

                                {{ Form::text('title', ($general->title ? $general->title : ''), array('class' => 'form-control')) }}
                            @else
                                {{ Form::hidden('id', Input::old('id'), array('class' => 'form-control')) }}

                                {{ Form::text('title', Input::old('title'), array('class' => 'form-control')) }}
                            @endif
                        </div>


                     

                        <div class="form-group">
                            {{ Form::label('description', 'Description') }}
                            <span class="error">* 
                                @if ($errors->has('description'))
                                    {{ $errors->first('description') }}
                                @endif
                            </span>

                            @if(isset($general))
                                {{ Form::textarea('description', ($general->description ? $general->description : ''), array('class' => 'form-control')) }}
                            @else
                                {{ Form::textarea('description', Input::old('description'), array('class' => 'form-control')) }}
                            @endif
                        </div>


                        <div class="form-group">
                            {{ Form::label('cancel_terms', 'Product Features & Description') }}
                            <span class="error">
                                @if ($errors->has('product_features'))
                                    {{ $errors->first('product_features') }}
                                @endif                                
                            </span>

                            @if(isset($general))
                                {{ Form::textarea('product_features', ($general->product_features ? $general->product_features : ''), array('class' => 'form-control', 'id' => 'gj_cancel_terms', 'rows' => '5')) }}
                            @else
                                {{ Form::textarea('product_features', Input::old('product_features'), array('class' => 'form-control', 'rows' => '5')) }}
                            @endif
                        </div>

                        <div class="form-group">
                            {{ Form::label('return_terms', 'Notes') }}
                            <span class="error">
                                @if ($errors->has('notes'))
                                    {{ $errors->first('notes') }}
                                @endif                                
                            </span>

                            @if(isset($general))
                                {{ Form::textarea('notes', ($general->notes ? $general->notes : ''), array('class' => 'form-control', 'id' => 'gj_return_terms', 'rows' => '5')) }}
                            @else
                                {{ Form::textarea('notes', Input::old('notes'), array('class' => 'form-control', 'rows' => '5')) }}
                            @endif
                        </div>

                        {{ Form::submit('Update', array('class' => 'btn btn-primary')) }}

                    {{ Form::close() }}
                    
                    </div>
                     <div class="col-md-12">
                      {{ Form::open(array('url' => 'build_pc_save','class'=>'gj_geneal_form1')) }}
                                                     <input type="hidden" name="work_count" value="1" id="work_count">

                        <div class="form-group">
                          <div class="gj_p_att_resp table-responsive">
                                        <table class="table table-stripped table-bordered gj_tab_att" id="work_table1">
                                        <tbody id="gj_att_bdy">

                    <tr>
                     
                      <td  class="td_style" ><center>Component Name <span class="red"> *</span></center></td>
                      <td colspan="2">
                        {!!Form::text('component_name',null,['class'=>'form-control','id'=>'component_name','placeholder'=>'Component Name','required'=>'true'])!!}

                      </td>
                      </tr>
                      <tr>
                     
                      <td  class="td_style" ><center>Remarks <span class="red"> *</span></center></td>
                      <td colspan="2">
                        {!!Form::textarea('component_remark',null,['class'=>'form-control','id'=>'component_remark','placeholder'=>'Remarks'])!!}

                      </td>
                      </tr>
                      <tr>
                      <td  class="td_style" ><center>Choose Category <span class="red"> *</span></center></td>
                      <td colspan="2">
                    <?php 
                        $opt = '';
                        $main = \DB::table('category_management_settings')->where('is_block',1)->get();
                        if(($main) && (count($main) != 0)){
                            foreach ($main as $key => $value) {
                                $opt.='<option value="'.$value->id.'">'.$value->main_cat_name.'</option>';
                            }
                        } 
                    ?>
                    <select id="main_cat_name" name="main_cat_name[]"  data_id="" class="form-control js-example-basic-multiple" multiple="multiple">
                        <option value=""  disabled>Select Main Category</option>
                       
                    </select>
                      </td>
                    </tr>
                    <tr>
                      <td  class="td_style" ><center>Choose Sub Category</center></td>
                      <td colspan="2">
                   
                   <select id="sub_cat_name" name="sub_cat_name[]" disabled class="form-control sub_cat_name"  multiple="multiple">
                                        <option value=""  disabled>Select Sub Category Name</option>
                                    </select>
                      </td>
                    </tr>
                        </tbody>
                            </table>
                            
                             </div>
                             <table class="table table-stripped table-bordered gj_tab_att" id="work_table">
                                        <tbody id="gj_att_bdy">

                    <tr id="work_tr1">
                             <td><input type="hidden" name="attr_id[]" id="attr_id1">
                          <label>Attribute Name1</label>
                        {!!Form::text('attribute_name[]',null,['class'=>'form-control','id'=>'attribute_name1','placeholder'=>'Attribute Name1'])!!}

                      </td>
                      <td>
                          <label>Attribute Value1</label>
                     {!!Form::text('attribute_value[]',null,['class'=>'form-control','id'=>'attribute_value1','placeholder'=>'Attribute Value1'])!!}

                      </td>
                      <td style="padding-top: 33px;">
                          <a href="#" class="btn btn-info btn-sm fa fa-plus" onclick="add_work(1)"></a>
                      </td>
                      </tr>
                      </tbody>
                      </table>
                             <input type="hidden" name="btn" value="1">
                                                          <input type="hidden" name="custom_id" id="custom_id">

                        </div>
                        @if(isset($general))
                                {{ Form::hidden('id', ($general->id ? $general->id : ''), array('class' => 'form-control')) }}
                         {{ Form::submit('Add', array('class' => 'btn btn-primary','id'=>'add_id')) }}
                         @else
                                                  {{ Form::submit('Add', array('class' => 'btn btn-primary','disabled'=>'true','id'=>'add_id')) }}

                         @endif

                         {{ Form::close() }}
                         @if(isset($general))
                         <?php
                         $component=App\BuildPcComponent::where('build_id',$general->id)->get();
                         ?>
                         @if(count($component)>0)
                         <table class="table table-stripped table-bordered gj_tab_att">
                                       

                    <tr id="">
                        <th>Component Name</th>
                        <th>Categories</th>
                        <th>#</th>
                    </tr>
                     <tbody id="gj_att_bdy">
                         @foreach($component as $value)
                         <?php
                         $cat=App\BuildPcCategory::where('component_id',$value->id)->get();
                         $c_str='';
                         ?>
                         <tr>
                             <td>{{$value->component_name}}</td>
                             <td>
                                 @foreach($cat as $c)
                                 <?php $c_str.=$c->category->main_cat_name.','; ?>
                                 @endforeach
                               {!!$c_str!!}
                             </td>
                             <td><a href="#" class="btn btn-info btn-sm" onclick="fetch_data({{$value->id}})"><i class="fa fa-pencil"></i></a> 
                             <a href="{{url('delete_custom/'.$value->id)}}" class="btn btn-warning btn-sm" ><i class="fa fa-trash"></i></a>
                             </td>
                         </tr>
                         
                         @endforeach
                      </tbody>
                      </table>
                      @endif
                      @endif
                </div>
            </div>
        </div>
    </div>
</section>

<script type="text/javascript">
    $('p.alert').delay(5000).slideUp(700);
    fetch_cat_name();
     function fetch_cat_name(ids=null)
   {
        
            $("#main_cat_name").html('');
            $.ajax({
                type: 'post',
                url: '{{url('/choose_sub_cat')}}',
                data: {type: 'cat',ids:ids},
                success: function(data){
                    if(data){
                        $("#main_cat_name").html(data);
                        
                               $('.js-example-basic-multiple').select2();

                        

                    } else {
                        
                        $("#main_cat_name").html(data);
                    }
                }
            });
        
    }
    $('#main_cat_name').change(function(){
        
        fetch_sub_cat_name($(this).val(),$(this).attr('data_id'));
       
    });
     function fetch_sub_cat_name(main_cat,ids=null)
   {
        //  var main_cat = $(id).val();
       
        if(main_cat!='') {
            $("#sub_cat_name").html('');
            $.ajax({
                type: 'post',
                url: '{{url('/choose_sub_cat')}}',
                data: {main_cat: main_cat, type: 'sub_cat',ids:ids},
                success: function(data){
                    if(data){
                        $("#sub_cat_name").html(data);
                        
                        $("#sub_cat_name").removeAttr("disabled");
                            $('.sub_cat_name').select2();
                        

                    } else {
                        
                        $("#sub_cat_name").html(data);
                    }
                }
            });
        }
    }
    $('.js-example-basic-multiple').select2();
                                $('.sub_cat_name').select2();

</script>

<!-- Editor Script Start -->
    <script src="https://cdn.ckeditor.com/4.11.4/standard/ckeditor.js"></script>

    <script>
     
        CKEDITOR.replace( 'description' );
        CKEDITOR.replace( 'product_features' );
         function add_work(id)
  {
      event.preventDefault();
      var c=$('#work_count').val();
      var count=parseInt(id)+1;
      $('#work_count').val(count);
      $('#work_table').append('<tr id="work_tr'+count+'">'+
                                          ' <td><input type="hidden" name="attr_id[]" id="attr_id'+count+'">'+
                     '<label>Attribute Name'+count+'</label><input type="text" class="form-control" name="attribute_name[]" id="attribute_name'+count+'" placeholder="Attribute Name'+count+'">'+
                      '</td>'+
                      ' <td>'+
                     '<label>Attribute Value'+count+'</label><input type="text" class="form-control" name="attribute_value[]" id="attribute_value'+count+'" placeholder="Attribute Value'+count+'">'+
                      '</td>'+
                     ' <td colspan="1" id="remove_td'+count+'"><a href="#" class="btn btn-info btn-sm fa fa-plus" onclick="add_work('+count+')"></a></td>'+
                    '</tr>');
    $('#remove_td'+id).html('<a href="#" class="btn btn-info btn-sm fa fa-trash" onclick="remove_work('+id+')"></a>');
  }
   function remove_work(id)
  {
       event.preventDefault();
        var c=$('#work_count').val();
      var count=parseInt(c)-1;
      $('#work_count').val(count);
      $('#work_tr'+id).remove();
  }
  function fetch_data(id)
  {
      event.preventDefault();
      $.ajax({
                type: 'post',
                url: '{{url('/choose_sub_cat')}}',
                data: {id: id, type: 'fetch_data'},
                success: function(data){
                    if(data)
                    {
                        $('#component_name').val(data.com['component_name']);
                         $('#component_remark').val(data.com['remarks']);
                        fetch_cat_name(data.cat);
                        $('#custom_id').val(data.com['id']);
                          fetch_sub_cat_name(data.cat,data.sub);
                         $('#main_cat_name').attr('data_id',data.sub);
                        $('#main_cat_name').trigger("change");
                        if((data.attr.length)>0)
                        {
                            
                            for(var i=0;i < data.attr.length-1 ;i++)
                            {
                                add_work();
                            }
                            for(var i=0;i < data.attr.length ;i++)
                            {
                                var k=i+1;
                               $('#attr_id'+k).val(data.attr[i]['id']);
                               $('#attribute_name'+k).val(data.attr[i]['att_name']);
                                $('#attribute_value'+k).val(data.attr[i]['att_value']);
                                $('#add_id').val('Update');
                            }
                        }
                    }
                }
      });
  }
  
  
   
    </script>
<!-- Editor Script End -->
@endsection
