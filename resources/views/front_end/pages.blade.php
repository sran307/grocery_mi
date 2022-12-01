<?php 
$banner_path = 'images/banner_image';
$main_cat_path = 'images/main_cat_image';
$sub_cat_path = 'images/sub_cat_image';
$product_path = 'images/featured_products';
$noimage = \DB::table('noimage_settings')->first();
$noimage_path = 'images/noimage';
?>
@extends('groceryView.layouts.headerFooter')
@section('title', 'Pages')

@section('content')
<div class="wrapper">
    <!-- bread crumb start -->
    <div class="grocery-Breadcrumb">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{url('/')}}">Home / </a></li>
                            <li class="active"><a href="#">{{isset($cms->page_name)?$cms->page_name:''}}</a></li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
    </div>
     <!-- bread crumb end -->

    <!--<div class="scp-breadcrumb pull-right">
        <ul class="breadcrumb">
            <li><a href="{{url('/')}}">Home</a></li>
            <li class="active"><a href="#">{{isset($cms->page_name)?$cms->page_name:''}}</a></li>
        
        </ul>
    </div>-->
    <!-- Pages SECTION START -->
    <section class="section contenz gj_pages_sec">
        <div class="container">
            <div class="row">
                <div class="col-sm-12">
                    <div class="contz">
                        <h3> {{isset($cms->page_name)?$cms->page_name:''}} </h3>
                        <div class="gj_pages_cnt anjkwopzzqwqwz">
                            
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Pages SECTION END -->

</div>
<?php 
    $cms_page = htmlentities($cms->page_description, ENT_QUOTES);                 
    $cms_page = preg_replace( "/\r|\n/", "", $cms_page);
    $cms_page = html_entity_decode($cms_page);
?>

<script src="{{asset('assetsGrocery/js/jquery-3.3.1.min.js')}}"></script>
<script type="text/javascript">
    $(document).ready(function(){
        $('.gj_pages_cnt').html('<?php echo $cms_page; ?>');
    });
</script>
@endsection
