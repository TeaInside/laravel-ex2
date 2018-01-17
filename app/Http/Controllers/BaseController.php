<?php


namespace App\Http\Controllers;

use DB;
use Config;
use App\Models\News;
use App\Models\Post;
use App\Models\Role;
use App\Models\User;
use App\Models\Vote;
use App\Models\Order;
use App\Models\Trade;
use App\Models\Limits;
use App\Models\Market;
use App\Models\Wallet;
use App\Models\Balance;
use App\Models\Deposit;
use App\Models\Setting;
use App\Models\FeeTrade;
use App\Models\CoinVote;
use App\Models\Transfer;
use App\Models\Withdraw;
use App\Models\Giveaways;
use App\Models\FeeWithdraw;
use App\Models\Notifications;
use App\Models\Giveawayclaims;
use App\Models\Authentication;
use App\Models\WalletLimitTrade;
use App\Models\SecurityQuestion;
use App\Models\UserAddressDeposit;
use App\Models\UserSecurityQuestion;
use Illuminate\Support\Facades\Session;

class BaseController extends Controller
{

    /**
     * Setup the layout used by the controller.
     *
     * @return void
     */
    protected function setupLayout()
    {
        if (! is_null($this->layout)) {
            $this->layout = view($this->layout);
        }
    }
    
    public function __construct()
    {
            // Run the 'csrf' filter on all post, put, patch and delete requests.
            //$this->beforeFilter('csrf', ['on' => ['post', 'put', 'patch', 'delete']]);
            
           $this -> configureLocale();
    }


     /**
     * Action used to set the application locale.
     *
     */
    public function setLocale()
    {
            $mLocale = Request::segment(2, Config::get('app.locale')); // Get parameter from URL.
        if (in_array($mLocale, Config::get('app.locales'))) {
             App::setLocale($mLocale);
              Session::put('locale', $mLocale);
               Cookie::forever('locale', $mLocale);
        }
             return Redirect::to(URL::previous());
             //return Redirect::back();//loi neu ko co link back
    }
    
    

    /**
     * Detect and set application localization environment (language).
     * NOTE: Don't foreget to ADD/SET/UPDATE the locales array in app/config/app.php!
     *
     */
    private function configureLocale()
    {
        // Set default locale.
        $mLocale = Config::get('app.locale');

        // Has a session locale already been set?
        if (!Session::has('locale')) {
        // No, a session locale hasn't been set.
            // Was there a cookie set from a previous visit?
            $mFromCookie = Cookie::get('locale', null);
            if ($mFromCookie != null && in_array($mFromCookie, Config::get('app.locales'))) {
            // Cookie was previously set and it's a supported locale.
                $mLocale = $mFromCookie;
            } else {
                // No cookie was set.
                // Attempt to get local from current URI.
                $mFromURI = Request::segment(1);
                if ($mFromURI != null && in_array($mFromURI, Config::get('app.locales'))) {
                // supported locale
                    $mLocale = $mFromURI;
                } else {
                    // attempt to detect locale from browser.
                    $mFromBrowser = substr(Request::server('http_accept_language'), 0, 2);
                    if ($mFromBrowser != null && in_array($mFromBrowser, Config::get('app.locales'))) {
                    // browser lang is supported, use it.
                        $mLocale = $mFromBrowser;
                    } // $mFromBrowser
                } // $mFromURI
            } // $mFromCookie

            Session::put('locale', $mLocale);
            Cookie::forever('locale', $mLocale);
        } // Session?
        else {
            // session locale is available, use it.
            $mLocale = Session::get('locale');
        } // Session?

        // set application locale for current session.
        App::setLocale($mLocale);

    }

    public function testLocate()
    {
        $locate=Config::get('app.locale');
        $arr=array();
        array_push($arr, $locate);
        var_dump($arr);
    }
}
