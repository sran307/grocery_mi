<?php $loged = session()->get('user'); ?>
@extends('layouts.master')
@section('title', 'Merchants Dashboard')
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
                    <h5 class="gj_heading"> Merchants Dashboard </h5>
                </header>

                <div class="col-lg-12">
                    <div class="gj_dbd_items text-center">                   
                        <a class="gj_quick_btn1 active" href="{{ route('manage_product') }}">
                            <i class="fa fa-check-square-o fa-2x"></i>
                            <span> Active Products  </span>
                            <span class="label label-danger">{{$active_products['cnt']}}</span>
                        </a>

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

                        @if($loged)
                            @if($loged->id)
                                <a class="gj_quick_btn1" href="{{ route('manage_store', ['id' => $loged->id]) }}">
                                    <i class="fa fa-check-square-o fa-2x"></i>
                                    <span> Stores  </span>
                                    <span class="label label-danger">{{$store['cnt']}}</span>
                                </a> 
                            @else
                                <a class="gj_quick_btn1" href="#">
                                    <i class="fa fa-check-square-o fa-2x"></i>
                                    <span> Stores  </span>
                                    <span class="label label-danger">0</span>
                                </a>    
                            @endif
                        @else
                            <a class="gj_quick_btn1" href="#">
                                <i class="fa fa-check-square-o fa-2x"></i>
                                <span> Stores  </span>
                                <span class="label label-danger">0</span>
                            </a>  
                        @endif

                        <a class="gj_quick_btn1" href="{{ route('manage_credits') }}">
                            <i class="fa fa-money fa-2x"></i>
                            <span> Credits   </span>
                            <span class="label btn_metis_2">Rs.{{$credits}}</span>
                        </a>
                    </div>
                        
                    <div style="height:30px"></div>
                </div>

                <div class="row">
                    <div class="col-lg-12">
                        <a style="color:#fff" href="{{ route('home') }}" target="_blank"><button class="btn btn-success btn-sm btn-grad" style="margin-bottom:10px;"> Go to Live  </button></a>
                    </div>
                </div>

                <div class="gj_box dark gj_next_box">
                    <div class="row gj_row">
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

                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <div class="gj_box dark gj_inside_box">
                                <header>
                                    <h5 class="gj_heading"> Total Transaction  </h5>
                                </header>

                                <div class="gj_tot_trs_pie">
                                    <div id="gj_tot_tras_pie" class="gj_tot_tras_pie"></div>
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
</div>

<script src="{{ asset('highcharts/highcharts.js')}}"></script>
<script src="{{ asset('highcharts/exporting.js')}}"></script>
<script src="{{ asset('highcharts/export-data.js')}}"></script>
<script>
    $(document).ready(function() { 
        $('p.alert').delay(5000).slideUp(500); 
    });

    /*Total Products Pie chart Script Start*/
    <?php if(sizeof($products) != 0) { ?>
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
                    name: 'All Products',
                    y: <?php echo $products['cnt']; ?>,
                    sliced: true,
                    selected: true,
                    color:'#4bb2c5'
                }, {
                    name: 'Active Products',
                    y: <?php echo $products['act_cnt']; ?>,
                    color:'green'
                }, {
                    name: 'Inactive Products',
                    y: <?php echo $products['inact_cnt']; ?>,
                    color:'red'
                }, {
                    name: 'Your Products',
                    y: <?php echo $products['pdt_cnt']; ?>,
                    color:'#eaa228'
                }]
            }]
        });
    <?php } else {
        echo '$(".gj_tot_pdt_pie").html("<p class=gj_nodata>No Data Here</p>");';
    } ?>
    /*Total Products Pie chart Script End*/

    /*Total Customers Pie chart Script Start*/
    <?php if((sizeof($cod_trans) != 0) && (sizeof($online_trans) != 0)) { ?>
        Highcharts.chart('gj_tot_tras_pie', {
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
                name: 'Transaction',
                colorByPoint: true,
                data: [{
                    name: 'COD Transaction',
                    y: <?php echo $cod_trans['cnt']; ?>,
                    sliced: true,
                    selected: true,
                    color:'#4bb2c5'
                }, {
                    name: 'Online Transaction',
                    y: <?php echo $online_trans['cnt']; ?>,
                    color:'#C6F9D2'
                }]
            }]
        });
    <?php } else {
        echo '$(".gj_tot_trs_pie").html("<p class=gj_nodata>No Data Here</p>");';
    } ?>
    /*Total Customers Pie chart Script End*/

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