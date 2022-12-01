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
                        <a href="{{ route('manage_credits') }}">
                            <i class="fa fa-users"></i> Vendor Credits Management
                       </a>                   
                    </li>

                    <li class="panel">
                        <a href="{{ route('manage_admin_comis') }}">
                            <i class="fa fa-user-circle-o"></i> Order Transaction Summary
                        </a>                   
                    </li>

                    <li class="panel">
                        <a href="{{ route('orderby_admin_comis') }}">
                            <i class="fa fa-user-circle"></i> OrderBy Admin Commision
                        </a>                   
                    </li>

                    <li class="panel">
                        <a href="{{ route('manage_cashout') }}">
                            <i class="fa fa-money"></i> Process Cashout
                        </a>                   
                    </li>

                    <li class="panel">
                        <a href="{{ route('bank_details') }}">
                            <i class="fa fa-user-circle"></i> Manage Bank Details
                        </a>                   
                    </li>

                    <li class="panel">
                        <a href="{{ route('manage_admin_cashout') }}">
                            <i class="fa fa-user-circle"></i> Manage Cashout Request
                        </a>                   
                    </li>

                    <li class="panel">
                        <a href="{{ route('add_admin_cashout') }}">
                            <i class="fa fa-user-circle"></i> Cashout Request to Vendor
                        </a>                   
                    </li>
                </ul>
            @elseif($value->user_type == 2 || $value->user_type == 3)
                <ul id="gj_menu" class="">
                    <li class="panel active">
                        <a href="{{ route('manage_credits') }}">
                            <i class="fa fa-users"></i> Manage Credits
                       </a>                   
                    </li>

                    <li class="panel">
                        <a href="{{ route('manage_admin_comis') }}">
                            <i class="fa fa-user-circle-o"></i> Order Transaction Summary
                        </a>                   
                    </li>

                    <li class="panel">
                        <a href="{{ route('orderby_admin_comis') }}">
                            <i class="fa fa-user-circle"></i> OrderBy Admin Commision
                        </a>                   
                    </li>

                    <li class="panel">
                        <a href="{{ route('add_bank_details') }}">
                            <i class="fa fa-user-circle"></i> Add Bank Details
                        </a>                   
                    </li>

                    <li class="panel">
                        <a href="{{ route('bank_details') }}">
                            <i class="fa fa-user-circle"></i> Manage Bank Details
                        </a>                   
                    </li>

                    <li class="panel">
                        <a href="{{ route('manage_cashout') }}">
                            <i class="fa fa-money"></i> Cashout Requests
                        </a>                   
                    </li>

                    <li class="panel">
                        <a href="{{ route('manage_admin_cashout') }}">
                            <i class="fa fa-user-circle"></i> Admin Cashout Request
                        </a>                   
                    </li>
                </ul>
            @endif
        @endif
    </div>
</div>