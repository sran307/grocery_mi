@extends('groceryView.layouts.headerFooter')
@section('title', 'Contact Us')
<link rel="stylesheet" type="text/css" href="{{ asset('login/animate.css')}}">
<link rel="stylesheet" type="text/css" href="{{ asset('login/main.css')}}">
<script src="https://www.google.com/recaptcha/api.js" async defer></script>
@section('content')
<div class="wrapper">
    <div class="grocery-Breadcrumb">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{url('/')}}">Home</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Contact Us</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
    </div>
    <div class="all-product-grid">
        <div class="container">
            <div class="row">
                @if($emails)
                <div class="col-lg-6 col-md-6">
	                <div class="condetails">
                        <ul>
                        <li> <i class="fa fa-phone"> </i> <p> {{$emails->contact_phone1}}  </p> </li>
                        <li> <i class="fa fa-envelope"> </i> <p> {{$emails->contact_email}} </p> </li>
                        <li> <i class="fa fa-map"> </i> <p>{{$emails->address1}}  </p> </li>
                        <li> <i class="fa fa-globe"> </i> <p> {{$emails->site_no_reply_email}}  </p> </li>
                        </ul>
                    </div>
	
	
                <div class="imapz">
                    {!!$emails->google_map!!}
                </div>
	
	
            </div>
@endif
<div class="col-lg-6 col-md-6">
<div class="contact-title">
<h4> Feel free to Contact Now</h4> 
</div>
<div class="contact-formz">
{{ Form::open(array('url' => 'contact','class'=>'contact-form','id'=>'contact_form','accept-charset'=>'UTF-8','files' => true)) }}
                                <div class="form-group mt-1">
                                <label class="control-label">Full Name*</label>
                                 <span class="error">* 
                                                @if ($errors->has('contact_name'))
                                                    {{ $errors->first('contact_name') }}
                                                @endif
                                            </span>
                                <div class="ui search focus">
                                <div class="ui left icon input swdh11 swdh19">
                                   
                                <input class="prompt srch_explore" type="text" name="contact_name" id="contact_name" required="" placeholder="Your Full">
                                </div>
                                </div>
                                </div>
                                <div class="form-group mt-1">
<label class="control-label">Email Address*</label>
<span class="error">* 
@if ($errors->has('contact_email'))
    {{ $errors->first('contact_email') }}
@endif
</span>
<div class="ui search focus">
<div class="ui left icon input swdh11 swdh19">
<input class="prompt srch_explore" type="email" name="contact_email" id="ContactFormEmail" required="" placeholder="Your Email Address">
</div>
</div>
</div>
<div class="form-group mt-1">
<label class="control-label">Your Phone Number*</label>
 <span class="error">* 
                                        @if ($errors->has('contact_no'))
                                            {{ $errors->first('contact_no') }}
                                        @endif
                                    </span>
<div class="ui search focus">
<div class="ui left icon input swdh11 swdh19">
<input class="prompt srch_explore" type="tel"  name="contact_no" id="ContactFormPhone" required="" placeholder="Your Phone Number..." pattern="[0-9\-]*">
</div>
</div>
</div>
<div class="form-group mt-1">
<div class="field">
<label class="control-label">Message*</label>
<span class="error">* 
                                        @if ($errors->has('message'))
                                            {{ $errors->first('message') }}
                                        @endif
                                    </span>
<textarea rows="3" class="form-control" id="ContactFormMessage" name="message" required="" placeholder="Write Message"></textarea>
</div>
</div>
<div class="field">
<span class="error">*
            @if ($errors->has('g-recaptcha-response'))
                {{ $errors->first('g-recaptcha-response') }}
            @endif
        </span>
        <div class="g-recaptcha" data-sitekey="6LdOeSgeAAAAAAK2f6HQjMXMKFlc9s7CsH2NsO_0"></div>
</div>
<button class="next-btn16 hover-btn mt-3" type="submit" data-btntext-sending="Sending...">Submit Request</button>
                                {{ Form::close() }}
</div>
</div>
</div>
</div>
</div>
</div>

<script>
    $(document).ready(function() { 
        $('p.alert').delay(5000).slideUp(500); 
    });
</script>

<script>
    $(document).ready(function(){
        $(".gj_cont_info").each(function(){
            //var embed ="<iframe width='100%' height='315' frameborder='0' scrolling='no'  marginheight='0' marginwidth='0' src='https://maps.google.com/maps?&amp;q="+ encodeURIComponent( $(this).text() ) +"&amp;output=embed'></iframe>";
            var embed ="<iframe width='100%' height='315' frameborder='0' scrolling='no'  marginheight='0' marginwidth='0' src='https://maps.google.com/maps?&amp;q= hariyana &amp;output=embed'></iframe>";
            $('.gj_map_div').html(embed);
        }); 
    });
</script>
@endsection
