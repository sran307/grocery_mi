<div class="gj_left_menu">
    <div id="gj_left" class="slider-parent active">
        <!-- <div class="media user-media well-small">
            <div class="media-body">
                <h5 class="media-heading">Settings</h5>
            </div>
            <br>
        </div> -->

        <?php $log = session()->get('user'); ?>

        @if($log)
            @if($log->user_type == 1)
                <ul id="gj_menu" class="">
                    <!-- <li class="panel active">
                        <a href="/product_dashboard">
                            <i class="fa fa-dashboard"></i> Products Dashboard</a>                   
                    </li> -->

                    <!--<li class="panel">
                        <a href="{{ route('add_measurement') }}">
                            <i class="fa fa-plus-circle"></i> Add Measurement Unit
                        </a>                   
                    </li>

                    <li class="panel">
                        <a href="{{ route('manage_measurement') }}">
                            <i class="fa fa-edit"></i> Manage Measurement Unit
                        </a>                   
                    </li>-->

                    <li class="panel">
                        <a href="{{ route('add_tag') }}">
                            <i class="fa fa-plus-circle"></i> Add Tags
                        </a>                   
                    </li>

                    <li class="panel">
                        <a href="{{ route('manage_tag') }}">
                            <i class="fa fa-edit"></i> Manage Tags
                        </a>                   
                    </li>

                    <li class="panel">
                        <a href="{{ route('add_brands') }}">
                            <i class="fa fa-plus-circle"></i> Add Brand
                        </a>                   
                    </li>

                    <li class="panel">
                        <a href="{{ route('manage_brands') }}">
                            <i class="fa fa-edit"></i> Manage Brands
                        </a>                   
                    </li>

                    <li class="panel">
                        <a href="{{ route('add_product') }}">
                            <i class="fa fa-plus-circle"></i> Add Products
                        </a>                   
                    </li>

                    <li class="panel active">
                        <a href="{{ route('manage_product') }}">
                            <i class="fa fa-edit"></i> Manage Products
                        </a>                   
                    </li>

                    <li class="panel">
                        <a href="{{ route('add_stock') }}">
                            <i class="fa fa-plus-circle"></i> Add Stock Details
                        </a>                   
                    </li>

                    <!--<li class="panel">
                        <a href="{{ route('damage_stock') }}">
                            <i class="fa fa-minus-circle"></i> Add Damage Stock Details
                        </a>                   
                    </li>-->

                    <li class="panel">
                        <a href="{{ route('manage_stock') }}">
                            <i class="fa fa-edit"></i> Manage Stock Details
                        </a>                   
                    </li>

                    <li class="panel">
                        <a href="{{ route('manage_pro_widget') }}">
                            <i class="fa fa-edit"></i> Manage Product Page Widget
                        </a>                   
                    </li>
<!--

                    <li class="panel">
                        <a href="{{ route('manage_offer_stock') }}">
                            <i class="fa fa-edit"></i> Manage Offer Stock Details
                        </a>                   
                    </li>-->

                    <!-- <li class="panel">
                        <a href="/product_bulk_upload">
                            <i class="fa fa-upload"></i> Products Add Bulk Upload
                       </a>                   
                    </li> -->

                   <!-- <li class="panel">
                        <a href="{{ route('add_offer') }}">
                            <i class="fa fa-plus-circle"></i> Add Offer
                        </a>                   
                    </li>

                    <li class="panel">
                        <a href="{{ route('manage_offer') }}">
                            <i class="fa fa-edit"></i> Manage Offer
                        </a>                   
                    </li>-->

                   <!-- <li class="panel">
                        <a href="{{ route('manage_stock_trans') }}">
                            <i class="fa fa-edit"></i> Manage Stock Transaction
                        </a>                   
                    </li>

                    <li class="panel">
                        <a href="{{ route('manage_offer_trans') }}">
                            <i class="fa fa-edit"></i> Manage Offer Stock Transaction
                        </a>                   
                    </li>-->

                    <!-- <li class="panel">
                        <a href="{{ route('sold_product') }}">
                            <i class="fa fa-credit-card"></i> Sold Products
                       </a>                   
                    </li> -->

                    
                </ul>
            @elseif($log->user_type == 3 || $log->user_type == 2)
                <ul id="gj_menu" class="">
                    <!-- <li class="panel active">
                        <a href="/product_dashboard">
                            <i class="fa fa-dashboard"></i> Products Dashboard</a>                   
                    </li> -->

                    <li class="panel">
                        <a href="{{ route('add_measurement') }}">
                            <i class="fa fa-plus-circle"></i> Add Measurement Unit
                        </a>                   
                    </li>

                    <li class="panel">
                        <a href="{{ route('manage_measurement') }}">
                            <i class="fa fa-edit"></i> Manage Measurement Unit
                        </a>                   
                    </li>

                    <li class="panel">
                        <a href="{{ route('add_tag') }}">
                            <i class="fa fa-plus-circle"></i> Add Tags
                        </a>                   
                    </li>

                    <li class="panel">
                        <a href="{{ route('manage_tag') }}">
                            <i class="fa fa-edit"></i> Manage Tags
                        </a>                   
                    </li>

                    <li class="panel">
                        <a href="{{ route('add_brands') }}">
                            <i class="fa fa-plus-circle"></i> Add Brand
                        </a>                   
                    </li>

                    <li class="panel">
                        <a href="{{ route('manage_brands') }}">
                            <i class="fa fa-edit"></i> Manage Brands
                        </a>                   
                    </li>

                    <li class="panel">
                        <a href="{{ route('add_product') }}">
                            <i class="fa fa-plus-circle"></i> Add Products
                        </a>                   
                    </li>

                    <li class="panel active">
                        <a href="{{ route('manage_product') }}">
                            <i class="fa fa-edit"></i> Manage Products
                        </a>                   
                    </li>

                    <li class="panel">
                        <a href="{{ route('add_stock') }}">
                            <i class="fa fa-plus-circle"></i> Add Stock Details
                        </a>                   
                    </li>

                    <li class="panel">
                        <a href="{{ route('manage_stock') }}">
                            <i class="fa fa-edit"></i> Manage Inventory Stock Details
                        </a>                   
                    </li>

                    <!-- <li class="panel">
                        <a href="/product_bulk_upload">
                            <i class="fa fa-upload"></i> Products Add Bulk Upload
                       </a>                   
                    </li> -->

                    <li class="panel">
                        <a href="{{ route('manage_offer_stock') }}">
                            <i class="fa fa-edit"></i> Manage Offer Stock Details
                        </a>                   
                    </li>

                    <!--<li class="panel">
                        <a href="{{ route('add_offer') }}">
                            <i class="fa fa-plus-circle"></i> Add Offer
                        </a>                   
                    </li>

                    <li class="panel">
                        <a href="{{ route('manage_offer') }}">
                            <i class="fa fa-edit"></i> Manage Offer
                        </a>                   
                    </li>-->

                    <li class="panel">
                        <a href="{{ route('manage_stock_trans') }}">
                            <i class="fa fa-edit"></i> Manage Stock Transaction
                        </a>                   
                    </li>

                    <li class="panel">
                        <a href="{{ route('manage_offer_trans') }}">
                            <i class="fa fa-edit"></i> Manage Offer Stock Transaction
                        </a>                   
                    </li>

                </ul>
            @endif
        @endif  
    </div>
</div>