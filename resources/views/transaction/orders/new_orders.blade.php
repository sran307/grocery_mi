@extends('layouts.master')
@section('title', 'New Orders')
@section('content')
<section class="gj_orders_setting">
    <div class="row gj_row">
        <div class="col-md-3 col-sm-3 col-xs-12">
            @include('layouts.transaction_sidebar')
        </div>

        <div class="col-md-9 col-sm-9 col-xs-12">
            <!-- <div class="row">
                <div class="col-lg-12">
                    <ul class="breadcrumb">
                        <li class=""><a> Home  </a></li>
                        <li class="active"><a> New Orders  </a></li>
                    </ul>
                </div>
            </div> -->

            <div class="gj_box dark">
                @if(Session::has('message'))
                    <p class="alert {{ Session::get('alert-class', 'alert-info') }}">{{ Session::get('message') }}</p>
                @endif
                
                <header>
                    <div class="gj_icons"><i class="fa fa-edit"></i></div>
                    <h5 class="gj_heading"> New Orders  </h5>
                </header>

                {{ Form::open(array('url' => 'new_orders','class'=>'gj_new_orders_form','files' => true)) }}
                    <div class="row gj_row">
                        <div class="col-md-6">
                            <div class="form-group">
                                {{ Form::label('return_type', 'Return Type') }}
                                <span class="error">* 
                                    @if ($errors->has('return_type'))
                                        {{ $errors->first('return_type') }}
                                    @endif
                                </span>

                                <div class="gj_py_ro_div">
                                    <span class="gj_py_ro">
                                        <input type="radio" name="return_type" value="Exchange"> Exchange
                                    </span>

                                     <span class="gj_py_ro">
                                        <input type="radio" name="return_type" value="Replacement" checked> Replacement
                                    </span>

                                    <!-- <span class="gj_py_ro">
                                        <input type="radio" name="return_type" value="Refund"> Refund
                                    </span> -->
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                {{ Form::label('grv_id', 'Select GRV') }}
                                <span class="error"> 
                                    @if ($errors->has('grv_id'))
                                        {{ $errors->first('grv_id') }}
                                    @endif
                                </span>

                                <select class="form-control gj_grv_id" id="grv_id" name="grv_id">
                                    <option value="">Select GRV</option>
                                    @if(sizeof($grv) != 0)
                                        @foreach ($grv as $k => $v)
                                            <option value="{{$v->id}}">{{$v->grv_code}}</option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="gj_get_grv">
                        <!-- GRV Item Get Here -->
                    </div>
                {{ Form::close() }}
            </div>
        </div>
    </div>
</section>

<script>
    function gj_round(value, decPlaces) {
      var val = value * Math.pow(10, decPlaces);
      var fraction = (Math.round((val - parseInt(val)) * 10) / 10);

      // -342.055 => -342.06
      if (fraction == -0.5) fraction = -0.6;

      val = Math.round(parseInt(val) + fraction) / Math.pow(10, decPlaces);
      return val;
    }

    var rowCount=0;
    function cut_off(sum, shc, sc, tax_tot, cnt_shc) {
        var is_cod = $("input[name='payment_mode']:checked").val();
        if(is_cod && is_cod == 1){
            is_cod = 1;
        } else {
            is_cod = 2;
        }

        $.ajax({
            type: 'post',
            url: '{{url('/check_cut_off')}}',
            data: {sum: sum, tax_tot: tax_tot, cnt_shc: cnt_shc, shc: shc, sc: sc, is_cod: is_cod, type: 'check_cut_off'},    
            dataType:"json",   
            success: function(data){
                if(data['error'] == 1){
                    $('#cut_off').val(data['shc']);

                    $('.gj_det_sub_tot').html(data['sum']);
                    $('#det_sub_tot').val(data['sum']);

                    $('.gj_ch_tax_tot').html(data['tax_tot']);
                    $('#det_tax_total').val(data['tax_tot']);
                    $('.gj_tax_amount').val(data['tax_tot']);

                    $('.gj_det_sc_tot').html(data['sc']);
                    $('#det_serv_charge').val(data['sc']);                

                    $('#det_shipping_charge').val(data['shc']);
                    $('.gj_shipping_charge').val(data['shc']);
                    $('.gj_ch_shc_tot').html(data['shc']);

                    $('.gj_ch_cod').html(data['cod_amount']);
                    $('#cod_charge').val(data['cod_amount']);

                    $('.gj_det_grand_tot').html(data['tot']);
                    $('#det_net_amount').val(data['tot']);
                    $('.gj_net_amount').val(data['tot']);
                }
            }
        });
    }

    function sum() {
        var sum = 0;
        var tax_tot = 0;
        var gj = 0;
        var sc = 0;
        var shc = 0;
        var cnt_shc = 0; 
        var rowCount = $('.gj_tab_odr_det tr').length;
        
        $(".det_totalprice").each(function() {
            var value = $(this).val();
            if(!isNaN(value) && value.length != 0) {
                sum += parseFloat(value);
            }
        });

        /*$(".det_return_tax_amount").each(function() {
          var values = $(this).val();
          if(!isNaN(values) && values.length != 0) {
            tax_tot += parseFloat(values);
          }
        });

        $(".gj_det_sc").each(function() {
            var value = $(this).val();
            if(!isNaN(value) && value.length != 0) {
                sc += parseFloat(value);
            }
        });*/

        if($("#gj_odr_det tr").find(".gj_det_spc").length) {
            cnt_shc = $("#gj_odr_det tr").find(".gj_det_spc").length;
        }

        /*var shc = Math.max.apply(Math, $('.gj_det_spc').map(function(i,elem){ 
            return Number($(elem).val()); 
        }));*/

        $(".det_return_qty").each(function() {
            var value = $(this).val();
            if(!isNaN(value) && value.length != 0) {
                gj += parseFloat(value);
            }
        });

        // cut_off(sum, shc, sc, tax_tot, cnt_shc);
        sum = gj_round(sum, 2);

        $('#det_total_items').val(gj);
        $('.gj_total_items').val(gj);

        $('.gj_ch_tax_tot').html(tax_tot);
        $('#det_tax_total').val(tax_tot);
        $('.gj_tax_amount').val(tax_tot);

        $('.gj_det_sc_tot').html(0);
        $('#det_serv_charge').val(0);                

        $('#det_shipping_charge').val(sc);
        $('.gj_shipping_charge').val(sc);
        $('.gj_ch_shc_tot').html(sc);

        $('.gj_ch_cod').html(0);
        $('#cod_charge').val(0);

        $('.gj_det_sub_tot').html(sum);
        $('#det_sub_tot').val(sum);

        $('.gj_det_grand_tot').html(sum);
        $('#det_net_amount').val(sum);
        $('.gj_net_amount').val(sum);
    }

    $(document).ready(function() {
        $('p.alert').delay(5000).slideUp(500); 
        $("#grv_id").select2();
        $("#gj_srh_prd").select2();

        sum();
    });

    $('body').on('change','input[name="payment_mode"]',function() {
        sum();
    });

    $('body').on('change','#grv_id',function() {
        var r_type = 0;
        var url = '{{url('/get_grv')}}';

        if($("input[name='return_type']:checked").val()) {
            r_type = $("input[name='return_type']:checked").val();
        }

        if(r_type == 'Exchange') {
            url = '{{url('/get_ex_grv')}}';
        }

        if($(this).select2('val')) {
            var id = $(this).select2('val');
            $.ajax({
                type: 'post',
                url: url,
                data: {id: id, r_type: r_type, type: 'get_GRV'}, 
                // dataType:"json",  
                success: function(data){
                    if(data != 0) {
                        $('.gj_get_grv').html(data);
                        sum();
                    } else {
                        $.confirm({
                            title: '',
                            content: 'Data Not Found or GRV Closed!',
                            icon: 'fa fa-exclamation',
                            theme: 'modern',
                            closeIcon: true,
                            animation: 'scale',
                            type: 'blue',
                            buttons: {
                                Ok: function(){
                                    window.location.reload();
                                }
                            }
                        });
                    }
                }
            });
        } else {
            $.confirm({
                title: '',
                content: 'Please Select GRV!',
                icon: 'fa fa-exclamation',
                theme: 'modern',
                closeIcon: true,
                animation: 'scale',
                type: 'red',
                buttons: {
                    Ok: function(){
                    }
                }
            });
        }
    });

    $('body').on('change','.cge_att_name',function() {
        var th = $(this);
        var product_id = 0;
        var id = 0;

        if($(this).closest('tr').find('.det_product_id').val()) {
            product_id = $(this).closest('tr').find('.det_product_id').val();
        }

        if($(this).val()) {
            id = $(this).val();

            $.ajax({
                type: 'post',
                url: '{{url('/select_att_vals')}}',
                data: {id: id, product_id: product_id, type: 'select_att_vals'},   
                success: function(data){
                    if(data) {
                        th.closest('tr').find('.cge_att_value').html(data);
                    } else {
                        $.confirm({
                            title: '',
                            content: 'Please Choose Change Attributes TO "Yes" & Select Attributes Name!',
                            icon: 'fa fa-exclamation',
                            theme: 'modern',
                            closeIcon: true,
                            animation: 'scale',
                            type: 'blue',
                            buttons: {
                                Ok: function(){
                                }
                            }
                        });
                    }
                }
            });
        } else {
            $.confirm({
                title: '',
                content: 'Please Select Attribute Name and must select Change Attribute to "Yes"!',
                icon: 'fa fa-exclamation',
                theme: 'modern',
                closeIcon: true,
                animation: 'scale',
                type: 'red',
                buttons: {
                    Ok: function() {
                        window.location.reload();
                    }
                }
            });
        }


    });

    $('body').on('change','.det_return_qty',function() {
        var id = $(this).closest('tr').find('.det_product_id').val();
        var qty = 1;
        var price = 0;
        var att_name = 0;
        var att_value = 0;
        var total = 0.00;
        var hm = $(this);

        if($(this).val() == 0) {
            var qty = 1;
            $(this).val(qty);
        } else {
            var qty = $(this).val();
        }

        var r_qty = 0;
        var a_qty = 0;

        if($(this).closest('tr').find('.det_old_return_qty').val()) {
            r_qty = $(this).closest('tr').find('.det_old_return_qty').val();
        }

        if($(this).closest('tr').find('.assign_qty').val()) {
            a_qty = $(this).closest('tr').find('.assign_qty').val();
        }

        if(qty == 0) {
            $.confirm({
                title: '',
                content: 'Please Enter Correct Value!',
                icon: 'fa fa-exclamation',
                theme: 'modern',
                closeIcon: true,
                animation: 'scale',
                type: 'red',
                buttons: {
                    Ok: function() {
                        
                    }
                }
            });
        } else if(r_qty == a_qty) {
            var qty = 1;
            $(this).val(qty);
            $.confirm({
                title: '',
                content: 'GRV Issued, Please Try Another GRV!',
                icon: 'fa fa-exclamation',
                theme: 'modern',
                closeIcon: true,
                animation: 'scale',
                type: 'purple',
                buttons: {
                    Ok: function() {
                        
                    }
                }
            });
            setTimeout(function(){ window.location.href = "<?php echo route('new_orders'); ?>"; }, 3000);
        } else if(r_qty < qty) {
            var qty = 1;
            $(this).val(qty);
            $.confirm({
                title: '',
                content: 'Return Quantity is Less Than or Equal to GRV Return Quantity!',
                icon: 'fa fa-exclamation',
                theme: 'modern',
                closeIcon: true,
                animation: 'scale',
                type: 'purple',
                buttons: {
                    Ok: function() {
                        
                    }
                }
            });
            setTimeout(function(){ window.location.href = "<?php echo route('new_orders'); ?>"; }, 3000);
        }

        if($(this).closest('tr').find('.det_att_name').val()) {
          var att_name = parseFloat($(this).closest('tr').find('.det_att_name').val());
        } 

        if($(this).closest('tr').find('.det_att_value').val()) {
          var att_value = parseFloat($(this).closest('tr').find('.det_att_value').val());
        } 

        if($(this).closest('tr').find('.det_unitprice').val()) {
          var price = parseFloat($(this).closest('tr').find('.det_unitprice').val());
        } 

        if($(this).closest('tr').find('.det_tax').val()) {
          tax = $(this).closest('tr').find('.det_tax').val();
        }

        if($(this).closest('tr').find('.det_tax_type').val()) {
          tax_type = $(this).closest('tr').find('.det_tax_type').val();
        }

        // var calc_tax = ((price * tax)/100);
        // price = price + calc_tax;

        if(id) {
            $.ajax({
                type: 'post',
                url: '{{url('/check_onhand_qty')}}',
                data: {id: id, qty: qty, price: price, att_name: att_name, att_value: att_value, type: 'check_onhand_qty'}, 
                dataType:"json",   
                success: function(data){
                  if(data['error'] == 2){
                    $.confirm({
                        title: '',
                        content: 'Out of Stock. Only ' + data['onhand_qty'] + ' Products Avaliable!',
                        icon: 'fa fa-exclamation',
                        theme: 'modern',
                        closeIcon: true,
                        animation: 'scale',
                        type: 'purple',
                        buttons: {
                            Ok: function(){
                                window.location.reload();
                            }
                        }
                    });
                        $(hm).val(1);
                        data = price * $(hm).val();
                        data = gj_round(data, 2);
                        $(hm).closest('tr').find('.det_h_totalprice').val(data);
                        $(hm).closest('tr').find('.det_totalprice').val(data);
                        c_tax = ((price * tax)/100) * $(hm).val();
                        c_tax = gj_round(c_tax, 2);
                        $(hm).closest('tr').find('.det_h_return_tax_amount').val(c_tax);
                        $(hm).closest('tr').find('.det_return_tax_amount').val(c_tax);

                        sum();
                    } else if(data['error'] == 3){
                        $.confirm({
                            title: '',
                            content: 'Out of Stock. Products Not Avaliable!',
                            icon: 'fa fa-exclamation',
                            theme: 'modern',
                            closeIcon: true,
                            animation: 'scale',
                            type: 'purple',
                            buttons: {
                                Ok: function(){
                                    window.location.reload();
                                }
                            }
                        });

                        $(hm).val(1);
                        data = price * $(hm).val();
                        data = gj_round(data, 2);
                        $(hm).closest('tr').find('.det_h_totalprice').val(data);
                        $(hm).closest('tr').find('.det_totalprice').val(data);
                        c_tax = ((price * tax)/100) * $(hm).val();
                        c_tax = gj_round(c_tax, 2);
                        $(hm).closest('tr').find('.det_h_return_tax_amount').val(c_tax);
                        $(hm).closest('tr').find('.det_return_tax_amount').val(c_tax);
                        
                        sum();
                  } else if (data != 1) {
                        data = gj_round(data, 2);
                        $(hm).closest('tr').find('.det_h_totalprice').val(data);
                        $(hm).closest('tr').find('.det_totalprice').val(data);
                        c_tax = ((price * tax)/100) * $(hm).val();
                        c_tax = gj_round(c_tax, 2);
                        $(hm).closest('tr').find('.det_h_return_tax_amount').val(c_tax);
                        $(hm).closest('tr').find('.det_return_tax_amount').val(c_tax);
                        
                        sum();
                  } else {
                        $(hm).val('1');
                        data = price * $(hm).val();
                        data = gj_round(data, 2);
                        $(hm).closest('tr').find('.det_h_totalprice').val(data);
                        $(hm).closest('tr').find('.det_totalprice').val(data);
                        c_tax = ((price * tax)/100) * $(hm).val();
                        c_tax = gj_round(c_tax, 2);
                        $(hm).closest('tr').find('.det_h_return_tax_amount').val(c_tax);
                        $(hm).closest('tr').find('.det_return_tax_amount').val(c_tax);
                        
                        sum();
                    }
                }
            });        
        }
    });
</script>

<script type="text/javascript">
    var cnt = 1;
    $('body').on('click','.gj_del_det',function() {
        cnt = $('.gj_tab_odr_det tr.gj_tr_det').length;
        if(cnt==1){
            $.confirm({
                title: '',
                content: 'No more items to remove!',
                icon: 'fa fa-exclamation',
                theme: 'modern',
                closeIcon: true,
                animation: 'scale',
                type: 'blue',
                buttons: {
                    Ok: function(){
                    }
                }
            }); 
            return false;
        }   
    
        cnt--;
        $(this).closest('tr').remove();
        /*if($(this).attr('data-del-id')) {
            var id = $(this).attr('data-del-id');
            $.ajax({
                type: 'post',
                url: '{{url('/delete_odr_det')}}',
                data: {id: id, type: 'delete_odr_det'},   
                success: function(data){
                    if(data == 1) {
                        sum();
                        $.confirm({
                            title: '',
                            content: 'Deleted Successfully!',
                            icon: 'fa fa-exclamation',
                            theme: 'modern',
                            closeIcon: true,
                            animation: 'scale',
                            type: 'blue',
                            buttons: {
                                Ok: function(){
                                }
                            }
                        }); 
                    }
                }
            });
        }*/
    });
</script>
@endsection
