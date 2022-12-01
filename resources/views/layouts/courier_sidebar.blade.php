<div class="gj_left_menu">
    <div id="gj_left" class="slider-parent active">
        <!-- <div class="media user-media well-small">
            <div class="media-body">
                <h5 class="media-heading">Settings</h5>
            </div>
            <br>
        </div> -->

        <ul id="gj_menu" class="">
            <li class="panel active">
                <a href="{{ route('courier_track') }}">
                    <i class="fa fa-first-order"></i> Export Courier Orders
                </a>                   
            </li>

            <?php $log = session()->get('user'); ?>

            @if($log)
                @if($log->user_type == 1)
                    <li class="panel">
                        <a href="{{ route('add_shipment_order') }}">
                            <i class="fa fa-cart-plus"></i> Add Shipment Details
                        </a>                   
                    </li>

                    <li class="panel">
                        <a href="{{ route('add_bulk_shipment_order') }}">
                            <i class="fa fa-cart-plus"></i> Add Bulk Shipment Details
                        </a>                   
                    </li>
                @endif
            @endif

            <li class="panel">
                <a href="{{ route('shipment_order') }}">
                    <i class="fa fa-truck"></i> Shipment Details
                </a>                   
            </li>
        </ul>
    </div>
</div>