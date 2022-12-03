<!DOCTYPE html>
namespace App\Models;

<head>
	<title>SmartHub Groceries </title>
	
	<link rel="shortcut icon" href="https://bioessenza.com/Smarthub/images/favicon/Nov-2021/logo.png" type="image/x-icon">

     <script src="{{ asset('frontend/js/jquery.min.js')}}"></script>
    <script src="{{asset('js/loopcounter.js')}}"></script>
    <script src="{{ asset('assets')}}/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <link href="{{asset('assets')}}/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">

<style>
    .comzon{margin:5% auto;display:block;text-align:center;color:#2f9e49;font-weight:700;font-size:50px;}
    .sr_login{
        position:absolute;
        right:10%;
    }
    .sr_timer{
      display: flex;
      justify-content: space-around;
      margin:0% 10%;
      text-align: center;
      color:#2f9e49 ;
  }
    .sr_day, .sr_hour, .sr_min, .sr_sec{
        font-weight: 900;
        font-size: 100px;
    }
    .sr_span{
        display: block;
        font-weight: 600;
        font-size: 40px;
    }
    
    .sr_input{
        margin-left: auto;
    }
    input{
        height: 35px;
        line-height: 1;
    }
</style>
	
</head>
<body>
        <a class="sr_login" href="{{route('admin')}}"><button class="btn btn-danger">Login</button></a>
        <img class="comzon sr_comzon" src="{{asset('images/favicon/Jan-2022/fav.png')}}" alt="">
        <h1 class="comzon">Site Under Construction...</h1>
        <form action="" method="post">
        <div class="text-center">
            <input type="text" name="coming_id" class=" sr_input"><button class="btn btn-success">Submit</button>
        </div>
        </form>
       
      
        <div class="myCountdown sr_timer" data-date="2022-02-18 23:59:59">
        
            <div class="sr_day"><span class="counter-days"></span><span class="sr_span">DAYS</span></div>
            
            <div class="sr_hour"><span class="counter-hours"></span><span class="sr_span">HOURS</span></div>
            
            <div class="sr_min"><span class="counter-minutes"></span><span class="sr_span">MINUTES</span></div>
            
            <div class="sr_sec"><span class="counter-seconds"></span><span class="sr_span">SECONDS</span></div>
        
        </div>

        <script>
            $(function(){
                loopcounter('myCountdown');
            });

        </script>
</body>
</html>