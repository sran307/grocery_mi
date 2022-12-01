@extends('layouts.master')
@section('title', 'Merchant Dashboard')
@section('content')
<section class="gj_mer_dashboard">
    <div class="row gj_row">
        <div class="col-md-3 col-sm-3 col-xs-12">
            @include('layouts.merchant_sidebar')
        </div>

        <div class="col-md-9 col-sm-9 col-xs-12">
            <!-- <div class="row">
                <div class="col-lg-12">
                    <ul class="breadcrumb">
                        <li class=""><a> Home  </a></li>
                        <li class="active"><a> Merchant Dashboard  </a></li>
                    </ul>
                </div>
            </div> -->

            <div class="gj_box dark">
                @if(Session::has('message'))
                    <p class="alert {{ Session::get('alert-class', 'alert-info') }}">{{ Session::get('message') }}</p>
                @endif
                <header>
                    <div class="gj_icons"><i class="fa fa-edit"></i></div>
                    <h5 class="gj_heading"> Merchant Dashboard  </h5>
                </header>

                <div class="row gj_row">
                    <div class="col-lg-12 gj_dsb_menus">
                        <button class="btn btn-success btn-sm btn-grad" style="margin-bottom:10px;"><a style="color:#fff" href="{{ route('home') }}" target="_blank"> Go to Live  </a></button>
                        <!-- <button class="btn btn-success btn-sm btn-grad"><a style="color:#fff" target="_blank" href="http://demo.laravelecommerce.com/product_all_orders"> See All Product Paypal Transaction  </a></button>
                        <button class="btn btn-success btn-sm btn-grad"><a style="color:#fff" target="_blank" href="http://demo.laravelecommerce.com/product_all_orders"> See All Product Paypal Transaction  </a></button>
                        <button class="btn btn-success btn-sm btn-grad"><a style="color:#fff" target="_blank" href="http://demo.laravelecommerce.com/cod_all_orders"> See All Product COD Transaction </a></button>
                        <button class="btn btn-success btn-sm btn-grad"><a style="color:#fff" target="_blank" href="http://demo.laravelecommerce.com/deals_all_orders"> See All Deal Paypal Transaction  </a></button>
                        <button class="btn btn-success btn-sm btn-grad"><a style="color:#fff" target="_blank" href="http://demo.laravelecommerce.com/dealscod_all_orders"> See All Deal COD Transaction </a></button>
                        <button class="btn btn-success btn-sm btn-grad"><a style="color:#fff" target="_blank" href="http://demo.laravelecommerce.com/product_payu_all_orders"> See All Product PayUmoney Transaction </a></button>
                        <button class="btn btn-success btn-sm btn-grad"><a style="color:#fff" target="_blank" href="http://demo.laravelecommerce.com/deals_payu_all_orders"> See All Deal PayUmoney Transaction </a></button> -->
                    </div>
                </div>

                <div class="row gj_row">
                    <div class="col-md-6 col-sm-6 col-xs-12">
                        <div class="gj_box dark gj_inside_box">
                            <header>
                                <h5 class="gj_heading"> Total Merchants  </h5>
                            </header>

                            <div class="gj_tot_mer_pie">
                                <div id="gj_tot_merchant_pie" class="gj_tot_merchant_pie"></div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 col-sm-6 col-xs-12">
                        <div class="gj_box dark gj_inside_box">
                            <header>
                                <h5 class="gj_heading"> Merchants Details  </h5>
                            </header>

                            <div class="gj_mer_prograss">
                                @if($merchant)
                                    <div class="table-responsive gj_mer_prog_tbl">
                                        <table class="gj_pgs_table" width="100%">
                                            <tbody>
                                                <tr>
                                                    <td> Total Merchants  </td>
                                                    <td>{{$merchant['cnt_total_merchant']}}</td>
                                                </tr>
                                                <tr>
                                                    <td colspan="2">
                                                        <div class="progress progress-striped active">
                                                            <div class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="{{$merchant['cnt_total_merchant']}}" aria-valuemin="0" aria-valuemax="100" style="width:{{$merchant['cnt_total_merchant']}}%"> 
                                                                <span class="sr-only">{{$merchant['cnt_total_merchant']}}%  Complete </span>
                                                            </div>
                                                        </div>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td> Active Merchant </td>
                                                    <td>{{$merchant['cnt_active_merchant']}}</td>
                                                </tr>
                                                <tr>
                                                   <td colspan="2">
                                                        <div class="progress progress-striped active">
                                                            <div class="progress-bar" role="progressbar" aria-valuenow="{{$merchant['cnt_active_merchant']}}" aria-valuemin="0" aria-valuemax="100" style="width:{{$merchant['cnt_active_merchant']}}%" ;=""> 
                                                                <span class="sr-only">{{$merchant['cnt_active_merchant']}}%  Complete </span>
                                                            </div>
                                                        </div>
                                                    </td>
                                                </tr>
                                                
                                                <tr>
                                                    <td> InActive Merchant </td>
                                                    <td>{{$merchant['cnt_inactive_merchant']}}</td>
                                                </tr>
                                                <tr>
                                                    <td colspan="2">
                                                        <div class="progress progress-striped active">
                                                            <div class="progress-bar progress-bar-warning" role="progressbar" aria-valuenow="{{$merchant['cnt_inactive_merchant']}}" aria-valuemin="0" aria-valuemax="100" style="width:{{$merchant['cnt_inactive_merchant']}}%" ;="">
                                                                <span class="sr-only">{{$merchant['cnt_inactive_merchant']}}%  Complete </span>
                                                            </div>
                                                        </div>
                                                    </td>  
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                @else
                                    <p class="gj_nodata">No data Here</p>
                                @endif
                            </div>
                        </div>

                        <div class="gj_box dark gj_inside_box">
                            <header>
                                <h5 class="gj_heading"> Merchant Registration Details  </h5>
                            </header>

                            <div class="gj_mer_reg">
                                @if($merchant)
                                    <div class="table-responsive gj_mer_regs_res_tbl">
                                        <table class="table table-striped table-bordered table-hover gj_mer_regs_tbl">
                                            <thead>
                                                <tr>
                                                    <th> Merchant Registration Details </th>
                                                    <th> Count </th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td> Today </td>
                                                    <td>{{$merchant['cnt_today_merchant']}}</td>
                                                </tr>
                                                <tr>
                                                    <td> Last  7  Days </td>
                                                    <td>{{$merchant['cnt_last7_merchant']}}</td>
                                                </tr>
                                                <tr>
                                                    <td> Last  30  Days </td>
                                                    <td>{{$merchant['cnt_last30_merchant']}}</td>
                                                </tr>
                                                <tr>
                                                    <td> Last  12  months   </td>
                                                    <td>{{$merchant['cnt_last12_merchant']}}</td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                @else
                                    <p class="gj_nodata">No data Here</p>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="gj_box dark gj_next_box">
                <div class="row gj_row">
                    <div class="col-md-6 col-sm-6 col-xs-12">
                        <div class="gj_box dark gj_inside_box">
                            <header>
                                <h5 class="gj_heading"> Total Stores  </h5>
                            </header>

                            <div class="gj_tot_str_pie">
                                <div id="gj_tot_stores_pie" class="gj_tot_stores_pie"></div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 col-sm-6 col-xs-12">
                        <div class="gj_box dark gj_inside_box">
                            <header>
                                <h5 class="gj_heading"> Merchants Stores  </h5>
                            </header>

                            <div class="gj_mer_prograss">
                                @if($merchant)
                                    <div class="table-responsive gj_mer_prog_tbl">
                                        <table class="gj_pgs_table" width="100%">
                                            <tbody>
                                                <tr>
                                                    <td> Total Stores  </td>
                                                    <td>{{$merchant['tot_store']}}</td>
                                                </tr>
                                                <tr>
                                                    <td colspan="2">
                                                        <div class="progress progress-striped active">
                                                            <div class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="{{$merchant['tot_store']}}" aria-valuemin="0" aria-valuemax="100" style="width:{{$merchant['tot_store']}}%"> 
                                                                <span class="sr-only">{{$merchant['tot_store']}}%  Complete </span>
                                                            </div>
                                                        </div>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td> Admin Add Stores </td>
                                                    <td>{{$merchant['tot_admin_store']}}</td>
                                                </tr>
                                                <tr>
                                                   <td colspan="2">
                                                        <div class="progress progress-striped active">
                                                            <div class="progress-bar" role="progressbar" aria-valuenow="{{$merchant['tot_admin_store']}}" aria-valuemin="0" aria-valuemax="100" style="width:{{$merchant['tot_admin_store']}}%" ;=""> 
                                                                <span class="sr-only">{{$merchant['tot_admin_store']}}%  Complete </span>
                                                            </div>
                                                        </div>
                                                    </td>
                                                </tr>
                                                
                                                <tr>
                                                    <td> Merchant Add Stores </td>
                                                    <td>{{$merchant['tot_website_store']}}</td>
                                                </tr>
                                                <tr>
                                                    <td colspan="2">
                                                        <div class="progress progress-striped active">
                                                            <div class="progress-bar progress-bar-warning" role="progressbar" aria-valuenow="{{$merchant['tot_website_store']}}" aria-valuemin="0" aria-valuemax="100" style="width:{{$merchant['tot_website_store']}}%" ;="">
                                                                <span class="sr-only">{{$merchant['tot_website_store']}}%  Complete </span>
                                                            </div>
                                                        </div>
                                                    </td>  
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                @else
                                    <p class="gj_nodata">No data Here</p>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="gj_box dark gj_next_box">
                <header>
                    <div class="gj_icons"><i class="fa fa-edit"></i></div>
                    <h5 class="gj_heading"> Statistics  </h5>
                </header>

                <div class="row gj_row">
                    <div class="col-md-12">
                        <div class="gj_hd_lastyear_div">
                            <p class="gj_hd_lastyear"> Last One Year Merchant Details </p>
                        </div>
                    </div>
                </div>

                <div class="row gj_row">
                    <div class="col-md-12">
                        <div class="gj_stat_div">
                            <div id="gj_tot_stat_bar" class="gj_tot_stat_bar"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<script src="{{ asset('highcharts/highcharts.js')}}"></script>
<script src="{{ asset('highcharts/exporting.js')}}"></script>
<script src="{{ asset('highcharts/export-data.js')}}"></script>
<script>
    $(document).ready(function() { 
        $('p.alert').delay(2000).slideUp(300); 
    });

    <?php
    if($merchant) { ?>
        Highcharts.chart('gj_tot_stores_pie', {
            chart: {
                plotBackgroundColor: null,
                plotBorderWidth: null,
                plotShadow: false,
                type: 'pie'
            },
            title: {
                text: ''
            },
            // tooltip: {
            //     pointFormat: '{series.name}: <b>{point.percentage:.1f}</b>'
            // },
            plotOptions: {
                pie: {
                    allowPointSelect: true,
                    cursor: 'pointer',
                    dataLabels: {
                        enabled: false
                    },
                    showInLegend: true
                }
            },
            credits: {
                enabled: false
            },
            series: [{
                name: 'Stores',
                colorByPoint: true,
                data: [{
                    name: 'Admin Add Stores',
                    y: {{$merchant['tot_admin_store']}},
                    sliced: true,
                    selected: true,
                    color:'#4bb2c5'
                }, {
                    name: 'Merchant Add Stores',
                    y: {{$merchant['tot_website_store']}},
                    color:'#eaa228'
                }]
            }]
        });
    <?php } else {
        echo '$(".gj_tot_mer_pie").html("<p class=gj_nodata>No Data Here</p>");';
    }

    if($merchant) { ?>
        Highcharts.chart('gj_tot_merchant_pie', {
            chart: {
                plotBackgroundColor: null,
                plotBorderWidth: null,
                plotShadow: false,
                type: 'pie'
            },
            title: {
                text: ''
            },
            // tooltip: {
            //     pointFormat: '{series.name}: <b>{point.percentage:.1f}</b>'
            // },
            plotOptions: {
                pie: {
                    allowPointSelect: true,
                    cursor: 'pointer',
                    dataLabels: {
                        enabled: false
                    },
                    showInLegend: true
                }
            },
            credits: {
                enabled: false
            },
            series: [{
                name: 'Merchants',
                colorByPoint: true,
                data: [{
                    name: 'Admin Add Merchant',
                    y: {{$merchant['cnt_admin_merchant']}},
                    sliced: true,
                    selected: true,
                    color:'#eaa228'
                }, {
                    name: 'Website Merchant',
                    y: {{$merchant['cnt_website_merchant']}},
                    color:'#4bb2c5'
                }]
            }]
        });
    <?php } else {
        echo '$(".gj_tot_mer_pie").html("<p class=gj_nodata>No Data Here</p>");';
    }

    if($merchant) { ?>
        Highcharts.chart('gj_tot_stat_bar', {
            chart: {
                type: 'column'
            },
            title: {
                text: ''
            },
            credits: {
                enabled: false
            },
            subtitle: {
                text: ''
            },
            xAxis: {
                categories: [
                    'Jan',
                    'Feb',
                    'Mar',
                    'Apr',
                    'May',
                    'Jun',
                    'Jul',
                    'Aug',
                    'Sep',
                    'Oct',
                    'Nov',
                    'Dec'
                ],
                crosshair: true
            },
            yAxis: {
                min: 0,
                title: {
                    text: ''
                }
            },
            plotOptions: {
                column: {
                    pointPadding: 0.2,
                    borderWidth: 0
                }
            },
            series: [{
                name: 'Merchants',
                data: [
                        <?php
                            $year = date('Y');
                            for ($i=1; $i <= 12; $i++) {
                                echo $merchant['cnt_last_'.date('F',mktime(0,0,0,$i,1,$year)).'_merchants'].',';
                            }
                        ?>
                   ]

            }]
        });
    <?php } else {
        echo '$(".gj_stat_div").html("<p class=gj_nodata>No Data Here</p>");';
    }
    ?>
</script>
@endsection
