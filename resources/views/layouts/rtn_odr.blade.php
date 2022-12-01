<div role="tabpanel" class="tab-pane fade" id="rtn_odr">
                            <h3> Return Orders   </h3>

                            @if(isset($re_orders) && count($re_orders) != 0)
                                <div class= "table-responsive">
                                    <table class="table text-center">
                                        <tr>
                                            <th> Order ID </th>
                                            <th> Order Date </th>
                                            <th> Return Date </th>
                                            <th> Status </th>
                                            <th> Quantity </th>
                                            <th> Total Amount </th>
                                            <th> Action </th>
                                        </tr>
                                        
                                        @foreach ($re_orders as $key => $value)
                                            <tr>
                                                <td> {{$value->order_code}} </td>
                                                <td> {{$value->order_date ? date('d-m-Y', strtotime($value->order_date)) : '------'}} </td>
                                                <td> {{$value->return_date ? date('d-m-Y', strtotime($value->return_date)) : '------'}} </td>
                                                <td> 
                                                    @if($value->Orders->return_order_status == 1)
                                                        {{'Order Return Initialized'}}
                                                    @elseif($value->Orders->return_order_status == 2)
                                                        Order Return Confirmed
                                                    @elseif($value->Orders->return_order_status == 3)
                                                        Order Return Cancelled
                                                    @else
                                                        {{'------'}}
                                                    @endif
                                                </td>
                                                <td> {{$value->total_items}} </td>
                                                <td> <i class="fa fa-inr"></i> {{$value->net_amount}} </td>
                                                <td class="stat"> 
                                                    <a href="{{ route('my_view_return_order', ['id' => $value->id]) }}" class="gj_my_vodr"> View Order </a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </table>
                                    <div class="gj_myacc_pge">
                                        {{$re_orders->links()}}
                                    </div>
                                </div>
                            @else
                                <p class="gj_no_data">Orders is Empty</p>
                            @endif
                        </div>