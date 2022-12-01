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
                <a href="{{ route('all_orders') }}">
                    <i class="fa fa-first-order"></i> All Orders
                </a>                   
            </li>

            <li class="panel">
                <a href="{{ route('all_transaction') }}">
                    <i class="fa fa-money"></i> All Transaction
                    <i class="fas fa-exchange-alt"></i>
                </a>                   
            </li>

            <li class="panel">
                <a href="{{ route('cancel_req_orders') }}">
                    <i class="fa fa-shopping-cart"></i> Orders Cancel Requests
                </a>                   
            </li>

            <li class="panel">
                <a href="{{ route('cancel_all_orders') }}">
                    <i class="fa fa-first-order"></i> Cancel Orders
                </a>                   
            </li>

            

           <!-- <li class="panel">
                <a href="{{ route('grv_orders') }}">
                    <i class="fa fa-first-order"></i> GRV Orders
                </a>                   
            </li>-->

           

           <!-- <li class="panel">
                <a href="{{ route('create_credit_notes') }}">
                    <i class="fa fa-money"></i> Create Credit Notes
                </a>                   
            </li>
-->
            
        </ul>
    </div>
</div>