<div role="tabpanel" class="tab-pane fade" id="Section5">
                            <h3> Past Orders    </h3>

                            @if(isset($past_orders) && count($past_orders) != 0)
                                <div class= "table-responsive">
                                    <table class="table text-center">
                                        <tr>
                                            <th> Order ID </th>
                                            <th> Order Date </th>
                                            <th> Order Status </th>
                                            <th> Quantity </th>
                                            <th> Total Amount </th>
                                            <th> Action </th>
                                        </tr>
                                        
                                        @foreach ($past_orders as $key => $value)
                                            <tr>
                                                <td> {{$value->order_code}} </td>
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
                                                <td> <i class="fa fa-inr"></i> {{$value->net_amount}} </td>
                                                <td class="stat"> 
                                               
                                                    @php
                                                    $alp=App\Review::where('order_id',$value->id)->where('user_id',$user->id)->count();
                                                    @endphp
                                                    @if($alp==0)
                                                    <a href="{{ route('my_review_orders', ['id' => $value->id]) }}" class="gj_my_rodr"> Review Order</a>
                                                    @endif
                                                     <a href="{{ route('report_admin', ['id' => $value->id]) }}" class="gj_my_rodr">Report Admin</a>

                                                    <a href="{{ route('my_view_orders', ['id' => $value->id]) }}" class="gj_my_vodr"> View Order </a>                   
                                                </td>
                                            </tr>
                                        @endforeach
                                    </table>
                                    <div class="gj_myacc_pge">
                                        {{$past_orders->links()}}
                                    </div>
                                </div>
                            @else
                                <p class="gj_no_data">Orders is Empty</p>
                            @endif
                        </div>