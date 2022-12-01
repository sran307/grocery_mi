<!DOCTYPE html>
<html>
  <head>
    <title>Ecambiar</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" type="text/css" media="all" href="{{ asset('css/bootstrap.min.css')}}">
    <script src="{{ asset('frontend/js/jquery.min.js')}}"></script>
    <script src="{{ asset('frontend/js/bootstrap.min.js')}}"></script>
  </head>

  <body onload="document.gj_payment_start.submit()">
      {{ Form::open(array('url' => 'payment_request','class'=>'gj_payment_start','name'=>'gj_payment_start','files' => true)) }}
        @if($pay_set) 
            @if($pay_set->cash_free_api)
                <input type="hidden" name="appId" value="{{$pay_set->cash_free_api}}"/>
            @else
                <input type="hidden" name="appId" value=""/>
            @endif 

            @if($pay_set->cash_free_secret)
                <input type="hidden" name="secretKey" value="{{$pay_set->cash_free_secret}}"/>
            @else
                <input type="hidden" name="secretKey" value=""/>
            @endif 

            @if($pay_set->payment_mode == 1)
                <input type="hidden" name="paymode" value="PROD"/>
            @else
                <input type="hidden" name="paymode" value="TEST"/>
            @endif 
        @else 
            <input type="hidden" name="appId" value=""/>
            <input type="hidden" name="secretKey" value=""/>
            <input type="hidden" name="paymode" value="TEST"/>
        @endif
        <input type="hidden" name="orderId" value="{{$order->order_code}}"/>
        <input type="hidden" name="orderAmount" value="{{$order->net_amount}}"/>
        <input type="hidden" name="orderCurrency" value="INR"/>
        <input type="hidden" name="orderNote" value=""/>
        <input type="hidden" name="customerName" value="{{$order->contact_person}}"/>
        <input type="hidden" name="customerEmail" value="{{$order->contact_email}}"/>
        <input type="hidden" name="customerPhone" value="{{$order->contact_no}}"/>
        <input type="hidden" name="returnUrl" value="{{ route('payment_response') }}"/>
        <input type="hidden" name="notifyUrl" value=""/>
      {{ Form::close() }}
  </body>
</html>