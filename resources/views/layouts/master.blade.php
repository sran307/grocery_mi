<?php 
$general = \DB::table('general_settings')->first();
$social = \DB::table('social_media_settings')->first();
$logo = \DB::table('logo_settings')->first();
$logo_path = 'images/logo';
$favicon = \DB::table('favicon_settings')->first();
$favicon_path = 'images/favicon';
$value = session()->get('user'); 
?>
<html lang="{{ app()->getLocale() }}">   
	<head>
		<meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">

     	<title> @if($general){{$general->site_name}} @else  Smart Hub @endif - @yield('title')</title>

     	@if($favicon)
     		<link rel="shortcut icon" href="{{ asset($favicon_path.'/'.$favicon->favicon_image)}}" type="image/x-icon">
     	@else
     		<link rel="shortcut icon" href="{{ asset('images/fav_icon.png')}}" type="image/x-icon">
     	@endif
    	<meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
     	<!-- Fonts -->
        <link rel="stylesheet" href="{{ asset('css/font-awesome.min.css')}}">
        <link rel="stylesheet" href="{{ asset('css/select2.min.css')}}">
        <!-- <link rel="stylesheet" href="{{ asset('https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css')}}"> -->
        <link rel="stylesheet" href="{{ asset('css/bootstrap.min.css')}}">
        <link rel="stylesheet" href="{{ asset('css/bootstrap-datetimepicker.min.css')}}">
        <link rel="stylesheet" href="{{ asset('css/jquery-confirm.min.css')}}">
        <link rel="stylesheet" href="{{ asset('css/style.css')}}">

        <script src="{{ asset('js/jquery.min.js')}}"></script>
        <script src="{{ asset('js/bootstrap.min.js')}}"></script>
        <script src="{{ asset('js/bootstrap-datetimepicker.min.js')}}"></script>
        <script src="{{ asset('js/select2.min.js')}}"></script>
        <script src="{{ asset('js/datatables.js')}}"></script>
        <script src="{{ asset('js/jquery-confirm.min.js')}}"></script>

   </head>
   <body class="@yield('body_class')">
       <style>
           .notification{
  padding-top: 30px;
  position: relative;
  display: inline-block;
}

.number{
  height: 22px;
  width:  22px;
  background-color: #d63031;
  border-radius: 20px;
  color: white;
  font-size:15px;
  text-align: center;
  top: 23px;
  left: 60px;
  padding: 6px;
  border-style: solid;
  border-width: 2px;
}

.number:empty {
   display: none;
}

.notBtn{
  transition: 0.5s;
  cursor: pointer
}

.fas{
  font-size: 17pt;
  
  color: black;
}

.box{
  width: 400px;
  height: 0px;
  border-radius: 10px;
  transition: 0.5s;
  position: absolute;
  overflow-y: scroll;
  padding: 0px;
  left: -366px;
  margin-top: 5px;
  background-color: #F4F4F4;
  -webkit-box-shadow: 10px 10px 23px 0px rgba(0,0,0,0.2);
  -moz-box-shadow: 10px 10px 23px 0px rgba(0,0,0,0.1);
  box-shadow: 10px 10px 23px 0px rgba(0,0,0,0.1);
  cursor: context-menu;
}

.fas:hover {
  color: #c3bbb6;
}

.notBtn:hover > .box{
  height: 80vh
}

.content{
  padding: 20px;
  color: black;
  vertical-align: middle;
  text-align: left;
}

.gry{
  background-color: #F4F4F4;
}

.top{
  color: black;
  padding: 10px
}

.display{
  position: relative;
}

.cont{
  position: absolute;
  top: 0;
  width: 100%;
  height: 100%;
  background-color: #F4F4F4;
}
.clone{
    margin: 0px;
    padding: 0px;
    border: 0px;
    background-color: #b5a7a7 !important;
    color: #fff;
}
.cont:empty{
  display: none;
}

.stick{
  text-align: center;  
  display: block;
  font-size: 50pt;
  padding-top: 70px;
  padding-left: 80px
}

.stick:hover{
  color: black;
}

.cent{
  text-align: center;
  display: block;
}

.sec{
  padding: 25px 10px;
  background-color: #b5a7a7;
  transition: 0.5s;
}

.profCont{
  padding-left: 15px;
}

.profile{
  -webkit-clip-path: circle(50% at 50% 50%);
  clip-path: circle(50% at 50% 50%);
  width: 75px;
  float: left;
}
.notBtn a{
    margin: 0px;
    padding: 0px;
    border: 0px;
    background-color: #fbf5f1;
    color: #fff;
}
.txt{
  vertical-align: top;
  font-size: 1.25rem;
  padding: 5px 10px 0px 115px;
}

.sub{
  font-size: 1rem;
  color: grey;
}

.new{
  border-style: none none solid none;
  border-color: red;
}

.sec:hover{
  background-color: #b5a7a7;
}

       </style>
   		<section class="gj_top_header @yield('hdr_class')">
   			<div class="gj_bg_sec">
	   			<div id="gj_top">
	         		<nav class="navbar navbar-inverse navbar-fixed-top " style="padding-top: 10px;">
	                	<!-- LOGO SECTION -->
	                	<header class="navbar-header">
	                   		<a href="{{ route('home') }}" class="navbar-brand">
	                   			@if($logo)
		                 	  		<img src="{{ asset($logo_path.'/'.$logo->logo_image)}}" alt="Logo">
	                   			@else
		                 	  		<img src="{{ asset('images/logo.png')}}" alt="Logo">
	                   			@endif
	                 	  	</a>
	                	</header>
	                	<!-- END LOGO SECTION -->
	                	<ul class="nav navbar-top-links navbar-right">
	                        @if($value)
                                @if($value->user_type == 2 || $value->user_type == 3)
                                    <?php  
                                        $credits = \DB::table('credits_managements')->Where('merchant_id', $value->id)->OrderBy('id', 'desc')->first();
                                    ?>

                                    @if($credits && ($credits->current_credits < 0))
                                        <li>
                                            <a href="{{ route('manage_credits') }}" class="btn btn-default"><i class="gj_bell fa fa-bell"></i>Negative Credits</a>
                                        </li>
                                    @endif
                                @endif

		                     <li>
		                        
  
   

		                     </li>

		                        <li>
		                     		<a href="{{ route('my_profile') }}" class="btn btn-default"><i class="fa fa-user"></i>My Profile</a>
		                        </li> 

                        	<!--	@if($value->user_type == 1)
			                        <li>
			                        	<a href="{{ route('user_previl') }}" class="btn btn-default"><i class="fa fa-gear"></i>Roles & Privileges</a>
			                        </li>
		                        @endif-->

		                        <li>
		                        	<a href="{{ route('logout') }}" class="btn btn-default"><i class="fa fa-sign-out"></i>Logout</a>
		                        </li>
	                        @else
	                        	<li>
		                        	<a href="{{ route('admin') }}" class="btn btn-default"><i class="fa fa-sign-in"></i>Login</a>
		                        </li>
	                        @endif
	                	</ul>
	            	</nav>
	            	
                    @if($value)
                        @if($value->user_type == 1)
							<div class="mainmenu">
								<div class="gj_drawer_menu">
									<a href="#" class="slider-trigger"> + </a>
								</div> 
				            	<ul class="gj_main">
					                <li><a href="{{ route('dashboard') }}" class="active">Dashboard</a></li>

					                <li><a href="{{ route('create_general_setting') }}">Settings</a></li>

				                	<!-- <li><a href="/product_dashboard"> Products </a>  </li> -->
				                	<li><a href="{{ route('manage_product') }}">Products</a>  </li>

					                <!--<li><a href="{{ route('manage_offer') }}">Offers</a></li>-->

		                			<!--<li><a href="{{ route('merchant_dashboard') }}">Merchants</a></li>-->

				                	<!-- <li><a href="/all_transaction"> Transaction</a> </li> -->
				                	<li><a href="{{ route('all_orders') }}">Transaction</a> </li>
				                	
				                	<!--<li><a href="{{ route('courier_track') }}">Courier Tracking</a> </li>-->

				                	<!-- <li><a href="/manage_publish_blog">Blogs</a></li> -->

				                	<!--<li><a href="{{ route('manage_enquiries') }}">Messages</a> </li>-->
				                	
				                	<li><a href="{{ route('manage_user') }}">Users</a></li>

				                	<!--<li><a href="{{ route('manage_credits') }}">Accounts</a></li>-->
				                	
				                	<!--<li><a href="{{ route('admin_build_pc') }}">Build Your PC's</a></li>-->
				                	
			                	</ul>
		            		</div>
                        @elseif($value->user_type == 2 || $value->user_type == 3)
                    		<div class="mainmenu">
								<div class="gj_drawer_menu">
									<a href="#" class="slider-trigger"> + </a>
								</div> 
				            	<ul class="gj_main">
                                	<li><a href="{{ route('merchants_dashboard') }}" class="active">Dashboard</a></li>

					                <li><a href="{{ route('manage_att_fields') }}">Settings</a>  </li>

				                	<li><a href="{{ route('manage_product') }}">Products</a>  </li>

		                			<!--<li><a href="{{ route('manage_store', ['id' => $value->id]) }}">Stores</a></li>-->

		                			<!--<li><a href="{{ route('all_orders') }}">Transaction</a> </li>-->

				                	<!-- <li><a href="{{ route('courier_track') }}">Courier Tracking</a></li> -->

				                	<li><a href="{{ route('my_profile') }}">Users</a></li>

				                	<li><a href="{{ route('manage_credits') }}">Accounts</a></li>
			                	</ul>
		            		</div>
                        @endif
                    @endif
	        	</div>
   			</div>
   		</section>
    	@yield('content')
    	<footer>
    		<div id="gj_footer">
		        <p>© {{ Carbon\Carbon::today()->format('Y') }} | @if($general){{$general->site_name}} @else  Smart Hub @endif | All Rights Reserved </p>
		    </div>
    	</footer>

        <script type="text/javascript">
          
        //   get_notification();
          
          function get_notification()
          {
              $.ajax({
                type: 'get',
                url: '{{url('/select_notification')}}',
                data: {},
                 success: function(data){
                    if(data){
                        $('.cont').html(data.html);
                        $('.number').html(data.count);
                    }
                 }
                 });
          }
        
        
        
        	var sliderTrigger = document.getElementsByClassName("slider-trigger")[0];
			var slider = document.getElementsByClassName('slider-parent')[0];

			sliderTrigger.addEventListener( "click" , function(el){

			    if(slider.classList.contains("active")){
			        slider.classList.remove("active");
			    } else{
			        slider.classList.add("active");
			    }

			});

			$(document).ready(function() {
			    @if(isset($page))
					var page = "<?php echo $page; ?>";
				@endif
				$('.gj_main li a').removeClass('active');
				$(".gj_main li a").each(function() {
			        if($(this).text() == page) {
			        	$(this).addClass('active');
			        }
		        });

			    var current = location.pathname;
			    var url  = window.location.href;     // Returns full URL
			    /*$('.gj_main li a').each(function(){
			        var $this = $(this);

			        // if the current path is like this link, make it active
			        if(($this.attr('href').indexOf(current) !== -1) && url == this.href) {
			        	$('.gj_main li a').removeClass('active');
			            $this.addClass('active');
			        } else {
			        	$('.gj_main li a').removeClass('active');
			        }
			    });*/

			    $('#gj_left li a').each(function(){
			        var $this = $(this);
			        $this.parent().removeClass('active');

			        // if the current path is like this link, make it active
			        if(($this.attr('href').indexOf(current) !== -1) && url == this.href) {
			            $this.parent().addClass('active');
			        }
			    });

			    $('#gj_menu li.panel ul li a').each(function(){
			        var $this = $(this);
			        $this.parent().removeClass('active');

			        // if the current path is like this link, make it active
			        if(($this.attr('href').indexOf(current) !== -1) && url == this.href) {
			            $this.parent().addClass('active');
			            $this.closest("li.panel").addClass('active');
			            $this.closest("ul").addClass('in');
			        }
			    });
			});
        </script>

        <!-- Auto Complete Off Script Start -->
	    <script type="text/javascript">
	      $(document).ready(function() {
	        $("input").attr('autocomplete', 'new-password');
	      });
	    </script>
	    <!-- Auto Complete Off Script End -->

	    <!-- Google Analytics Start -->
	    @if($social)
          @if($social->analytics_code)
            <div><?php echo htmlspecialchars_decode($social->analytics_code); ?></div>
          @endif
        @endif
	    <!-- Google Analytics End -->
   </body>
</html>