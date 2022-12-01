<?php
use App\Products;
use Illuminate\Http\Request;
use App\Http\Controllers\{
    UIController
};
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

/*Clear Cache*/
Route::get('/clear_cache', function() {
    $exitCode = Artisan::call('cache:clear');
});
/*Clear Cache*/

Route::get('/clear_config', function() {
			Artisan::call('config:clear');
			return "Config is cleared";
		});

/*Authentication Route Start*/
Route::get('/download_file', 'UsersController@download_file')->name('download-file');

Route::get('/admin', 'UsersController@Login')->name('admin');
Route::get('/merchant', 'UsersController@Login')->name('merchant');
Route::post('/admin', 'UsersController@CheckLogin')->name('check_login');
Route::post('/check_signin', 'UsersController@CheckSignInEmail')->name('check_signin');
Route::post('/check_signin_mob', 'UsersController@CheckSignInMobile')->name('check_signin_mob');
Route::post('/login_otp', 'UsersController@LoginOTP')->name('login_otp');
Route::get('/logout', 'UsersController@Logout')->name('logout');
Route::get('/forgot', 'UsersController@Forgot')->name('forgot');
Route::post('/forgot', 'UsersController@CheckForgot')->name('check_forgot');
Route::get('/reset', 'UsersController@Reset')->name('reset');
Route::post('/reset_password', 'UsersController@ResetPassword')->name('reset_password');
Route::get('/activation/{code}', 'UIController@Activation')->name('activation');
Route::get('/app_activation/{code}', 'UIController@AppActivation')->name('app_activation');
Route::get("/app_verify", "UIController@app_verify")->name("app_verify");
Route::get('/resend_url', 'UIController@ResendUrl')->name('resend_url');
Route::post('/resend_url', 'UIController@ResendActivateUrl')->name('resend_activate_url');
Route::get('/chk_act_question', 'UIController@ChkActQuestion')->name('chk_act_question');
Route::post('/chk_act_answer', 'UIController@ChkActAnswer')->name('chk_act_answer');
Route::get('/chk_repwd_question', 'UsersController@ChkRepwdQuestion')->name('chk_repwd_question');
Route::post('/chk_repwd_answer', 'UsersController@ChkRepwdAnswer')->name('chk_repwd_answer');
Route::get('/verify/{on}/{id}', 'UIController@Verify')->name('verify');
Route::post('/verify', 'UIController@CheckVerify')->name('checkverify');

Route::get('/change_password', 'UsersController@change_password')->name('change_password');

Route::get('/edit_profile', 'UsersController@EditProfile')->name('edit_profile');
Route::post('/edit_profile', 'UsersController@UpdateProfile')->name('update_profile');

Route::get('/manage_address', 'UsersController@manage_address')->name('manage_address');

Route::post('/store_address', 'UsersController@store_address')->name('store_address');

/*Authentication Route End*/

/* Front End Routes Start */
// Route::get('/', 'UIController@Home')->name('home');
Route::get('/', [UIController::class, 'Home'])->name('home');
Route::get('/demo', 'UIController@demo')->name('demo');
Route::get('/sell_on_ecambiar', 'UIController@SellOnEcambiar')->name('sell_on_ecambiar');
Route::post('/sell_on_ecambiar', 'UIController@StoreSellOnEcambiar')->name('store_sell_on_ecambiar');
Route::get('/signin', 'UIController@SignIn')->name('signin');
Route::get('/signup', 'UIController@SignUp')->name('signup');
Route::get('/redirect/{service}', 'SocialAuthController@redirect')->name('redirect');
Route::get('/callback/{service}', 'SocialAuthController@callback')->name('callback');
Route::post('/register', 'UIController@Register')->name('register');
// Route::post('/google_signin', 'UIController@GoogleSignin')->name('google_signin');

Route::get('/my_account', 'UIController@MyAccount')->name('my_account');



Route::post('/update_profile_image', 'UIController@update_profile_image')->name('update_profile_image');

Route::get('/my_account/view_order/{id}', 'UIController@ViewOrder')->name('my_view_orders');
Route::get('/my_account/track_order/{id}', 'UIController@TrackOrder')->name('my_track_orders');
Route::get('/my_account/live_track_order/{id}', 'UIController@LiveTrackOrder')->name('live_track_order');
Route::get('/my_account/review_order/{id}', 'UIController@ReviewOrder')->name('my_review_orders');
Route::get('/my_account/my_view_return_order/{id}', 'UIController@MyViewReturnOrder')->name('my_view_return_order');
Route::post('/customer_cancel_order', 'UIController@CustomerCancelOrder')->name('customer_cancel_order');
Route::get('/customer_return_order/{id}', 'UIController@CustomerReturnOrder')->name('customer_return_order');
Route::post('/customer_return_order/', 'UIController@SaveReturnOrder')->name('save_return_order');
Route::post('/send_feedback', 'UIController@SendFeedBack')->name('send_feedback');
Route::post('/submit_review', 'UIController@SubmitReview')->name('submit_review');
Route::post('/send_admin_report', 'UIController@send_admin_report')->name('send_admin_report');

Route::get('/my_account/report_admin/{id}', 'UIController@report_admin')->name('report_admin');

Route::post('/product_search', 'UIController@product_search')->name('product_search');
Route::get('/main_search/', 'UIController@MainSearch')->name('main_search');
Route::get('/all_products/', 'UIController@AllProducts')->name('all_products');
Route::get('/build_your_pc/', 'UIController@build_your_pc')->name('build_your_pc');

Route::post('/render_action/', 'UIController@render_action')->name('render_action');

Route::get('/all_cat_products/{main_cat}', 'UIController@AllCatProducts')->name('all_cat_products');
Route::get('/all_filter_products', 'UIController@AllFilterProducts')->name('all_filter_products');
Route::get('/value_filter_products/{id}', 'UIController@ValueFilterProducts')->name('value_filter_products');
Route::get('/sort_filter_products', 'UIController@SortFilterProducts')->name('sort_filter_products');
Route::get('/offer_products/', 'UIController@OfferProducts')->name('offer_products');
Route::get('/offer_products/{id}', 'UIController@OfferProductsDetails')->name('offer_products_dets');
Route::get('/sub_category/{main_cat}', 'UIController@SubCategory')->name('sub_category');
Route::get('/sub_sub_category/{sub_cat}', 'UIController@SubSubCategory')->name('sub_sub_category');
Route::get('/sub_sub_category/products/{sub_sub_cat}', 'UIController@SubSubCategoryProducts')->name('sub_sub_category_products');
Route::get('/category/products/{main_cat}', 'UIController@CategoryProducts')->name('category_products');
Route::get('/brands/products/{id}', 'UIController@BrandsProducts')->name('brands_products');
Route::get('/view_products/{id}', 'UIController@ViewProducts')->name('view_products');
Route::get('/tag_products/{id}', 'UIController@TagProducts')->name('tag_products');
Route::post('/attributes_image', 'UIController@AttributesImage')->name('attributes_image');
Route::get('/pages/{name}', 'UIController@Pages')->name('pages');
Route::get('/terms_conditions', 'UIController@Terms')->name('terms_conditions');
Route::post('/send_otp', 'UIController@send_otp')->name('send_otp');
Route::post('/verify_otp', 'UIController@verify_otp')->name('verify_otp');
Route::post('/login_otp', 'UIController@login_otp')->name('login_otp');
Route::post('/verify_login_otp', 'UIController@verify_login_otp')->name('verify_login_otp');

Route::get('/about', 'UIController@About')->name('about');
Route::get('/contact', 'UIController@Contact')->name('contact');
Route::post('/contact', 'UIController@StoreContact')->name('store_contact');
Route::get('/how_to_find_us', 'UIController@HowToFindUs')->name('how_to_find_us');
Route::get('/privacy', 'UIController@Privacy')->name('privacy');
Route::get('/disclaimer', 'UIController@Disclaimer')->name('disclaimer');
Route::post('/news_letters', 'UIController@NewsLetters')->name('news_letters');
Route::get('/unsubcribe/{id}', 'UIController@UnSubscribeNewsLetters')->name('unsubcribe');
Route::post('/add_to_cart', 'UIController@AddToCart')->name('add_to_cart');
Route::post('/add_to_list', 'UIController@add_to_list')->name('add_to_list');

Route::post('/remove_list', 'UIController@remove_list')->name('remove_list');

Route::post('/offer_add_to_cart', 'UIController@OfferAddToCart')->name('offer_add_to_cart');
Route::post('/delete_cart', 'UIController@DeleteCart')->name('delete_cart');
Route::get('/cart', 'UIController@Cart')->name('cart');
Route::get('/get_orders', 'UIController@get_orders')->name('get_orders');
Route::delete('/delete_address/{id}', 'UsersController@delete_address')->name('address.del');

Route::post('/setAddress/{id}', 'UsersController@setAddress')->name('address.set.default');

Route::post('/cart', 'UIController@CartSave')->name('cart_save');
Route::post('/data_billing', 'UIController@DataBilling')->name('data_billing');
Route::post('/check_onhand_qty', 'UIController@CheckOnHandQty')->name('check_onhand_qty');
Route::get('/wishlist', 'UIController@WishList')->name('wishlist');
Route::post('/wishlist', 'UIController@WishListSave')->name('wishlist_save');
Route::post('/delete_wishlist', 'UIController@DeleteWishList')->name('delete_wishlist');
Route::get('/checkout', 'UIController@Checkout')->name('checkout');
Route::post('/check_cut_off', 'UIController@CheckCutOffs')->name('check_cut_off');
Route::post('/checkout', 'UIController@CheckoutTrans')->name('checkout_trans');
Route::post('/checkout_verif', 'UIController@CheckoutVerif')->name('checkout_verif');
Route::post('/payment_start', 'UIController@PaymentStart')->name('payment_start');
Route::post('/payment_request', 'UIController@PaymentRequest')->name('payment_request');
Route::post('/payment_response', 'UIController@PaymentResponse')->name('payment_response');
Route::post('/update_qty', 'UIController@UpdateQty')->name('update_qty');
Route::post('/pincode_check', 'UIController@PincodeCheck')->name('pincode_check');
Route::post('/brand_auto_complete', 'UIController@BrandAutoComplete')->name('brand_auto_complete');
Route::post('/select_state', 'MerchantsController@SelectState')->name('select_state');
Route::post('/select_city', 'MerchantsController@SelectCity')->name('select_city');
Route::post('/select_att_vals', 'ProductsController@SelectAttVals')->name('select_att_vals');
/* Front End Routes End */


Route::group(['middleware' => 'check_login'], function () {
	/* Dashboard Routes Start */
	Route::get('/dashboard', 'DashboardController@Dashboard')->name('dashboard');
	/* Dashboard Routes End */

	/*User Route Start*/
	Route::get('/manage_user', 'UsersController@index')->name('manage_user');
	Route::get('/add_user', 'UsersController@create')->name('add_user');
	Route::post('/select_citys', 'UsersController@SelectCity')->name('select_citys');
	Route::post('/add_user', 'UsersController@store')->name('store_user');
	Route::get('/edit_user/{id}', 'UsersController@edit')->name('edit_user');
	

	Route::post('/edit_user', 'UsersController@update')->name('update_user');
	Route::get('/view_user/{id}', 'UsersController@view')->name('view_user');
	Route::post('/delete_user', 'UsersController@delete')->name('delete_user');
	Route::post('/delete_user_all', 'UsersController@DeleteAll')->name('delete_user_all');
	Route::get('/status_user/{id}', 'UsersController@StatusUser')->name('status_user');
	Route::get('/approve_user/{id}', 'UsersController@ApprovedUser')->name('approve_user');
	Route::post('/user_block', 'UsersController@UserBlock')->name('user_block');
	Route::post('/user_unblock', 'UsersController@UserUnblock')->name('user_unblock');
	Route::get('/my_profile', 'UsersController@MyProfile')->name('my_profile');
	/*User Route End*/

	/*Feed Back Route Start*/
	Route::get('/feedbacks', 'FeedBackController@index')->name('feedbacks');
	Route::get('/view_feedbacks/{id}', 'FeedBackController@view')->name('view_feedbacks');
	Route::post('/delete_feedbacks', 'FeedBackController@delete')->name('delete_feedbacks');
	Route::post('/delete_feedbacks_all', 'FeedBackController@DeleteAll')->name('delete_feedbacks_all');
	Route::get('/status_feedbacks/{id}', 'FeedBackController@StatusFeedbacks')->name('status_feedbacks');
	Route::post('/feedbacks_block', 'FeedBackController@FeedbacksBlock')->name('feedbacks_block');
	Route::post('/feedbacks_unblock', 'FeedBackController@FeedbacksUnblock')->name('feedbacks_unblock');
	/*Feed Back Route End*/

	/*Modules Route Start*/
	Route::get('/manage_modules', 'ModulesController@index')->name('manage_modules');
	Route::get('/add_modules', 'ModulesController@create')->name('add_modules');
	Route::post('/add_modules', 'ModulesController@store')->name('store_modules');
	Route::get('/edit_modules/{id}', 'ModulesController@edit')->name('edit_modules');
	Route::post('/edit_modules', 'ModulesController@update')->name('update_modules');
	Route::post('/delete_modules', 'ModulesController@delete')->name('delete_modules');
	Route::post('/delete_modules_all', 'ModulesController@DeleteAll')->name('delete_modules_all');
	/*Modules Route End*/

    /*Roles Route Start*/
    Route::get('/manage_role', 'RolesController@index')->name('manage_role');
    Route::get('/add_role', 'RolesController@create')->name('add_role');
    Route::post('/add_role', 'RolesController@store')->name('store_role');
    Route::get('/edit_role/{id}', 'RolesController@edit')->name('edit_role');
    Route::post('/edit_role', 'RolesController@update')->name('update_role');
    Route::post('/delete_role', 'RolesController@delete')->name('delete_role');
    Route::post('/delete_role_all', 'RolesController@DeleteAll')->name('delete_role_all');
    Route::get('/status_role/{id}', 'RolesController@Statusrole')->name('status_role');
    Route::post('/role_block', 'RolesController@roleBlock')->name('role_block');
    Route::post('/role_unblock', 'RolesController@roleUnblock')->name('role_unblock');

    Route::get('/user_previl', 'RolesController@UserPrivileges')->name('user_previl');
    Route::post('/user_previl', 'RolesController@SavePrivileges')->name('save_user_previl');
    Route::post('/select_user_previl', 'RolesController@SelectPrivileges')->name('select_user_previl');
    /*Roles Route End*/

	/*Account Setting Route Start*/
	Route::get('/manage_credits', 'CreditsManagementController@index')->name('manage_credits');
	Route::get('/add_credits/{id}', 'CreditsManagementController@create')->name('add_credits');
	Route::post('/add_credits', 'CreditsManagementController@store')->name('store_credits');
	
		/*build own pc Route Start*/
	Route::get('/admin_build_pc', 'AdminBuildPcController@index')->name('admin_build_pc');
 	Route::get('/delete_custom/{id}', 'AdminBuildPcController@delete_custom')->name('delete_custom');
 	Route::post('/build_pc_save', 'AdminBuildPcController@store')->name('build_pc_save');
    Route::post('/choose_sub_cat', 'AdminBuildPcController@SelectSubCat')->name('choose_sub_cat');


    Route::get('/manage_package_dimension', 'ManagePackageController@index')->name('manage_package_dimension');
    Route::get('/add_package_dimension', 'ManagePackageController@create')->name('add_package_dimension');
    Route::post('/store_package_dimension', 'ManagePackageController@store')->name('store_package_dimension');
    Route::get('/edit_package_dimension', 'ManagePackageController@edit')->name('edit_package_dimension');
    Route::post('/update_package_dimension', 'ManagePackageController@update')->name('update_package_dimension');

    Route::post('/delete_package', 'ManagePackageController@delete')->name('delete_package');

    Route::post('/package_unblock', 'ManagePackageController@packssUnblock')->name('package_unblock');

    Route::post('/packsBlock', 'ManagePackageController@packsBlock')->name('packsBlock');

    Route::post('/delete_pack_all', 'ManagePackageController@delete_pack_all')->name('delete_pack_all');

    Route::get('/status_package/{id}', 'ManagePackageController@StatusPackage')->name('status_package');

	/* Admin Commision Routes Start */
	Route::get('/manage_admin_comis', 'AdminCommisionController@index')->name('manage_admin_comis');
	Route::get('/orderby_admin_comis', 'AdminCommisionController@OrderByAdminCom')->name('orderby_admin_comis');
	Route::post('/status_comis', 'AdminCommisionController@StatusComis')->name('status_comis');
	Route::post('/remark_comis', 'AdminCommisionController@RemarkComis')->name('remark_comis');
	Route::get('/search_comis', 'AdminCommisionController@SearchComis')->name('search_comis');
	Route::post('/export_com_csv', 'AdminCommisionController@ExportComCSV')->name('export_com_csv');
	/* Admin Commision Routes End */

	/* Cashout Routes Start */
	Route::get('/manage_cashout', 'CashoutController@index')->name('manage_cashout');
	Route::get('/add_cashout', 'CashoutController@create')->name('add_cashout');
	Route::post('/add_cashout', 'CashoutController@store')->name('store_cashout');
	Route::get('/view_cashout/{id}', 'CashoutController@view')->name('view_cashout');
	Route::get('/make_pay/{id}', 'CashoutController@MakePay')->name('make_pay');
	Route::post('/process_pay', 'CashoutController@ProcessPay')->name('process_pay');
	Route::post('/export_cho_csv', 'CashoutController@ExportChoCSV')->name('export_cho_csv');
	Route::post('/search_cashout', 'CashoutController@SearchCashout')->name('search_cashout');
	/* Cashout Routes End */

    /* Admin Cashout Routes Start */
    Route::get('/manage_admin_cashout', 'AdminCashoutsController@index')->name('manage_admin_cashout');
    Route::get('/add_admin_cashout', 'AdminCashoutsController@create')->name('add_admin_cashout');
    Route::post('/add_admin_cashout', 'AdminCashoutsController@store')->name('store_admin_cashout');
    Route::get('/view_admin_cashout/{id}', 'AdminCashoutsController@view')->name('view_admin_cashout');
    Route::post('/export_admin_cashout_csv', 'AdminCashoutsController@ExportACCSV')->name('export_admin_cashout_csv');
    Route::post('/search_admin_cashout', 'AdminCashoutsController@SearchAdminCashout')->name('search_admin_cashout');
    Route::post('/select_vendor', 'AdminCashoutsController@SelectVendor')->name('select_vendor');
    Route::post('/select_credit_note', 'AdminCashoutsController@SelectCreditNote')->name('select_credit_note');
    Route::get('/remark_admin_cashout/{id}', 'AdminCashoutsController@remark')->name('remark_admin_cashout');
    Route::post('/remark_admin_cashout', 'AdminCashoutsController@storeremark')->name('store_remark_admin_cashout');
    /* Admin Cashout Routes End */
	/*Account Setting Route End*/

	/* Settings Routes Start */
	/*General Setting Route Start*/
	Route::get('/general_setting', 'GeneralSettingsController@create')->name('create_general_setting');
	Route::post('/general_setting', 'GeneralSettingsController@store')->name('store_general_setting');
	/*General Setting Route End*/

	/*Email Setting Route Start*/
	Route::get('/email_setting', 'EmailSettingsController@create')->name('create_email_setting');
	Route::post('/email_setting', 'EmailSettingsController@store')->name('store_email_setting');
	/*Email Setting Route End*/

	/*Email Setting Route Start*/
	Route::get('/widget_setting', 'WidgetController@create')->name('create_widget_setting');
	Route::post('/widget_setting', 'WidgetController@store')->name('store_widget_setting');
	/*Email Setting Route End*/

	/*Social Media Setting Route Start*/
	Route::get('/social_media_setting', 'SocialMediaSettingsController@create')->name('create_social_media_setting');
	Route::post('/social_media_setting', 'SocialMediaSettingsController@store')->name('store_social_media_setting');
	/*Social Media Setting Route End*/

	/*Manage Country Setting Route Start*/
	Route::get('/manage_country', 'CountriesManagementController@index')->name('index_manage_country');
	Route::get('/add_country', 'CountriesManagementController@create')->name('create_add_country');
	Route::post('/add_country', 'CountriesManagementController@store')->name('store_add_country');
	Route::get('/edit_country/{id}', 'CountriesManagementController@edit')->name('edit_edit_country');
	Route::post('/edit_country', 'CountriesManagementController@update')->name('update_edit_country');
	Route::get('/status_country_submit/{id}', 'CountriesManagementController@status_country')->name('status_country_submit');
	Route::post('/country_details', 'CountriesManagementController@CountryDetails')->name('country_details');
	Route::post('/country_block', 'CountriesManagementController@CountryBlock')->name('country_block');
	Route::post('/country_unblock', 'CountriesManagementController@CountryUnblock')->name('country_unblock');
	/*Manage Country Setting Route End*/

	/*Manage New Country Setting Route Start*/
	Route::get('/manage_all_country', 'CountriesManagementController@All')->name('index_manage_all_country');
	Route::get('/new_country', 'CountriesManagementController@NewCreate')->name('create_new_country');
	Route::post('/new_country', 'CountriesManagementController@NewStore')->name('store_new_country');
	Route::get('/edit_all_country/{id}', 'CountriesManagementController@NewEdit')->name('edit_edit_all_country');
	Route::post('/edit_all_country', 'CountriesManagementController@NewUpdate')->name('update_edit_all_country');
	Route::post('/delete_all_country', 'CountriesManagementController@NewDelete')->name('delete_all_country');
	Route::post('/delete_all_country_all', 'CountriesManagementController@NewDeleteAll')->name('delete_all_country_all');
	/*Manage New Country Setting Route End*/

	/*Payment Setting Route Start*/
	Route::get('/payment_setting', 'PaymentSettingsController@create')->name('create_payment_setting');
	Route::post('/payment_setting', 'PaymentSettingsController@store')->name('store_payment_setting');
	Route::post('/pcountry_details', 'PaymentSettingsController@CountryDetails')->name('pcountry_details');
	/*Payment Setting Route End*/

	/*Logo Setting Route Start*/
	Route::get('/logo_setting', 'LogoSettingsController@create')->name('create_logo_setting');
	Route::post('/logo_setting', 'LogoSettingsController@store')->name('store_logo_setting');
	/*Logo Setting Route End*/

	/*Fav Icon Setting Route Start*/
	Route::get('/favicon_setting', 'FaviconSettingsController@create')->name('create_favicon_setting');
	Route::post('/favicon_setting', 'FaviconSettingsController@store')->name('store_favicon_setting');
	/*Fav Icon Setting Route End*/

	/*No Image Setting Route Start*/
	Route::get('/noimage_setting', 'NoimageSettingsController@create')->name('create_noimage_setting');
	Route::post('/noimage_setting', 'NoimageSettingsController@store')->name('store_noimage_setting');
	/*No Image Setting Route End*/

	/*Banner Setting Route Start*/
	Route::get('/manage_banner_image', 'BannerImageSettingsController@index')->name('manage_banner_image');
	Route::get('/add_banner_image', 'BannerImageSettingsController@create')->name('add_banner_image');
	
		Route::get('/add_side_banner_image', 'BannerImageSettingsController@add_side_banner_image')->name('add_side_banner_image');

	Route::post('/add_side_banner_image', 'BannerImageSettingsController@add_side_banner_image_store')->name('add_side_banner_image');

		Route::get('/manage_side_banner_image', 'BannerImageSettingsController@manage_side_banner_image')->name('manage_side_banner_image');
	Route::get('/edit_side_banner_image/{id}', 'BannerImageSettingsController@edit_side_banner_image')->name('edit_side_banner_image');
	Route::post('/edit_side_banner_image', 'BannerImageSettingsController@edit_side_banner_image_store')->name('edit_side_banner_image');

	Route::post('/delete_side_banner_image', 'BannerImageSettingsController@delete_side_banner_image')->name('delete_side_banner_image');

	Route::post('/add_banner_image', 'BannerImageSettingsController@store')->name('store_banner_image');
	Route::get('/edit_banner_image/{id}', 'BannerImageSettingsController@edit')->name('edit_banner_image');
	Route::post('/edit_banner_image', 'BannerImageSettingsController@update')->name('update_banner_image');
	Route::post('/delete_banner_image', 'BannerImageSettingsController@delete')->name('delete_banner_image');
	Route::post('/delete_banner_image_all', 'BannerImageSettingsController@DeleteAll')->name('delete_banner_image_all');
	Route::get('/status_banner_image/{id}', 'BannerImageSettingsController@StatusBannerImage')->name('status_banner_image');
	Route::post('/banner_image_block', 'BannerImageSettingsController@BannerImageBlock')->name('banner_image_block');
	Route::post('/banner_image_unblock', 'BannerImageSettingsController@BannerImageUnblock')->name('banner_image_unblock');
	/*Banner Setting Route End*/

	/*Category Advertisement Settings Route Start*/
	Route::get('/manage_advertisement', 'CategoryAdvertisementSettingsController@index')->name('manage_advertisement');
	Route::get('/add_advertisement', 'CategoryAdvertisementSettingsController@create')->name('add_advertisement');
	Route::post('/add_advertisement', 'CategoryAdvertisementSettingsController@store')->name('store_advertisement');
	Route::get('/edit_advertisement/{id}', 'CategoryAdvertisementSettingsController@edit')->name('edit_advertisement');
	Route::post('/edit_advertisement', 'CategoryAdvertisementSettingsController@update')->name('update_advertisement');
	Route::post('/delete_advertisement', 'CategoryAdvertisementSettingsController@delete')->name('delete_advertisement');
	Route::post('/delete_advertisement_all', 'CategoryAdvertisementSettingsController@DeleteAll')->name('delete_advertisement_all');
	Route::get('/status_advertisement/{id}', 'CategoryAdvertisementSettingsController@StatusAdvertisement')->name('status_advertisement');
	Route::post('/advertisement_block', 'CategoryAdvertisementSettingsController@AdvertisementBlock')->name('advertisement_block');
	Route::post('/advertisement_unblock', 'CategoryAdvertisementSettingsController@AdvertisementUnblock')->name('advertisement_unblock');
	/*Category Advertisement Settings Route End*/

	/*Category Banner Settings Route Start*/
	Route::get('/manage_category_banner', 'CategoryBannerSettingsController@index')->name('manage_category_banner');
	Route::get('/add_category_banner', 'CategoryBannerSettingsController@create')->name('add_category_banner');
	Route::post('/add_category_banner', 'CategoryBannerSettingsController@store')->name('store_category_banner');
	Route::get('/edit_category_banner/{id}', 'CategoryBannerSettingsController@edit')->name('edit_category_banner');
	Route::post('/edit_category_banner', 'CategoryBannerSettingsController@update')->name('update_category_banner');
	Route::post('/delete_category_banner', 'CategoryBannerSettingsController@delete')->name('delete_category_banner');
	Route::post('/delete_category_banner_all', 'CategoryBannerSettingsController@DeleteAll')->name('delete_category_banner_all');
	Route::get('/status_category_banner/{id}', 'CategoryBannerSettingsController@StatusCategoryBanner')->name('status_category_banner');
	Route::post('/category_banner_block', 'CategoryBannerSettingsController@CategoryBannerBlock')->name('category_banner_block');
	Route::post('/category_banner_unblock', 'CategoryBannerSettingsController@CategoryBannerUnblock')->name('category_banner_unblock');
	/*Category Banner Settings Route End*/

	/*Colour Settings Route Start*/
	Route::get('/manage_color', 'ColorSettingsController@index')->name('manage_color');
	Route::get('/add_color', 'ColorSettingsController@create')->name('add_color');
	Route::post('/add_color', 'ColorSettingsController@store')->name('store_color');
	Route::get('/edit_color/{id}', 'ColorSettingsController@edit')->name('edit_color');
	Route::post('/edit_color', 'ColorSettingsController@update')->name('update_color');
	Route::post('/delete_color', 'ColorSettingsController@delete')->name('delete_color');
	Route::post('/delete_color_all', 'ColorSettingsController@DeleteAll')->name('delete_color_all');
	Route::get('/status_color/{id}', 'ColorSettingsController@StatusColor')->name('status_color');
	Route::post('/color_block', 'ColorSettingsController@ColorBlock')->name('color_block');
	Route::post('/color_unblock', 'ColorSettingsController@ColorUnblock')->name('color_unblock');
	/*Colour Settings Route End*/

	/*Size Settings Route Start*/
	Route::get('/manage_size', 'SizeSettingsController@index')->name('manage_size');
	Route::get('/add_size', 'SizeSettingsController@create')->name('add_size');
	Route::post('/add_size', 'SizeSettingsController@store')->name('store_size');
	Route::get('/edit_size/{id}', 'SizeSettingsController@edit')->name('edit_size');
	Route::post('/edit_size', 'SizeSettingsController@update')->name('update_size');
	Route::post('/delete_size', 'SizeSettingsController@delete')->name('delete_size');
	Route::post('/delete_size_all', 'SizeSettingsController@DeleteAll')->name('delete_size_all');
	Route::get('/status_size/{id}', 'SizeSettingsController@StatusSize')->name('status_size');
	Route::post('/size_block', 'SizeSettingsController@SizeBlock')->name('size_block');
	Route::post('/size_unblock', 'SizeSettingsController@SizeUnblock')->name('size_unblock');
	/*Size Settings Route End*/
    
    	Route::get('/manage_pincode', 'PincodeSettingsController@index')->name('manage_pincode');
    
        	Route::get('/add_pincode', 'PincodeSettingsController@create')->name('add_pincode');
        	Route::post('/store_pincode', 'PincodeSettingsController@store')->name('store_pincode');
        	Route::post('/update_pincode', 'PincodeSettingsController@update')->name('update_pincode');
        	Route::get('/edit_pincode/{id}', 'PincodeSettingsController@edit')->name('edit_pincode');
        	Route::get('/status_pincode/{id}', 'PincodeSettingsController@StatusSize')->name('status_pincode');

            	Route::post('/delete_pincode', 'PincodeSettingsController@delete')->name('delete_pincode');

    
    
	/*Capacity Settings Route Start*/
	Route::get('/manage_capacity', 'CapacitySettingsController@index')->name('manage_capacity');
	Route::get('/add_capacity', 'CapacitySettingsController@create')->name('add_capacity');
	Route::post('/add_capacity', 'CapacitySettingsController@store')->name('store_capacity');
	Route::get('/edit_capacity/{id}', 'CapacitySettingsController@edit')->name('edit_capacity');
	Route::post('/edit_capacity', 'CapacitySettingsController@update')->name('update_capacity');
	Route::post('/delete_capacity', 'CapacitySettingsController@delete')->name('delete_capacity');
	Route::post('/delete_capacity_all', 'CapacitySettingsController@DeleteAll')->name('delete_capacity_all');
	Route::get('/status_capacity/{id}', 'CapacitySettingsController@StatusCapacity')->name('status_capacity');
	Route::post('/capacity_block', 'CapacitySettingsController@CapacityBlock')->name('capacity_block');
	Route::post('/capacity_unblock', 'CapacitySettingsController@CapacityUnblock')->name('capacity_unblock');
	/*Capacity Settings Route End*/

	/*Attributes Fields Settings Route Start*/
	Route::get('/manage_att_fields', 'AttributesFieldsController@index')->name('manage_att_fields');
	Route::get('/add_att_fields', 'AttributesFieldsController@create')->name('add_att_fields');
	Route::post('/add_att_fields', 'AttributesFieldsController@store')->name('store_att_fields');
	Route::get('/edit_att_fields/{id}', 'AttributesFieldsController@edit')->name('edit_att_fields');
	Route::post('/edit_att_fields', 'AttributesFieldsController@update')->name('update_att_fields');
	Route::post('/delete_att_fields', 'AttributesFieldsController@delete')->name('delete_att_fields');
	Route::post('/delete_att_fields_all', 'AttributesFieldsController@DeleteAll')->name('delete_att_fields_all');
	Route::get('/status_att_fields/{id}', 'AttributesFieldsController@StatusAttFields')->name('status_att_fields');
	Route::post('/att_fields_block', 'AttributesFieldsController@AttFieldsBlock')->name('att_fields_block');
	Route::post('/att_fields_unblock', 'AttributesFieldsController@AttFieldsUnblock')->name('att_fields_unblock');
	/*Attributes Fields Settings Route End*/

	/*Attributes Settings Route Start*/
	Route::get('/manage_attributes', 'AttributesSettingsController@index')->name('manage_attributes');
	Route::get('/add_attributes', 'AttributesSettingsController@create')->name('add_attributes');
	Route::post('/add_attributes', 'AttributesSettingsController@store')->name('store_attributes');
	Route::get('/edit_attributes/{id}', 'AttributesSettingsController@edit')->name('edit_attributes');
	Route::post('/edit_attributes', 'AttributesSettingsController@update')->name('update_attributes');
	Route::post('/delete_attributes', 'AttributesSettingsController@delete')->name('delete_attributes');
	Route::post('/delete_attributes_all', 'AttributesSettingsController@DeleteAll')->name('delete_attributes_all');
	Route::get('/status_attributes/{id}', 'AttributesSettingsController@StatusAttributes')->name('status_attributes');
	Route::post('/attributes_block', 'AttributesSettingsController@AttributesBlock')->name('attributes_block');
	Route::post('/attributes_unblock', 'AttributesSettingsController@AttributesUnblock')->name('attributes_unblock');
	/*Attributes Settings Route End*/

	/*City Settings Route Start*/
	Route::get('/manage_city', 'CityManagementController@index')->name('manage_city');
	Route::get('/add_city', 'CityManagementController@create')->name('add_city');
	Route::post('/add_city', 'CityManagementController@store')->name('store_city');
	Route::get('/edit_city/{id}', 'CityManagementController@edit')->name('edit_city');
	Route::post('/edit_city', 'CityManagementController@update')->name('update_city');
	Route::post('/delete_city', 'CityManagementController@delete')->name('delete_city');
	Route::post('/delete_city_all', 'CityManagementController@DeleteAll')->name('delete_city_all');
	Route::get('/status_city/{id}', 'CityManagementController@StatusCity')->name('status_city');
	Route::post('/city_block', 'CityManagementController@CityBlock')->name('city_block');
	Route::post('/city_unblock', 'CityManagementController@CityUnblock')->name('city_unblock');
	Route::post('/city_default', 'CityManagementController@CityDefault')->name('city_default');
	Route::post('/state_details', 'CityManagementController@StateDetails')->name('state_details');
	/*City Settings Route End*/

	/*State Settings Route Start*/
	Route::get('/manage_state', 'StateManagementsController@index')->name('manage_state');
	Route::get('/add_state', 'StateManagementsController@create')->name('add_state');
	Route::post('/add_state', 'StateManagementsController@store')->name('store_state');
	Route::get('/edit_state/{id}', 'StateManagementsController@edit')->name('edit_state');
	Route::post('/edit_state', 'StateManagementsController@update')->name('update_state');
	Route::post('/delete_state', 'StateManagementsController@delete')->name('delete_state');
	Route::post('/delete_state_all', 'StateManagementsController@DeleteAll')->name('delete_state_all');
	Route::get('/status_state/{id}', 'StateManagementsController@StatusState')->name('status_state');
	Route::post('/state_block', 'StateManagementsController@StateBlock')->name('state_block');
	Route::post('/state_unblock', 'StateManagementsController@StateUnblock')->name('state_unblock');
	Route::post('/state_default', 'StateManagementsController@StateDefault')->name('state_default');
	/*State Settings Route End*/

	/*Category Management Settings Route Start*/
	Route::get('/manage_category', 'CategoryManagementSettingsController@index')->name('manage_category');
	Route::get('/add_category', 'CategoryManagementSettingsController@create')->name('add_category');
	Route::post('/add_category', 'CategoryManagementSettingsController@store')->name('store_category');
	Route::get('/edit_category/{id}', 'CategoryManagementSettingsController@edit')->name('edit_category');
	Route::post('/edit_category', 'CategoryManagementSettingsController@update')->name('update_category');
	Route::get('/status_category/{id}', 'CategoryManagementSettingsController@StatusMainCategory')->name('status_category');
	Route::post('/category_block', 'CategoryManagementSettingsController@MainCategoryBlock')->name('category_block');
	Route::post('/category_unblock', 'CategoryManagementSettingsController@MainCategoryUnblock')->name('category_unblock');
	Route::post('/home_view', 'CategoryManagementSettingsController@HomeView')->name('home_view');
	/*Category Management Settings Route End*/

	/*Sub Category Management Settings Route Start*/
	Route::get('/manage_sub_category/{id}', 'SubCategoryManagementSettingsController@index')->name('manage_sub_category');
	Route::get('/add_sub_category/{id}', 'SubCategoryManagementSettingsController@create')->name('add_sub_category');
	Route::post('/add_sub_category', 'SubCategoryManagementSettingsController@store')->name('store_sub_category');
	Route::get('/edit_sub_category/{id}', 'SubCategoryManagementSettingsController@edit')->name('edit_sub_category');
	Route::post('/edit_sub_category', 'SubCategoryManagementSettingsController@update')->name('update_sub_category');
	Route::get('/status_sub_category/{id}', 'SubCategoryManagementSettingsController@StatusMainCategory')->name('status_sub_category');
	Route::post('/sub_category_block', 'SubCategoryManagementSettingsController@MainCategoryBlock')->name('sub_category_block');
	Route::post('/sub_category_unblock', 'SubCategoryManagementSettingsController@MainCategoryUnblock')->name('sub_category_unblock');
	/*Sub Category Management Settings Route End*/

	/*Sub Sub Category Management Settings Route Start*/
	Route::get('/manage_sub_sub_category/{id}', 'SubSubCategoryManagementSettingsController@index')->name('manage_sub_sub_category');
	Route::get('/add_sub_sub_category/{id}', 'SubSubCategoryManagementSettingsController@create')->name('add_sub_sub_category');
	Route::post('/add_sub_sub_category', 'SubSubCategoryManagementSettingsController@store')->name('store_sub_sub_category');
	Route::get('/edit_sub_sub_category/{id}', 'SubSubCategoryManagementSettingsController@edit')->name('edit_sub_sub_category');
	Route::post('/edit_sub_sub_category', 'SubSubCategoryManagementSettingsController@update')->name('update_sub_sub_category');
	Route::post('/delete_sub_sub_category', 'SubSubCategoryManagementSettingsController@delete')->name('delete_sub_sub_category');
	Route::post('/delete_sub_sub_category_all', 'SubSubCategoryManagementSettingsController@DeleteAll')->name('delete_sub_sub_category_all');
	Route::get('/status_sub_sub_category/{id}', 'SubSubCategoryManagementSettingsController@StatusMainCategory')->name('status_sub_sub_category');
	Route::post('/sub_sub_category_block', 'SubSubCategoryManagementSettingsController@MainCategoryBlock')->name('sub_sub_category_block');
	Route::post('/sub_sub_category_unblock', 'SubSubCategoryManagementSettingsController@MainCategoryUnblock')->name('sub_sub_category_unblock');
	/*Sub Sub Category Management Settings Route End*/

	/*CMS PAGE Settings Route Start*/
	Route::get('/manage_cms_page', 'CMSPageManagementController@index')->name('manage_cms_page');
	Route::get('/add_cms_page', 'CMSPageManagementController@create')->name('add_cms_page');
	Route::post('/add_cms_page', 'CMSPageManagementController@store')->name('store_cms_page');
	Route::get('/edit_cms_page/{id}', 'CMSPageManagementController@edit')->name('edit_cms_page');
	Route::post('/edit_cms_page', 'CMSPageManagementController@update')->name('update_cms_page');
	Route::post('/delete_cms_page', 'CMSPageManagementController@delete')->name('delete_cms_page');
	Route::post('/delete_cms_page_all', 'CMSPageManagementController@DeleteAll')->name('delete_cms_page_all');
	Route::get('/status_cms_page/{id}', 'CMSPageManagementController@StatusCMSPage')->name('status_cms_page');
	Route::post('/cms_page_block', 'CMSPageManagementController@CMSPageBlock')->name('cms_page_block');
	Route::post('/cms_page_unblock', 'CMSPageManagementController@CMSPageUnblock')->name('cms_page_unblock');
	Route::get('/widget1', 'AboutUsCMSSettingsController@widget1')->name('widget1');
	Route::get('/edit_widget_1/{id}', 'AboutUsCMSSettingsController@edit_widget1')->name('edit_widget_1');
	Route::post('/update_widget1', 'AboutUsCMSSettingsController@update_widget1')->name('update_widget1');

	Route::get("delivery_manage", "AboutUsCMSSettingsController@delivery_manage")->name('delivery_manage');
	Route::get("add_delivery", "AboutUsCMSSettingsController@add_delivery")->name('add_delivery');
	Route::get("edit_delivery/{id}", "AboutUsCMSSettingsController@edit_delivery")->name('edit_delivery');
	Route::post("add_delivery_time", "AboutUsCMSSettingsController@add_delivery_time")->name('add_delivery_time');
	Route::post("edit_delivery_time", "AboutUsCMSSettingsController@edit_delivery_time")->name('edit_delivery_time');
	Route::post("delete_delivery", "AboutUsCMSSettingsController@delete_delivery")->name('delete_delivery');
	/*CMS PAGE Settings Route End*/

	//heading management route start
	Route::get("/manage_headings", "HeadingController@manage_headings")->name("manage_headings");
	Route::get("/edit_heading/{id}", "HeadingController@edit_heading")->name("edit_heading");
	Route::post("/update_heading", "HeadingController@update_heading")->name("update_heading");
	//heading management route end

	/*About Us CMS PAGE Settings Route Start*/
	Route::get('/manage_about_page', 'AboutUsCMSSettingsController@index')->name('manage_about_page');
	Route::get('/add_about_page', 'AboutUsCMSSettingsController@create')->name('add_about_page');
	Route::post('/add_about_page', 'AboutUsCMSSettingsController@store')->name('store_about_page');
	Route::get("/manage_about_1", "AboutUsCMSSettingsController@manage_about_1")->name("manage_about_1");
	Route::get("/manage_about_2", "AboutUsCMSSettingsController@manage_about_2")->name("manage_about_2");
	Route::get("/edit_about_widget_1/{id}", "AboutUsCMSSettingsController@edit_about_widget_1")->name("edit_about_widget_1");
	Route::get("/edit_widget_2/{id}", "AboutUsCMSSettingsController@edit_widget_2")->name("edit_widget_2");
	Route::post("/update_widget_1", "AboutUsCMSSettingsController@update_widget_1")->name("update_widget_1");
	Route::post("/update_widget_2", "AboutUsCMSSettingsController@update_widget_2")->name("update_widget_2");
	
	Route::get('/widget1', 'AboutUsCMSSettingsController@widget1')->name('widget1');
	/*About Us CMS PAGE Settings Route End*/

    /*Disclaimers CMS PAGE Settings Route Start*/
    Route::get('/add_disclaimers', 'DisclaimersController@create')->name('add_disclaimers');
    Route::post('/add_disclaimers', 'DisclaimersController@store')->name('store_disclaimers');
    /*Disclaimers CMS PAGE Settings Route End*/
    //cms
    
        Route::get('/get_settings/{slug}', 'DisclaimersController@get_settings')->name('get_settings');
        Route::post('/add_update_ettings/{slug}', 'DisclaimersController@add_update_ettings')->name('add_update_ettings');

	/*Terms CMS PAGE Settings Route Start*/
	Route::get('/manage_terms', 'TermsCMSSettingsController@index')->name('manage_terms');
	Route::get('/terms', 'TermsCMSSettingsController@create')->name('terms');
	Route::post('/terms', 'TermsCMSSettingsController@store')->name('store_terms');
	/*Terms CMS PAGE Settings Route End*/

	/*Tax Management Route Start*/
	Route::get('/manage_tax', 'TaxManagementController@index')->name('manage_tax');
	Route::get('/add_tax', 'TaxManagementController@create')->name('add_tax');
	Route::post('/add_tax', 'TaxManagementController@store')->name('store_tax');
	Route::get('/edit_tax/{id}', 'TaxManagementController@edit')->name('edit_tax');
	Route::post('/edit_tax', 'TaxManagementController@update')->name('update_tax');
	Route::post('/delete_tax', 'TaxManagementController@delete')->name('delete_tax');
	Route::post('/delete_tax_all', 'TaxManagementController@DeleteAll')->name('delete_tax_all');
	Route::get('/status_tax/{id}', 'TaxManagementController@StatusTax')->name('status_tax');
	Route::post('/tax_block', 'TaxManagementController@TaxBlock')->name('tax_block');
	Route::post('/tax_unblock', 'TaxManagementController@TaxUnblock')->name('tax_unblock');
	/*Tax Management Route End*/

	/*Tax Cut OFF Management Route Start*/
	Route::get('/manage_cutoff', 'TaxCutoffController@index')->name('manage_cutoff');
	Route::get('/add_cutoff', 'TaxCutoffController@create')->name('add_cutoff');
	Route::post('/add_cutoff', 'TaxCutoffController@store')->name('store_cutoff');
	Route::get('/edit_cutoff/{id}', 'TaxCutoffController@edit')->name('edit_cutoff');
	Route::post('/edit_cutoff', 'TaxCutoffController@update')->name('update_cutoff');
	Route::post('/delete_cutoff', 'TaxCutoffController@delete')->name('delete_cutoff');
	Route::post('/delete_cutoff_all', 'TaxCutoffController@DeleteAll')->name('delete_cutoff_all');
	Route::get('/status_cutoff/{id}', 'TaxCutoffController@StatusCutoff')->name('status_cutoff');
	Route::post('/cutoff_block', 'TaxCutoffController@CutoffBlock')->name('cutoff_block');
	Route::post('/cutoff_unblock', 'TaxCutoffController@CutoffUnblock')->name('cutoff_unblock');
	/*Tax Cut OFF Management Route End*/

    /*COD Management Route Start*/
    Route::get('/manage_cod', 'CodController@index')->name('manage_cod');
    Route::get('/add_cod', 'CodController@create')->name('add_cod');
    Route::post('/add_cod', 'CodController@store')->name('store_cod');
    Route::get('/edit_cod/{id}', 'CodController@edit')->name('edit_cod');
    Route::post('/edit_cod', 'CodController@update')->name('update_cod');
    Route::post('/delete_cod', 'CodController@delete')->name('delete_cod');
    Route::post('/delete_cod_all', 'CodController@DeleteAll')->name('delete_cod_all');
    Route::get('/status_cod/{id}', 'CodController@Statuscod')->name('status_cod');
    Route::post('/cod_block', 'CodController@codBlock')->name('cod_block');
    Route::post('/cod_unblock', 'CodController@codUnblock')->name('cod_unblock');
    /*COD Management Route End*/

	/*Login Security Route Start*/
	Route::get('/manage_secure', 'LoginSecurityController@index')->name('manage_secure');
	Route::get('/add_secure', 'LoginSecurityController@create')->name('add_secure');
	Route::post('/add_secure', 'LoginSecurityController@store')->name('store_secure');
	Route::get('/edit_secure/{id}', 'LoginSecurityController@edit')->name('edit_secure');
	Route::post('/edit_secure', 'LoginSecurityController@update')->name('update_secure');
	Route::post('/delete_secure', 'LoginSecurityController@delete')->name('delete_secure');
	Route::post('/delete_secure_all', 'LoginSecurityController@DeleteAll')->name('delete_secure_all');
	Route::get('/status_secure/{id}', 'LoginSecurityController@StatusSecure')->name('status_secure');
	Route::post('/secure_block', 'LoginSecurityController@SecureBlock')->name('secure_block');
	Route::post('/secure_unblock', 'LoginSecurityController@SecureUnblock')->name('secure_unblock');
	/*Login Security Route End*/

	/* Settings Routes End */

	/* Merchants Routes Start */
	Route::get('/merchant_dashboard', 'MerchantsController@dashboard')->name('merchant_dashboard');
	Route::get('/manage_merchant', 'MerchantsController@index')->name('manage_merchant');
	Route::get('/add_merchant', 'MerchantsController@create')->name('add_merchant');
	Route::post('/add_merchant', 'MerchantsController@store')->name('store_merchant');
	Route::get('/view_merchant/{id}', 'MerchantsController@view')->name('view_merchant');
    Route::get('/edit_merchant/{id}', 'MerchantsController@edit')->name('edit_merchant');
	Route::post('/edit_merchant', 'MerchantsController@update')->name('update_merchant');
	Route::get('/status_merchant/{id}', 'MerchantsController@StatusMerchant')->name('status_merchant');
	Route::get('/approve_merchant/{id}', 'MerchantsController@ApproveMerchant')->name('approve_merchant');
	Route::post('/merchant_block', 'MerchantsController@MerchantBlock')->name('merchant_block');
	Route::post('/merchant_unblock', 'MerchantsController@MerchantUnblock')->name('merchant_unblock');
	/* Merchants Routes End */

	/* Stores Routes Start */
	Route::get('/manage_store/{id}', 'StoreController@index')->name('manage_store');
	Route::get('/add_store/{id}', 'StoreController@create')->name('add_store');
	Route::post('/add_store', 'StoreController@store')->name('store_store');
	Route::get('/edit_store/{id}', 'StoreController@edit')->name('edit_store');
	Route::post('/edit_store', 'StoreController@update')->name('update_store');
	Route::get('/status_store/{id}', 'StoreController@StatusStore')->name('status_store');
	Route::post('/store_block', 'StoreController@StoreBlock')->name('store_block');
	Route::post('/store_unblock', 'StoreController@StoreUnblock')->name('store_unblock');
	/* Stores Routes End */

	/*Tag Route Start*/
	Route::get('/manage_tag', 'TagsController@index')->name('manage_tag');
	Route::get('/add_tag', 'TagsController@create')->name('add_tag');
	Route::post('/add_tag', 'TagsController@store')->name('store_tag');
	Route::get('/edit_tag/{id}', 'TagsController@edit')->name('edit_tag');
	Route::post('/edit_tag', 'TagsController@update')->name('update_tag');
	Route::post('/delete_tag', 'TagsController@delete')->name('delete_tag');
	Route::post('/delete_tag_all', 'TagsController@DeleteAll')->name('delete_tag_all');
	Route::get('/status_tag/{id}', 'TagsController@StatusTag')->name('status_tag');
	Route::post('/tag_block', 'TagsController@TagBlock')->name('tag_block');
	Route::post('/tag_unblock', 'TagsController@TagUnblock')->name('tag_unblock');
	/*Tag Route End*/

	/*Measurement Route Start*/
	Route::get('/manage_measurement', 'MeasurementUnitsController@index')->name('manage_measurement');
	Route::get('/add_measurement', 'MeasurementUnitsController@create')->name('add_measurement');
	Route::post('/add_measurement', 'MeasurementUnitsController@store')->name('store_measurement');
	Route::get('/edit_measurement/{id}', 'MeasurementUnitsController@edit')->name('edit_measurement');
	Route::post('/edit_measurement', 'MeasurementUnitsController@update')->name('update_measurement');
	Route::post('/delete_measurement', 'MeasurementUnitsController@delete')->name('delete_measurement');
	Route::post('/delete_measurement_all', 'MeasurementUnitsController@DeleteAll')->name('delete_measurement_all');
	Route::get('/status_measurement/{id}', 'MeasurementUnitsController@StatusMeasurement')->name('status_measurement');
	Route::post('/measurement_block', 'MeasurementUnitsController@MeasurementBlock')->name('measurement_block');
	Route::post('/measurement_unblock', 'MeasurementUnitsController@MeasurementUnblock')->name('measurement_unblock');
	/*Measurement Route End*/

	/*Products Route Start*/
	Route::get('/select_notification', 'UIController@select_notification')->name('select_notification');

	Route::get('/manage_product', 'ProductsController@index')->name('manage_product');
	Route::get('/add_product', 'ProductsController@create')->name('add_product');
	Route::post('/select_sub_cat', 'ProductsController@SelectSubCat')->name('select_sub_cat');
	Route::post('/select_sub_sub_cat', 'ProductsController@SelectSubSubCat')->name('select_sub_sub_cat');
	Route::post('/add_product', 'ProductsController@store')->name('store_product');
	Route::get('/view_product/{id}', 'ProductsController@view')->name('view_product');
	Route::get('/edit_product/{id}', 'ProductsController@edit')->name('edit_product');
	Route::post('/edit_product', 'ProductsController@update')->name('update_product');
	Route::post('/delete_product', 'ProductsController@delete')->name('delete_product');
	Route::post('/delete_product_all', 'ProductsController@DeleteAll')->name('delete_product_all');
	Route::get('/status_product/{id}', 'ProductsController@StatusProduct')->name('status_product');
	Route::post('/product_block', 'ProductsController@ProductBlock')->name('product_block');
	Route::post('/product_unblock', 'ProductsController@ProductUnblock')->name('product_unblock');
	Route::post('/export_csv', 'ProductsController@ExportCSV')->name('export_csv');
	Route::post('/sold_product', 'ProductsController@SoldProduct')->name('sold_product');
	Route::post('/get_tax', 'ProductsController@GetTax')->name('get_tax');
    Route::get('/search_products', 'ProductsController@SearchProducts')->name('search_products');
	Route::get("manage_pro_widget", "ProductsController@manage_pro_widget")->name("manage_pro_widget");
	Route::get('/edit_pro_widget/{id}', 'ProductsController@edit_pro_widget')->name('edit_pro_widget');
	Route::post('/update_pro_widget', 'ProductsController@update_pro_widget')->name('update_pro_widget');
	// Route::post('/select_att_vals', 'ProductsController@SelectAttVals')->name('select_att_vals');
	/*Products Route End*/

	/*Offers Route Start*/
	Route::get('/manage_offer', 'OffersController@index')->name('manage_offer');
	Route::get('/add_offer', 'OffersController@create')->name('add_offer');
	Route::post('/check_stock', 'OffersController@CheckStock')->name('check_stock');
    Route::post('/select_atts', 'OffersController@SelectAtts')->name('select_atts');
	Route::post('/add_offer', 'OffersController@store')->name('store_offer');
	Route::get('/view_offer/{id}', 'OffersController@view')->name('view_offer');
	Route::get('/edit_offer/{id}', 'OffersController@edit')->name('edit_offer');
	Route::post('/edit_offer', 'OffersController@update')->name('update_offer');
	Route::post('/delete_offer', 'OffersController@delete')->name('delete_offer');
	Route::post('/delete_offer_all', 'OffersController@DeleteAll')->name('delete_offer_all');
	Route::get('/status_offer/{id}', 'OffersController@StatusOffer')->name('status_offer');
	Route::post('/offer_block', 'OffersController@OfferBlock')->name('offer_block');
	Route::post('/offer_unblock', 'OffersController@OfferUnblock')->name('offer_unblock');
	/*Offers Route End*/

    /*Offers Stock Trans Settings Route Start*/
    Route::get('/manage_offer_stock', 'OffersController@OfferStock')->name('manage_offer_stock');
    Route::post('/export_offer_stock_csv', 'OffersController@ExportOfferStockCSV')->name('export_offer_stock_csv');
    
    Route::get('/manage_offer_trans', 'OffersController@OfferTrans')->name('manage_offer_trans');
    Route::post('/export_offer_trans_csv', 'OffersController@ExportOfferTransCSV')->name('export_offer_trans_csv');
    /*Offers Stock Trans Settings Route End*/

	/*Review Route Start*/
	Route::get('/manage_review', 'ReviewController@index')->name('manage_review');
	Route::post('/delete_review', 'ReviewController@delete')->name('delete_review');
	Route::post('/delete_review_all', 'ReviewController@DeleteAll')->name('delete_review_all');
	Route::get('/status_review/{id}', 'ReviewController@StatusReview')->name('status_review');
	Route::post('/review_block', 'ReviewController@ReviewBlock')->name('review_block');
	Route::post('/review_unblock', 'ReviewController@ReviewUnblock')->name('review_unblock');
	/*Review Route End*/

	/*Stock Settings Route Start*/
	Route::get('/manage_stock', 'StockManagementController@index')->name('manage_stock');
    Route::get('/manage_stock/{filter}', 'StockManagementController@Filter')->name('filter_manage_stock');
	Route::get('/manage_substock/{id}', 'StockManagementController@SubStock')->name('manage_substock');
	Route::get('/add_stock', 'StockManagementController@create')->name('add_stock');
	Route::post('/add_stock', 'StockManagementController@store')->name('store_stock');
    Route::get('/damage_stock', 'StockManagementController@Damagecreate')->name('damage_stock');
    Route::post('/damage_stock', 'StockManagementController@Damagestore')->name('store_damage_stock');
	Route::post('/select_qty', 'StockManagementController@SelectQty')->name('select_qty');
	Route::get('/edit_stock/{id}', 'StockManagementController@edit')->name('edit_stock');
	Route::post('/edit_stock', 'StockManagementController@update')->name('update_stock');
	Route::post('/delete_stock', 'StockManagementController@delete')->name('delete_stock');
	Route::post('/delete_stock_all', 'StockManagementController@DeleteAll')->name('delete_stock_all');
	Route::get('/status_stock/{id}', 'StockManagementController@StatusStock')->name('status_stock');
	Route::post('/stock_block', 'StockManagementController@StockBlock')->name('stock_block');
	Route::post('/stock_unblock', 'StockManagementController@StockUnblock')->name('stock_unblock');
	Route::post('/export_stock_csv', 'StockManagementController@ExportStockCSV')->name('export_stock_csv');
	Route::get('/search_inv_stock', 'StockManagementController@SearchInvStock')->name('search_inv_stock');
	/*Stock Settings Route End*/

	/*Stock Trans Settings Route Start*/
	Route::get('/manage_stock_trans', 'StockTransactionsController@index')->name('manage_stock_trans');
	Route::post('/export_sck_trans_csv', 'StockTransactionsController@ExportStockCSV')->name('export_sck_trans_csv');
	/*Stock Trans Settings Route End*/

	/*Brands Route Start*/
	Route::get('/manage_brands', 'BrandsController@index')->name('manage_brands');
	Route::get('/add_brands', 'BrandsController@create')->name('add_brands');
	Route::post('/city_details', 'BrandsController@CityDetails')->name('city_details');
	Route::post('/add_brands', 'BrandsController@store')->name('store_brands');
	Route::get('/edit_brands/{id}', 'BrandsController@edit')->name('edit_brands');
	Route::post('/edit_brands', 'BrandsController@update')->name('update_brands');
	Route::post('/delete_brands', 'BrandsController@delete')->name('delete_brands');
	Route::post('/delete_brands_all', 'BrandsController@DeleteAll')->name('delete_brands_all');
	Route::get('/status_brands/{id}', 'BrandsController@StatusBrands')->name('status_brands');
	Route::post('/brands_block', 'BrandsController@BrandsBlock')->name('brands_block');
	Route::post('/brands_unblock', 'BrandsController@BrandsUnblock')->name('brands_unblock');
	/*Brands Route End*/

	/*Messages Route Start*/
	/*Enquery Route Start*/
	Route::get('/manage_enquiries', 'EnqueriesController@index')->name('manage_enquiries');
	Route::post('/delete_enquiries', 'EnqueriesController@delete')->name('delete_enquiries');
	Route::post('/delete_enquiries_all', 'EnqueriesController@DeleteAll')->name('delete_enquiries_all');
	Route::get('/status_enquiries/{id}', 'EnqueriesController@StatusEnquiries')->name('status_enquiries');
	Route::get('/view_enquiries/{id}', 'EnqueriesController@ViewEnquiries')->name('view_enquiries');
	Route::post('/enquiries_block', 'EnqueriesController@EnquiriesBlock')->name('enquiries_block');
	Route::post('/enquiries_unblock', 'EnqueriesController@EnquiriesUnblock')->name('enquiries_unblock');
	/*Enquery Route End*/

	/*News Letter Route Start*/
	Route::get('/manage_news_letters', 'NewsLetterController@index')->name('manage_news_letters');
	Route::post('/delete_news_letters', 'NewsLetterController@delete')->name('delete_news_letters');
	Route::post('/delete_news_letters_all', 'NewsLetterController@DeleteAll')->name('delete_news_letters_all');
	Route::get('/status_news_letters/{id}', 'NewsLetterController@StatusNewsLetters')->name('status_news_letters');
	Route::post('/news_letters_block', 'NewsLetterController@NewsLettersBlock')->name('news_letters_block');
	Route::post('/news_letters_unblock', 'NewsLetterController@NewsLettersUnblock')->name('news_letters_unblock');
	Route::get('/send_news_letters', 'NewsLetterController@SendNewsLetters')->name('send_news_letters');
	Route::post('/send_news_letters', 'NewsLetterController@MailedNewsLetters')->name('mailed_news_letters');
	/*News Letter Route End*/
	/*Messages Route End*/

	/*Transaction Route Start*/
	/*Orders Route Start*/
	Route::get('/all_orders', 'OrdersController@AllOrders')->name('all_orders');
	
	Route::get('/admin_report_products', 'OrdersController@admin_report_products')->name('admin_report_products');

	Route::get('/replace_all_orders', 'OrdersController@ReplaceOrders')->name('replace_all_orders');
	Route::get('/cancel_all_orders', 'OrdersController@CancelAllOrders')->name('cancel_all_orders');
	Route::get('/cancel_req_orders', 'OrdersController@CancelReqOrders')->name('cancel_req_orders');
	Route::get('/cancel_req_accept/{id}', 'OrdersController@CancelReqAccept')->name('cancel_req_accept');
	Route::get('/report_admin_action/{id}', 'OrdersController@report_admin_action')->name('report_admin_action');

    Route::post('/cancel_req_status/', 'OrdersController@CancelReqStatus')->name('cancel_req_status');
	Route::get('/new_orders/', 'OrdersController@NewOrders')->name('new_orders');
    Route::get('/create_credit_notes/', 'OrdersController@CreateCreditNotes')->name('create_credit_notes');
	Route::post('/new_orders/', 'OrdersController@SaveNewOrders')->name('save_new_orders');
	Route::post('/get_grv/', 'OrdersController@GetGRV')->name('get_grv');
    Route::post('/get_ex_grv/', 'OrdersController@GetEXGRV')->name('get_ex_grv');
    Route::post('/get_cn_grv/', 'OrdersController@GetCNGRV')->name('get_cn_grv');
	Route::get('/edit_orders/{id}', 'OrdersController@edit')->name('edit_orders');
	Route::post('/edit_orders', 'OrdersController@update')->name('update_orders');
	Route::get('/delivery_orders/{id}', 'OrdersController@EditDelivery')->name('delivery_orders');
	Route::post('/delivery_orders', 'OrdersController@UpdateDelivery')->name('update_delivery_orders');
	Route::get('/view_orders/{id}', 'OrdersController@view')->name('view_orders');
	Route::post('/status_orders', 'OrdersController@StatusOrders')->name('status_orders');
	Route::post('/paymentstatus_orders', 'OrdersController@PaymentStatusOrders')->name('paymentstatus_orders');
	Route::post('/delete_orders', 'OrdersController@delete')->name('delete_orders');
	Route::post('/delete_all_orders', 'OrdersController@DeleteAll')->name('delete_all_orders');
	Route::post('/check_tax', 'OrdersController@CheckTax')->name('check_tax');
	Route::post('/delete_odr_det', 'OrdersController@DeleteOrderDetails')->name('delete_odr_det');
	Route::post('/srh_products', 'OrdersController@SearchProducts')->name('srh_products');
	Route::post('/apply_products', 'OrdersController@ApplyProducts')->name('apply_products');
	Route::post('/export_csv_order', 'OrdersController@ExportCSV')->name('export_csv_order');
	Route::get('/search_order', 'OrdersController@SearchOrder')->name('search_order');

    Route::get('/manage_credit_notes', 'OrdersController@AllCreditNotes')->name('manage_credit_notes');
    Route::get('/view_credit_notes/{id}', 'OrdersController@ViewCreditNotes')->name('view_credit_notes');
    Route::post('/status_credit_notes', 'OrdersController@StatusCreditNotes')->name('status_credit_notes');
    Route::get('/transaction_summary', 'OrdersController@TransactionSummary')->name('transaction_summary');
    Route::get('/filter_transaction_summary', 'OrdersController@FilterTransactionSummary')->name('filter_transaction_summary');
	/*Orders Route End*/

	/*Return Orders Route Start*/
	Route::get('/return_all_orders', 'ReturnOrderController@ReturnAllOrders')->name('return_all_orders');
	Route::get('/view_return_orders/{id}', 'ReturnOrderController@view')->name('view_return_orders');
	Route::post('/return_sts_orders/', 'ReturnOrderController@ReturnStsOrders')->name('return_sts_orders');
    Route::get('/get_reject_return_orders/{id}', 'ReturnOrderController@GetReturnOrdersStatus')->name('get_reject_return_orders');
    Route::post('/reject_return_orders/', 'ReturnOrderController@ReturnOrdersStatus')->name('reject_return_orders');
    Route::post('/delete_ret_detz/', 'ReturnOrderController@ReturnOrdersDelete')->name('delete_ret_detz');
	Route::post('/export_return_order', 'ReturnOrderController@ExportCSV')->name('export_return_order');
	/*Return Orders Route End*/

	/*GRV Return Orders Route Start*/
	Route::get('/grv_orders', 'GrvOrdersController@GRVOrders')->name('grv_orders');
	Route::get('/create_grv_orders/{id}', 'GrvOrdersController@CreateGRVOrders')->name('create_grv_orders');
	Route::post('/create_grv_orders', 'GrvOrdersController@StoreGRVOrders')->name('store_grv_orders');
	Route::get('/view_grv_orders/{id}', 'GrvOrdersController@view')->name('view_grv_orders');
	Route::post('/grv_sts_orders/', 'GrvOrdersController@GRVStsOrders')->name('grv_sts_orders');
	Route::get('/edit_grv_orders/{id}', 'GrvOrdersController@edit')->name('edit_grv_orders');
	Route::post('/edit_grv_orders', 'GrvOrdersController@update')->name('update_grv_orders');
	Route::post('/export_grv_order', 'GrvOrdersController@ExportCSV')->name('export_grv_order');
	/*GRV Return Orders Route End*/

	/*Courier Route Start*/
	Route::get('/courier_track', 'CourierTrackController@AllOrders')->name('courier_track');
	Route::get('/view_courier_track/{id}', 'CourierTrackController@view')->name('view_courier_track');
	Route::post('/export_co_csv_order', 'CourierTrackController@ExportCourierCSV')->name('export_co_csv_order');
	Route::get('/search_cou_order', 'CourierTrackController@SearchCouOrder')->name('search_cou_order');
	/*Courier Route End*/

	/*Shipment Orders Route Start*/
	Route::get('/shipment_order', 'ShipmentController@AllShipment')->name('shipment_order');
	Route::get('/add_shipment_order', 'ShipmentController@create')->name('add_shipment_order');
	Route::post('/add_shipment_order', 'ShipmentController@store')->name('store_shipment_order');
	Route::get('/add_bulk_shipment_order', 'ShipmentController@BulkCreate')->name('add_bulk_shipment_order');
	Route::post('/add_bulk_shipment_order', 'ShipmentController@BulkStore')->name('store_bulk_shipment_order');
	Route::get('/view_shipment_order/{id}', 'ShipmentController@view')->name('view_shipment_order');
	Route::get('/edit_shipment_order/{id}', 'ShipmentController@edit')->name('edit_shipment_order');
	Route::post('/edit_shipment_order', 'ShipmentController@update')->name('update_shipment_order');
	Route::post('/delete_shipment_order', 'ShipmentController@delete')->name('delete_shipment_order');
	Route::post('/delete_all_shipment_order', 'ShipmentController@DeleteAll')->name('delete_all_shipment_order');
	Route::get('/search_shipment', 'ShipmentController@SearchShipment')->name('search_shipment');
	Route::post('/export_shipment_order', 'ShipmentController@ExportShipmentCSV')->name('export_shipment_order');
	/*Shipment Orders Route End*/

	/*Transaction Route Start*/
	Route::get('/all_transaction', 'OrdersTransactionsController@AllTransaction')->name('all_transaction');
	Route::get('/view_transaction/{id}', 'OrdersTransactionsController@view')->name('view_transaction');
	Route::post('/export_csv_trans', 'OrdersTransactionsController@ExportTransCSV')->name('export_csv_trans');
	Route::post('/delete_trans', 'OrdersTransactionsController@delete')->name('delete_trans');
	Route::post('/delete_all_trans', 'OrdersTransactionsController@DeleteAll')->name('delete_all_trans');
	Route::get('/search_trans', 'OrdersTransactionsController@SearchTrans')->name('search_trans');
	/*Transaction Route End*/
	
	/*Transaction Route End*/


	/*Merchant User Route Start*/
	/* Dashboard Routes Start */
	Route::get('/merchants_dashboard', 'DashboardController@MerchantsDashboard')->name('merchants_dashboard');
	/* Dashboard Routes End */

	/* Settings Routes Start */
	/*Account Setting Route Start*/
	Route::get('/account_setting', 'AccountSettingsController@create')->name('create_account_setting');
	Route::post('/account_setting', 'AccountSettingsController@store')->name('store_account_setting');
	/*Account Setting Route End*/
	/* Settings Routes End */

	/*Bank Details Route Start*/
	Route::get('/bank_details', 'BankDetailsController@index')->name('bank_details');
	Route::get('/add_bank_details', 'BankDetailsController@create')->name('add_bank_details');
	Route::post('/add_bank_details', 'BankDetailsController@store')->name('store_bank_details');
	Route::get('/view_bank_details/{id}', 'BankDetailsController@view')->name('view_bank_details');
    Route::get('/edit_bank_details/{id}', 'BankDetailsController@edit')->name('edit_bank_details');
	Route::post('/edit_bank_details', 'BankDetailsController@update')->name('update_bank_details');
	Route::post('/delete_bank_details', 'BankDetailsController@delete')->name('delete_bank_details');
	Route::post('/delete_all_bank_details', 'BankDetailsController@DeleteAll')->name('delete_all_bank_details');
	Route::post('/bank_default', 'BankDetailsController@BankDefault')->name('bank_default');
	/*Bank Details Route End*/

	/*Merchant User Route End*/
});

Route::get("push_notification", "ApiController@push_notification")->name("push_notification");