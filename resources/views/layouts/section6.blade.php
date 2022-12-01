<div role="tabpanel" class="tab-pane fade" id="Section6">
                            <h3> Cancel Orders   </h3>

                            @if(isset($cancel_orders) && count($cancel_orders) != 0)
                                <div class= "table-responsive">
                                    <table class="table text-center">
                                        <tr>
                                            <th> Order ID </th>
                                            <th> Order Date </th>
                                            <th> Cancel Date </th>
                                            <th> Remarks </th>
                                         
                                            <!--<th> Status </th>-->
                                            <th> Quantity </th>
                                            <th> Total Amount </th>
                                        </tr>
                                        
                                        @foreach ($cancel_orders as $key => $value)
                                            <tr>
                                                <td> {{$value->order_code}} </td>
                                                <td> {{$value->order_date ? date('d-m-Y', strtotime($value->order_date)) : '------'}} </td>
                                                <td> {{$value->cancel_date ? date('d-m-Y', strtotime($value->cancel_date)) : '------'}} </td>
                                                <td> {{$value->cancel_remarks}} </td>
                                                
                                               <!-- <td> 
                                                    @if($value->cancel_approved == 1)
                                                        {{'Accept'}}
                                                    @elseif($value->cancel_approved == 2)
                                                        Reject
                                                    @elseif($value->cancel_approved == 3)
                                                        Process
                                                    @else
                                                        {{'------'}}
                                                    @endif
                                                </td>-->
                                                <td> {{$value->total_items}} </td>
                                                <td> <i class="fa fa-inr"></i> {{$value->net_amount}} </td>
                                            </tr>
                                        @endforeach
                                    </table>
                                    <div class="gj_myacc_pge">
                                        {{$cancel_orders->links()}}
                                    </div>
                                </div>
                            @else
                                <p class="gj_no_data">Orders is Empty</p>
                            @endif
                        </div>