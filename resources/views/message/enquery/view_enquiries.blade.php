@extends('layouts.master')
@section('title', 'View Enqueries')
@section('content')
<section class="gj_enquiries_setting">
    <div class="row gj_row">
        <div class="col-md-3 col-sm-3 col-xs-12">
            @include('layouts.message_sidebar')
        </div>

        <div class="col-md-9 col-sm-9 col-xs-12">
            <!-- <div class="row">
                <div class="col-lg-12">
                    <ul class="breadcrumb">
                        <li class=""><a> Home  </a></li>
                        <li class="active"><a> View Enqueries  </a></li>
                    </ul>
                </div>
            </div> -->

            <div class="gj_box dark">
                @if(Session::has('message'))
                    <p class="alert {{ Session::get('alert-class', 'alert-info') }}">{{ Session::get('message') }}</p>
                @endif
                <header>
                    <div class="gj_icons"><i class="fa fa-edit"></i></div>
                    <h5 class="gj_heading"> View Enqueries  </h5>
                </header>

                <div class="col-md-12">
                    <div class="table-responsive gj_manage_enquiries">
                        <table class="table table-bordered table-striped" id="gj_vw_enquiries_table">
                            @if($enquiries)
                                <tr>
                                    <th>Name</th>
                                    <td>{{$enquiries->contact_name}}</td>
                                <tr>
                                <tr>
                                    <th>E-Mail</th>
                                    <td>{{$enquiries->contact_email}}</td>
                                <tr>
                                <tr>
                                    <th>Phone No</th>
                                    <td>{{$enquiries->contact_no}}</td>
                                <tr>
                                <tr>
                                    <th>Message</th>
                                    <td>{{$enquiries->message}}</td>
                                <tr>
                            @endif
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<script>
    $(document).ready(function () {
        $('p.alert').delay(1000).slideUp(300);
    });
</script>
@endsection
