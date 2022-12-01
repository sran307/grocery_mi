@extends('layouts.master')
@section('title', 'Roles & Privileges')
@section('content')
<section class="gj_rol_setting">
    <div class="row gj_row">
        <div class="col-md-3 col-sm-3 col-xs-12">
            @include('layouts.sidebar')
        </div>

        <div class="col-md-9 col-sm-9 col-xs-12">
            <!-- <div class="row">
                <div class="col-lg-12">
                    <ul class="breadcrumb">
                        <li class=""><a> Home  </a></li>
                        <li class="active"><a> Roles & Privileges  </a></li>
                    </ul>
                </div>
            </div> -->

            <div class="gj_box dark">
                @if(Session::has('message'))
                    <p class="alert {{ Session::get('alert-class', 'alert-info') }}">{{ Session::get('message') }}</p>
                @endif
                <header>
                    <div class="gj_icons"><i class="fa fa-edit"></i></div>
                    <h5 class="gj_heading"> Roles & Privileges  </h5>
                </header>

                <div class="col-md-12">
                    {{ Form::open(array('url' => 'user_previl','class'=>'gj_roles_form','files' => true)) }}
                        <div class="gj_pre_sel">
                            <div class="row">
                                <!-- <div class="col-md-5">
                                    <div class="form-group">
                                        {{ Form::label('user', 'Users') }}
                                        <span class="error">* 
                                            @if ($errors->has('user'))
                                                {{ $errors->first('user') }}
                                            @endif
                                        </span>

                                        <?php 
                                            $opt = '<option value="0">Select User</option>';
                                            if(sizeof($users) != 0) {
                                                foreach($users as $key => $value) {
                                                    {{$opt.='<option value="'.$value->id.'">'.$value->first_name.''.$value->last_name.'</option>';}}
                                                }
                                            }
                                        ?>
                                        <select id="user" name="user" class="form-control gj_user" autocomplete="off">
                                            <?php echo $opt; ?>
                                        </select>
                                    </div>
                                </div> -->
                                <div class="col-md-5">
                                    <div class="form-group">
                                        {{ Form::label('roles', 'Roles') }}
                                        <span class="error">* 
                                            @if ($errors->has('roles'))
                                                {{ $errors->first('roles') }}
                                            @endif
                                        </span>

                                        <select id="roles" name="roles" class="form-control gj_roles" autocomplete="off">
                                            <option value="0">Select Roles</option>
                                            @if(sizeof($roles) != 0)
                                                @foreach ($roles as $rky => $rval)
                                                    <option value="{{$rval->id}}">{{$rval->role}}</option>
                                                @endforeach
                                            @endif
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <a href="{{route('user_previl')}}"><button type="button" class="btn btn-danger gj_clr_rol">Clear</button></a>
                                </div>    
                            </div>
                        </div>  

                        <div class="gj_pre_tdiv">
                            <div class="table-responsive gj_pre_rep">
                                <table class="table gj_pre_tbl" id="gj_pre_tbl">
                                    <thead>
                                        <tr>
                                            <th>S.No</th>
                                            <th>#</th>
                                            <th>Module</th>
                                            <th class="list_all">List <span><input type="checkbox" id="list_all" name="list_all" autocomplete="off"/></span></th>
                                            <th class="add_all">Add <span><input type="checkbox" id="add_all" name="add_all" autocomplete="off"/></span></th>
                                            <th class="edit_all">Edit <span><input type="checkbox" id="edit_all" name="edit_all" autocomplete="off"/></span></th>
                                            <th class="view_all">View <span><input type="checkbox" id="view_all" name="view_all" autocomplete="off"/></span></th>
                                            <th class="delete_all">Delete <span><input type="checkbox" id="delete_all" name="delete_all" autocomplete="off"/></span></th>
                                            <th class="status_all">Status <span><input type="checkbox" id="status_all" name="status_all" autocomplete="off"/></span></th>
                                            <th class="export_all">Export <span><input type="checkbox" id="export_all" name="export_all" autocomplete="off"/></span></th>
                                        </tr>                                        
                                    </thead>

                                    <tbody id="gj_pre_tbody">
                                        @if(sizeof($modules) != 0)
                                            @php ($i = 1)
                                            @foreach($modules as $mkey =>$mval)
                                                <tr>
                                                    <td>{{$i}}</td>
                                                    <td>
                                                        <input type="hidden" name="mid[]" value="{{$mval->id}}"  autocomplete="off">
                                                        <input type="checkbox" name="rowcheck[]" class="rowcheck" id="rowcheck_{{$i}}" autocomplete="off"/>
                                                    </td>
                                                    <td>{{$mval->module_name}}</td>
                                                    <td>
                                                        <input type="checkbox" name="listcheck[]" class="listcheck" id="listcheck_{{$mval->id}}" value="0" autocomplete="off"/>
                                                        <input type="hidden" name="h_listcheck[]" class="h_listcheck" id="hlistcheck_{{$mval->id}}" value="0" autocomplete="off"/>
                                                    </td>
                                                    <td>
                                                        <input type="checkbox" name="addcheck[]" class="addcheck" id="addcheck_{{$mval->id}}" value="0" autocomplete="off"/>
                                                        <input type="hidden" name="h_addcheck[]" class="h_addcheck" id="addcheck_{{$mval->id}}" value="0" autocomplete="off"/>
                                                    </td>
                                                    <td>
                                                        <input type="checkbox" name="editcheck[]" class="editcheck" id="editcheck_{{$mval->id}}" value="0" autocomplete="off"/>
                                                        <input type="hidden" name="h_editcheck[]" class="h_editcheck" id="h_editcheck_{{$mval->id}}" value="0" autocomplete="off"/>
                                                     </td>
                                                    <td>
                                                        <input type="checkbox" name="viewcheck[]" class="viewcheck" id="viewcheck_{{$mval->id}}" value="0" autocomplete="off"/>
                                                        <input type="hidden" name="h_viewcheck[]" class="h_viewcheck" id="h_viewcheck_{{$mval->id}}" value="0" autocomplete="off"/>
                                                    </td>
                                                    <td>
                                                        <input type="checkbox" name="deletecheck[]" class="deletecheck" id="deletecheck_{{$mval->id}}" value="0" autocomplete="off"/>
                                                        <input type="hidden" name="h_deletecheck[]" class="h_deletecheck" id="h_deletecheck_{{$mval->id}}" value="0" autocomplete="off"/>
                                                    </td>
                                                    <td>
                                                        <input type="checkbox" name="statuscheck[]" class="statuscheck" id="statuscheck_{{$mval->id}}" value="0" autocomplete="off"/>
                                                        <input type="hidden" name="h_statuscheck[]" class="h_statuscheck" id="h_statuscheck_{{$mval->id}}" value="0" autocomplete="off"/>
                                                    </td>
                                                    <td>
                                                        <input type="checkbox" name="exportcheck[]" class="exportcheck" id="exportcheck_{{$mval->id}}" value="0" autocomplete="off"/>
                                                        <input type="hidden" name="h_exportcheck[]" class="h_exportcheck" id="h_exportcheck_{{$mval->id}}" value="0" autocomplete="off"/>
                                                    </td>
                                                </tr>
                                                @php ($i = $i+1)
                                            @endforeach
                                        @endif
                                    </tbody>
                                </table>
                            </div>
                        </div>  

                        {{ Form::submit('Save', array('class' => 'btn btn-primary gj_pre_save')) }}

                    {{ Form::close() }}
                </div>
            </div>
        </div>
    </div>
</section>

<script>
    $(document).ready(function() {
        $(':checkbox').val('2'); 
        $(':checkbox').next().val('2'); 
        $('body').on('change',':checkbox',function() {
            if (!$(this).prop("checked")) {
                $(this).val('2');
                $(this).next().val('2');
            } else {
                $(this).val('1');
                $(this).next().val('1');
            }
        });

        // $('#gj_pre_tbl').dataTable({
        //     "paginate": true,
        //     "searching": false,
        //     "bInfo" : false,
        //     "sort": true
        // });

        $('p.alert').delay(5000).slideUp(800); 
        $("#user").select2();
        $("#roles").select2();

        /*Check All Row Script Start*/
        $('body').on('click','.rowcheck',function() { 
            $(this).closest('tr').find(':checkbox').prop('checked', this.checked);
            if ($(this).closest('tr').find(':checkbox').prop('checked', this.checked)) {
                if($(this).closest('tr').find(':checkbox').val() == 1) {
                    $(this).closest('tr').find(':checkbox').val('2');
                    $(this).closest('tr').find(':checkbox').next().val('2');
                } else {
                    $(this).closest('tr').find(':checkbox').val('1');
                    $(this).closest('tr').find(':checkbox').next().val('1');
                }
            } else {
                $(this).closest('tr').find(':checkbox').prop('checked', '');
                $(this).closest('tr').find(':checkbox').val('2');
                $(this).closest('tr').find(':checkbox').next().val('2');
            }
        });
        
        $('body').on('change',':checkbox',function() {
            if (!$(this).prop("checked")) {
                $(this).closest('tr').find(".rowcheck").prop("checked",false);
            }
        });
        /*Check All Row Script End*/

        /*Check All List Script Start*/
        $('body').on('click','#list_all',function() { 
            $(".listcheck").prop('checked', $(this).prop('checked'));
            $('.listcheck').each(function () {
                if (this.checked) {
                    $(this).val('1');
                    $(this).next().val('1');
                } else {
                    $(this).val('2');
                    $(this).next().val('2');
                }
            });
        });
        
        $('body').on('change','.listcheck',function() {
            if (!$(this).prop("checked")){
                $("#list_all").prop("checked",false);
            }
        });
        /*Check All List Script End*/

        /*Check All Add Script Start*/
        $('body').on('click','#add_all',function() { 
            $(".addcheck").prop('checked', $(this).prop('checked'));
            $('.addcheck').each(function () {
                if (this.checked) {
                    $(this).val('1');
                    $(this).next().val('1');
                } else {
                    $(this).val('2');
                    $(this).next().val('2');
                }
            });
        });
        
        $('body').on('change','.addcheck',function() {
            if (!$(this).prop("checked")){
                $("#add_all").prop("checked",false);
            }
        });
        /*Check All Add Script End*/

        /*Check All Edit Script Start*/
        $('body').on('click','#edit_all',function() { 
            $(".editcheck").prop('checked', $(this).prop('checked'));
            $('.editcheck').each(function () {
                if (this.checked) {
                    $(this).val('1');
                    $(this).next().val('1');
                } else {
                    $(this).val('2');
                    $(this).next().val('2');
                }
            });
        });
        
        $('body').on('change','.editcheck',function() {
            if (!$(this).prop("checked")){
                $("#edit_all").prop("checked",false);
            }
        });
        /*Check All Edit Script End*/

        /*Check All View Script Start*/
        $('body').on('click','#view_all',function() { 
            $(".viewcheck").prop('checked', $(this).prop('checked'));
            $('.viewcheck').each(function () {
                if (this.checked) {
                    $(this).val('1');
                    $(this).next().val('1');
                } else {
                    $(this).val('2');
                    $(this).next().val('2');
                }
            });
        });
        
        $('body').on('change','.viewcheck',function() {
            if (!$(this).prop("checked")){
                $("#view_all").prop("checked",false);
            }
        });
        /*Check All View Script End*/

        /*Check All Delete Script Start*/
        $('body').on('click','#delete_all',function() { 
            $(".deletecheck").prop('checked', $(this).prop('checked'));
            $('.deletecheck').each(function () {
                if (this.checked) {
                    $(this).val('1');
                    $(this).next().val('1');
                } else {
                    $(this).val('2');
                    $(this).next().val('2');
                }
            });
        });
        
        $('body').on('change','.deletecheck',function() {        
            if (!$(this).prop("checked")){
                $("#delete_all").prop("checked",false);
            }
        });
        /*Check All Delete Script End*/

        /*Check All Status Script Start*/
        $('body').on('click','#status_all',function() { 
            $(".statuscheck").prop('checked', $(this).prop('checked'));
            $('.statuscheck').each(function () {
                if (this.checked) {
                    $(this).val('1');
                    $(this).next().val('1');
                } else {
                    $(this).val('2');
                    $(this).next().val('2');
                }
            });
        });
        
        $('body').on('change','.statuscheck',function() {        
            if (!$(this).prop("checked")){
                $("#status_all").prop("checked",false);
            }
        });
        /*Check All Status Script End*/

        /*Check All Export Script Start*/
        $('body').on('click','#export_all',function() { 
            $(".exportcheck").prop('checked', $(this).prop('checked'));
            $('.exportcheck').each(function () {
                if (this.checked) {
                    $(this).val('1');
                    $(this).next().val('1');
                } else {
                    $(this).val('2');
                    $(this).next().val('2');
                }
            });
        });
        
        $('body').on('change','.exportcheck',function() { 
            if (!$(this).prop("checked")){
                $("#export_all").prop("checked",false);
            }
        });
        /*Check All Export Script End*/

        /*User to View Roles Script Start */
        $('body').on('change','#roles',function() {
            var roles = 0;
            if ($('#roles').select2('val') != 0) {
                var roles = $('#roles').select2('val');
            }

            var data = 0;
            if ($('#gj_pre_tbody').html()) {
                var data = $('#gj_pre_tbody').html();
            }

            if(roles != 0 && data != 0) {
                $.ajax({
                    type: 'post',
                    url: '{{url('/select_user_previl')}}',
                    data: {roles: roles, data: data, type: 'select'},
                    success: function(data){
                        if(data != 1) {
                            $('#gj_pre_tbody').html(data);
                            // window.location.reload();
                        } else {
                            // alert("No Action Performed!");
                            window.location.reload();
                        }
                    }
                });
            } else {
                $.confirm({
                    title: '',
                    content: 'Please Enter Roles!',
                    icon: 'fa fa-exclamation',
                    theme: 'modern',
                    closeIcon: true,
                    animation: 'scale',
                    type: 'purple',
                    buttons: {
                        Ok: function(){
                            window.location.reload();
                        }
                    }
                });
                window.location.reload();
            }
        });
        /*User to View Roles Script End */
    });

    /*$('.gj_roles_form').on('submit',function(e){
        e.preventDefault();
        // alert();
        // var str = $("form").serializeArray();
        var str = $("form").serialize();
        // var str = new FormData(); 
        $.ajax({
            type: 'post',
            url: '{{url('/user_previl')}}',
            data: str,
            success: function(data){
                if(data == 0) {
                    $.confirm({
                        title: '',
                        content: 'Roles & Privileges Changed Successfully!',
                        icon: 'fa fa-check',
                        theme: 'modern',
                        closeIcon: true,
                        animation: 'scale',
                        type: 'green',
                        buttons: {
                            Ok: function(){
                                window.location.reload();
                            }
                        }
                    });
                } else if(data == 2) {
                    $.confirm({
                        title: '',
                        content: 'Please Select Roles!',
                        icon: 'fa fa-exclamation',
                        theme: 'modern',
                        closeIcon: true,
                        animation: 'scale',
                        type: 'red',
                        buttons: {
                            Ok: function(){
                                window.location.reload();
                            }
                        }
                    });
                    // window.location.reload();
                } else if(data == 3) {
                    $.confirm({
                        title: '',
                        content: 'Roles & Privileges Changed Failed!',
                        icon: 'fa fa-exclamation',
                        theme: 'modern',
                        closeIcon: true,
                        animation: 'scale',
                        type: 'red',
                        buttons: {
                            Ok: function(){
                                window.location.reload();
                            }
                        }
                    });
                    // window.location.reload();
                } else if(data == 4) {
                    $.confirm({
                        title: '',
                        content: 'You Are Not Access This Module!',
                        icon: 'fa fa-exclamation',
                        theme: 'modern',
                        closeIcon: true,
                        animation: 'scale',
                        type: 'red',
                        buttons: {
                            Ok: function(){
                                window.location.href='{{ url()->previous() }}';
                            }
                        }
                    });
                    // window.location.reload();
                } else if(data == 5) {
                    $.confirm({
                        title: '',
                        content: 'Please Login Properly!',
                        icon: 'fa fa-exclamation',
                        theme: 'modern',
                        closeIcon: true,
                        animation: 'scale',
                        type: 'red',
                        buttons: {
                            Ok: function(){
                                window.location.href='{{ url()->previous() }}';
                            }
                        }
                    });
                    // window.location.reload();
                } else {
                    $.confirm({
                        title: '',
                        content: 'No Action Performed!',
                        icon: 'fa fa-exclamation',
                        theme: 'modern',
                        closeIcon: true,
                        animation: 'scale',
                        type: 'purple',
                        buttons: {
                            Ok: function(){
                                window.location.reload();
                            }
                        }
                    });
                }
            }
        });
    });*/
</script>
@endsection
