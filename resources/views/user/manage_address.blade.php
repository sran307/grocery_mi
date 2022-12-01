@extends('groceryView.layouts.headerFooter')
@section('title', 'My Account')
<!--<link rel="stylesheet" href="{{ asset('css/bootstrap.min.css')}}">-->
<!--<link rel="stylesheet" type="text/css" href="{{ asset('login/animate.css')}}">-->
<!--<link rel="stylesheet" type="text/css" href="{{ asset('login/main.css')}}">-->
@section('content')


                         @include('layouts.normal_user_sidebar')
                         <?php
                                $users1 = session()->get('user');
                                $users=App\User::find($users1->id);
                            ?>
                   <div class="col-lg-9 col-md-8">
<div class="dashboard-right">
<div class="row">
<div class="col-md-12">
<div class="main-title-tab">
<h4><i class="uil uil-location-point"></i>My Address</h4>
</div>
</div>
<div class="col-lg-12 col-md-12">
<div class="pdpt-bg">
<div class="pdpt-title">
<h4>My Address</h4>
</div>
<div class="address-body">
   
    @if(is_null($value))
<a href="#" class="add-address hover-btn" data-toggle="modal" data-target="#address_model">Add Shipping Address</a>
@endif
<div class="modal fade" id="address_model" role="dialog">
    <div class="modal-dialog">
    
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Add Shipping Address</h4>
        </div>
        <div class="modal-body">
         <div class="row">
<div class="col-lg-12">
<form class="" method="post" action="{{url('store_address')}}">

<div class="form-group">
<div class="product-radio">
<ul class="product-now">
<li>
<input type="radio" id="ad1" value="Home" name="address_type" checked="">
<label for="ad1">Home</label>
</li>
<li>
<input type="radio" id="ad2" value="Office" name="address_type">
<label for="ad2">Office</label>
</li>
<li>
<input type="radio" id="ad3" value="Other" name="address_type">
<label for="ad3">Other</label>
</li>
</ul>
</div>
</div>
<div class="address-fieldset">
<div class="row">
<div class="col-lg-6 col-md-12">
<div class="form-group">
<label class="control-label">First Name*</label>
<input id="first_name" name="first_name" type="text" value="{{$users->first_name}}" placeholder="First Name" class="form-control input-md" required="">
</div>
</div>
<div class="col-lg-6 col-md-12">
<div class="form-group">
<label class="control-label">Last Name*</label>
<input id="last_name" name="last_name" type="text" value="{{$users->last_name}}"  placeholder="Last Name" class="form-control input-md" required="">
</div>
</div>
<div class="col-lg-6 col-md-12">
<div class="form-group">
<label class="control-label">Email Address*</label>
<input id="email" name="email" type="email" placeholder="Email Address" value="{{$users->email}}"  class="form-control input-md" required="">
</div>
</div>
<div class="col-lg-6 col-md-12">
<div class="form-group">
<label class="control-label">Phone Number*</label>
<input id="phone_number" name="phone_number" type="text" placeholder="Phone Number" value="{{$users->phone}}"  class="form-control input-md" required="">
</div>
</div>
<div class="col-lg-6 col-md-12">
<div class="form-group">
<label class="control-label">Alternate Phone Number*</label>
<input id="alternate_phone_number" name="alternate_phone_number" type="text" value="{{$users->phone2}}" placeholder="Alternate Phone Number" class="form-control input-md">
</div>
</div>
<div class="col-lg-12 col-md-12">
<div class="form-group">
<label class="control-label">Address with Flat Number / Building Name.*</label>
 <input id="address" name="address" type="text" placeholder="Address" class="form-control input-md" required="">
</div>
</div>
<div class="col-lg-12 col-md-12">
<div class="form-group">
<label class="control-label">Full Address *</label>
<input id="full_address" name="full_address" type="text" placeholder="Full Street Address" class="form-control input-md">
</div>
</div>
<div class="col-lg-6 col-md-12">
<div class="form-group">
<label class="control-label">Pincode*</label>
<input id="pincode" name="pincode" type="text" placeholder="Pincode" class="form-control input-md" required="">
</div>
</div>
<div class="col-lg-6 col-md-12">
<div class="form-group">
<label class="control-label">Landmark*</label>
<input id="landmark" name="landmark" type="text" placeholder="Landmark" class="form-control input-md" >
</div>
</div>
<div class="col-lg-6 col-md-12">
<div class="form-group">
<label class="control-label">Country*</label>
<select class="form-control country input-md" name="country" id="country" required>
<option value="">--select country--</option>
@if(isset($country) && count($country) != 0)
  @foreach ($country as $key => $valuex)
    @if ($valuex->id == $users->country)
      <option selected value="{{$valuex->id}}">{{$valuex->country_name}}</option>
    @else
      <option value="{{$valuex->id}}">{{$valuex->country_name}}</option>
    @endif 
  @endforeach 
@endif
</select>
</div>
</div>
<div class="col-lg-6 col-md-12">
<div class="form-group">
<label class="control-label">State *</label>
 <select class="form-control state input-md" name="state" id="state" disabled required>
<option value="">--Select State--</option>
</select>
</div>
</div>

<div class="col-lg-6 col-md-12">
<div class="form-group">
<label class="control-label">City *</label>
 <select class="form-control city" name="city" id="city" disabled>
    <option value="">--Select City--</option>
  </select>
</div>
</div>
<div class="col-lg-12 col-md-12">
<div class="form-group">
<div class="address-btns">
<button type="submit" class="save-btn16 hover-btn pull-right btn btn-success">Save</button>

</div>
</div>
</div>
</div>
</div>
</form>
</div>
</div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        </div>
      </div>
      
    </div>
  </div>
  @if(!is_null($value))
  
<div class="address-item">
<div class="address-icon1">
<i class="uil uil-home-alt"></i>
</div>
<div class="address-dt-all">
<h4>{{$value->address_type}}</h4>
<p>{{$value->full_address}}</p>

<input type="hidden" id="statez{{$value->id}}" value="{{$value->state}}">
<input type="hidden" id="cityz{{$value->id}}" value="{{$value->city}}">
<input type="hidden" id="countryz{{$value->id}}" value="{{$value->country}}">
<ul class="action-btns">
<li><a href="#" class="action-btn"  data-toggle="modal" data-target="#address_model{{$value->id}}" onclick="show_edit({{$value->id}})"><i class="uil uil-edit"></i></a></li>
<li>
    
</li>
</ul>
<div class="modal fade" id="deletemodal{{ $value->id }}" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog modal-sm" role="document">
    <div class="modal-content">
            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal">&times;</button>
              <div class="delete-icon"></div>
            </div>
            <div class="modal-body text-center">
              <h5 class="modal-heading">{{ __('Are You Sure ?') }}</h5>
              <p>{{ __('Do you really want to delete this address? This process cannot be undone') }}.</p>
            </div>
            <div class="modal-footer">
               <form method="post" action="{{route('address.del',$value->id)}}" class="pull-right">
                             {{csrf_field()}}
                             {{method_field("DELETE")}}
                <button type="reset" class="btn btn-gray translate-y-3" data-dismiss="modal">
                  {{ __('No') }}
                </button>
                <button type="submit" class="btn btn-danger">
                  {{ __('Yes') }}
                </button>
              </form>
            </div>
          </div>
  </div>
</div>
<div class="modal fade" id="address_model{{$value->id}}" role="dialog">
    <div class="modal-dialog">
    
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Update Address</h4>
        </div>
        <div class="modal-body">
         <div class="row">
<div class="col-lg-12">
<form class="" method="post" id="unniform{{$value->id}}" action="{{url('store_address')}}">
<input type="hidden" name="update_btn" value="{{$value->id}}">
<div class="form-group">
<div class="product-radio">
<ul class="product-now">
<li>
<input type="radio" id="ad1" value="Home" name="address_type" @if($value->address_type=='Home') checked="" @endif>
<label for="ad1">Home</label>
</li>
<li>
<input type="radio" id="ad2" value="Office" name="address_type" @if($value->address_type=='Office') checked="" @endif>
<label for="ad2">Office</label>
</li>
<li>
<input type="radio" id="ad3" value="Other" name="address_type" @if($value->address_type=='Other') checked="" @endif>
<label for="ad3">Other</label>
</li>
</ul>
</div>
</div>
<div class="address-fieldset">
<div class="row">
<div class="col-lg-6 col-md-12">
<div class="form-group">
<label class="control-label">First Name*</label>
<input id="first_name" name="first_name" type="text" value="{{$value->first_name}}" placeholder="First Name" class="form-control input-md" required="">
</div>
</div>
<div class="col-lg-6 col-md-12">
<div class="form-group">
<label class="control-label">Last Name*</label>
<input id="last_name" name="last_name" type="text" value="{{$value->last_name}}"  placeholder="Last Name" class="form-control input-md" required="">
</div>
</div>
<div class="col-lg-6 col-md-12">
<div class="form-group">
<label class="control-label">Email Address*</label>
<input id="email" name="email" type="email" placeholder="Email Address" value="{{$value->email}}"  class="form-control input-md" required="">
</div>
</div>
<div class="col-lg-6 col-md-12">
<div class="form-group">
<label class="control-label">Phone Number*</label>
<input id="phone_number" name="phone_number" type="text" placeholder="Phone Number" value="{{$value->contact_no}}"  class="form-control input-md" required="">
</div>
</div>
<div class="col-lg-6 col-md-12">
<div class="form-group">
<label class="control-label">Alternate Phone Number*</label>
<input id="alternate_phone_number" name="alternate_phone_number" type="text" value="{{$value->alternate_contact_number}}" placeholder="Alternate Phone Number" class="form-control input-md">
</div>
</div>
<div class="col-lg-12 col-md-12">
<div class="form-group">
<label class="control-label">Address with Flat Number / Building Name.*</label>
 <input id="address" name="address" type="text" value="{{$value->address}}" placeholder="Address" class="form-control input-md" required="">
</div>
</div>
<div class="col-lg-12 col-md-12">
<div class="form-group">
<label class="control-label">Full Address *</label>
<input id="full_address" name="full_address" type="text" value="{{$value->full_address}}" placeholder="Full Street Address" class="form-control input-md">
</div>
</div>
<div class="col-lg-6 col-md-12">
<div class="form-group">
<label class="control-label">Pincode*</label>
<input id="pincode" name="pincode" type="text" placeholder="Pincode" value="{{$value->pincode}}" class="form-control input-md" required="">
</div>
</div>
<div class="col-lg-6 col-md-12">
<div class="form-group">
<label class="control-label">Landmark*</label>
<input id="landmark" name="landmark" type="text" value="{{$value->landmark}}"  placeholder="Landmark" class="form-control input-md" >
</div>
</div>
<div class="col-lg-6 col-md-12">
<div class="form-group">
<label class="control-label">Country*</label>
<select class="form-control country input-md" name="country_s" id="country_s{{$value->id}}" required onchange="get_state({{$value->id}})">
<option value="">--select country--</option>
@if(isset($country) && count($country) != 0)
  @foreach ($country as $key => $value1)
    @if ($value1->id == $value->country)
      <option selected value="{{$value1->id}}">{{$value1->country_name}}</option>
    @else
      <option value="{{$value1->id}}">{{$value1->country_name}}</option>
    @endif 
  @endforeach 
@endif
</select>
</div>
</div>
<div class="col-lg-6 col-md-12">
<div class="form-group">
<label class="control-label">State *</label>
 <select class="form-control state input-md" name="state_s" id="state_s{{$value->id}}" onchange="get_city({{$value->id}})" disabled required>
<option value="">--Select State--</option>
</select>
</div>
</div>

<div class="col-lg-6 col-md-12">
<div class="form-group">
<label class="control-label">City *</label>
 <select class="form-control city" name="city_s" id="city_s{{$value->id}}" disabled>
    <option value="">--Select City--</option>
  </select>
</div>
</div>
<div class="col-lg-12 col-md-12">
<div class="form-group">
<div class="address-btns">
<button type="submit" class="save-btn16 hover-btn pull-right btn btn-success">Update</button>

</div>
</div>
</div>
</div>
</div>
</form>
</div>
</div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        </div>
      </div>
      
    </div>
  </div>
</div>
</div>

@endif
</div>
</div>
</div>

</div>
</div>
</div>
</div>
</div>
</div>
</div>
  
                        <?php
                                $user = session()->get('user');
                            ?>
                      
                        

                        

                        
                          
                        
                    
                  
               

<script>     
    $(document).ready(function() {
        <?php if(isset($_GET['tab_id']) && ($_GET['tab_id'] == 'Section4' || $_GET['tab_id'] == 'Section5')) { ?>
        @if($_GET['tab_id'] == 'Section4')
        
            $('.vertical-tab .nav-tabs li a[href="#Section4"]').tab('show');
            $('.vertical-tab .nav-tabs li').removeClass('active'); 
            $('.vertical-tab .nav-tabs li a[href="#Section4"]').parent().addClass('active');
        @endif
             @if($_GET['tab_id'] == 'Section5')
             
            $('.vertical-tab .nav-tabs li a[href="#Section5"]').tab('show');
            $('.vertical-tab .nav-tabs li').removeClass('active'); 
            $('.vertical-tab .nav-tabs li a[href="#Section5"]').parent().addClass('active');
             @endif
        <?php } ?>

        $('.vertical-tab .nav-tabs li').click(function(){ 
            $('.vertical-tab .nav-tabs li').removeClass('active'); 
            $(this).addClass('active'); 
        });

        $('#logout').click(function(){ 
            window.location.href = "{{ route('logout') }}";
        });

        $('.buzin').click(function(){ 
            $(".buzzacc").toggle(); 
        })

    });
</script>

<script>
    // $('body').on('click','.gj_myacc_pge ul.pagination li',function() {
    //     $('a[href="#Section4"]').trigger();                                                                      
    // });
    function show_edit(id)
    {
         
        // $('#address_model'+id).modal('show');
        fetch_country(id);
    }
    function fetch_country(id)
    {
        var country=$('#countryz'+id).val();
         if(country) {
            
            $.ajax({
                type: 'post',
                url: '{{url('/select_state')}}',
                data: {country: country,state:$('#statez'+id).val(), type: 'state'},
                success: function(data){
                    if(data){
                        $("#state_s"+id).html(data);
                        $("#state_s"+id).removeAttr("disabled");

                        var st = $("#statez"+id).val();
                        if(st) {
                            $.ajax({
                                type: 'post',
                                url: '{{url('/select_city')}}',
                                data: {st: st, city: $("#cityz"+id).val(), type: 'city'},
                                success: function(data){
                                    if(data){
                                        $("#city_s"+id).html(data);
                                        $("#city_s"+id).removeAttr("disabled");
                                    } else {
                                        $.confirm({
                                            title: '',
                                            content: 'Please Select State!',
                                            icon: 'fa fa-ban',
                                            theme: 'modern',
                                            closeIcon: true,
                                            animation: 'scale',
                                            type: 'blue',
                                            buttons: {
                                                Ok: function(){
                                                }
                                            }
                                        });
                                        $("#city_s"+id).prop("disabled", true);
                                    }
                                }
                            });
                        } else {
                            $.confirm({
                                title: '',
                                content: 'Please Select State!',
                                icon: 'fa fa-ban',
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
                    } else {
                        /*$.confirm({
                            title: '',
                            content: 'Please Select Country!',
                            icon: 'fa fa-ban',
                            theme: 'modern',
                            closeIcon: true,
                            animation: 'scale',
                            type: 'blue',
                            buttons: {
                                Ok: function(){
                                }
                            }
                        });*/
                        $("#state_s"+id).prop("disabled", true);
                        $("#city_s"+id).prop("disabled", true);
                    }
                }
            });
        }
    }
    function get_state(id)
    {
        
      var country = $('#country_s'+id).val();
      if(country) {
          $.ajax({
              type: 'post',
              url: '{{url('/select_state')}}',
              data: {country: country, type: 'state'},
              success: function(data){
                  if(data){
                      $("#state_s"+id).html(data);
                     $("#state_s"+id).removeAttr("disabled");
                  } else {
                      
                      $("#state_s"+id).val(0);
                      $("#city_s"+id).val(0);
                      $("#state_s"+id).prop("disabled", true);
                      $("#city_s"+id).prop("disabled", true);
                  }
              }
          });
      } else {
        
      }
    
    }
    function get_city(id)
    {
        
      var st = $('#state_s'+id).val();
      if(st) {
        $.ajax({
            type: 'post',
            url: '{{url('/select_city')}}',
            data: {st: st, type: 'city'},
            success: function(data){
                if(data){
                    $('#city_s'+id).html(data);
                    $('#city_s'+id).removeAttr("disabled");
                } else {
                    
                    $('#city_s'+id).val(0);
                    $('#city_s'+id).prop("disabled", true);
                }
            }
        });
      } else {
        
      }
    
    }
    function getUrlVars() {
        var vars = [], hash;
        var hashes = window.location.href.slice(window.location.href.indexOf('?') + 1).split('&');
        for(var i = 0; i < hashes.length; i++)
        {
            hash = hashes[i].split('=');
            vars.push(hash[0]);
            vars[hash[0]] = hash[1];
        }
        return vars;
    }

    $(document).ready(function() { 
        $('p.alert').delay(5000).slideUp(500); 
        $("#country").select2();
        $("#state").select2();
        $("#city").select2();
        $("#question").select2();

        var trgr = false;
        var url = document.location.href;
        var res = url.toString().split('#');
        var resu = url.toString().split('my_account');

        if(res[1]) {
            var trgr = res[1];
        }

        if(trgr) {
            $('.vertical-tab .nav-tabs li a[href="#' + trgr + '"]').tab('show');
            $('.vertical-tab .nav-tabs li').removeClass('active'); 
            $('.vertical-tab .nav-tabs li a[href="#' + trgr + '"]').parent().addClass('active');
        }

        /*@if(isset($orders) && count($orders) != 0)
            if(resu[1]) {
                if(resu[1] == '?page=<?php echo $orders->currentPage(); ?>') {
                    $('.vertical-tab .nav-tabs li a[href="#Section4"]').tab('show');
                    $('.vertical-tab .nav-tabs li').removeClass('active'); 
                    $('.vertical-tab .nav-tabs li a[href="#Section4"]').parent().addClass('active');
                }
            }
        @endif*/

        var country = $('#country').select2('val');
        @if($user->state)
            var state = <?php echo $user->state; ?>;
        @else
            var state = 0;
        @endif

        @if($user->city)
            var city = <?php echo $user->city; ?>;
        @else
            var city = 0;
        @endif

        if(city) {
            city = city;          
        } else {
            city = 0;
        }

        if(country) {
            $.ajax({
                type: 'post',
                url: '{{url('/select_state')}}',
                data: {country: country, state: state, type: 'state'},
                success: function(data){
                    if(data){
                        $("#state").html(data);
                        $("#state").removeAttr("disabled");

                        var st = $('#state').val();
                        if(st) {
                            $.ajax({
                                type: 'post',
                                url: '{{url('/select_city')}}',
                                data: {st: st, city: city, type: 'city'},
                                success: function(data){
                                    if(data){
                                        $("#city").html(data);
                                        $("#city").removeAttr("disabled");
                                    } else {
                                        $.confirm({
                                            title: '',
                                            content: 'Please Select State!',
                                            icon: 'fa fa-ban',
                                            theme: 'modern',
                                            closeIcon: true,
                                            animation: 'scale',
                                            type: 'blue',
                                            buttons: {
                                                Ok: function(){
                                                }
                                            }
                                        });
                                        $("#city").prop("disabled", true);
                                    }
                                }
                            });
                        } else {
                            $.confirm({
                                title: '',
                                content: 'Please Select State!',
                                icon: 'fa fa-ban',
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
                    } else {
                        /*$.confirm({
                            title: '',
                            content: 'Please Select Country!',
                            icon: 'fa fa-ban',
                            theme: 'modern',
                            closeIcon: true,
                            animation: 'scale',
                            type: 'blue',
                            buttons: {
                                Ok: function(){
                                }
                            }
                        });*/
                        $("#state").prop("disabled", true);
                        $("#city").prop("disabled", true);
                    }
                }
            });
        } else {
            /*$.confirm({
                title: '',
                content: 'Please Select Country!',
                icon: 'fa fa-ban',
                theme: 'modern',
                closeIcon: true,
                animation: 'scale',
                type: 'blue',
                buttons: {
                    Ok: function(){
                    }
                }
            });*/
        }

        @if(isset($user['docs']))
            var cnt = <?php echo count($user['docs']) + 1;?>;
        @else
            var cnt = 2;
        @endif

        $("#img_addButton").click(function () {
            var newTextBoxDiv = $(document.createElement('tr')).attr("id", 'gj_tr_m_doc_' + cnt);
            newTextBoxDiv.after().html('<td><input class="form-control gj_d_name" placeholder="Enter Product Name" name="d_name[]" type="text" id="d_name_' + cnt + '"></td><td><input type="file" name="d_image[]" id="d_image_' + cnt + '" class="gj_d_image form-control"></td><td><button type="button" id="img_removeButton_' + cnt + '" class="gj_m_doc_rem"><i class="fa fa-trash"></i></button></td>');
            newTextBoxDiv.appendTo("#gj_m_doc_bdy");
            cnt++;
        });

        $('body').on('click','.gj_m_doc_rem',function() {
            if(cnt==1){
                $.confirm({
                    title: '',
                    content: 'No more textbox to remove!',
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
                return false;
            }   
        
            cnt--;
            $(this).closest('tr').remove();
        });
    });

    $('#country').on('change',function() {
        var country = $(this).val();
        if(country) {
            $.ajax({
                type: 'post',
                url: '{{url('/select_state')}}',
                data: {country: country, type: 'state'},
                success: function(data){
                    if(data){
                        $("#state").html(data);
                        $("#state").removeAttr("disabled");
                    } else {
                        $.confirm({
                            title: '',
                            content: 'Please Select Country!',
                            icon: 'fa fa-ban',
                            theme: 'modern',
                            closeIcon: true,
                            animation: 'scale',
                            type: 'blue',
                            buttons: {
                                Ok: function(){
                                }
                            }
                        });
                        $("#state").prop("disabled", true);
                        $("#city").prop("disabled", true);
                    }
                }
            });
        } else {
            $.confirm({
                title: '',
                content: 'Please Select Country!',
                icon: 'fa fa-ban',
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

    $('#state').on('change',function() {
        var st = $(this).val();
        if(st) {
            $.ajax({
                type: 'post',
                url: '{{url('/select_city')}}',
                data: {st: st, type: 'city'},
                success: function(data){
                    if(data){
                        $("#city").html(data);
                        $("#city").removeAttr("disabled");
                    } else {
                        $.confirm({
                            title: '',
                            content: 'Please Select State!',
                            icon: 'fa fa-ban',
                            theme: 'modern',
                            closeIcon: true,
                            animation: 'scale',
                            type: 'blue',
                            buttons: {
                                Ok: function(){
                                }
                            }
                        });
                        $("#city").prop("disabled", true);
                    }
                }
            });
        } else {
            $.confirm({
                title: '',
                content: 'Please Select State!',
                icon: 'fa fa-ban',
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

<!-- Cancel Order Script Start -->
<script type="text/javascript">
    $('body').on('click','.gj_my_codr',function() {
        var id = 0;                                                       
        var th = $(this);                                                       
        if($(this).attr('data-id')){
            id = $(this).attr('data-id');
        }   
    
        if(id != 0) {
            $.confirm({
                title: '',
                content: 'Are You Sure to Cancel this Order?',
                icon: 'fa fa-ban',
                theme: 'modern',
                closeIcon: true,
                animation: 'scale',
                type: 'purple',
                buttons: {
                    Ok: function(){
                        $.ajax({
                            type: 'post',
                            url: '{{url('/customer_cancel_order')}}',
                            data: {id: id, type: 'cancel'},
                            success: function(data) {
                                if(data == 1) {
                                    $.confirm({
                                        title: '',
                                        content: 'Your Order Cancel Request Send Successfully!!',
                                        icon: 'fa fa-check',
                                        theme: 'modern',
                                        closeIcon: true,
                                        animation: 'scale',
                                        type: 'green',
                                        buttons: {
                                            Ok: function(){
                                                $('.modal').removeClass('show');
                                                $('.modal-backdrop').removeClass('show');
                                                window.location.href = "<?php echo route('my_account').'#Section4'; ?>";
                                            },
                                            Cancel:function() {
                                                $('.modal').removeClass('show');
                                                $('.modal-backdrop').removeClass('show');
                                            }
                                        }
                                    });
                                } else if(data == 5){
                                    $.confirm({
                                        title: '',
                                        content: 'You can cancel order request send after 24 hours of ordering!',
                                        icon: 'fa fa-exclamation',
                                        theme: 'modern',
                                        closeIcon: true,
                                        animation: 'scale',
                                        type: 'red',
                                        buttons: {
                                            Ok: function(){
                                                $('.modal').removeClass('show');
                                                $('.modal-backdrop').removeClass('show');
                                                window.location.href = "<?php echo route('my_account').'#Section4'; ?>";
                                            },
                                            Cancel:function() {
                                                $('.modal').removeClass('show');
                                                $('.modal-backdrop').removeClass('show');
                                            }
                                        }
                                    });
                                    window.location.href = "<?php echo route('my_account').'#Section4'; ?>";
                                } else {
                                    $.confirm({
                                        title: '',
                                        content: 'No Way to Cancel This Order!',
                                        icon: 'fa fa-ban',
                                        theme: 'modern',
                                        closeIcon: true,
                                        animation: 'scale',
                                        type: 'red',
                                        buttons: {
                                            Ok: function(){
                                                $('.modal').removeClass('show');
                                                $('.modal-backdrop').removeClass('show');
                                                window.location.href = "<?php echo route('my_account').'#Section4'; ?>";
                                            },
                                            Cancel:function() {
                                                $('.modal').removeClass('show');
                                                $('.modal-backdrop').removeClass('show');
                                            }
                                        }
                                    });
                                    th.css("pointer-events", "none");
                                }
                            }
                        });
                    },
                    Cancel:function() {
                        $('.modal').removeClass('show');
                        $('.modal-backdrop').removeClass('show');
                    }
                }
            });
        } else {
            $.confirm({
                title: '',
                content: 'You Are Not Cancelled this Order!',
                icon: 'fa fa-ban',
                theme: 'modern',
                closeIcon: true,
                animation: 'scale',
                type: 'red',
                buttons: {
                    Ok: function(){
                        $('.modal').removeClass('show');
                        $('.modal-backdrop').removeClass('show');
                        window.location.href = "<?php echo route('my_account').'#Section4'; ?>";
                    },
                    Cancel:function() {
                        $('.modal').removeClass('show');
                        $('.modal-backdrop').removeClass('show');
                    }
                }
            });

            window.location.href = "<?php echo route('my_account').'#Section4'; ?>";
        }
    });
</script>
<!-- Cancel Order Script End -->

<!-- Return Order Script Start -->
<!-- <script type="text/javascript">
    $('body').on('click','.gj_my_rodr_req',function() {
        var id = 0;                                                       
        var th = $(this);                                                       
        if($(this).attr('data-id')){
            id = $(this).attr('data-id');
        }   
    
        if(id != 0) {
            $.confirm({
                title: '',
                content: 'Are You Sure to Return / Replace this Order?',
                icon: 'fa fa-exclamation',
                theme: 'modern',
                closeIcon: true,
                animation: 'scale',
                type: 'red',
                buttons: {
                    Ok: function(){
                        $.ajax({
                            type: 'post',
                            url: '{{url('/customer_cancel_order')}}',
                            data: {id: id, type: 'cancel'},
                            success: function(data) {
                                if(data == 1) {
                                    $.confirm({
                                        title: '',
                                        content: 'Your Order Cancel Request Send Successfully!!',
                                        icon: 'fa fa-check',
                                        theme: 'modern',
                                        closeIcon: true,
                                        animation: 'scale',
                                        type: 'green',
                                        buttons: {
                                            Ok: function(){
                                                $('.modal').removeClass('show');
                                                $('.modal-backdrop').removeClass('show');
                                                window.location.href = "<?php echo route('my_account').'#Section4'; ?>";
                                            },
                                            Cancel:function() {
                                                $('.modal').removeClass('show');
                                                $('.modal-backdrop').removeClass('show');
                                            }
                                        }
                                    });
                                } else if(data == 5){
                                    $.confirm({
                                        title: '',
                                        content: 'You can cancel order request send after two days of ordering!',
                                        icon: 'fa fa-exclamation',
                                        theme: 'modern',
                                        closeIcon: true,
                                        animation: 'scale',
                                        type: 'red',
                                        buttons: {
                                            Ok: function(){
                                                $('.modal').removeClass('show');
                                                $('.modal-backdrop').removeClass('show');
                                                window.location.href = "<?php echo route('my_account').'#Section4'; ?>";
                                            },
                                            Cancel:function() {
                                                $('.modal').removeClass('show');
                                                $('.modal-backdrop').removeClass('show');
                                            }
                                        }
                                    });
                                    window.location.href = "<?php echo route('my_account').'#Section4'; ?>";
                                } else {
                                    $.confirm({
                                        title: '',
                                        content: 'No Way to Cancel This Order!',
                                        icon: 'fa fa-ban',
                                        theme: 'modern',
                                        closeIcon: true,
                                        animation: 'scale',
                                        type: 'red',
                                        buttons: {
                                            Ok: function(){
                                                $('.modal').removeClass('show');
                                                $('.modal-backdrop').removeClass('show');
                                                window.location.href = "<?php echo route('my_account').'#Section4'; ?>";
                                            },
                                            Cancel:function() {
                                                $('.modal').removeClass('show');
                                                $('.modal-backdrop').removeClass('show');
                                            }
                                        }
                                    });
                                    th.css("pointer-events", "none");
                                }
                            }
                        });
                    },
                    Cancel:function() {
                        $('.modal').removeClass('show');
                        $('.modal-backdrop').removeClass('show');
                    }
                }
            });
        } else {
            $.confirm({
                title: '',
                content: 'You Are Not Cancelled this Order!',
                icon: 'fa fa-ban',
                theme: 'modern',
                closeIcon: true,
                animation: 'scale',
                type: 'red',
                buttons: {
                    Ok: function(){
                        $('.modal').removeClass('show');
                        $('.modal-backdrop').removeClass('show');
                        window.location.href = "<?php echo route('my_account').'#Section4'; ?>";
                    },
                    Cancel:function() {
                        $('.modal').removeClass('show');
                        $('.modal-backdrop').removeClass('show');
                    }
                }
            });

            window.location.href = "<?php echo route('my_account').'#Section4'; ?>";
        }
    });
</script> -->
<!-- Return Order Script End -->
@endsection