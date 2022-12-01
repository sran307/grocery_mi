@extends('layouts.master')
@section('title', 'View Bank Details')
@section('content')
<section class="gj_vw_bnk_setting">
    <div class="row gj_row">
        <div class="col-md-3 col-sm-3 col-xs-12">
            @include('layouts.user_sidebar')
        </div>

        <div class="col-md-9 col-sm-9 col-xs-12">
            <!-- <div class="row">
                <div class="col-lg-12">
                    <ul class="breadcrumb">
                        <li class=""><a> Home  </a></li>
                        <li class="active"><a> View Bank Details  </a></li>
                    </ul>
                </div>
            </div> -->

            <div class="gj_box dark">
                @if(Session::has('message'))
                    <p class="alert {{ Session::get('alert-class', 'alert-info') }}">{{ Session::get('message') }}</p>
                @endif
                <header>
                    <div class="gj_icons"><i class="fa fa-eye"></i></div>
                    <h5 class="gj_heading"> View Bank Details  </h5>
                </header>

                <div class="col-md-12">
                    <div class="gj_box dark gj_inside_box">
                        <header>
                            <h5 class="gj_heading"> Bank Details  </h5>
                        </header>
                        
                        <div class="col-md-12">
                            @if($banks)
                                <div class="table-responsive gj_vw_p_res">
                                    <table class="table table-bordered">
                                        <tr>
                                            <th>Merchant</th>
                                            <td>
                                                @if($banks->merchant_id)
                                                    {{$banks->Merchants->first_name.' '.$banks->Merchants->last_name}}
                                                @else
                                                    {{'------'}}
                                                @endif
                                            </td>
                                        </tr>

                                        <tr>
                                            <th>A/C No</th>
                                            <td>
                                                @if($banks->ac_no)
                                                    {{$banks->ac_no}}
                                                @else
                                                    {{'------'}}
                                                @endif
                                            </td>
                                        </tr>

                                        <tr>
                                            <th>A/C Holder Name</th>
                                            <td>
                                                @if($banks->ac_name)
                                                    {{$banks->ac_name}}
                                                @else
                                                    {{'------'}}
                                                @endif
                                            </td>
                                        </tr>

                                        <tr>
                                            <th>A/C Type</th>
                                            <td>
                                                @if($banks->ac_type)
                                                    {{$banks->ac_type}}
                                                @else
                                                    {{'------'}}
                                                @endif
                                            </td>
                                        </tr>

                                        <tr>
                                            <th>Bank Name</th>
                                            <td>
                                                @if($banks->bank_name)
                                                    {{$banks->bank_name}}
                                                @else
                                                    {{'------'}}
                                                @endif
                                            </td>
                                        </tr>

                                        <tr>
                                            <th>Bank Branch</th>
                                            <td>
                                                @if($banks->bank_branch)
                                                    {{$banks->bank_branch}}
                                                @else
                                                    {{'------'}}
                                                @endif
                                            </td>
                                        </tr>

                                        <tr>
                                            <th>Bank Ifsc</th>
                                            <td>
                                                @if($banks->bank_ifsc)
                                                    {{$banks->bank_ifsc}}
                                                @else
                                                    {{'------'}}
                                                @endif
                                            </td>
                                        </tr>

                                        <tr>
                                            <th>Remarks</th>
                                            <td>
                                                @if($banks->remarks)
                                                    {{$banks->remarks}}
                                                @else
                                                    {{'------'}}
                                                @endif
                                            </td>
                                        </tr>
                                        
                                        <tr>
                                            <th>Create Date</th>
                                            <td>{{date('d-m-Y', strtotime($banks->created_at))}}</td>
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
