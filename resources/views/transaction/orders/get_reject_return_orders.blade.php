@extends('layouts.master')
@section('title', 'Reject Return Orders')
@section('content')
<section class="gj_rro_set">
    <div class="row gj_row">
        <div class="col-md-3 col-sm-3 col-xs-12">
            @include('layouts.transaction_sidebar')
        </div>

        <div class="col-md-9 col-sm-9 col-xs-12">
            <!-- <div class="row">
                <div class="col-lg-12">
                    <ul class="breadcrumb">
                        <li class=""><a> Home  </a></li>
                        <li class="active"><a> Reject Return Orders  </a></li>
                    </ul>
                </div>
            </div> -->

            <div class="gj_box dark">
                @if(Session::has('message'))
                    <p class="alert {{ Session::get('alert-class', 'alert-info') }}">{{ Session::get('message') }}</p>
                @endif
                <header>
                    <div class="gj_icons"><i class="fa fa-edit"></i></div>
                    <h5 class="gj_heading"> Reject Return Orders  </h5>
                </header>

                <div class="col-md-12">
                    @if($orders)
                        {{ Form::open(array('url' => 'reject_return_orders','class'=>'gj_rro_form','files' => true)) }}
                            <div class="form-group">
                                {{ Form::label('order_id', 'Order Code') }}
                                <span class="error">* 
                                    @if ($errors->has('order_id'))
                                        {{ $errors->first('order_id') }}
                                    @endif
                                </span>

                                {{ Form::text('h_order_code', ($orders->order_code ? $orders->order_code : Input::old('order_code')), array('class' => 'form-control gj_order_code','placeholder' => 'Order Code','disabled')) }}

                                {{ Form::hidden('order_id', ($orders->order_id ? $orders->order_id : Input::old('order_id')), array('class' => 'form-control gj_order_id','placeholder' => 'Order ID')) }}

                                {{ Form::hidden('order_code', ($orders->order_code ? $orders->order_code : Input::old('order_code')), array('class' => 'form-control gj_order_code','placeholder' => 'Order Code')) }}

                                {{ Form::hidden('return_order_id', ($orders->id ? $orders->id : Input::old('return_order_id')), array('class' => 'form-control gj_return_order_id','placeholder' => 'Return Order ID')) }}
                            </div>

                            <div class="gj_box dark gj_inside_box">
                                <header>
                                    <h5 class="gj_heading"> Reject Return Order Details  </h5>
                                </header>
                                
                                <div class="col-md-12">
                                    <div class="gj_p_grv_div">
                                        <div class="gj_tot_err">
                                            @if ($errors->any())
                                                <ul class="error">
                                                    @foreach ($errors->all() as $error)
                                                        <li>{{$error}}</li>
                                                    @endforeach
                                                </ul>
                                            @endif
                                        </div>

                                        <div class="gj_p_grv_resp table-responsive">
                                            <table class="table table-stripped table-bordered gj_tab_grv">
                                                <thead>
                                                    <tr>
                                                        <th>Name</th>
                                                        <!-- <th>Qty</th> -->
                                                        <!-- <th>Price</th> -->
                                                        <!-- <th>Tax</th> -->
                                                        <th>Status</th>
                                                        <th>Admin Remarks</th>
                                                        <th>Return Type</th>
                                                        <th>Return Qty</th>
                                                        <th>Return Amount</th>
                                                        <!-- <th>Return Tax</th> -->
                                                        <th>Reason</th>
                                                        <th>Remarks</th>
                                                        <th>Image</th>
                                                        <th>#</th>
                                                    </tr>
                                                </thead>
                                                <tbody id="gj_grv_bdy">
                                                    @if(($orders->details) && (sizeof($orders->details) != 0))
                                                        @foreach ($orders->details as $keys => $values)
                                                            @if(($values->order_returned == 'No') && ($values->status != 'Reject'))
                                                                <tr class="gj_tr_grv" id="gj_tr_grv_{{$keys+1}}">
                                                                    <td>
                                                                        <span>
                                                                            {{$values->product_title}}
                                                                            @if(isset($values->att_name) && $values->att_name != 0)
                                                                                @if(isset($values->AttName->att_name) && isset($values->AttValue->att_value)) 
                                                                                    <span>({{$values->AttName->att_name}} : {{$values->AttValue->att_value}})</span>
                                                                                @endif
                                                                            @endif
                                                                        </span>

                                                                        <input type="hidden" class="form-control gj_product_title" placeholder="Enter Price" name="product_title[]" id="producttitle_{{$keys+1}}" value="{{$values->product_title}}">

                                                                        <input type="hidden" name="product_id[]" id="productid_{{$keys+1}}" value="{{$values->product_id}}">

                                                                        <input type="hidden" name="att_name[]" id="attname_{{$keys+1}}" value="{{$values->att_name}}">

                                                                        <input type="hidden" name="att_value[]" id="attvalue_{{$keys+1}}" value="{{$values->att_value}}">

                                                                        <input type="hidden" name="rtn_odr_det_id[]" id="rtnodrdetid_{{$keys+1}}" value="{{$values->id}}">

                                                                        <input type="hidden" name="odr_det_id[]" id="odrdetid_{{$keys+1}}" value="{{$values->rtn_odr_det_id}}">
                                                                    </td>

                                                                    <!-- <td>
                                                                        @if($values->order_qty)
                                                                            <span class="order_qty">{{$values->order_qty}}</span>
                                                                        @else
                                                                            {{'------'}}
                                                                        @endif
                                                                    </td> -->

                                                                    <!-- <td>
                                                                        @if($values->totalprice)
                                                                            Rs. <span class="totalprice">{{$values->totalprice}}</span>

                                                                            <input type="hidden" name="unitprice[]" value="{{$values->unitprice}}" class="gj_unitprice">
                                                                        @else
                                                                            {{'------'}}
                                                                        @endif
                                                                    </td> -->

                                                                    <!-- <td>
                                                                        @if($values->tax_amount)
                                                                            Rs. <span class="tax_amount">{{$values->tax_amount}}</span>
                                                                            <input type="hidden" name="tax[]" value="{{$values->tax}}" class="gj_tax">
                                                                        @else
                                                                            {{'------'}}
                                                                        @endif
                                                                    </td> -->

                                                                    <td>
                                                                        <select name="status[]" class="return_type form-control" id="return_type_{{$keys+1}}">
                                                                            <option value="Process">Select Status</option>
                                                                            <option @if($values->status == 'Process') selected @endif value="Process">Process</option>
                                                                            <option @if($values->status == 'Accept') selected @endif value="Accept">Accepted</option>
                                                                            <option @if($values->status == 'Reject') selected @endif value="Reject">Rejected</option>
                                                                        </select>
                                                                    </td>

                                                                    <td>
                                                                        <textarea class="form-control gj_admin_remarks" placeholder="Enter admin_remarks" name="admin_remarks[]" id="admin_remarks_{{$keys+1}}" rows="1" cols="5">{{$values->admin_remarks}}</textarea>
                                                                    </td>

                                                                    <td>
                                                                        <select name="return_type[]" class="return_type form-control" id="return_type_{{$keys+1}}">
                                                                            <option value="">Select Return Type</option>
                                                                            <option @if($values->return_type == 'Exchange') selected @endif value="Exchange">Exchange</option>
                                                                            <option @if($values->return_type == 'Replacement') selected @endif value="Replacement">Replacement</option>
                                                                            <option @if($values->return_type == 'Refund') selected @endif value="Refund">Refund</option>
                                                                        </select>
                                                                    </td>

                                                                    <td>
                                                                        <span>{{$values->return_qty}}</span>

                                                                        <!-- <input type="number" class="form-control gj_return_qty" placeholder="Enter Quantity" name="return_qty[]" id="returnqty_{{$keys+1}}" value="{{$values->return_qty}}"> -->
                                                                        <input type="hidden" name="old_return_qty[]" value="{{$values->return_qty}}" class="gj_old_return_qty">
                                                                        <input type="hidden" name="assign_qty[]" value="{{$values->assign_qty}}" class="gj_assign_qty">
                                                                    </td>

                                                                    <td>
                                                                        <span>{{$values->return_amount}}</span>
                                                                        <!-- <input type="text" class="form-control gj_return_amount" placeholder="Enter Amount" name="return_amount[]" id="returnamount_{{$keys+1}}" value="{{$values->return_amount}}"> -->
                                                                    </td>

                                                                    <!-- <td>
                                                                        <input type="text" class="form-control gj_return_tax_amount" placeholder="Enter Amount" name="return_tax_amount[]" id="returntaxamount_{{$keys+1}}" value="{{$values->return_tax_amount}}">
                                                                    </td> -->

                                                                    <td>
                                                                        <span>{{$values->reason}}</span>
                                                                        <!-- <textarea class="form-control gj_reason" placeholder="Enter reason" name="reason[]" id="reason_{{$keys+1}}" rows="1">{{$values->reason}}</textarea> -->
                                                                    </td>

                                                                    <td>
                                                                        <span>{{$values->remarks}}</span>
                                                                        <!-- <span>{{$values->reason}}</span> -->
                                                                        <!-- <textarea class="form-control gj_remarks" placeholder="Enter remarks" name="remarks[]" id="remarks_{{$keys+1}}" rows="1">{{$values->remarks}}</textarea> -->
                                                                    </td>

                                                                    <td>
                                                                        <?php  
                                                                            $grv_file_path = 'images/return_order_image';
                                                                        ?>
                                                                        @if($values->rtn_image)
                                                                            <div class="gj_grvimg_div">
                                                                                <a href="{{ asset($grv_file_path.'/'.$values->rtn_image)}}" target="_blank"><img src="{{ asset($grv_file_path.'/'.$values->rtn_image)}}" class="img-responsive gj_old_grv_img"></a>

                                                                                {{ Form::hidden('old_rtn_image[]', $values->rtn_image, array('class' => 'form-control')) }}
                                                                            </div>
                                                                        @endif
                                                                        <!-- <input type="file" name="rtn_image[]" id="rtn_image_{{$keys+1}}" accept="image/*" class="gj_rtn_image gj_edit_rtn_image form-control"> -->
                                                                    </td>

                                                                    <td>
                                                                        <button type="button" class="btn btn-danger gj_del_det" data-id="{{$values->id}}"><i class="fa fa-trash"></i></button>
                                                                    </td>
                                                                </tr>
                                                            @endif
                                                        @endforeach
                                                    @else
                                                        <tr>
                                                            <td colspan="7">
                                                                <p class="gj_nodata">Sorry! No More Details.</p>
                                                            </td>
                                                        </tr>
                                                    @endif 
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{ Form::submit('Submit', array('class' => 'btn btn-primary')) }}

                        {{ Form::close() }}
                    @else
                        <p class="gj_nodata">Reject Return Orders Not Possible!</p>
                        <a href="{{ url()->previous() }}" class="btn btn-info gj_back pull-right">Back</a>
                    @endif
                </div>
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

    $(document).ready(function() { 
        $('p.alert').delay(5000).slideUp(500); 
    });

    /*$(".gj_return_qty").change(function() {
        var r_qty = $(this).closest('tr').find('.gj_old_return_qty').val();
        var a_qty = $(this).closest('tr').find('.gj_assign_qty').val();
        var totalprice = 0.00;
        var tax = 0.00;
        var qty = 0;
        var price = 0.00;

        if($(this).val()) {
            qty = parseInt($(this).val());
        }

        if($(this).closest('tr').find('.gj_unitprice').val()) {
            totalprice = $(this).closest('tr').find('.gj_unitprice').val();
        }

        if($(this).closest('tr').find('.gj_tax').val()) {
            tax = $(this).closest('tr').find('.gj_tax').val();
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
            $(this).val('');
            $(this).closest('tr').find('.gj_return_amount').val(0.00);
            // $(this).closest('tr').find('.gj_return_tax_amount').val(0.00);
        } else if(Number.isInteger(qty)) {
            if(r_qty == a_qty) {
                $.confirm({
                    title: '',
                    content: 'GRV Order Not Available For This Product!',
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
                $(this).closest('tr').remove();
            } else if(r_qty >= qty) {
                price = qty * totalprice;
                price = gj_round(price ,2);
                $(this).closest('tr').find('.gj_return_amount').val(price);
                // cal_tax = gj_round(((totalprice * tax)/100) * qty, 2);
                // $(this).closest('tr').find('.gj_return_tax_amount').val(cal_tax);
            } else {
                $.confirm({
                    title: '',
                    content: 'Return Quantity is Less Than or Equal to Customer Return Quantity!',
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
                $(this).val('');
                $(this).closest('tr').find('.gj_return_amount').val(0.00);
                // $(this).closest('tr').find('.gj_return_tax_amount').val(0.00);
            }
        } else {
            $.confirm({
                title: '',
                content: 'Enter Return Quantity is Correct Value!',
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
            $(this).val('');
            $(this).closest('tr').find('.gj_return_amount').val(0.00);
        }
    });*/
</script>

<script type="text/javascript">
    var cnt = 1;
    $('body').on('click','.gj_del_det',function() {
        cnt = $('.gj_tab_grv tr.gj_tr_grv').length;
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
    });

    /*remove script start*/
    /*$('body').on('click','.gj_del_det',function() {
        var id = 0;
        if($(this).attr('data-id')) {
            id = $(this).attr('data-id');
        }

        $.confirm({
            title: '',
            content: 'Are You Sure to Delete?',
            icon: 'fa fa-trash-o',
            theme: 'modern',
            closeIcon: true,
            animation: 'scale',
            type: 'blue',
            buttons: {
                Ok: function(){
                    $.ajax({
                        type: 'post',
                        url: '{{url('/delete_ret_detz')}}',
                        data: {id: id, type: 'delete'},
                        success: function(data){
                            if(data == 0){
                                window.location.reload();
                            } else {
                                $.confirm({
                                    title: '',
                                    content: 'No Action Performed!',
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
                            }
                        }
                    });
                },
                Cancel:function() {
                }
            }
        });
    });*/
    /*remove script end*/
</script>
@endsection