@extends('layouts.frontend')
@section('title', 'Privacy Policy')
<link rel="stylesheet" type="text/css" href="{{ asset('login/animate.css')}}">
<link rel="stylesheet" type="text/css" href="{{ asset('login/main.css')}}">
@section('content')
@if(Session::has('message'))
    <p class="alert {{ Session::get('alert-class', 'alert-info') }}">{{ Session::get('message') }}</p>
@endif
<div class="scp-breadcrumb pull-right">
    <ul class="breadcrumb">
        <li><a href="{{url('/')}}">Home</a></li>
        <li class="active"><a href="#">Privacy Policy</a></li>
      
    </ul>
</div>
<?php

$privacy=App\SiteSettings::where('slug','privacy')->first();
?>
<section class="section contenz">
    <div class="container">
        <div class="row">
            <div class="col-sm-12">
                <div class="contz">
                    {!!isset($privacy)?$privacy->value:''!!}
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
@endsection
