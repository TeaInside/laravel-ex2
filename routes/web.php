<?php

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
/*
Route::get('/', function () {
    return view('welcome');
});
*/

// Config route site frontent

// Chac goi 1

// Route::get('/', function(){
    // return View::make('index');
// });

// Cach goi 2

Route::get('/test',function(){
return View::make('test');
});

Route::get('testLocate','BaseController@testLocate');

Route::get('/', 'HomeController@index'); // call index page
Route::get('market/{market}', 'HomeController@index');
Route::get('page/{page}', 'HomeController@routePage');
Route::post('get-chart', 'HomeController@getChart');
Route::post('voting', 'VoteCoinController@doVoting');
Route::get('maintenance', 'HomeController@maintenanceMode');

//locate
Route::get( '/locale/{locale}', 'BaseController@setLocale' );
//end locate

//added by krm
Route::post('page/contact', 'HomeController@sendEmail');
Route::post('page/submit-coin', 'HomeController@submitCoin');

//pages , news
Route::get('post/{post}', 'HomeController@viewPost');
#################################################################################
Route::group(array('before' => array('auth','admin'),'prefix' => 'admin'), function()
{
    Route::get('/', 'admin\\Admin_SettingController@routePage');
    Route::get('setting', 'admin\\Admin_SettingController@routePage');
    Route::get('setting/{page}', 'admin\\Admin_SettingController@routePage');
    Route::get('setting/{page}/{pager_page}', 'admin\\Admin_SettingController@routePage');

    Route::get('statistic/{page}', 'admin\\Admin_SettingController@routePage');

    //content
    Route::get('content/{page}', 'admin\\Admin_SettingController@routePage');
    Route::get('content/{page}/{pager_page}', 'admin\\Admin_SettingController@routePage');

    //manage
    Route::get('manage/{page}', 'admin\\Admin_SettingController@routePage');
    Route::post('manage/{page}', 'admin\\Admin_SettingController@routePage');
    Route::post('manage/{page}/{pager_page}', 'admin\\Admin_SettingController@routePage');
    Route::get('manage/{page}/{pager_page}', 'admin\\Admin_SettingController@routePage');

    Route::post('add-wallet', 'admin\\Admin_SettingController@addNewWallet');
    Route::get('edit-wallet/{wallet}', 'admin\\Admin_SettingController@editWallet');
    Route::post('edit-wallet', 'admin\\Admin_SettingController@doEditWallet');
    Route::post('delete-wallet', 'admin\\Admin_SettingController@deleteWallet');

    //market
    Route::post('add-market', 'admin\\Admin_SettingController@addNewMarket');
    Route::post('delete-market', 'admin\\Admin_SettingController@deleteMarket');

    //pages , news
    Route::post('add-post', 'admin\\Admin_SettingController@addNewPost');
    Route::get('edit-post/{post}', 'admin\\Admin_SettingController@editPost');
    Route::post('edit-post', 'admin\\Admin_SettingController@doEditPost');
    Route::post('delete-post', 'admin\\Admin_SettingController@deletePost');

    //coin news
    Route::post('add-coin-news', 'admin\\Admin_SettingController@addCoinNews');
    Route::get('edit-coin-news/{post}', 'admin\\Admin_SettingController@editCoinNews');
    Route::post('edit-coin-news', 'admin\\Admin_SettingController@doEditCoinNews');
    Route::post('delete-coin-news', 'admin\\Admin_SettingController@deleteCoinNews');

    //withdraw limits
    Route::post('add-withdraw-limit', 'admin\\Admin_SettingController@addWithdrawLimit');
    Route::get('edit-withdraw-limit/{post}', 'admin\\Admin_SettingController@editWithdrawLimit');
    Route::post('edit-withdraw-limit', 'admin\\Admin_SettingController@doEditWithdrawLimit');
    Route::post('delete-withdraw-limit', 'admin\\Admin_SettingController@deleteWithdrawLimit');
	
    //coin giveaways
    Route::post('add-coin-giveaway', 'admin\\Admin_SettingController@addCoinGiveaway');
    Route::get('edit-coin-giveaway/{post}', 'admin\\Admin_SettingController@editCoinGiveaway');
    Route::post('edit-coin-giveaway', 'admin\\Admin_SettingController@doEditCoinGiveaway');
    Route::post('delete-coin-giveaway', 'admin\\Admin_SettingController@deleteCoinGiveaway');
	
    Route::post('send-coin', 'admin\\Admin_SettingController@doSendCoin');

    Route::get('backup', 'admin\\Admin_SettingController@formBackup');
    Route::post('restore', 'admin\\Admin_SettingController@doBackup');
    Route::get('restore', 'admin\\Admin_SettingController@formRestore');
    Route::post('restore', 'admin\\Admin_SettingController@doRestore');

    //limit trade
    Route::post('add-limit-trade', 'admin\\Admin_SettingController@addNewLimitTrade');
    Route::get('edit-limit-trade/{wallet}', 'admin\\Admin_SettingController@editLimitTrade');
    Route::post('edit-limit-trade', 'admin\\Admin_SettingController@doEditLimitTrade');
    Route::post('delete-limit-trade', 'admin\\Admin_SettingController@deleteLimitTrade');
});
// Confide routes
Route::get( 'referral/{referral}',                 'UserController@create');
Route::get( 'user/register',                 'UserController@create');
Route::get( 'user/register',                 'UserController@register')->name('register');
Route::post('user',                        'UserController@store');
Route::get( 'login',                        'UserController@login');
Route::post('user/login',                  'UserController@do_login');
Route::get( 'user/confirm/{code}',         'UserController@confirm');
Route::get( 'user/forgot_password',        'UserController@forgot_password')->name('forgot_password');
Route::post('user/forgot_password',        'UserController@do_forgot_password');
Route::get( 'user/reset_password/{token}', 'UserController@reset_password');
Route::post('user/reset_password',         'UserController@do_reset_password');
Route::get( 'user/logout',                 'UserController@logout');
Route::post( 'check-captcha',               'UserController@checkCaptcha');
Route::post( 'user/update-setting',         'UserController@updateSetting');
//user profile
Route::group(array('before' => 'auth', 'prefix' => 'user'), function () {
    //Normal route
    Route::get('profile', 'UserController@viewProfile');
    
	//Connect Clef to account. //Install 2fa
	Route::get('profile/two-factor-auth/clef', 'ClefController@first_authentication');	

    Route::get('profile/{page}', 'UserController@viewProfile');
    Route::post('profile/{page}', 'UserController@viewProfile');
    Route::get('profile/{page}/{filter}', 'UserController@viewProfile');
    Route::post('profile/{page}/{filter}', 'UserController@viewProfile');
    Route::get('deposit/{wallet_id}', 'UserController@formDeposit');
    
    Route::get('withdraw/{wallet_id}', 'UserController@formWithdraw');
    Route::post('withdraw', 'UserController@doWithdraw');
    Route::get('withdraw-confirm/{withdraw_id}/{confirmation_code}', 'UserController@confirmWithdraw');
    Route::post('referrer-tradekey', 'UserController@referreredTradeKey');
    Route::post('cancel-withdraw', 'UserController@cancelWithdraw');
	Route::post('coin-giveaway', 'UserController@doCoinGiveaway');
	
    //transfer
    Route::get('transfer-coin/{wallet_id}', 'UserController@formTransfer');
    Route::post('transfer-coin', 'UserController@doTransfer');
   /* Route::post('viewtranfer/{type}', 'UserController@viewTransferOut');*/
   
   /* Route::post('profile/notifications', 'UserController@viewProfile'); */
});



//trading
Route::post('dobuy', 'OrderController@doBuy')->name('order.do.buy');
Route::post('dosell', 'OrderController@doSell')->name('order.do.sell');
Route::post('docancel', 'OrderController@doCancel');

//correct here
Route::post('get-orderdepth-chart', 'OrderController@getOrderDepthChart');
//end correct here






//Route::post('dotest', 'HomeController@doTest');

//deposit
Route::post('generate-addr-deposit', 'DepositController@generateNewAddrDeposit');
Route::get('cron-update-deposit', 'DepositController@cronUpdateDeposit');
Route::get('callback-update-deposit/{wallet_type}', 'DepositController@callbackUpdateDeposit');
Route::get('callback-update-deposit-test/{wallet_type}', 'DepositController@callbackUpdateDeposit_test');
Route::get('blocknotify-update-deposit/{wallet_type}', 'DepositController@blocknotifyUpdateDeposit');

//prevent CSRF attacks
//Route::when('*', 'csrf', array('post', 'put', 'delete'));
//Route::when('*', 'csrf', array('post'));
//Route::when('*', 'csrf', array('post', 'put', 'patch', 'delete'));
/*
//How to skip CSRF on webhooks
Route::filter('csrf', function()
{
    if( ! in_array(Route::currentRouteName(), array('blockchain','clef.logout')))
        if (Session::token() != Input::get('_token'))
            throw new Illuminate\Session\TokenMismatchException;
});

*/


/**
 * Clef related - 2fa
 * Clef.io logout
 */
Route::post('logout/two-factor-auth/clef', 'ClefController@logout');
/**
 * Login  with Clef.io
 */
Route::get('two-factor-auth/login2fa', 'ClefController@authentication');
//Connect Clef to account	(added in user group above)
//Route::get('user/profile/two-factor-auth/clef', 'ClefController@first_authentication');	//Install 2fa

//2fa two-factor-auth
Route::post( '/two-factor-auth/first_auth', 'UserController@firstAuth' );

//Route::post( 'user/verify_token', 'AuthController@ajVerifyToken' );
Route::post( '/two-factor-auth/disable', 'AuthController@removeTwoFactorAuth' );	