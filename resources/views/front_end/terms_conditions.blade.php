<?php 
$banner_path = 'images/banner_image';
$main_cat_path = 'images/main_cat_image';
$sub_cat_path = 'images/sub_cat_image';
$product_path = 'images/featured_products';
$noimage = \DB::table('noimage_settings')->first();
$noimage_path = 'images/noimage';
?>
@extends('layouts.frontend')
@section('title', 'Terms & Conditions')

@section('content')
<!-- Pages SECTION START -->
<section class="gj_tc_sec">
    <div class="container">
        <div class="row">
            <div class="col-sm-12">
                <div class="contz">
                    <div class="gj_terms_cnt">
                        
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- Pages SECTION END -->
<?php 
    $cms_page = htmlentities($terms->page_data, ENT_QUOTES);                 
    $cms_page = preg_replace( "/\r|\n/", "", $cms_page);
    $cms_page = html_entity_decode($cms_page);
?>
<script type="text/javascript">
    $(document).ready(function(){
        $('.gj_terms_cnt').html('<?php echo $cms_page; ?>');
    });
</script>
@endsection