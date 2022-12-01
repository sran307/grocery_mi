@extends('layouts.frontend')
@section('title', 'Disclaimer')
<link rel="stylesheet" type="text/css" href="{{ asset('login/animate.css')}}">
<link rel="stylesheet" type="text/css" href="{{ asset('login/main.css')}}">
@section('content')
@if(Session::has('message'))
    <p class="alert {{ Session::get('alert-class', 'alert-info') }}">{{ Session::get('message') }}</p>
@endif
<div class="scp-breadcrumb pull-right">
    <ul class="breadcrumb">
        <li><a href="{{url('/')}}">Home</a></li>
        <li class="active"><a href="#">Disclaimer</a></li>
      
    </ul>
</div>
<!-- <section class="section contenz">
    <div class="container">
        <div class="row">
            <div class="col-sm-12">
                <div class="contz">
                    <h3> Disclaimer </h3>
                    <p> We have tried our best to make this as simple, easy to understand, digest as possible so please make sure you have read and understood this section that you may have concerns about. </p>
                    <p>Please reach out to us on 02038429314 for any clarifications on any points. </p>
                    <p>We makes no warranty or representation that the Website will meet your requirements, that it will be of satisfactory quality, that it will be fit for a particular purpose, that it will not infringe the rights of third parties, that it will be compatible with all systems, that it will be secure and that all information provided will be accurate. We make no guarantee of any specific results from the use of our Services. </p>
                    <p> No part of this Website is intended to constitute advice and the content of this Website should not be relied upon when making any decisions or taking any action of any kind. </p>
                    <p>No part of this Website is intended to constitute a contractual offer capable of acceptance. </p>
                    <p>  Whilst we uses reasonable endeavors to ensure that the Website is secure and free of errors, viruses and other malware, all users are advised to take responsibility for their own security, that of their personal details and their computers </p>
                </div>
            </div>
        </div>
    </div>
</section> -->
 <section class="section contenz">
            <div class="container">
               <div class="row">
                  <div class="col-sm-12">
                     <div class="contz">
                         @if($disc && $disc->page_data)
        <?php echo $disc->page_data; ?>
    @else
        <p class="gj_no_data">Data Not Found</p>
    @endif
                                  </div>
                  </div>
               </div>
            </div>
         </section>


<script>
    $(document).ready(function() { 
        $('p.alert').delay(5000).slideUp(500); 
    });
</script>
@endsection
