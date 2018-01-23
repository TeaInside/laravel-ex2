<?php

namespace App\Http\Middleware;

use DB;
use App;
use URL;
use View;
use Auth;
use Post;
use User;
use Trade;
use Route;
use Config;
use Wallet;
use Market;
use Confide;
use Setting;
use Closure;
use Request;
use Balance;
use Redirect;

class BeforeMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        
        if ($request->getMethod() === 'POST') {
            //exit ('asas');
            //Route::callRouteFilter('csrf', [], '', $request);
        }
        
        //echo "<pre>user: "; print_r($user); echo "</pre>";
        if (!Request::is('maintenance') && !Request::is('login')  && !Request::is('first_auth') && !Request::is('user/login') && !Request::is('user/logout')) {
            $maintenance_check=true;
            if (!Auth::guest()) {
                $user = Confide::user();
                /*
                echo '$user->id: '.$user->id;
                echo '<hr />';
                echo 'username: '.$user->username;
                echo '<hr />';
                echo 'user: '.Auth::basic('username');
                echo '<hr />';
                echo 'Auth::id: '.(Auth::id());
                echo '<hr />';
                
                //exit ( var_dump($user) );
                
                */
                
                //if(Auth::id()->hasRole('Admin')) $maintenance_check=false;
                if (User::find($user->id)->hasRole('Admin')) {
                    $maintenance_check=false;
                }
            }

            $setting = new Setting();
            $site_mode = $setting->getSetting('site_mode', 0);
            if ($site_mode == 1 && $maintenance_check) {
                return Redirect::to('/maintenance');
            }
        }
        if (!Auth::guest()) {
            
            $user = Confide::user();
            $timeout = trim($user->timeout);
            if (empty($timeout)) {
                $timeout = "45 minutes";
            }
            $lastest_login = $user->lastest_login;
            $new_date = date("Y-m-d H:i:s", strtotime($lastest_login." +".$timeout));
            $cur_date = date("Y-m-d H:i:s");
            if (strtotime($cur_date) >= strtotime($new_date)) {
                Confide::logout();
                
                return Redirect::to('/login');
            }
        }

        View::composer('layouts.default', function ($view) {
        
            //check for auto logout
            if (!Auth::guest()) {
                $markets = Market::get();

                $wallets = Wallet::orderby('type')->get();
                $balance = new Balance();
                $available_balances = array();
                foreach ($wallets as $wallet) {
                    $market_id = 0;
                    foreach ($markets as $m) {
                        if ($m->wallet_from == $wallet->id) {
                            $market_id = $m->id;
                        } elseif ($m->wallet_to == $wallet->id) {
                            $market_id = $m->id;
                        }
                    }

                    $available_balances[$wallet->id]['balance'] = $balance->getBalance($wallet->id);
                    $available_balances[$wallet->id]['type'] = $wallet->type;
                    $available_balances[$wallet->id]['market_id'] = $market_id;
                }
                $view->with('available_balances', $available_balances);
            }
            






            $btc_wallet = Wallet::where('type', 'BTC')->first();
            $ltc_wallet = Wallet::where('type', 'LTC')->first();
            $btc_markets = array();
            $ltc_markets = array();
            //$previous_day = date('Y-m-d H:i:s',strtotime(date('Y-m-d H:i:s') . " -1 day"));
            //$previous_day = date( "Y-m-d H:i:s", strtotime( " -24 hours" ));
            $previous_day = date('Y-m-d H:i:s', strtotime(date('Y-m-d H:i:s') . " -1 day"));

            //btc market on sidebar left
            $all_market_btc = array();
            if (isset($btc_wallet->id)) {
                $btc_markets = Market::leftJoin('wallets', 'market.wallet_from', '=', 'wallets.id')
                            ->select('market.*', 'wallets.name', 'wallets.type', 'wallets.enable_trading')->where('wallet_to', $btc_wallet->id)->orderby('wallets.type')->get();
                            //->leftJoin('trade_history', 'market.wallet_from', '=', 'trade_history.market_id')
                            //->select('market.*', 'wallets.name', 'wallets.type')->where('wallet_to',$btc_wallet->id)->where('trade_history.created_at','>=',$previous_day)->orderby('wallets.type')->get();
                $btc_datainfo = array();
                
                    
                foreach ($btc_markets as $value) {
                    $all_market_btc[]=$value->id;
                    $btc_datainfo[$value->id] = Trade::where('market_id', $value->id)->orderby('id', 'desc')->take(2)->get()->toArray();
                    $select="SELECT SUM( amount * price ) AS total FROM trade_history WHERE `market_id`='".$value->id."' AND `created_at` >= '".$previous_day."' GROUP BY market_id";
                    
                    $total_btc = DB::select($select);
                    
                    if (isset($total_btc[0])) {
                        $btc_datainfo[$value->id]['total'] = $total_btc[0]->total;  ////Get the latest(not latest 24h) total volume,
                    } else {
                        $btc_datainfo[$value->id]['total'] = 0;
                    }
                    
                }
                /*
                echo 'Curr price';
                echo $btc_datainfo[67][0]['price'];
                
                echo '<br />Pre price';
                echo $btc_datainfo[67][1]['price'];
                echo '<br />';
                
                var_dump( $btc_datainfo);
                */
                
                //exit();
                //echo "<pre>btc_datainfo: "; print_r($btc_datainfo); echo "</pre>";
                 $view->with('btc_datainfo', $btc_datainfo);
            }
            //ltc market on sidebar left
            $all_market_ltc = array();
            if (isset($ltc_wallet->id)) {
                $ltc_markets = Market::leftJoin('wallets', 'market.wallet_from', '=', 'wallets.id')
                            ->select('market.*', 'wallets.name', 'wallets.type', 'wallets.enable_trading')->where('wallet_to', $ltc_wallet->id)->orderby('wallets.type')->get();
                            //->leftJoin('trade_history', 'market.wallet_from', '=', 'trade_history.market_id')
                            //->select('market.*', 'wallets.name', 'wallets.type')->where('wallet_to',$ltc_wallet->id)->where('trade_history.created_at','>=',$previous_day)->orderby('wallets.type')->get();
                            
                $ltc_datainfo = array();
                foreach ($ltc_markets as $value) {
                    $all_market_ltc[]=$value->id;
                    $ltc_datainfo[$value->id] = Trade::where('market_id', $value->id)->orderby('id', 'desc')->take(2)->get()->toArray();
                    $select="SELECT SUM( amount * price ) AS total FROM trade_history Where `market_id`='".$value->id."' AND `created_at` >= '".$previous_day."' GROUP BY market_id";
                    $total_ltc = DB::select($select);
                    if (isset($total_ltc[0])) {
                        $ltc_datainfo[$value->id]['total'] = $total_ltc[0]->total;
                    } else {
                        $ltc_datainfo[$value->id]['total'] = 0;
                    }
                }
                //echo "<pre>ltc_datainfo: "; print_r($ltc_datainfo); echo "</pre>";
                $view->with('ltc_datainfo', $ltc_datainfo);
            }
            $view->with('btc_markets', $btc_markets);
            $view->with('ltc_markets', $ltc_markets);

            /*
             @ BTC / LTC Total Volume
             @ Total Trades
            */
            //24 Hour Statistics on sidebar left
            
            //echo "Date: ".date("Y-m-d H:i:s");
            
            //echo "+24 hours: ".$previous_day;
            if (!empty($all_market_btc)) {
                $btcmarkets = "'".implode("','", $all_market_btc)."'";
                $select="SELECT COUNT(*) as number_trade,SUM( amount * price ) AS total FROM trade_history WHERE `created_at` >= '".$previous_day."' AND `market_id` IN (".$btcmarkets.")";
                
                $statistic_btc = DB::select($select);
                //echo "<pre>statistic_btc: "; print_r($statistic_btc); echo "</pre>";
                $view->with('statistic_btc', $statistic_btc[0]);
            }
            if (!empty($all_market_ltc)) {
                $ltcmarkets = "'".implode("','", $all_market_ltc)."'";
                $select="SELECT COUNT(*) as number_trade,SUM( amount * price ) AS total FROM trade_history Where `created_at` >= '".$previous_day."' AND `market_id` IN (".$ltcmarkets.")";
                $statistic_ltc = DB::select($select);
                //echo "<pre>statistic_ltc: "; print_r($statistic_ltc); echo "</pre>";
                $view->with('statistic_ltc', $statistic_ltc[0]);
            }
            $menu_pages = Post::where('type', 'page')->where('show_menu', 1)->get();
                //get locate
                $locs= Config::get('app.locales');
                $view->with('loc', $locs);
                //end get locate
                $view->with('menu_pages', $menu_pages);
        });




        return $next($request);
    }
}
