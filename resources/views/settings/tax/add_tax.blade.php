@extends('layouts.master')
@section('title', 'Add GST Tax')
@section('content')
<section class="gj_tax_setting">
    <div class="row gj_row">
        <div class="col-md-3 col-sm-3 col-xs-12">
            @include('layouts.sidebar')
        </div>

        <div class="col-md-9 col-sm-9 col-xs-12">
            <!-- <div class="row">
                <div class="col-lg-12">
                    <ul class="breadcrumb">
                        <li class=""><a> Home  </a></li>
                        <li class="active"><a> Add GST Tax  </a></li>
                    </ul>
                </div>
            </div> -->

            <div class="gj_box dark">
                @if(Session::has('message'))
                    <p class="alert {{ Session::get('alert-class', 'alert-info') }}">{{ Session::get('message') }}</p>
                @endif
                <header>
                    <div class="gj_icons"><i class="fa fa-edit"></i></div>
                    <h5 class="gj_heading"> Add GST Tax  </h5>
                </header>

                <div class="col-md-12">
                    {{ Form::open(array('url' => 'add_tax','class'=>'gj_tax_form','files' => true)) }}
                        <div class="form-group">
                            {{ Form::label('main_cat_name', 'Category Name') }}
                            <span class="error">* 
                                @if ($errors->has('main_cat_name'))
                                    {{ $errors->first('main_cat_name') }}
                                @endif
                            </span>

                            <?php 
                                $opt = '';
                                if(isset($main_cat) && (count($main_cat) != 0)){
                                    foreach ($main_cat as $key => $value) {
                                        $opt.='<option value="'.$value->id.'">'.$value->main_cat_name.'</option>';
                                    }
                                } 
                            ?>
                            <select id="main_cat_name" name="main_cat_name" class="form-control">
                                <option value="0" selected>Select Category</option>
                                <?php echo $opt; ?>
                            </select>
                        </div>                         

                        <div class="form-group">
                            {{ Form::label('tax', 'GST Tax (%)') }}
                            <span class="error">* 
                                @if ($errors->has('tax'))
                                    {{ $errors->first('tax') }}
                                @endif
                            </span>

                            {{ Form::text('tax', Input::old('tax'), array('class' => 'form-control gj_tax','placeholder' => 'Enter tax in Percentage')) }}
                        </div>

                        {{ Form::submit('Update', array('class' => 'btn btn-primary')) }}

                    {{ Form::close() }}
                </div>
            </div>
        </div>
    </div>
</section>

<script>
    $(document).ready(function() { 
        $('p.alert').delay(2000).slideUp(300); 
        $("#main_cat_name").select2();
    });
</script>
@endsection
