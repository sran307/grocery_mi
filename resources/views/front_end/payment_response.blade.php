@extends('layouts.frontend')
@section('title', 'Payment Response')
<link rel="stylesheet" type="text/css" media="all" href="{{ asset('css/bootstrap.min.css')}}">
<link rel="stylesheet" type="text/css" href="{{ asset('login/animate.css')}}">
<link rel="stylesheet" type="text/css" href="{{ asset('login/main.css')}}">
@section('content')
	<section class="gj_pay_redp">
		<h1 align="center" class="gj_pay_redp_hd">Payment Response</h1>	

		<?php  
	 	$secretkey = "e8db0749178be2d6c394a1a528eed2e6b9d6f0bf";
	 	$orderId = $_POST["orderId"];
	 	$orderAmount = $_POST["orderAmount"];
	 	$referenceId = $_POST["referenceId"];
	 	$txStatus = $_POST["txStatus"];
	 	$paymentMode = $_POST["paymentMode"];
	 	$txMsg = $_POST["txMsg"];
	 	$txTime = $_POST["txTime"];
	 	$signature = $_POST["signature"];
	 	$data = $orderId.$orderAmount.$referenceId.$txStatus.$paymentMode.$txMsg.$txTime;
	 	$hash_hmac = hash_hmac('sha256', $data, $secretkey, true) ;
	 	$computedSignature = base64_encode($hash_hmac);
	 	if ($signature == $computedSignature) { ?>
			<div class="container"> 
				<div class="panel panel-success">
			  		<div class="panel-heading">Signature Verification Successful</div>
			  		<div class="panel-body">
				  	<!-- <div class="container"> -->
				 		<table class="table table-hover">
						    <tbody>
						      <tr>
						        <td>Order ID</td>
						        <td><?php echo $orderId; ?></td>
						      </tr>
						      <tr>
						        <td>Order Amount</td>
						        <td><?php echo $orderAmount; ?></td>
						      </tr>
						      <tr>
						        <td>Reference ID</td>
						        <td><?php echo $referenceId; ?></td>
						      </tr>
						      <tr>
						        <td>Transaction Status</td>
						        <td><?php echo $txStatus; ?></td>
						      </tr>
						      <tr>
						        <td>Payment Mode </td>
						        <td><?php echo $paymentMode; ?></td>
						      </tr>
						      <tr>
						        <td>Message</td>
						        <td><?php echo $txMsg; ?></td>
						      </tr>
						      <tr>
						        <td>Transaction Time</td>
						        <td><?php echo $txTime; ?></td>
						      </tr>
						    </tbody>
						</table>
					<!-- </div> -->
			   		</div>
				</div>
			</div>
	 	<?php } else { ?>
			<div class="container"> 
				<div class="panel panel-danger">
	  				<div class="panel-heading">Signature Verification failed</div>
				  	<div class="panel-body">
					  	<!-- <div class="container"> -->
					 		<table class="table table-hover">
							    <tbody>
							      <tr>
							        <td>Order ID</td>
							        <td><?php echo $orderId; ?></td>
							      </tr>
							      <tr>
							        <td>Order Amount</td>
							        <td><?php echo $orderAmount; ?></td>
							      </tr>
							      <tr>
							        <td>Reference ID</td>
							        <td><?php echo $referenceId; ?></td>
							      </tr>
							      <tr>
							        <td>Transaction Status</td>
							        <td><?php echo $txStatus; ?></td>
							      </tr>
							      <tr>
							        <td>Payment Mode </td>
							        <td><?php echo $paymentMode; ?></td>
							      </tr>
							      <tr>
							        <td>Message</td>
							        <td><?php echo $txMsg; ?></td>
							      </tr>
							      <tr>
							        <td>Transaction Time</td>
							        <td><?php echo $txTime; ?></td>
							      </tr>
							    </tbody>
							</table>
						<!-- </div> -->
				  	</div>	
				</div>	
			</div>	
		<?php } ?>
	</section>

	<script type="text/javascript">
	    $(document).ready(function() { 
	        setTimeout(function(){ window.location = "{{ route('home') }}"; },10000);
	    });
	</script>
@endsection