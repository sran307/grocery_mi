@extends('layouts.frontend')
@section('title', 'How To Find Us')
<link rel="stylesheet" type="text/css" href="{{ asset('login/animate.css')}}">
<link rel="stylesheet" type="text/css" href="{{ asset('login/main.css')}}">
@section('content')
@if(Session::has('message'))
    <p class="alert {{ Session::get('alert-class', 'alert-info') }}">{{ Session::get('message') }}</p>
@endif

<section class="section contenz">
    <div class="container">
        <div class="row">
            <div class="col-sm-6">
                <div class="addrz">
                    <h4> How to Find Us </h4>
                    <ul>
                        @if($emails)
                            <!-- <li> <i class="fa fa-map"></i> {{$emails->address1}},{{$emails->address2}},{{$emails->City->city_name}},{{$emails->State->state}},{{$emails->Country->country_name}},{{$emails->pincode}} </li> -->
                            <li> <i class="fa fa-map"></i> <span class="gj_cont_info"> {{$emails->address1}},{{$emails->address2}},{{$emails->City->city_name}} </span> </li>
                            <li> <i class="fa fa-phone"></i> {{$emails->contact_phone1}} </li>
                            <li> <i class="fa fa-fax"></i> {{$emails->contact_phone2}} </li>
                            <li> <i class="fa fa-envelope"></i> {{$emails->contact_email}} </li>
                        @else
                            <li> <i class="fa fa-map"></i> Ecambiar , New Level Building, Corner Midway, Bangalore </li>
                            <li> <i class="fa fa-phone"></i> 971 925 6546 </li>
                            <li> <i class="fa fa-fax"></i> 0471 58669464 </li>
                            <li> <i class="fa fa-envelope"></i> info@ecambiar.com </li>
                        @endif
                        @if($general)
                            <li> <i class="fa fa-globe"></i> {{$general->frontend_url}} </li>
                        @else
                            <li> <i class="fa fa-globe"></i> www.ecambiar.com </li>
                        @endif
                    </ul>
                </div>
            </div>
            <div class="col-sm-6">
                <div class="gj_h_map_div">
                    <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d6171.637229807007!2d77.63470370432195!3d12.979029068118612!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3bae16a2cac5f61d%3A0xa9ed4bf5e6deaa46!2sUlsoor+Eidgah!5e0!3m2!1sen!2sin!4v1541665145390" width="100%" height="278" frameborder="0" style="border:0" allowfullscreen></iframe>
                </div>
            </div>
        </div>
    </div>
</section>

<script>
    $(document).ready(function() { 
        $('p.alert').delay(2000).slideUp(300); 
    });
</script>

<script>
    $(document).ready(function(){
        $(".gj_cont_info").each(function(){
            var embed ="<iframe width='100%' height='278' frameborder='0' style='border:0' allowfullscreen src='https://maps.google.com/maps?&amp;q="+ encodeURIComponent( $(this).text() ) +"&amp;output=embed'></iframe>";
            $('.gj_h_map_div').html(embed);
        }); 
    });
</script>
@endsection
