<div class="gj_left_menu">
    <div id="gj_left" class="slider-parent active">
        <!-- <div class="media user-media well-small">
            <div class="media-body">
                <h5 class="media-heading">Merchants</h5>
            </div>
            <br>
        </div> -->
        <?php 
            $value = session()->get('user'); 
        ?>
        @if($value)
            @if($value->user_type == 1)
                <ul id="gj_menu" class="">
                    <li class="panel active">
                        <a href="{{ route('merchant_dashboard') }}">
                            <i class="fa fa-dashboard"></i> Merchants Dashboard</a>                   
                    </li>

                    <li class="panel">
                        <a href="{{ route('add_merchant') }}">
                            <i class="fa fa-user"></i> Add Merchant Account
                        </a>                   
                    </li>

                    <li class="panel">
                        <a href="{{ route('manage_merchant') }}">
                            <i class="fa fa-check-circle"></i> Manage Merchant Accounts
                       </a>                   
                    </li>

                    <!-- <li class="panel">
                        <a href="/manage_merchant">
                            <i class="fa fa-comment"></i> Manage Store Review
                       </a>                   
                    </li> -->
                </ul>
            @elseif($value->user_type == 2 || $value->user_type == 3)
                <ul id="gj_menu" class="">
                    <li class="panel active">
                        <a href="{{ route('merchant_dashboard') }}">
                            <i class="fa fa-dashboard"></i> Merchants Dashboard</a>                   
                    </li>

                    <li class="panel">
                        <a href="{{ route('add_store', ['id' => $value->id]) }}">
                            <i class="fa fa-user"></i> Add Merchant Store
                        </a>                   
                    </li>

                    <li class="panel">
                        <a href="{{ route('manage_store', ['id' => $value->id]) }}">
                            <i class="fa fa-check-circle"></i> Manage Merchant Store
                       </a>                   
                    </li>
                </ul>
            @endif
        @endif

    </div>
</div>