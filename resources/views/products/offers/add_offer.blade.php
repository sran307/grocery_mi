@extends('layouts.master')
@section('title', 'Add Offer')
@section('content')
<link rel="stylesheet" href="{{ asset('css/jquery-ui.css')}}">

<section class="gj_offer_setting">
    <div class="row gj_row">
        <div class="col-md-3 col-sm-3 col-xs-12">
            @include('layouts.product_sidebar')
        </div>

        <div class="col-md-9 col-sm-9 col-xs-12">
            <!-- <div class="row">
                <div class="col-lg-12">
                    <ul class="breadcrumb">
                        <li class=""><a> Home  </a></li>
                        <li class="active"><a> Add Offer  </a></li>
                    </ul>
                </div>
            </div> -->

            <div class="gj_box dark">
                @if(Session::has('message'))
                    <p class="gj_bk_alt alert {{ Session::get('alert-class', 'alert-info') }}">{{ Session::get('message') }}</p>
                @endif
                <header>
                    <div class="gj_icons"><i class="fa fa-edit"></i></div>
                    <h5 class="gj_heading"> Add Offer  </h5>
                </header>

                <div class="col-md-12">
                    {{ Form::open(array('url' => 'add_offer','class'=>'gj_offer_form','files' => true)) }}
                        <div class="gj_box dark gj_inside_box">
                            <header>
                                <h5 class="gj_heading"> Offers Details  </h5>
                            </header>
                            
                            <div class="col-md-12">
                                <div class="form-group">
                                    {{ Form::label('offer_title', 'Offer Title') }}
                                    <span class="error">* 
                                        @if ($errors->has('offer_title'))
                                            {{ $errors->first('offer_title') }}
                                        @endif
                                    </span>

                                    {{ Form::text('offer_title', Input::old('offer_title'), array('class' => 'form-control gj_offer_title','placeholder' => 'Enter Offer Title in English')) }}
                                </div>

                                <div class="form-group">
                                    {{ Form::label('description', 'Offer Description') }}
                                    <span class="error">* 
                                        @if ($errors->has('description'))
                                            {{ $errors->first('description') }}
                                        @endif
                                    </span>

                                    {{ Form::textarea('description', Input::old('description'), array('class' => 'form-control gj_description', 'rows' => '5', 'placeholder' => 'Enter Offer Description in English')) }}
                                </div>

                                <div class="form-group">
                                    {{ Form::label('offer_type', 'Select Offer Type') }}
                                    <span class="error"> 
                                        @if ($errors->has('offer_type'))
                                            {{ $errors->first('offer_type') }}
                                        @endif
                                    </span>

                                    <select id="offer_type" name="offer_type" class="form-control gj_offer_type">
                                        <option value="" selected>Select Offer Type</option>
                                        <option value="New">New</option>
                                        <option value="Hot Sale">Hot Sale</option>
                                        <option value="Featured">Featured</option>
                                    </select>
                                </div>

                                <div class="form-group">
                                    {{ Form::label('x_pro_cnt', 'X-Product Count') }}
                                    <span class="error">* 
                                        @if ($errors->has('x_pro_cnt'))
                                            {{ $errors->first('x_pro_cnt') }}
                                        @endif
                                    </span>

                                    {{ Form::text('x_pro_cnt', Input::old('x_pro_cnt'), array('class' => 'form-control gj_x_pro_cnt','placeholder' => 'Enter X-Product Count (Selected Category X value)')) }}
                                </div>

                                <div class="form-group">
                                    {{ Form::label('y_pro_cnt', 'Y-Product Count') }}
                                    <span class="error">* 
                                        @if ($errors->has('y_pro_cnt'))
                                            {{ $errors->first('y_pro_cnt') }}
                                        @endif
                                    </span>

                                    {{ Form::text('y_pro_cnt', Input::old('y_pro_cnt'), array('class' => 'form-control gj_y_pro_cnt','placeholder' => 'Enter Y-Product Count')) }}
                                </div>

                                <div class="form-group">
                                    {{ Form::label('offer_start', 'Start Date') }}
                                    <span class="error">* 
                                        @if ($errors->has('offer_start'))
                                            {{ $errors->first('offer_start') }}
                                        @endif
                                    </span>

                                    <div class="input-group date gj_form_datetime" data-date="{{ Carbon\Carbon::today() }}" data-date-format="dd MM yyyy - hh:ii:ss p" data-link-field="offer_start">
                                        <input class="form-control gj_offer_start" type="text" value="" readonly>
                                        <!-- <span class="input-group-addon"><span class="glyphicon glyphicon-remove"></span></span> -->
                                        <span class="input-group-addon"><span class="fa fa-calendar"></span></span>
                                    </div>
                                    <input type="hidden" name="offer_start" id="offer_start"/>
                                </div>

                                <div class="form-group">
                                    {{ Form::label('offer_end', 'End Date') }}
                                    <span class="error">* 
                                        @if ($errors->has('offer_end'))
                                            {{ $errors->first('offer_end') }}
                                        @endif
                                    </span>
                                    
                                    <div class="input-group date gj_form_datetime" data-date="{{ Carbon\Carbon::today() }}" data-date-format="dd MM yyyy - hh:ii:ss p" data-link-field="offer_end">
                                        <input class="form-control gj_offer_end" type="text" value="" readonly>
                                        <!-- <span class="input-group-addon"><span class="glyphicon glyphicon-remove"></span></span> -->
                                        <span class="input-group-addon"><span class="fa fa-calendar"></span></span>
                                    </div>
                                    <input type="hidden" name="offer_end" id="offer_end">
                                </div>


                                <div class="form-group">
                                    {{ Form::label('grab_offer', 'Grab Offer') }}
                                    <span class="error">* 
                                        @if ($errors->has('grab_offer'))
                                            {{ $errors->first('grab_offer') }}
                                        @endif
                                    </span>

                                    <textarea class="gj_grab_offer form-control" placeholder="Enter grab_offer ..." rows="5" cols="5" name="grab_offer" id="grab_offer"></textarea>
                                </div> 

                                <div class="form-group">
                                    {{ Form::label('image', 'Upload Featured Offer Image') }}
                                    <span class="error">* 
                                        @if ($errors->has('image'))
                                            {{ $errors->first('image') }}
                                        @endif
                                    </span>
                                    <p class="gj_not" style="color:red"><em>image size must be 800 x 800 pixels</em></p>

                                    <input type="file" name="image" id="image" accept="image/*" class="gj_offr_image">
                                </div>
                            </div>
                        </div>

                        <div class="gj_box dark gj_inside_box">
                            <header>
                                <h5 class="gj_heading"> Offer Products </h5>
                            </header>
                            
                            <div class="col-md-12">
                                <div class="gj_off_prod_div">
                                    <div class="gj_tot_err">
                                        <ul class="error">
                                            @if ($errors->has('product_id.*'))
                                                @foreach ($errors->get('product_id.*') as $errormsg)
                                                    @foreach ($errormsg as $error)
                                                        <li class="error">{{ $error }}</li>
                                                    @endforeach
                                                @endforeach 
                                            @endif

                                            @if ($errors->has('att_name.*'))
                                                @foreach ($errors->get('att_name.*') as $errormsg)
                                                    @foreach ($errormsg as $error)
                                                        <li class="error">{{ $error }}</li>
                                                    @endforeach
                                                @endforeach 
                                            @endif

                                            @if ($errors->has('att_value.*'))
                                                @foreach ($errors->get('att_value.*') as $errormsg)
                                                    @foreach ($errormsg as $error)
                                                        <li class="error">{{ $error }}</li>
                                                    @endforeach
                                                @endforeach 
                                            @endif

                                            @if ($errors->has('qty.*'))
                                                @foreach ($errors->get('qty.*') as $errormsg)
                                                    @foreach ($errormsg as $error)
                                                        <li class="error">{{ $error }}</li>
                                                    @endforeach
                                                @endforeach 
                                            @endif

                                            @if ($errors->has('offer_price.*'))
                                                @foreach ($errors->get('offer_price.*') as $errormsg)
                                                    @foreach ($errormsg as $error)
                                                        <li class="error">{{ $error }}</li>
                                                    @endforeach
                                                @endforeach 
                                            @endif

                                            @if ($errors->has('type.*'))
                                                @foreach ($errors->get('type.*') as $errormsg)
                                                    @foreach ($errormsg as $error)
                                                        <li class="error">{{ $error }}</li>
                                                    @endforeach
                                                @endforeach 
                                            @endif
                                        </ul>
                                    </div>

                                    <div class="gj_p_att_resp table-responsive">
                                        <table class="table table-stripped table-bordered gj_tab_att">
                                            <thead>
                                                <tr>
                                                    <th>Product</th>
                                                    <th>Attribute Name</th>
                                                    <th>Attribute Value</th>
                                                    <th>Quantity</th>
                                                    <th>Type</th>
                                                    <th>#</th>
                                                </tr>
                                            </thead>
                                            <tbody id="gj_att_bdy">
                                                <?php $off_product = ""; ?>
                                                <tr id="gj_tr_off_1">
                                                    <td>
                                                        <select id="productid_1" name="product_id[]" class="form-control gj_off_product_id">
                                                            <option value="" selected>Select Products</option>
                                                            @if(isset($products) && count($products) != 0)
                                                                @foreach ($products as $att_key => $att_val)
                                                                    <option value="{{$att_val->id}}">{{$att_val->product_title}}</option>
                                                                    <?php $off_product.='<option value="'.$att_val->id.'">'.$att_val->product_title.'</option>'; ?>
                                                                @endforeach
                                                            @endif
                                                        </select>
                                                    </td>

                                                    <td>
                                                        <select id="attname_1" name="att_name[]" class="form-control gj_off_att_name">
                                                            <option value="">Select Attribute Name</option>
                                                        </select>
                                                    </td>

                                                    <td>
                                                        <select id="attvalue_1" name="att_value[]" class="form-control gj_off_att_value">
                                                            <option value="">Select Attribute Value</option>
                                                        </select>
                                                    </td>

                                                    <td>
                                                        <input type="number" class="form-control gj_off_qty" placeholder="Enter Assign Quantity" name="qty[]" id="qty_1">

                                                        <input type="hidden" class="form-control gj_offer_cost" placeholder="Enter Assign Cost" name="offer_cost[]" id="offer_cost_1" value ="0.00">

                                                        <input type="hidden" class="form-control gj_offer_tax_amount" placeholder="Enter Assign Tax Amount" name="offer_tax_amount[]" id="offer_tax_amount_1" value ="0.00">

                                                        <input type="hidden" class="form-control gj_off_price" placeholder="Enter Assign Price" name="offer_price[]" id="offerprice_1" value ="0.00">
                                                    </td>

                                                    <td>
                                                        <select id="type_1" name="type[]" class="form-control gj_off_type">
                                                            <option value="" selected>Select Offer Product Type</option>
                                                            <option value="1">X-Products</option>
                                                            <option value="2">Y-Products</option>
                                                        </select>
                                                    </td>

                                                    <td>
                                                        <button type='button' id='removeButton_1' class="gj_off_rem"><i class="fa fa-trash"></i></button>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>

                                        <input type='button' value='Add New' id='addButton'>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{ Form::submit('Save', array('class' => 'btn btn-primary')) }}

                    {{ Form::close() }}
                </div>
            </div>
        </div>
    </div>
</section>

<script>
    $(document).ready(function() { 
        $('p.gj_bk_alt').delay(5000).slideUp(500); 
        $("#offer_cat").select2();
    });

    $('body').on('change','.gj_off_product_id',function() {
        var id = $(this).val();
        var ths = $(this);

        if(id && (id != 0)) {
            $.ajax({
                type: 'post',
                url: '{{url('/check_stock')}}',
                data: {id: id, type: 'check_stock'},
                dataType:"json",    
                success: function(data){
                    if(data['error'] == 3){
                        ths.val(0);
                        ths.closest('tr').find('.gj_off_qty').val('1');
                        ths.closest('tr').find('.gj_off_att_name').attr('disabled', true);
                        $.confirm({
                            title: '',
                            content: "Out Of Stock, " + data['onhand_qty'] + " Products",
                            icon: 'fa fa-exclamation',
                            theme: 'modern',
                            closeIcon: true,
                            animation: 'scale',
                            type: 'blue',
                            buttons: {
                                Ok: function(){
                                    // window.location.reload();
                                }
                            }
                        });
                    } else if(data['error'] == 2){
                        ths.val(0);
                        ths.closest('tr').find('.gj_off_qty').val('1');
                        ths.closest('tr').find('.gj_off_att_name').attr('disabled', true);
                        $.confirm({
                            title: '',
                            content: "Please Enter Quantity Below " + data['onhand_qty'],
                            icon: 'fa fa-exclamation',
                            theme: 'modern',
                            closeIcon: true,
                            animation: 'scale',
                            type: 'blue',
                            buttons: {
                                Ok: function(){
                                }
                            }
                        });
                    } else if(data['error'] == 0){
                        ths.val(0);
                        ths.closest('tr').find('.gj_off_qty').val('1');
                        ths.closest('tr').find('.gj_off_att_name').attr('disabled', true);
                        $.confirm({
                            title: '',
                            content: 'Please Select Product And Then Enter Quantity',
                            icon: 'fa fa-exclamation',
                            theme: 'modern',
                            closeIcon: true,
                            animation: 'scale',
                            type: 'blue',
                            buttons: {
                                Ok: function(){
                                }
                            }
                        });
                    } else if (data['error'] == 1){
                        ths.closest('tr').find('.gj_off_qty').val('1');
                        ths.closest('tr').find('.gj_offer_cost').val(data['offer_cost']);
                        ths.closest('tr').find('.gj_offer_tax_amount').val(data['offer_tax_amount']);
                        ths.closest('tr').find('.gj_off_price').val(data['offer_price']);
                        ths.closest('tr').find('.gj_off_att_name').attr('disabled', false);
                        $.ajax({
                            type: 'post',
                            url: '{{url('/select_atts')}}',
                            data: {id: id, type: 'select_atts'},
                            // dataType:"json",    
                            success: function(data){
                                if(data != 0) {
                                    ths.closest('tr').find('.gj_off_att_name').html(data);
                                } else {
                                    ths.closest('tr').find('.gj_off_att_name').html('<option value="">Select Attribute Name</option>');
                                    $.confirm({
                                        title: '',
                                        content: 'This Product to not set the Attributes!',
                                        icon: 'fa fa-exclamation',
                                        theme: 'modern',
                                        closeIcon: true,
                                        animation: 'scale',
                                        type: 'red',
                                        buttons: {
                                            Ok: function(){
                                            }
                                        }
                                    });
                                }
                            }
                        });
                    }
                }
            });
        }
    });      

    $('body').on('change','.gj_off_att_name',function() {
        var ths = $(this);
        var product_id = 0;
        var id = 0;

        if($(this).val()) {
            var id = $(this).val();
        }

        if($(this).closest('tr').find('.gj_off_product_id').val()) {
            var product_id = $(this).closest('tr').find('.gj_off_product_id').val();
        }

        if(id && id != 0) {
            $.ajax({
                type: 'post',
                url: '{{url('/select_att_vals')}}',
                data: {id: id, product_id: product_id, type: 'select_att_vals'},
                success: function(data){
                    if(data != 0) {
                        ths.closest('tr').find('.gj_off_att_value').html(data);
                    } else {
                        ths.closest('tr').find('.gj_off_att_value').html('<option value="">Select Attribute Value</option>');
                        $.confirm({
                            title: '',
                            content: 'Please Select Another Attribute Name',
                            icon: 'fa fa-exclamation',
                            theme: 'modern',
                            closeIcon: true,
                            animation: 'scale',
                            type: 'red',
                            buttons: {
                                Ok: function(){
                                }
                            }
                        });
                    }
                }
            });
        } else {
            $.confirm({
                title: '',
                content: 'Please Select Attribute Name!',
                icon: 'fa fa-exclamation',
                theme: 'modern',
                closeIcon: true,
                animation: 'scale',
                type: 'blue',
                buttons: {
                    Ok: function(){
                    }
                }
            });
        }
    });

    $('body').on('change','.gj_off_att_value',function() {
        var ths = $(this);
        var id = 0;
        var att_name = 0;
        var att_value = 0;
        var qty = 1;

        if($(this).val()) {
            var att_value = $(this).val();
        }

        if($(this).closest('tr').find('.gj_off_product_id').val()) {
            var id = $(this).closest('tr').find('.gj_off_product_id').val();
        }

        if($(this).closest('tr').find('.gj_off_att_name').val()) {
            var att_name = $(this).closest('tr').find('.gj_off_att_name').val();
        }

        if(att_value && att_value != 0 && id && id != 0) {
            $.ajax({
                type: 'post',
                url: '{{url('/check_stock')}}',
                data: {id: id, qty: qty, att_name: att_name, att_value: att_value, type: 'check_stock'},
                dataType:"json",    
                success: function(data){
                    if(data['error'] == 3){
                        ths.val(0);
                        ths.closest('tr').find('.gj_off_qty').val('1');
                        ths.closest('tr').find('.gj_off_att_name').attr('disabled', true);
                        $.confirm({
                            title: '',
                            content: "Out Of Stock, " + data['onhand_qty'] + " Products",
                            icon: 'fa fa-exclamation',
                            theme: 'modern',
                            closeIcon: true,
                            animation: 'scale',
                            type: 'blue',
                            buttons: {
                                Ok: function(){
                                    // window.location.reload();
                                }
                            }
                        });
                    } else if(data['error'] == 2){
                        ths.val(0);
                        ths.closest('tr').find('.gj_off_qty').val('1');
                        ths.closest('tr').find('.gj_off_att_name').attr('disabled', true);
                        $.confirm({
                            title: '',
                            content: "Please Enter Quantity Below " + data['onhand_qty'],
                            icon: 'fa fa-exclamation',
                            theme: 'modern',
                            closeIcon: true,
                            animation: 'scale',
                            type: 'blue',
                            buttons: {
                                Ok: function(){
                                }
                            }
                        });
                    } else if(data['error'] == 0){
                        ths.val(0);
                        ths.closest('tr').find('.gj_off_qty').val('1');
                        ths.closest('tr').find('.gj_off_att_name').attr('disabled', true);
                        $.confirm({
                            title: '',
                            content: 'Please Select Product And Then Enter Quantity',
                            icon: 'fa fa-exclamation',
                            theme: 'modern',
                            closeIcon: true,
                            animation: 'scale',
                            type: 'blue',
                            buttons: {
                                Ok: function(){
                                }
                            }
                        });
                    } else if (data['error'] == 1){
                        ths.closest('tr').find('.gj_off_qty').val('1');
                        ths.closest('tr').find('.gj_offer_cost').val(data['offer_cost']);
                        ths.closest('tr').find('.gj_offer_tax_amount').val(data['offer_tax_amount']);
                        ths.closest('tr').find('.gj_off_price').val(data['offer_price']);
                    }
                }
            });
        } else {
            $.confirm({
                title: '',
                content: 'Please Select Attribute Value!',
                icon: 'fa fa-exclamation',
                theme: 'modern',
                closeIcon: true,
                animation: 'scale',
                type: 'blue',
                buttons: {
                    Ok: function(){
                    }
                }
            });
        }
    });

    $('body').on('change','.gj_off_qty',function() {
        var qty = parseInt($(this).val());
        var ths = $(this);
        var id = 0;
        var att_name = 0;
        var att_value = 0;

        if($(this).closest('tr').find('.gj_off_product_id').val()) {
            var id = $(this).closest('tr').find('.gj_off_product_id').val();
        }

        if($(this).closest('tr').find('.gj_off_att_name').val()) {
            var att_name = $(this).closest('tr').find('.gj_off_att_name').val();
        }

        if($(this).closest('tr').find('.gj_off_att_value').val()) {
            var att_value = $(this).closest('tr').find('.gj_off_att_value').val();
        }

        if(id && (id != 0) && qty && (qty != 0)) {
            $.ajax({
                type: 'post',
                url: '{{url('/check_stock')}}',
                data: {qty: qty, id: id, att_name: att_name, att_value: att_value, type: 'check_stock'},
                dataType:"json",   
                success: function(data){
                    if(data['error'] == 3){
                        $.confirm({
                            title: '',
                            content: "Out of Stock, " + data['onhand_qty'] + " Products",
                            icon: 'fa fa-exclamation',
                            theme: 'modern',
                            closeIcon: true,
                            animation: 'scale',
                            type: 'blue',
                            buttons: {
                                Ok: function(){
                                }
                            }
                        });
                        ths.val('1');
                    } else if(data['error'] == 2){
                        $.confirm({
                            title: '',
                            content: "Please Enter Quantity Below " + data['onhand_qty'],
                            icon: 'fa fa-exclamation',
                            theme: 'modern',
                            closeIcon: true,
                            animation: 'scale',
                            type: 'blue',
                            buttons: {
                                Ok: function(){
                                }
                            }
                        });
                        ths.val('1');
                    } else if(data['error'] == 0){
                        $.confirm({
                            title: '',
                            content: 'Please Select Product And Then Enter Quantity',
                            icon: 'fa fa-exclamation',
                            theme: 'modern',
                            closeIcon: true,
                            animation: 'scale',
                            type: 'blue',
                            buttons: {
                                Ok: function(){
                                }
                            }
                        });
                        ths.val('');
                    }
                }
            });
        } else {
            $.confirm({
                title: '',
                content: 'Please Select Product And Enter Valid Quantity',
                icon: 'fa fa-exclamation',
                theme: 'modern',
                closeIcon: true,
                animation: 'scale',
                type: 'blue',
                buttons: {
                    Ok: function(){
                    }
                }
            });
        }
    });
</script>

<script type="text/javascript">
    $(document).ready(function(){
        var counter = 2;
        $("#addButton").click(function () {
            var newTextBoxDiv = $(document.createElement('tr')).attr("id", 'gj_tr_off_' + counter);
            newTextBoxDiv.after().html('<td><select id="productid_' + counter + '" name="product_id[]" class="form-control gj_off_product_id"><option value="0" selected>Select Products</option><?php echo $off_product; ?></select></td><td><select id="attname_' + counter + '" name="att_name[]" class="form-control gj_off_att_name"><option value="">Select Attribute Name</option></select></td><td><select id="attvalue_' + counter + '" name="att_value[]" class="form-control gj_off_att_value"><option value="">Select Attribute Value</option></select></td><td><input type="number" class="form-control gj_off_qty" placeholder="Enter Assign Quantity" name="qty[]" id="qty_' + counter + '"><input type="hidden" class="form-control gj_offer_cost" placeholder="Enter Assign Cost" name="offer_cost[]" id="offer_cost_' + counter + '" value ="0.00"><input type="hidden" class="form-control gj_offer_tax_amount" placeholder="Enter Assign Tax Amount" name="offer_tax_amount[]" id="offer_tax_amount_' + counter + '" value ="0.00"><input type="hidden" class="form-control gj_off_price" placeholder="Enter Assign Price" name="offer_price[]" id="offerprice_' + counter + '" value ="0.00"></td><td><select id="type_' + counter + '" name="type[]" class="form-control gj_off_type"><option value="0" selected>Select Offer Product Type</option><option value="1">X-Products</option><option value="2">Y-Products</option></select></td><td><button type="button" id="removeButton_' + counter + '" class="gj_off_rem"><i class="fa fa-trash"></i></button></td>');
            // newTextBoxDiv.appendTo("#TextBoxesGroup");
            newTextBoxDiv.appendTo("#gj_att_bdy");
            counter++;
        });

        $('body').on('click','.gj_off_rem',function() {
            if(counter==2){
                $.confirm({
                    title: '',
                    content: 'No more textbox to remove!',
                    icon: 'fa fa-ban',
                    theme: 'modern',
                    closeIcon: true,
                    animation: 'scale',
                    type: 'red',
                    buttons: {
                        Ok: function(){
                        }
                    }
                });
                return false;
            }   
        
            counter--;
            $(this).closest('tr').remove();
        });
    });
</script>


<script type="text/javascript">
    $('.gj_form_datetime').datetimepicker({
        //language:  'fr',
        weekStart: 1,
        todayBtn:  1,
        autoclose: 1,
        todayHighlight: 1,
        startView: 2,
        forceParse: 0,
        minView: 0,
        maxView: 1,
        showMeridian: 1
    });
</script>

<!-- Editor Script Start -->
    <script src="https://cdn.ckeditor.com/4.11.4/standard/ckeditor.js"></script>

    <script>
        CKEDITOR.replace( 'grab_offer' );
    </script>
<!-- Editor Script End -->
@endsection