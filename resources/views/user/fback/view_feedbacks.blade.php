@extends('layouts.master')
@section('title', 'View Feed Back')
@section('content')
<section class="gj_vw_fbck_setting">
    <div class="row gj_row">
        <div class="col-md-3 col-sm-3 col-xs-12">
            @include('layouts.user_sidebar')
        </div>

        <div class="col-md-9 col-sm-9 col-xs-12">
            <!-- <div class="row">
                <div class="col-lg-12">
                    <ul class="breadcrumb">
                        <li class=""><a> Home  </a></li>
                        <li class="active"><a> View Feed Back  </a></li>
                    </ul>
                </div>
            </div> -->

            <div class="gj_box dark">
                @if(Session::has('message'))
                    <p class="alert {{ Session::get('alert-class', 'alert-info') }}">{{ Session::get('message') }}</p>
                @endif
                <header>
                    <div class="gj_icons"><i class="fa fa-edit"></i></div>
                    <h5 class="gj_heading"> View Feed Back  </h5>
                </header>

                <div class="col-md-12">
                    <div class="gj_box dark gj_inside_box">
                        <header>
                            <h5 class="gj_heading"> User Feed Back  </h5>
                        </header>
                        
                        <div class="col-md-12">
                            @if($feeds)
                                <div class="table-responsive gj_vw_p_res">
                                    <table class="table table-bordered">
                                        <tr>
                                            <th>Customer Name</th>
                                            <td>
                                                @if($feeds->user_id)
                                                    {{$feeds->Customer->first_name.' '.$feeds->Customer->last_name}}
                                                @else
                                                    {{'------'}}
                                                @endif
                                            </td>
                                        </tr>

                                        <tr>
                                            <th>Subject</th>
                                            <td>
                                                @if($feeds->subject)
                                                    {{$feeds->subject}}
                                                @else
                                                    {{'------'}}
                                                @endif
                                            </td>
                                        </tr>

                                        <tr>
                                            <th>Message</th>
                                            <td>
                                                @if($feeds->message)
                                                    <pre>{{$feeds->message}}</pre>
                                                @else
                                                    {{'------'}}
                                                @endif
                                            </td>
                                        </tr>
                                        
                                        <tr>
                                            <th>Create Date</th>
                                            <td>{{date('d-m-Y', strtotime($feeds->created_at))}}</td>
                                        </tr>
                                    </table>
                                </div>
                            @endif
                        </div>
                    </div>
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
