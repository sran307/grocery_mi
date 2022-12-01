 
                            <h3> My Orders   </h3>

                          <!-- @if(isset($orders) && count($orders) != 0)
                                <div class= "table table-responsive table-bordered">
                                    <table class="table text-center">
                                        <tr>
                                            <th> Order ID </th>
                                            <th> Order Date </th>
                                            <th> Order Status </th>
                                            <th> Quantity </th>
                                            <th> Total Amount </th>
                                            <th> Action </th>
                                        </tr>
                                        
                                        @foreach ($orders as $key => $value)
                                            <tr>
                                                <td> 
                                                    {{$value->order_code}}
                                                    @if($value->ref_order_id)
                                                        @if($value->Reference->order_code)
                                                            <p class="gj_fd_ref_odr">Reference Order : {{$value->Reference->order_code}}</p>
                                                        @endif
                                                    @endif
                                                </td>
                                                <td> {{$value->order_date ? date('d-m-Y', strtotime($value->order_date)) : '------'}} </td>
                                                <td> 
                                                    @if($value->order_status == 1)
                                                        {{'Order Placed'}}
                                                    @elseif($value->order_status == 2)
                                                        Order Dispatched
                                                    @elseif($value->order_status == 3)
                                                        Order Delivered
                                                    @elseif($value->order_status == 4)
                                                        Order Complete
                                                    @elseif($value->order_status == 5)
                                                        Order Cancelled
                                                    @else
                                                        {{'------'}}
                                                    @endif
                                                </td>
                                                <td> {{$value->total_items}} </td>
                                                <td> {{$code}} {{$value->net_amount}} </td>
                                                <td class="stat"> 
                                                    <a href="{{ route('my_track_orders', ['id' => $value->id]) }}" class="gj_my_todr"> Track Order </a>

                                                    <a href="{{ route('my_view_orders', ['id' => $value->id]) }}" class="gj_my_vodr"> View Order </a>

                                                    <a href="#" data-toggle="modal" data-target="#myModal{{$value->id}}"
                                                    @if($value->order_status != 1) 
                                                    style="pointer-events: none;   
                                                    background-color: #ffae42 !important;" title="Order Cancel Not Possible" 
                                                    @endif @if($value->cancel_approved == 2)
                                                    style="pointer-events: none;     background-color: #7c1111 !important;" title="Order Cancel Request Rejected"
                                                    @endif @if($value->cancel_approved == 3) 
                                                    style="pointer-events: none;     background-color: #FA8072 !important;" title="Order Cancel Request Processed"
                                                    @endif class="gj_my_codr_req" 
                                                    data-id="{{$value->id}}"> Cancel Order </a>

                                                    <div class="modal fade" id="myModal{{$value->id}}" role="dialog">
                                                        <div class="modal-dialog">
                                                            <div class="modal-content">
                                                                <div class="modal-header">
                                                                    <h4 class="modal-title">Term & Condition For Cancel Order</h4>
                                                                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                                </div>
                                                                <div class="modal-body">
                                                                    @if($general)
                                                                        @if($general->cancel_terms)
                                                                            <div class="gj_can_trm"><?php echo $general->cancel_terms; ?></div>
                                                                        @else
                                                                            <p>Please Click Accept Button</p>
                                                                        @endif
                                                                    @else
                                                                        <p>Please Click Accept Button</p>
                                                                    @endif
                                                                </div>

                                                                <div class="modal-footer">
                                                                    <a href="#" @if($value->order_status != 1)
                                                                    style="pointer-events: none;     background-color: #ffae42 !important;" title="Order Cancel Not Possible"
                                                                    @endif 
                                                                    @if($value->cancel_approved == 2) style="pointer-events: none;     background-color: #7c1111 !important;" title="Order Cancel Request Rejected" 
                                                                    @endif @if($value->cancel_approved == 3) 
                                                                    style="pointer-events: none;     background-color: #FA8072 !important;" title="Order Cancel Request Processed"
                                                                    @endif class="gj_my_codr" data-id="{{$value->id}}"> Accept </a>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <?php  
                                                        //$n_date = date('Y-m-d');
                                                        //$r_date = date('Y-m-d', strtotime($value->delivery_date. ' + 14 days'));
                                                    ?>
                                                    @if($value->order_status == 3 && $value->return_order_status == 0 && ($r_date >= $n_date))
                                                        <a href="#" data-toggle="modal" data-target="#rn_odr{{$value->id}}" class="gj_my_rodr_req" data-id="{{$value->id}}"> Return / Replace Order </a>

                                                        <div class="modal gj_trms fade" id="rn_odr{{$value->id}}" role="dialog">
                                                            <div class="modal-dialog">
                                                                <div class="modal-content">
                                                                    <div class="modal-header">
                                                                        <h4 class="modal-title">Term & Condition For Return/Replace Order</h4>
                                                                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                                    </div>
                                                                    <div class="modal-body">
                                                                        @if($general)
                                                                            @if($general->return_terms)
                                                                                <div class="gj_ret_trm"><?php echo $general->return_terms; ?></div>
                                                                            @else
                                                                                <p>Please Click Accept Button</p>
                                                                            @endif
                                                                        @else
                                                                            <p>Please Click Accept Button</p>
                                                                        @endif
                                                                    </div>
                                                                    <div class="modal-footer">
                                                                        <a href="{{ route('customer_return_order', ['id' => $value->id]) }}" @if($value->order_status != 3) style="pointer-events: none;     background-color: #ffae42 !important;" @endif  class="gj_my_rodr"> Accept </a>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                    </table>
                                    <div class="gj_myacc_pge">
                                        {{$orders->links()}}
                                    </div>
                                </div>
                            @else
                                <p class="gj_no_data">Orders is Empty</p>
                            @endif-->

                            <!-- active order section started -->
                            @foreach ($orders as $key => $value)
                            <div class="pdpt-bg">
                                <div class="pdpt-title">
                                    <h6>Delivery Timing {{date('Y-m-d', strtotime($value->delivery_date. ' + 1 days'))}}</h6>
                                </div>
                                <div class="order-body10">
                                    <ul class="order-dtsll">
                                        <li>
                                            <div class="order-dt-img">
                                                <img src="{{asset('images/groceries.svg')}}" alt="">
                                            </div>
                                        </li>
                                        <li>
                                            <div class="order-dt47">
                                                <h4>Grocery 360 - Ludhiana</h4>
                                                <p>Delivered - Grocery 360</p>
                                                <div class="order-title">{{$value->total_items}} Items <span data-inverted="" data-tooltip="<?php 
                                                    $details=App\OrderDetails::where("order_id", $value->id)->get();
                                                    foreach($details as $detail){
                                                        echo $detail->order_qty."-".$detail->product_title;
                                                        echo ", ";
                                                    }
                                                ?>" data-position="top center">?</span></div>
                                            </div>
                                        </li>
                                    </ul>
                                    <div class="total-dt">
                                        <div class="total-checkout-group">
                                            <div class="cart-total-dil">
                                                <h4>Sub Total</h4>
                                                <span>Inr {{$value->total_amount}}</span>
                                            </div>
                                            <div class="cart-total-dil pt-3">
                                                <h4>Tax Amount</h4>
                                                    <span>Inr {{$value->tax_amount}}</span>
                                            </div>
                                            <div class="cart-total-dil pt-3">
                                                <h4>Service Charges</h4>
                                                    <span>Inr {{$value->service_charge}}</span>
                                            </div>
                                            <div class="cart-total-dil pt-3">
                                                <h4>Delivery Charges</h4>
                                                <span>Inr {{$value->shipping_charge}}</span>
                                            </div>
                                        </div>
                                        <div class="main-total-cart">
                                            <h2>Total</h2>
                                            <span>Inr {{$value->net_amount}}</span>
                                        </div>
                                    </div>
                                    <div class="track-order">
                                        <h4>Track Order</h4>
                                        <div class="bs-wizard" style="border-bottom:0;">
                                                <div class="bs-wizard-step <?php if($value->order_status==1 ){echo 'active';}else if($value->order_status==2 || $value->order_status==3 || $value->order_status==4 || $value->order_status==5){ echo 'complete'; }?>">
                                                    <div class="text-center bs-wizard-stepnum">Placed</div>
                                                        <div class="progress"><div class="progress-bar"></div></div>
                                                        <a href="#" class="bs-wizard-dot"></a>
                                                </div>
                                                <div class="bs-wizard-step <?php if($value->order_status==2){echo 'active';}else if($value->order_status==3 || $value->order_status==4 || $value->order_status==5){ echo 'complete'; }else{ echo 'disabled';}?>">
                                                    <div class="text-center bs-wizard-stepnum">Dispatched</div>
                                                    <div class="progress"><div class="progress-bar"></div></div>
                                                    <a href="#" class="bs-wizard-dot"></a>
                                                </div>
                                                <div class="bs-wizard-step <?php if($value->order_status==3){echo 'active';}else if($value->order_status==4 || $value->order_status==5){ echo 'complete'; }else{ echo 'disabled';}?>">
                                                <div class="text-center bs-wizard-stepnum">Delivered</div>
                                                    <div class="progress"><div class="progress-bar"></div></div>
                                                        <a href="#" class="bs-wizard-dot"></a>
                                                </div>
                                                <div class="bs-wizard-step <?php if($value->order_status==4){echo 'active';}else if($value->order_status==5){ echo 'complete'; }else{ echo 'disabled';}?>">
                                                    <div class="text-center bs-wizard-stepnum">Completed</div>
                                                    <div class="progress"><div class="progress-bar"></div></div>
                                                        <a href="#" class="bs-wizard-dot"></a>
                                                </div>
                                                <div class="bs-wizard-step <?php if($value->order_status==5){echo 'active';}else{ echo 'disabled';}?>">
                                                    <div class="text-center bs-wizard-stepnum">Cancelled</div>
                                                    <div class="progress"><div class="progress-bar"></div></div>
                                                        <a href="#" class="bs-wizard-dot"></a>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="alert-offer">
                                            <img src="{{asset('images/ribbon.svg')}}" alt="">
                                            Cashback of Inr 2 will be credit to Grocery 360 Super Market wallet 6-12 hours of delivery.
                                        </div>
                                        <div class="call-bill">
                                            <div class="delivery-man">
                                            Delivery Boy - <a href="#"><i class="uil uil-phone"></i> Call Us</a>
                                        </div>
                                        <div class="order-bill-slip">
                                            <a href="#" class="bill-btn5 hover-btn">View Bill</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                           @endforeach
                            <!-- active order section ended -->
                            <!-- completed order section started -->
                            @if(count($past_orders)>0)
                            <h3> Completed Orders   </h3>
                            @endif
                            @foreach($past_orders as $key => $value)
                            <div class="pdpt-bg">
                                <div class="pdpt-title">
                                    <h6>Delivery Timing {{$value->delivery_date}}</h6>
                                </div>
                                <div class="order-body10">
                                    <ul class="order-dtsll">
                                        <li>
                                            <div class="order-dt-img">
                                                <img src="{{asset('images/groceries.svg')}}" alt="">
                                            </div>
                                        </li>
                                        <li>
                                            <div class="order-dt47">
                                                <h4>Grocery 360 - Ludhiana</h4>
                                                <p>Delivered - Grocery 360</p>
                                                <div class="order-title">{{$value->total_items}} Items <span data-inverted="" data-tooltip="<?php 
                                                    $details=App\OrderDetails::where("order_id", $value->id)->get();
                                                    foreach($details as $detail){
                                                        echo $detail->order_qty."-".$detail->product_title;
                                                        echo ", ";
                                                    }
                                                ?>" data-position="top center">?</span></div>
                                            </div>
                                        </li>
                                    </ul>
                                    <div class="total-dt">
                                        <div class="total-checkout-group">
                                            <div class="cart-total-dil">
                                                <h4>Sub Total</h4>
                                                <span>Inr {{$value->total_amount}}</span>
                                            </div>
                                            <div class="cart-total-dil pt-3">
                                                <h4>Tax Amount</h4>
                                                    <span>Inr {{$value->tax_amount}}</span>
                                            </div>
                                            <div class="cart-total-dil pt-3">
                                                <h4>Service Charges</h4>
                                                    <span>Inr {{$value->service_charge}}</span>
                                            </div>
                                            <div class="cart-total-dil pt-3">
                                                <h4>Delivery Charges</h4>
                                                <span>Inr {{$value->shipping_charge}}</span>
                                            </div>
                                        </div>
                                        <div class="main-total-cart">
                                            <h2>Total</h2>
                                            <span>Inr {{$value->net_amount}}</span>
                                        </div>
                                    </div>
                                    <div class="track-order">
                                        <h4>Track Order</h4>
                                        <div class="bs-wizard" style="border-bottom:0;">
                                            <div class="bs-wizard-step <?php if($value->order_status==1 ){echo 'active';}else if($value->order_status==2 || $value->order_status==3 || $value->order_status==4 || $value->order_status==5){ echo 'complete'; }?>">
                                                <div class="text-center bs-wizard-stepnum">Placed</div>
                                                    <div class="progress"><div class="progress-bar"></div></div>
                                                    <a href="#" class="bs-wizard-dot"></a>
                                            </div>
                                            <div class="bs-wizard-step <?php if($value->order_status==2){echo 'active';}else if($value->order_status==3 || $value->order_status==4 || $value->order_status==5){ echo 'complete'; }else{ echo 'disabled';}?>">
                                                <div class="text-center bs-wizard-stepnum">Dispatched</div>
                                                    <div class="progress"><div class="progress-bar"></div></div>
                                                    <a href="#" class="bs-wizard-dot"></a>
                                            </div>
                                            <div class="bs-wizard-step <?php if($value->order_status==3){echo 'active';}else if($value->order_status==4 || $value->order_status==5){ echo 'complete'; }else{ echo 'disabled';}?>">
                                                <div class="text-center bs-wizard-stepnum">Delivered</div>
                                                    <div class="progress"><div class="progress-bar"></div></div>
                                                        <a href="#" class="bs-wizard-dot"></a>
                                            </div>
                                            <div class="bs-wizard-step <?php if($value->order_status==4){echo 'active';}else if($value->order_status==5){ echo 'complete'; }else{ echo 'disabled';}?>">
                                                <div class="text-center bs-wizard-stepnum">Completed</div>
                                                    <div class="progress"><div class="progress-bar"></div></div>
                                                        <a href="#" class="bs-wizard-dot"></a>
                                            </div>
                                            <div class="bs-wizard-step <?php if($value->order_status==5){echo 'active';}else{ echo 'disabled';}?>">
                                                <div class="text-center bs-wizard-stepnum">Cancelled</div>
                                                    <div class="progress"><div class="progress-bar"></div></div>
                                                        <a href="#" class="bs-wizard-dot"></a>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="call-bill">
                                        <div class="delivery-man">
                                            <a href="#"><i class="uil uil-rss"></i>Feedback</a>
                                        </div>
                                        <div class="order-bill-slip">
                                            <a href="#" class="bill-btn5 hover-btn">View Bill</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endforeach
                            <!-- completed order section Ended -->