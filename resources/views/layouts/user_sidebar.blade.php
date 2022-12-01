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
                        <a href="{{ route('manage_user') }}">
                            <i class="fa fa-users"></i> Manage Users
                       </a>                   
                    </li>

                    <li class="panel">
                        <a href="{{ route('add_user') }}">
                            <i class="fa fa-user-plus"></i> Add Users
                        </a>                   
                    </li>

                    <li class="panel">
                        <a href="{{ route('my_profile') }}">
                            <i class="fa fa-user-secret"></i> My Profile
                        </a>                   
                    </li>

                    <li class="panel">
                        <a href="{{ route('edit_profile') }}">
                            <i class="fa fa-user"></i> Edit Profile
                        </a>                   
                    </li>

                    <li class="panel">
                        <a href="{{ route('forgot') }}">
                            <i class="fa fa-unlock-alt"></i> Change Password
                        </a>                   
                    </li>

                </ul>
            @elseif($value->user_type == 2 || $value->user_type == 3)
                <ul id="gj_menu" class="">
                    <li class="panel active">
                        <a href="{{ route('my_profile') }}">
                            <i class="fa fa-user-secret"></i> My Profile
                        </a>                   
                    </li>

                    <li class="panel">
                        <a href="{{ route('edit_profile') }}">
                            <i class="fa fa-user"></i> Edit Profile
                        </a>                   
                    </li>
                </ul>
            @endif
        @endif
    </div>
</div>