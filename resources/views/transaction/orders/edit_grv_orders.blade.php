@extends('layouts.master')
@section('title', 'Edit GRV Orders')
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
                        <li class="active"><a> Edit GRV Orders  </a></li>
                    </ul>
                </div>
            </div> -->

            <div class="gj_box dark">
                <header>
                    <div class="gj_icons"><i class="fa fa-edit"></i></div>
                    <h5 class="gj_heading"> Edit GRV Orders  </h5>
                </header>

                <div class="col-md-12">
                    @if($orders)
                        {{ Form::open(array('url' => 'edit_grv_orders','class'=>'gj_grv_odr_form','files' => true)) }}
                            <div class="form-group">
                                {{ Form::hidden('grv_id', $orders->id, array('class' => 'form-control gj_grv_id')) }}

                                {{ Form::label('grv_code', 'GRV Code') }}
                                <span class="error">* 
                                    @if ($errors->has('grv_code'))
                                        {{ $errors->first('grv_code') }}
                                    @endif
                                </span>

                                {{ Form::text('h_grv_code', ($orders->grv_code ? $orders->grv_code : Input::old('grv_code')), array('class' => 'form-control gj_grv_code','placeholder' => 'GRV Code','disabled')) }}

                                {{ Form::hidden('grv_code', ($orders->grv_code ? $orders->grv_code : Input::old('grv_code')), array('class' => 'form-control gj_grv_code','placeholder' => 'GRV Code')) }}
                            </div>

                            <div class="form-group">
                                {{ Form::label('order_id', 'Order Code') }}
                                <span class="error">* 
                                    @if ($errors->has('order_id'))
                                        {{ $errors->first('order_id') }}
                                    @endif
                                </span>

                                {{ Form::text('h_order_code', ($orders->Orders->order_code ? $orders->Orders->order_code : Input::old('order_code')), array('class' => 'form-control gj_order_code','placeholder' => 'Order Code','disabled')) }}

                                {{ Form::hidden('order_code', ($orders->Orders->order_code ? $orders->Orders->order_code : Input::old('order_code')), array('class' => 'form-control gj_order_code','placeholder' => 'Order Code')) }}

                                {{ Form::hidden('order_id', ($orders->order_id ? $orders->order_id : Input::old('order_id')), array('class' => 'form-control gj_order_id','placeholder' => 'Order ID')) }}

                                {{ Form::hidden('return_order_id', ($orders->return_order_id ? $orders->return_order_id : Input::old('return_order_id')), array('class' => 'form-control gj_return_order_id','placeholder' => 'Return Order ID')) }}
                            </div>

                            <div class="form-group">
                                {{ Form::label('grv_remarks', 'GRV Remarks') }}
                                <span class="error">* 
                                    @if ($errors->has('grv_remarks'))
                                        {{ $errors->first('grv_remarks') }}
                                    @endif
                                </span>

                                {{ Form::textarea('grv_remarks', ($orders->grv_remarks ? $orders->grv_remarks : Input::old('grv_remarks')), array('class' => 'form-control gj_grv_remarks','placeholder' => 'Enter Remarks','rows' => '5')) }}
                            </div>

                            <div class="form-group">
                                {{ Form::label('grv_status', 'GRV Status') }}
                                <span class="error">* 
                                    @if ($errors->has('grv_status'))
                                        {{ $errors->first('grv_status') }}
                                    @endif
                                </span>

                                <select name="grv_status" id="grv_status" class="form-control grv_status">
                                    <option value="">Select GRV Status</option>
                                    <option @if($orders->grv_status == 1) selected @endif value="1">GRV Opened</option>
                                    <option @if($orders->grv_status == 2) selected @endif value="2">GRV Closed</option>
                                </select>
                            </div>

                            <div class="gj_box dark gj_inside_box">
                                <header>
                                    <h5 class="gj_heading"> GRV Orders Details  </h5>
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
                                                        <th>Qty</th>
                                                        <th>Price</th>
                                                        <th>Return Type</th>
                                                        <th>Return Qty</th>
                                                        <th>Return Amount</th>
                                                        <th>Reason</th>
                                                        <th>Remarks</th>
                                                        <th>Image</th>
                                                    </tr>
                                                </thead>
                                                <tbody id="gj_grv_bdy">
                                                    @if(($orders->details) && (sizeof($orders->details) != 0))
                                                        @foreach ($orders->details as $keys => $values)
                                                            <tr class="gj_tr_grv" id="gj_tr_grv_{{$keys+1}}">
                                                                <td>
                                                                    <input type="hidden" name="rtn_odr_det_id[]" id="rtnodrdetid_{{$keys+1}}" value="{{$values->rtn_odr_det_id}}">

                                                                    <span>
                                                                        {{$values->product_title}}
                                                                        @if(isset($values->att_name) && $values->att_name != 0)
                                                                            @if(isset($values->AttName->att_name) && isset($values->AttValue->att_value)) 
                                                                                <span>({{$values->AttName->att_name}} : {{$values->AttValue->att_value}})</span>
                                                                            @endif
                                                                        @endif
                                                                    </span>

                                                                    <input type="hidden" name="product_id[]" id="productid_{{$keys+1}}" value="{{$values->product_id}}">

                                                                    <input type="hidden" class="form-control gj_product_title" placeholder="Enter Price" name="product_title[]" id="producttitle_{{$keys+1}}" value="{{$values->product_title}}">

                                                                    <input type="hidden" name="att_name[]" id="attname_{{$keys+1}}" value="{{$values->att_name}}">

                                                                    <input type="hidden" name="att_value[]" id="attvalue_{{$keys+1}}" value="{{$values->att_value}}">
                                                                    
                                                                    <input type="hidden" name="tax[]" id="tax_{{$keys+1}}" value="{{$values->tax}}">
                                                                    
                                                                    <input type="hidden" name="tax_type[]" id="taxtype_{{$keys+1}}" value="{{$values->tax_type}}">
                                                                </td>

                                                                <td>
                                                                    @if($values->order_qty)
                                                                        <span class="order_qty">{{$values->order_qty}}</span>
                                                                    @else
                                                                        {{'------'}}
                                                                    @endif

                                                                    <input type="hidden" name="order_qty[]" id="orderqty_{{$keys+1}}" value="{{$values->order_qty}}">
                                                                </td>

                                                                <td>
                                                                    <input type="hidden" name="unitprice[]" id="unitprice_{{$keys+1}}" value="{{$values->unitprice}}">
                                                                    
                                                                    <input type="hidden" name="totalprice[]" id="totalprice_{{$keys+1}}" value="{{$values->totalprice}}">

                                                                    @if($values->totalprice)
                                                                        Rs. <span class="totalprice">{{$values->totalprice}}</span>
                                                                    @else
                                                                        {{'------'}}
                                                                    @endif
                                                                </td>

                                                                <td>
                                                                    <select name="return_type[]" class="return_type form-control" id="return_type_{{$keys+1}}">
                                                                        <option value="">Select Return Type</option>
                                                                        <option @if($values->return_type == 'Exchange') selected @endif value="Exchange">Exchange</option>
                                                                        <option @if($values->return_type == 'Replacement') selected @endif value="Replacement">Replacement</option>
                                                                        <option value="">Select Return Type</option>
                                                                        <option @if($values->return_type == 'Refund') selected @endif value="Refund">Refund</option>
                                                                    </select>
                                                                </td>

                                                                <td>
                                                                    <input type="number" class="form-control gj_return_qty" placeholder="Enter Quantity" name="return_qty[]" id="returnqty_{{$keys+1}}" value="{{$values->return_qty}}">

                                                                    <input type="hidden" class="form-control gj_old_return_qty" placeholder="Enter Quantity" name="old_return_qty[]" id="oldreturnqty_{{$keys+1}}" value="{{$values->return_qty}}">
                                                                </td>

                                                                <td>
                                                                    <input type="text" class="form-control gj_return_amount" placeholder="Enter Amount" name="return_amount[]" id="returnamount_{{$keys+1}}" value="{{$values->return_amount}}">
                                                                </td>

                                                                <td>
                                                                    <select name="grv_issued[]" id="grvissued_{{$keys+1}}" class="form-control grv_issued">
                                                                        <option value="">Select GRV Issued</option>
                                                                        <option @if($values->grv_issued == "Yes") selected @endif value="Yes">Yes</option>
                                                                        <option @if($values->grv_issued == "No") selected @endif value="No">No</option>
                                                                    </select>
                                                                </td>

                                                                <td>
                                                                    <textarea class="form-control gj_reason" placeholder="Enter reason" name="reason[]" id="reason_{{$keys+1}}" rows="1">{{$values->reason}}</textarea>
                                                                </td>

                                                                <td>
                                                                    <textarea class="form-control gj_remarks" placeholder="Enter remarks" name="remarks[]" id="remarks_{{$keys+1}}" rows="1">{{$values->remarks}}</textarea>
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
                                                                    <input type="file" name="rtn_image[]" id="rtn_image_{{$keys+1}}" accept="image/*" class="gj_rtn_image gj_edit_rtn_image form-control">
                                                                </td>

                                                                <td>
                                                                    <button type="button" class="btn btn-danger gj_del_det"><i class="fa fa-trash"></i></button>
                                                                </td>
                                                            </tr>
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

                            {{ Form::submit('Update', array('class' => 'btn btn-primary')) }}

                        {{ Form::close() }}
                    @else
                        <p class="gj_nodata">GRV Edit Not Possible!</p>
                        <a href="{{ url()->previous() }}" class="btn btn-info gj_back pull-right">Back</a>
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

    $(".gj_return_qty").change(function() {
        var order_qty = $(this).closest('tr').find('.order_qty').text();
        var totalprice = $(this).closest('tr').find('.totalprice').text();
        var qty = $(this).val();
        var price = 0.00;
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
        } else if(order_qty >= qty) {
            price = qty * totalprice;
            price = (price).toFixed(2);
            $(this).closest('tr').find('.gj_return_amount').val(price);
        } else {
            $.confirm({
                title: '',
                content: 'Return Quantity is Less Than or Equal to Order Quantity!',
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
    });
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
</script>
@endsection