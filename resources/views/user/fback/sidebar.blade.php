<style>
.avatar-upload {
  position: relative;
  max-width: 140px;
  margin: 50px auto;
}
.avatar-upload .avatar-edit {
  position: absolute;
  right: 12px;
  z-index: 1;
  top: 10px;
}
.avatar-upload .avatar-edit input {
  display: none;
}
.avatar-upload .avatar-edit input + label {
  display: inline-block;
  width: 34px;
  height: 34px;
  margin-bottom: 0;
  border-radius: 100%;
  background: #FFFFFF;
  border: 1px solid transparent;
  box-shadow: 0px 2px 4px 0px rgba(0, 0, 0, 0.12);
  cursor: pointer;
  font-weight: normal;
  transition: all 0.2s ease-in-out;
}
.avatar-upload .avatar-edit input + label:hover {
  background: #f1f1f1;
  border-color: #d6d6d6;
}
.avatar-upload .avatar-edit input + label:after {
  content: "\f040";
  font-family: 'FontAwesome';
  color: #757575;
  position: absolute;
  top: 10px;
  left: 0;
  right: 0;
  text-align: center;
  margin: auto;
}
.avatar-upload .avatar-preview {
  width: 125px;
  height: 125px;
  position: relative;
  border-radius: 100%;
  border: 6px solid #F8F8F8;
  box-shadow: 0px 2px 4px 0px rgba(0, 0, 0, 0.1);
}
.avatar-upload .avatar-preview > div {
  width: 100%;
  height: 100%;
  border-radius: 100%;
  background-size: cover;
  background-repeat: no-repeat;
  background-position: center;
}



</style>

<?php
$user=App\User::find(Auth::user()->id);
 ?>
 <div class="col-lg-4">
     <div class="ps-section__left">
       <a href="{{url('email_share')}}" class="btn btn-info fa fa-share">Invite</a>
         <aside class="ps-widget--account-dashboard">
<div class="ps-widget__header">

 @if($user->image!=null)
<?php
$imc=url('images/user/'.$user->image);
 ?>
@else
<?php
$imc= Avatar::create(Auth::user()->name)->toBase64() ;
 ?>
@endif


  <div class="avatar-upload">
        <div class="avatar-edit">
            <input type='file' id="imageUpload" accept=".png, .jpg, .jpeg" />
            <label for="imageUpload"></label>
        </div>
        <div class="avatar-preview">
            <div id="imagePreview" style="background-image: url('{{$imc}}');">
            </div>
        </div>
        <figure>
            <figcaption>{{$user->name}}</figcaption>
            <p><a href="#"><span> {{$user->email}} </span></a></p>
            </figure>
    </div>

</div>
 <div class="ps-widget__content">
<ul>
    <li class="active"><a href="{{url('my_account')}}"><i class="icon-user"></i> Personal Information</a></li>
    <li><a href="#"><i class="icon-alarm-ringing"></i> Notifications</a></li>

    <li><a href="{{url('myOrders')}}"><i class="icon-papers"></i> Orders</a></li>
    <li><a href="#"><i class="icon-papers"></i> Invoices</a></li>
    <li><a href="{{url('manageaddress')}}"><i class="icon-map-marker"></i>Shipping Address</a></li>
    <li><a href="{{url('billingaddress')}}"><i class="icon-map-marker"></i>Billing Address</a></li>
    <li><a href="{{url('wishlist')}}"><i class="icon-heart"></i> Wishlist</a></li>
    <li><a href="{{ route('logout') }}"
                                           onclick="event.preventDefault();
                                                         document.getElementById('logout-form').submit();"><i class="icon-power-switch"></i>Logout</a></li>
</ul>
<form id="logout-form" action="{{ route('logout') }}" method="POST" class="display-none">
    @csrf
</form>
<form id="profile_form" action="#" method="POST" class="display-none">
    @csrf
</form>
</div>

</aside>
</div>
</div>
@push('page_scripts')
<script>

function readURL(input) {
    if (input.files && input.files[0]) {
      var lo=APP_URL+'/images/waiting.gif';
    var str=new FormData(document.getElementById('profile_form'));
     str.append('fea_images',input.files[0]);

     $.ajaxSetup({
         headers: {
             'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content')
         }
     });
      $.ajax({
      url:  APP_URL+"/update_profile/"+'{{Auth::user()->id}}',
      type: "POST",
      data:str,
      dataType: 'json',
      processData: false,
       contentType: false,
       beforeSend:function(){
         $('#imagePreview').html('<center><img height="70px" width="70px" src='+lo+'></center>');
       },
      success:function(response){
        if(response)
        {
          $('#imagePreview').html('');
          var reader = new FileReader();
          reader.onload = function(e) {
              $('#imagePreview').css('background-image', 'url('+e.target.result +')');
              $('#imagePreview').hide();
              $('#imagePreview').fadeIn(650);
          }
          reader.readAsDataURL(input.files[0]);
        }
      }
    });

    }
}
$("#imageUpload").change(function() {
    readURL(this);
});


</script>
@endpush
