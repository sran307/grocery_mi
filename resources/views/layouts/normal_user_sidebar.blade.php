<!-- <li role="presentation" class="active"><a href="#Section1" aria-controls="home" role="tab" data-toggle="tab">My Profile</a></li>

<li role="presentation"><a href="#Section2" aria-controls="profile" role="tab" data-toggle="tab">Edit Profile</a></li>

<li role="presentation"><a href="#Section3" aria-controls="messages" role="tab" data-toggle="tab"> Change Password</a></li>

<li role="presentation"><a href="#Section4" aria-controls="orders" role="tab" data-toggle="tab">My Orders</a></li>

<li role="presentation"><a href="#Section5" aria-controls="past_orders" role="tab" data-toggle="tab">Past Orders</a></li>

<li role="presentation"><a href="#Section6" aria-controls="cancel_orders" role="tab" data-toggle="tab">Cancel Orders</a></li>

<li role="presentation"><a href="#rtn_odr" aria-controls="cancel_orders" role="tab" data-toggle="tab">Return Orders</a></li>

<li role="presentation"><a href="#Section7" aria-controls="feed_back" role="tab" data-toggle="tab">Feed Back</a></li>

<li role="presentation" id="logout"><a href="{{ route('logout') }}" aria-controls="logout" role="tab" data-toggle="tab">Logout</a></li>-->
<div class="wrapper">
  <?php
                                $users = session()->get('user');
                                $user=App\User::find($users->id);
                            ?>
                            {{ Form::open(array('method' => 'post','class'=>'display-none','files' => true,'id'=>'profile_form')) }}
                            
                            {{Form::close()}}
<div class="dashboard-group">
<div class="container">
<div class="row">
<div class="col-lg-12">
<div class="user-dt">
<div class="user-img">
@if($user->profile_img)
<img src="{{ asset('images/profile_img/'.$user->profile_img)}}" id="imagePreview" > 
@else
<img src="{{ asset('images/site_img/default_profile.jpg')}}" id="imagePreview" > 
@endif
<div class="img-add">
<input type='file' id="file" accept=".png, .jpg, .jpeg" />
<label for="file"><i class="uil uil-camera-plus"></i></label>
</div>
</div>
<h4 class="text-uppercase">{{$user->first_name}}</h4>
<p>{{$user->phone}}<a href="{{url('edit_profile')}}"><i class="uil uil-edit"></i></a></p>
 
</div>
</div>
</div>
</div>
</div>
<div class="">
<div class="container">
<div class="row">
<div class="col-lg-3 col-md-4">
<div class="left-side-tabs">
<div class="dashboard-left-links">
<a href="{{url('my_account')}}" class="user-item @if(Request::url() == url('my_account')) active @endif"><i class="uil uil-apps"></i>My Profile</a>
<a href="{{url('edit_profile')}}" class="user-item @if(Request::url() == url('edit_profile')) active @endif"><i class="uil uil-box"></i>Edit Profile</a>
 
<a href="{{url('change_password')}}" class="user-item  @if(Request::url() == url('change_password')) active @endif"><i class="uil uil-wallet"></i>Change Password</a>
<a href="{{url('wishlist')}}" class="user-item  @if(Request::url() == url('wishlist')) active @endif"><i class="uil uil-heart"></i> My Wishlist</a>
<a href="{{url('get_orders')}}" class="user-item @if(Request::url() == url('get_orders')) active @endif"><i class="uil uil-box"></i>My Orders</a>

<a href="{{url('manage_address')}}" class="user-item "><i class="uil uil-location-point"></i>My Address</a>
<a href="{{ route('logout') }}" class="user-item"><i class="uil uil-exit"></i>Logout</a>
</div>
</div>
</div>

<script>
    function readURL(input) {
    if (input.files && input.files[0]) {
      
        var str=new FormData(document.getElementById('profile_form'));
     str.append('fea_images',input.files[0]);

     $.ajaxSetup({
         headers: {
             'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content')
         }
     });
      $.ajax({
      url: '{{url("/update_profile_image")}}',
      type: "POST",
      data:str,
      dataType: 'json',
      processData: false,
       contentType: false,
       
      success:function(response){
        if(response)
        {
         
          var reader = new FileReader();
          reader.onload = function(e) {
            $('#imagePreview').attr('src', e.target.result);
            $('#imagePreview').hide();
            $('#imagePreview').fadeIn(650);
        }
        reader.readAsDataURL(input.files[0]);
        }
      }
    });
        
    }
}
$("#file").change(function() {
    readURL(this);
});
</script>