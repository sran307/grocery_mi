@extends('layouts.master')
@section('title', 'Dashboard')
@section('sidebar')
    @parent
    <p>This refers to the master sidebar.</p>
@endsection
@section('content')
<div class="container">
    <div class="row">
        <div class="col-lg-12">
            @if(Session::has('message'))
                <p class="alert {{ Session::get('alert-class', 'alert-info') }}">{{ Session::get('message') }}</p>
            @endif
            <div class="gj_box">
                <header>
                    <div class="gj_icons"><i class="fa fa-dashboard"></i></div>
                    <h5 class="gj_heading"> Dashboard </h5>
                </header>

                <div class="col-lg-12">
                    <div class="gj_dbd_items text-center">                   
                        <a class="gj_quick_btn1 active" href="{{ route('manage_product') }}">
                            <i class="fa fa-check-square-o fa-2x"></i>
                            <span> Active Products  </span>
                            <span class="label label-danger">{{$active_products['cnt']}}</span>
                        </a>
                      <!--  <a class="gj_quick_btn1" href="{{ route('manage_offer') }}">
                            <i class="fa fa-minus-square-o fa-2x"></i>
                            <span> Offers  </span>
                            <span class="label label-success">{{$offers['cnt']}}</span>
                        </a> -->
                        <a class="gj_quick_btn1" href="{{ route('all_orders') }}">
                            <i class="fa fa-cloud-upload fa-2x"></i>
                            <span> Place Orders  </span>
                            <span class="label label-warning">{{$place_odr['cnt']}}</span>
                        </a>
                        <a class="gj_quick_btn1" href="{{ route('all_orders') }}">
                            <i class="fa fa-external-link fa-2x"></i>
                            <span> Complete Order   </span>
                            <span class="label btn_metis_2">{{$complete_odr['cnt']}}</span>
                        </a> 
                                                                                                           
                        <!--<a class="gj_quick_btn1" href="{{ route('manage_user',['type'=>4]) }}">
                            <i class="fa fa-check-square-o fa-2x"></i>
                            <span> Retailers  </span>
                            <span class="label label-danger">{{$customers['cnt']}}</span>
                        </a>-->
                        
                       <!-- <a class="gj_quick_btn1" href="{{ route('manage_user',['type'=>5]) }}">
                            <i class="fa fa-check-square-o fa-2x"></i>
                            <span> Dealers  </span>
                            <span class="label label-danger">{{$merchant['cnt']}} </span>
                        </a>-->
                        
                       <!-- <a class="gj_quick_btn1" href="{{ route('manage_merchant') }}">
                            <i class="fa fa-check-square-o fa-2x"></i>
                            <span> Stores  </span>
                            <span class="label label-danger">{{$store['cnt']}}</span>
                        </a>-->

                        <a class="gj_quick_btn1" href="{{ route('manage_enquiries') }}">
                            <i class="fa fa-check-square-o fa-2x"></i>
                            <span>Users Enquiry  </span>
                            <span class="label label-danger">{{$enquiries['cnt']}} </span>
                        </a> 
                    </div>
                        
                    <div style="height:30px"></div>
                </div>
            </div>

            <!--<div class="row">
                <div class="col-lg-12">
                    <a style="color:#fff" href="{{ route('home') }}" target="_blank"><button class="btn btn-success btn-sm btn-grad" style="margin-bottom:10px;"> Go to Live  </button></a>
                </div>
            </div>-->

            <div class="gj_box dark gj_next_box">
                <header>
                    <div class="gj_icons"><i class="fa fa-edit"></i></div>
                    <h5 class="gj_heading"> Users  </h5>
                </header>

                <div class="row gj_row">
                    <div class="col-md-12">
                        <div class="gj_hd_lastyear_div">
                            <p class="gj_hd_lastyear"> Last One Year Users Details </p>
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

            <div class="gj_box dark gj_next_box">
                <div class="row gj_row">
                    <div class="col-md-6 col-sm-6 col-xs-12">
                        <div class="gj_box dark gj_inside_box">
                            <header>
                                <h5 class="gj_heading"> Total Customers  </h5>
                            </header>

                            <div class="gj_tot_cus_pie">
                                <div id="gj_tot_customers_pie" class="gj_tot_customers_pie"></div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 col-sm-6 col-xs-12">
                        <div class="gj_box dark gj_inside_box">
                            <header>
                                <h5 class="gj_heading"> Total Products  </h5>
                            </header>

                            <div class="gj_tot_pdt_pie">
                                <div id="gj_tot_products_pie" class="gj_tot_products_pie"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="gj_box dark gj_next_box">
                <header>
                    <div class="gj_icons"><i class="fa fa-edit"></i></div>
                    <h5 class="gj_heading"> Transactions  </h5>
                </header>

                <div class="row gj_row">
                    <div class="col-md-12">
                        <div class="gj_hd_lastyear_div">
                            <p class="gj_hd_lastyear"> Last one year Transactions report </p>
                        </div>
                    </div>
                </div>

                <div class="row gj_row">
                    <div class="col-md-12">
                        <div class="gj_trans_div">
                            <div id="gj_tot_trans_bar" class="gj_tot_trans_bar"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div> 
</div>

<script src="{{ asset('highcharts/highcharts.js')}}"></script>
<script src="{{ asset('highcharts/exporting.js')}}"></script>
<script src="{{ asset('highcharts/export-data.js')}}"></script>
<script>
    $(document).ready(function() { 
        $('p.alert').delay(5000).slideUp(500); 
    });

    /*last one year users chart script start*/
    <?php if((sizeof($merchant) != 0) && (sizeof($customers) != 0)) { ?>
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
                name: 'Dealers',
                data: [
                    <?php
                        $year = date('Y');
                        for ($i=1; $i <= 12; $i++) {
                            echo $merchant['cnt_last_'.date('F',mktime(0,0,0,$i,1,$year)).'_merchants'].',';
                        }
                    ?>
                ]

            }, {
                name: 'Retailers',
                data: [
                    <?php
                        $year = date('Y');
                        for ($i=1; $i <= 12; $i++) {
                            echo $customers['cnt_last_'.date('F',mktime(0,0,0,$i,1,$year)).'_customers'].',';
                        }
                    ?>
                ]

            }]
        });
    <?php } else {
        echo '$(".gj_stat_div").html("<p class=gj_nodata>No Data Here</p>");';
    }
    ?>
    /*last one year users chart script end*/

    /*Total Customers Pie chart Script Start*/
    <?php if((sizeof($customers) != 0)) { ?>
        Highcharts.chart('gj_tot_customers_pie', {
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
                name: 'Customers',
                colorByPoint: true,
                data: [{
                    name: 'Website Customers',
                    y: <?php echo $customers['web_cnt']; ?>,
                    sliced: true,
                    selected: true,
                    color:'#4bb2c5'
                }, {
                    name: 'Facebook Customers',
                    y: <?php echo $customers['fb_cnt']; ?>,
                    color:'#eaa228'
                }, {
                    name: 'Google Customers',
                    y: <?php echo $customers['gg_cnt']; ?>,
                    color:'#C6F9D2'
                }]
            }]
        });
    <?php } else {
        echo '$(".gj_tot_cus_pie").html("<p class=gj_nodata>No Data Here</p>");';
    } ?>
    /*Total Customers Pie chart Script End*/

    /*Total Products Pie chart Script Start*/
    <?php if((sizeof($products) != 0)) { ?>
        Highcharts.chart('gj_tot_products_pie', {
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
                name: 'Products',
                colorByPoint: true,
                data: [{
                    name: 'Active Products',
                    y: <?php echo $products['act_cnt']; ?>,
                    sliced: true,
                    selected: true,
                    color:'green'
                }, {
                    name: 'Inactive Products',
                    y: <?php echo $products['inact_cnt']; ?>,
                    color:'red'
                }, {
                    name: 'Admin Products',
                    y: <?php echo $products['adm_cnt']; ?>,
                    color:'#4bb2c5'
                }]
            }]
        });
    <?php } else {
        echo '$(".gj_tot_pdt_pie").html("<p class=gj_nodata>No Data Here</p>");';
    } ?>
    /*Total Products Pie chart Script End*/

    /*last one year Tranaction chart script start*/
    <?php if((sizeof($cod_trans) != 0) && (sizeof($online_trans) != 0)) { ?>
        Highcharts.chart('gj_tot_trans_bar', {
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
                name: 'COD Transaction',
                data: [
                    <?php
                        $year = date('Y');
                        for ($i=1; $i <= 12; $i++) {
                            echo $cod_trans['cnt_last_'.date('F',mktime(0,0,0,$i,1,$year)).'_cod_trans'].',';
                        }
                    ?>
                ]

            }, {
                name: 'Online Transaction',
                data: [
                    <?php
                        $year = date('Y');
                        for ($i=1; $i <= 12; $i++) {
                            echo $online_trans['cnt_last_'.date('F',mktime(0,0,0,$i,1,$year)).'_online_trans'].',';
                        }
                    ?>
                ]

            }]
        });
    <?php } else {
        echo '$(".gj_trans_div").html("<p class=gj_nodata>No Data Here</p>");';
    }
    ?>
    /*last one year Transaction chart script end*/
</script>
@endsection