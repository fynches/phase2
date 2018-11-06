<?php
View::addExtension('html', 'php');
Route::get('/admin', function() {
	return Redirect::to("admin/login");
});

Route::get('/login', function() {
	return Redirect::to("admin/login");
});

Route::get ( '/redirect/{service}/{type}', 'SocialAuthController@redirect' );
Route::get ( '/callback/{service}', 'SocialAuthController@callback' );

Route::get('/checkout-event','Site\PublishController@CheckoutEvent');
Route::get('/checkout_confirm','Site\PublishController@checkout_confirm');
Route::post('/checkout-event','Site\PublishController@CheckoutEvent');
//Route::post('/confirm_payment','Site\PublishController@ConfirmPayment');
Route::post('/store-checkout-data','Site\PublishController@StoreCheckoutData');
Route::post('/confirm_gift','Site\PublishController@ConfirmGift');
Route::get('/duplicate-Email', 'Site\PublishController@DuplicateEmailCheck');
Route::get('/get-States/{id}', 'Site\PublishController@getStatesByCountry');
Route::get('/success-payment', 'Site\PublishController@PaymentSuccess');
Route::post('/contact-us/','Site\PublishController@ContactUs');
//Route::get('/{title}', 'Site\PublishController@publishPage');

Route::group(['middleware' => 'guest'], function () {	
	Route::auth();
	Route::post('/user/register', 'Site\UserController@create');
});

Route::prefix('site')->group(function() {
	Route::get('logout/', 'Auth\SiteLoginController@logout')->name('site.logout');
	
});
Route::post('/betaSignupUser','Site\HomeController@storeBetaUser');

Route::group(['middlewareGroups' => 'auth', 'after' => 'auth'], function () {
	Route::get('/', 'Site\HomeController@betaSignup');

	Route::get('/dashboard', 'Site\DashboardController@index');	
	Route::get('/validate-url', 'Site\EventController@validatePublishUrl');
	Route::get('/validate-event-url', 'Site\EventController@validateEventUrl');
	
	Route::get('/delete-event/{id}','Site\EventController@DeleteEvent');
	
	
	Route::get('/event', 'Site\EventController@create')->name('event');
	Route::get('/save-publish-event/{id}','Site\EventController@SaveAndPublishEvent');
	Route::post('/save-event','Site\EventController@saveEvent');
	Route::get('/save-and-publish-event','Site\EventController@saveAndPublishEvent');
	Route::post('/update-event','Site\EventController@UpdateEvent');
	Route::get('/create-event','Site\EventController@createEvent');
	Route::post('/store-event','Site\EventController@StoreAllSteps');	
	Route::post('/get-recomanded-experiences','Site\EventController@GetRecomandedExperiences');
	//Route::get('/search-experience-ajax/{page}','Site\EventController@searchExperienceAjax');
	Route::get('/search-experience-ajax','Site\EventController@searchExperienceAjax');
	Route::get('create-event/{page}','Site\EventController@createEvent');
	Route::get('/add-find-perfect-exp/{event_id}','Site\EventController@AddFindPerfectExp');
	Route::get('/share-event/{id}','Site\EventController@ShareEvent');
	Route::post('/share-event/','Site\EventController@ShareMyEvent');
	Route::post('/send-thankyou/','Site\EventController@SendThankyouEmail');
	
	
	//Experience
	Route::get('/create-experience/{id}', 'Site\ExperienceController@createExperience');
	Route::get('/experience/delete/{id}', 'Site\ExperienceController@DeleteExperience');
	Route::post('/create-experience','Site\ExperienceController@SaveExperience');
	Route::post('/update-experience','Site\ExperienceController@UpdateExperience');
	Route::post('/add-comment','Site\ExperienceController@AddComment');
	Route::post('/load-more-comment','Site\ExperienceController@LoadmoreComment');
	Route::post('/delete-comment','Site\ExperienceController@DeleteComment');
	Route::get('/stripeCurl','Site\ExperienceController@stripeCurl');
	Route::post('/add-experience','Site\ExperienceController@AddYelpExperience');
	Route::post('/search-and-add','Site\ExperienceController@searchAndAdd');
	Route::post('/search-and-remove','Site\ExperienceController@searchAndRemove');
	Route::post('/search-and-add-with-event','Site\ExperienceController@searchAndAddWithEventId');
	Route::get('/search-custom-exp/{title?}','Site\ExperienceController@searchCustomExp');
	Route::post('/delete-yelp-experience','Site\ExperienceController@DeleteYelpExp');
	Route::post('/delete-yelp-experience-session','Site\ExperienceController@DeleteYelpExpSession');
	
	Route::post('/search-experience','Site\PublishController@searchExperience');
	Route::get('/find-perfect-experience/{title}/{location?}/{search_flag?}','Site\PublishController@FindPerfectExperience')->name('search');
	Route::get('/funding-report/{event_id}','Site\EventController@GetFundingReports');
	Route::get('/get-payment-status/{status}/{id}', 'Site\PublishController@UpdatePaymentStatus');

	Route::post('/scrap-exprience','Site\ExperienceController@ScrapExperience');
	Route::get('/recommended-ajax','Site\EventController@AjaxRecommended');
	Route::post('/save-comment','Site\ExperienceController@SaveComment');
	//Route::post('/search-experience-ajax','Site\EventController@searchExperienceAjax');

});


   Route::get('/login','Auth\SiteLoginController@showLoginForm')->name('site.login');
   Route::post('/login', 'Auth\SiteLoginController@login')->name('site.login.submit');
   
   Route::get('/signup','Auth\SiteLoginController@showRegisterForm')->name('site.register');
   Route::post('/signup', 'Auth\SiteLoginController@register')->name('site.register.submit');
   Route::get('/verify/{token}', 'Auth\SiteLoginController@verifyToken');
   
   Route::get('/validate_email', 'Auth\SiteLoginController@validate_email');
   
   
   //Forgot Password
	Route::post('forgot_password', 'Site\UserController@forgot_password');
    Route::get('password/resets/{id}', 'Site\UserController@reset_password');
	Route::post('update_passwords', 'Site\UserController@update_password');
	
	Route::resource('dashboard', 'Site\DashboardController');
   
   
	
	//Event
	
	
	
	//About Us
	Route::get('/aboutUs', 'Site\UserController@getaboutUs');
	
	//Contact Us
	Route::get('/contactUs', 'Site\UserController@getcontactUs');
	
	//Terms & Condition
	Route::get('/terms-condition', 'Site\UserController@getTermsCondition');
	
	//Privacy Policy
	Route::get('/privacy-policy', 'Site\UserController@getPrivacyPolicy');
	
	//Faq
	Route::get('/faq', 'Site\UserController@getFAQ');
	
	//Need help
	Route::get('/need-help', 'Site\UserController@NeedHelp');
	
	//How fynches workds
	Route::get('/how-fynches-work', 'Site\UserController@getHowFynchesWorks');
	
	
	Route::get('logout', '\App\Http\Controllers\Auth\LoginController@logout');
	
	Route::get('/{title}', 'Site\PublishController@publishPage');

Route::group(array('prefix' => 'admin', 'middlewareGroups' => 'auth', 'after' => 'auth'), function() {
	
	Route::auth();
	Route::get('/', function() {
		return Redirect::to("admin/login");
	});
	
	Route::resource('dashboard', 'Admin\DashboardController');

	//Betasignup
	Route::post('/user/getBetaData', 'Admin\UserController@getBetaData');
	Route::get('betaSignup', 'Admin\UserController@getbetaSignupData');
	Route::get('delete_betaUser/{id}', 'Admin\UserController@delete_betaUser'); 
	Route::get('delete_multiple_betaUser/{id}', 'Admin\UserController@multiple_row_delete');
	Route::get('export_csv', 'Admin\UserController@export');

	//User
	Route::post('/user/getData', 'Admin\UserController@getData');
	Route::get('/user/delete/{id}', 'Admin\UserController@destroy');
	Route::get('delete_multiple_User/{id}', 'Admin\UserController@multiple_user_row_delete');
	
	//Admins
	Route::get('/user/admin_index', 'Admin\UserController@admin_index');
	Route::get('/user/admin_create', 'Admin\UserController@admin_create');
	Route::post('/user/create_admin', 'Admin\UserController@create_admin');
	Route::get('/user/list_admins', 'Admin\UserController@list_admins');
	
	
	Route::get('/user/edit_admin/{id}', 'Admin\UserController@edit_admin');
	Route::get('/user/show_admin_info/{id}', 'Admin\UserController@show_admin_info');
	Route::post('/user/update_admin', 'Admin\UserController@update_admin');
	Route::resource('user', 'Admin\UserController');
	
	//Email template
	Route::get('/emailtemplates/getData', 'Admin\EmailTemplatesController@getData');
	Route::resource('emailtemplates', 'Admin\EmailTemplatesController');
	Route::get('/emailtemplates/delete/{id}', 'Admin\EmailTemplatesController@destroy'); 
	Route::get('delete_multiple_EmailTemplates/{id}', 'Admin\EmailTemplatesController@multiple_emailTemplate_row_delete');

	//Change password
	Route::get('/changepassword', 'Admin\ChangepasswordController@index');
	Route::post('/changepassword/update', 'Admin\ChangepasswordController@update');


	Route::get('/changepassword/password/{id}/{type}', 'Admin\ChangepasswordController@password');
	Route::post('/changepassword/update-password', 'Admin\ChangepasswordController@updatePassword');

	//Event
	Route::get('/event/delete_event_img/{id}', 'Admin\EventController@delete_event_img');
	Route::get('/event/validate-publish-url', 'Admin\EventController@validatePublishUrl');
	Route::post('multiple_img_upload', 'Admin\EventController@multiple_img_upload');
	Route::put('multiple_img_upload', 'Admin\EventController@multiple_img_upload');
	
	//Route::post('delete_event_img', 'Admin\EventController@delete_event_img'); 
	Route::post('/event/getData', 'Admin\EventController@getData');
	Route::get('/event/delete/{id}', 'Admin\EventController@destroy'); 
	Route::get('delete_multiple_Event/{id}', 'Admin\EventController@multiple_row_delete');
	
	Route::resource('event', 'Admin\EventController');
	
	
	
	
	//Experience
	Route::get('/experience/getData/{id}', 'Admin\ExperienceController@getData');
	Route::get('/experience/delete/{id}', 'Admin\ExperienceController@destroy'); 
	Route::resource('experience', 'Admin\ExperienceController');
	
	Route::get('/event/experience/{id}', 'Admin\ExperienceController@index');
	Route::get('/experience/create/{id}', 'Admin\ExperienceController@create');
	Route::get('event/experience/delete_multiple_Experience/{id}', 'Admin\ExperienceController@multiple_row_delete');


	//Cms
	Route::get('/cms/getData', 'Admin\CmsController@getData');
	Route::get('/cms/delete/{id}', 'Admin\CmsController@destroy'); 
	Route::get('delete_multiple_Cms/{id}', 'Admin\CmsController@multiple_row_delete');
	Route::resource('cms', 'Admin\CmsController');
	
	//Testimonial
	Route::get('/testimonial/getData', 'Admin\TestimonialController@getData');
	Route::resource('testimonial', 'Admin\TestimonialController');
	Route::get('/testimonial/delete/{id}', 'Admin\TestimonialController@destroy'); 
	Route::get('delete_multiple_Testimonial/{id}', 'Admin\TestimonialController@multiple_row_delete');
	
	// Static Block
	Route::get('/staticblock/getData', 'Admin\StaticBlockController@getData');
	Route::resource('staticblock', 'Admin\StaticBlockController');
	Route::get('/staticblock/delete/{id}', 'Admin\StaticBlockController@destroy'); 
	Route::get('delete_multiple_StatickBlock/{id}', 'Admin\StaticBlockController@multiple_row_delete');
	
	
	//country
	Route::post('/country/getData', 'Admin\CountryController@getData');
	Route::get('/country/delete/{id}', 'Admin\CountryController@destroy'); 
	Route::get('delete_multiple_Country/{id}', 'Admin\CountryController@multiple_row_delete');
	Route::resource('country', 'Admin\CountryController');
	
	//state
	Route::post('/state/getData', 'Admin\StateController@getData');
	Route::get('/state/delete/{id}', 'Admin\StateController@destroy'); 
	Route::get('delete_multiple_State/{id}', 'Admin\StateController@multiple_row_delete');
	Route::resource('state', 'Admin\StateController');
	
	//Global Setting
	
	Route::post('/globalsetting/update', 'Admin\GlobalSettingController@update');
	Route::resource('globalsetting', 'Admin\GlobalSettingController');
	
	//Payment Listing
	Route::get('/payment/getData', 'Admin\PaymentController@getData');
	Route::resource('payment', 'Admin\PaymentController');
		
});	
	 
