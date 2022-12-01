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

     	<title>E-Cambiar - @yield('title')</title>

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
   <body>
   		<section class="gj_top_header">
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
		                     	<li>
		                     		<a href="{{ route('my_profile') }}" class="btn btn-default"><i class="fa fa-user"></i>{{$value->first_name.' '.$value->last_name}}</a>
		                        </li>

		                        <!-- <li>
		                     		<a href="{{ route('my_profile') }}" class="btn btn-default"><i class="fa fa-user"></i>My Profile</a>
		                        </li> -->

                        		@if($value->user_type == 1)
			                        <li>
			                        	<a href="{{ route('user_previl') }}" class="btn btn-default"><i class="fa fa-gear"></i>Roles & Privileges</a>
			                        </li>
		                        @endif

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

					                <li><a href="{{ route('manage_offer') }}">Offers</a></li>

		                			<li><a href="{{ route('merchant_dashboard') }}">Merchants</a></li>

				                	<!-- <li><a href="/all_transaction"> Transaction</a> </li> -->
				                	<li><a href="{{ route('all_orders') }}">Transaction</a> </li>
				                	
				                	<li><a href="{{ route('courier_track') }}">Courier Tracking</a> </li>

				                	<!-- <li><a href="/manage_publish_blog">Blogs</a></li> -->

				                	<li><a href="{{ route('manage_enquiries') }}">Messages</a> </li>
				                	
				                	<li><a href="{{ route('manage_user') }}">Users</a></li>

				                	<li><a href="{{ route('manage_credits') }}">Accounts</a></li>
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

		                			<li><a href="{{ route('manage_store', ['id' => $value->id]) }}">Stores</a></li>

		                			<li><a href="{{ route('all_orders') }}">Transaction</a> </li>

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
		        <p>© {{ Carbon\Carbon::today()->format('Y') }} | @if($general){{$general->site_name}} @else  E-Cambiar @endif | All Rights Reserved </p>
		    </div>
    	</footer>

        <script type="text/javascript">
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