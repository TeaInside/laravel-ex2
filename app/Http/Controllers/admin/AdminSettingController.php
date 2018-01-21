<?php

namespace App\Http\Controllers\admin;

use DB;
use Hash;
use Lang;
use Config;
use Request;
use Redirect;
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
use App\Http\Controllers\Controller;

$a = DB::connection()->getPdo();
$a->exec("SET sql_mode = ''; ");
$a->exec("SET GLOBAL sql_mode=(SELECT REPLACE(@@sql_mode,'ONLY_FULL_GROUP_BY',''));");


class AdminSettingController extends Controller
{

    /*
	|--------------------------------------------------------------------------
	| Default Home Controller
	|--------------------------------------------------------------------------
	|
	| You may wish to use controllers instead of, or in addition to, Closure
	| based routes. That's great! Here is an example controller method to
	| get you started. To route to this controller, just add the route:
	|
	|	Route::get('/', 'HomeController@showWelcome');
	|
	*/

    public function routePage($page = '', $pager_page = '')
    {
        $markets = Market::get();
        $market_wallet = array();
        foreach ($markets as $market) {
            $market_wallet[$market->id] = $market->getWalletType($market->id);
        }
        $data['markets'] = $market_wallet;
        switch ($page) {
            case 'news':
                break;
            case 'fee':
                $markets = Market::get();

                $wallets = Wallet::orderby('type')->get();
                $market_list = array();
                foreach ($markets as $market) {
                    $from = $to = "";
                    foreach ($wallets as $wallet) {
                        if ($market->wallet_from == $wallet->id) {
                            $from = $wallet->type;
                        }
                        if ($market->wallet_to == $wallet->id) {
                            $to = $wallet->type;
                        }
                    }
                    $market_list[$market->id] = "{$from}/{$to}";
                }
                $data['market_list'] = $market_list;
                
                $data['fee_trades'] = FeeTrade::leftJoin('market', 'fee_trade.market_id', '=', 'market.id')
                    ->leftJoin('wallets', 'market.wallet_from', '=', 'wallets.id')
                    ->select('fee_trade.*', 'market.wallet_from', 'wallets.name', 'wallets.type')->orderby('wallets.name', 'asc')->get();
                //echo "<pre>fee_trades: "; print_r($fee_trades); echo "</pre>";
                //echo "<pre>markets: "; print_r($markets); echo "</pre>";exit;
                return view('admin.setting_fee', $data);
                break;
            case 'fee-withdraw':
                $data['fee_withdraws'] = FeeWithdraw::leftjoin('wallets', 'fee_withdraw.wallet_id', '=', 'wallets.id')->select('fee_withdraw.*', 'wallets.type', 'wallets.name')->orderby('wallets.type')->get();
                return view('admin.setting_fee_withdraw', $data);
                break;
            case 'limit-trade':
                $record_per_page = 15;
                $total = WalletLimitTrade::count();
                if ($pager_page=='') {
                    $pager_page = 1;
                }
                $data['total_pages'] = ceil($total/$record_per_page);//15 user per page
                $data['cur_page'] = $pager_page;
                $offset_start = ($pager_page-1)*$record_per_page;
                $data['wallets'] = Wallet::orderby('type')->get();
                $data['limit_trades'] = WalletLimitTrade::leftjoin('wallets', 'wallet_limittrade.wallet_id', '=', 'wallets.id')->select('wallet_limittrade.*', 'wallets.type as wallet_type', 'wallets.name as wallet_name')->skip($offset_start)->take($record_per_page)->orderby('wallet_type')->get();
                return view('admin.limittrade.setting_limittrade', $data);
                break;
            case 'statistic-coin-exchanged':
                $select = "SELECT mk.wallet_from, mk.wallet_to, sum(amount) as total_amount from trade_history a left join market mk on a.market_id=mk.id";
                $select_maincoin = "SELECT mk.wallet_from, mk.wallet_to, sum(amount*price) as total_amount from trade_history a left join market mk on a.market_id=mk.id";
                $where = '';
                if (isset($_GET['filter_time']) && $_GET['filter_time']!='') {
                    switch ($_GET['filter_time']) {
                        case 'hourly':
                            $hourly=date('Y-m-d H:i:s', strtotime('-1 hour'));
                            $where = $where==''? " Where a.created_at>='".$hourly."'": $where." Where a.created_at>='".$hourly."'";
                            break;
                        case 'daily':
                            $daily=date('Y-m-d H:i:s', strtotime('-24 hour'));
                            $where = $where==''? " Where a.created_at>='".$daily."'": $where." Where a.created_at>='".$daily."'";
                            break;
                        case 'weekly':
                            $thisweek=date('Y-m-d', strtotime('-7 day'));
                            $where = $where==''? " Where a.created_at>='".$thisweek."'": $where." Where a.created_at>='".$thisweek."'";
                            break;
                        case 'monthly':
                            $thismonth=date('Y-m-1', strtotime('-1 month'));
                            $where = $where==''? " Where a.created_at>='".$thismonth."'": $where." Where a.created_at>='".$thismonth."'";
                            break;
                    }
                }
                
                $where = (empty($where)) ? ' WHERE mk.wallet_from IS NOT NULL  AND mk.wallet_to IS NOT NULL' : $where . ' AND mk.wallet_from IS NOT NULL  AND mk.wallet_to IS NOT NULL';
                
                $select .= " ".$where."  group by mk.wallet_from";
                
                $coins_exchanged = DB::select($select);
                $data['coins_exchanged'] = $coins_exchanged;
                $select_maincoin .= " ".$where." group by mk.wallet_to";
                $maincoins_exchanged = DB::select($select_maincoin);
                $data['maincoins_exchanged'] = $maincoins_exchanged;
                // echo "<pre>"; print_r($fees); echo "</pre>";
                 $wallets_temp = Wallet::get();
                $wallets = array();
                foreach ($wallets_temp as $wallet) {
                    $wallets[$wallet->id] = $wallet;
                }
                $data['wallets'] = $wallets;
                return view('admin.statistics.statistic_coin_exchanged', $data);
                break;
            case 'statistic-fees':
                $select = "SELECT mk.wallet_from, mk.wallet_to, sum(fee_sell) as fee_sell, sum(fee_buy) as fee_buy from trade_history a left join market mk on a.market_id=mk.id";
                $select_maincoin =  "SELECT mk.wallet_from, mk.wallet_to, sum(fee_sell) as fee_sell, sum(fee_buy) as fee_buy from trade_history a left join market mk on a.market_id=mk.id";
                $where = '';
                if (isset($_GET['filter_time']) && $_GET['filter_time']!='') {
                    switch ($_GET['filter_time']) {
                        case 'hourly':
                            $hourly=date('Y-m-d H:i:s', strtotime('-1 hour'));
                            $where = $where==''? " Where a.created_at>='".$hourly."'": $where." Where a.created_at>='".$hourly."'";
                            break;
                        case 'daily':
                            $daily=date('Y-m-d H:i:s', strtotime('-24 hour'));
                            $where = $where==''? " Where a.created_at>='".$daily."'": $where." Where a.created_at>='".$daily."'";
                            break;
                        case 'weekly':
                            $thisweek=date('Y-m-d', strtotime('-7 day'));
                            $where = $where==''? " Where a.created_at>='".$thisweek."'": $where." Where a.created_at>='".$thisweek."'";
                            break;
                        case 'monthly':
                            $thismonth=date('Y-m-1', strtotime('-1 month'));
                            $where = $where==''? " Where a.created_at>='".$thismonth."'": $where." Where a.created_at>='".$thismonth."'";
                            break;
                    }
                }

                $where = (empty($where)) ? ' WHERE mk.wallet_from IS NOT NULL  AND mk.wallet_to IS NOT NULL' : $where . ' AND mk.wallet_from IS NOT NULL  AND mk.wallet_to IS NOT NULL';
                
                $select .= " ".$where." group by mk.wallet_from order by `created_at` desc";
                
                $fees = DB::select($select);
                $data['fees'] = $fees;

                $where = (empty($where)) ? ' WHERE mk.wallet_from IS NOT NULL  AND mk.wallet_to IS NOT NULL' : $where . ' AND mk.wallet_from IS NOT NULL  AND mk.wallet_to IS NOT NULL';

                $select_maincoin .= " ".$where." group by mk.wallet_to order by `created_at` desc";
                $fees_maincoin = DB::select($select_maincoin);
                $data['fees_maincoin'] = $fees_maincoin;
                // echo "<pre>"; print_r($fees); echo "</pre>";
                 $wallets_temp = Wallet::get();
                $wallets = array();
                foreach ($wallets_temp as $wallet) {
                    $wallets[$wallet->id] = $wallet;
                }
                $data['wallets'] = $wallets;
                return view('admin.statistics.statistic_fees', $data);
                break;
            case 'statistic-fee-withdraw':
                $select = "SELECT w.type, w.name, sum(fee_amount) as total_fee from withdraws a left join wallets w on a.wallet_id=w.id";
                $where = '';
                if (isset($_GET['filter_time']) && $_GET['filter_time']!='') {
                    switch ($_GET['filter_time']) {
                        case 'hourly':
                            $hourly=date('Y-m-d H:i:s', strtotime('-1 hour'));
                            $where = $where==''? " Where a.created_at>='".$hourly."'": $where." Where a.created_at>='".$hourly."'";
                            break;
                        case 'daily':
                            $daily=date('Y-m-d H:i:s', strtotime('-24 hour'));
                            $where = $where==''? " Where a.created_at>='".$daily."'": $where." Where a.created_at>='".$daily."'";
                            break;
                        case 'weekly':
                            $thisweek=date('Y-m-d', strtotime('-7 day'));
                            $where = $where==''? " Where a.created_at>='".$thisweek."'": $where." Where a.created_at>='".$thisweek."'";
                            break;
                        case 'monthly':
                            $thismonth=date('Y-m-1', strtotime('-1 month'));
                            $where = $where==''? " Where a.created_at>='".$thismonth."'": $where." Where a.created_at>='".$thismonth."'";
                            break;
                    }
                }
                   
                $select .= " ".$where." group by a.wallet_id";
                $withdraw_fees = DB::select($select);
                $data['withdraw_fees'] = $withdraw_fees;
                return view('admin.statistics.statistic_fee_withdraw', $data);
                break;
            case 'add-page':
                $data['type'] = 'page';
                return view('admin.pages.add_post', $data);
                break;
            case 'add-news':
                $data['type'] = 'news';
                return view('admin.pages.add_post', $data);
                break;
            case 'all-page':
                $data['type'] = 'page';
                $record_per_page = 15;
                $total = Post::where('type', $data['type'])->count();
                if ($pager_page=='') {
                    $pager_page = 1;
                }
                $data['total_pages'] = ceil($total/$record_per_page);//15 user per page
                $data['cur_page'] = $pager_page;
                $offset_start = ($pager_page-1)*$record_per_page;
                $posts = Post::where('type', $data['type'])->skip($offset_start)->take($record_per_page)->get();
                $data['posts'] = $posts;
                return view('admin.pages.all_posts', $data);
                break;
            case 'all-news':
                $data['type'] = 'news';
                $record_per_page = 15;
                $total = Post::where('type', $data['type'])->count();
                if ($pager_page=='') {
                    $pager_page = 1;
                }
                $data['total_pages'] = ceil($total/$record_per_page);//15 user per page
                $data['cur_page'] = $pager_page;
                $offset_start = ($pager_page-1)*$record_per_page;
                $posts = Post::where('type', $data['type'])->skip($offset_start)->take($record_per_page)->get();
                $data['posts'] = $posts;
                return view('admin.pages.all_posts', $data);
                break;

            case 'add-coin-news':
                $markets = Market::get();

                $wallets = Wallet::orderby('type')->get();
                $market_list = array();
                foreach ($markets as $market) {
                    $from = $to = "";
                    foreach ($wallets as $wallet) {
                        if ($market->wallet_from == $wallet->id) {
                            $from = $wallet->type;
                        }
                        if ($market->wallet_to == $wallet->id) {
                            $to = $wallet->type;
                        }
                    }
                    $market_list[$market->id] = "{$from}/{$to}";
                }
                $data['market_list'] = $market_list;

                return view('admin.pages.add_coin_news', $data);
                break;
            case 'all-coin-news':
                $record_per_page = 15;
                $total = News::count();
                if ($pager_page=='') {
                    $pager_page = 1;
                }
                $data['total_pages'] = ceil($total/$record_per_page);//15 user per page
                $data['cur_page'] = $pager_page;
                $offset_start = ($pager_page-1)*$record_per_page;
                $news = News::skip($offset_start)->take($record_per_page)->get();
                $data['news'] = $news;
                return view('admin.pages.all_coin_news', $data);
                break;
            case 'withdraw-limits':
                $record_per_page = 50;
                $total_limits = Limits::count();
                if ($pager_page=='') {
                    $pager_page = 1;
                }
                $data['total_pages'] = ceil($total_limits/$record_per_page);
                $data['cur_page'] = $pager_page;
                $offset_start = ($pager_page-1)*$record_per_page;
                //$offset_end = ($pager_page*$record_per_page)-1;

                $limits = Limits::skip($offset_start)->take($record_per_page)->get();

                foreach ($limits as $key => $value) {

                }

                $data['limits'] = $limits;

                return view('admin.limits.all_limits', $data);
                break;
            case 'add-withdraw-limit':
                $wallets = Wallet::orderby('type')->get();
                $wallet_list = array();
                foreach ($wallets as $wallet) {
                    $wallet_list[$wallet->id] = $wallet->type;
                }
                $data['wallet_list'] = $wallet_list;

                return view('admin.limits.add_withdraw_limit', $data);
                break;

            case 'coin-giveaways':
                $record_per_page = 50;
                $total_giveaways = Giveaways::count();
                if ($pager_page=='') {
                    $pager_page = 1;
                }
                $data['total_pages'] = ceil($total_giveaways/$record_per_page);
                $data['cur_page'] = $pager_page;
                $offset_start = ($pager_page-1)*$record_per_page;
                //$offset_end = ($pager_page*$record_per_page)-1;

                $giveaways = Giveaways::skip($offset_start)->take($record_per_page)->get();

                foreach ($giveaways as $key => $value) {

                }

                $data['giveaways'] = $giveaways;

                return view('admin.giveaways.all_giveaways', $data);
                break;
            case 'add-coin-giveaway':
                $wallets = Wallet::orderby('type')->get();
                $wallet_list = array();
                foreach ($wallets as $wallet) {
                    $wallet_list[$wallet->id] = $wallet->type;
                }
                $data['wallet_list'] = $wallet_list;

                return view('admin.giveaways.add_coin_giveaway', $data);
                break;

            case 'users':
                $record_per_page = 15;
                $total_users = User::count();
                if ($pager_page=='') {
                    $pager_page = 1;
                }
                $data['total_pages'] = ceil($total_users/$record_per_page);//15 user per page
                $data['cur_page'] = $pager_page;
                $offset_start = ($pager_page-1)*$record_per_page;
                //$offset_end = ($pager_page*$record_per_page)-1;

                $users = User::skip($offset_start)->take($record_per_page)->get()->toArray();

                foreach ($users as $key => $value) {
                    
                    $user = USer::find($value['id']);
                    $users[$key]['roles'] = $user->roles->toArray();
                    //echo "<pre>roles:"; print_r($user->roles->toArray()); echo "</pre>";
                }
                //echo "<pre>"; print_r($users); echo "</pre>";
                $roles = Role::get();
                $data['users'] = $users;
                $data['roles'] = $roles;
                return view('admin.user.manage_users', $data);
                break;
            case 'orders':
                $record_per_page = 15;
                if ($pager_page=='') {
                    $pager_page = 1;
                }
                $data['cur_page'] = $pager_page;
                $offset_start = ($pager_page-1)*$record_per_page;
                //$offset_end = ($pager_page*$record_per_page)-1;
                
                $select = "select a.*, b.wallet_from as `from`, b.wallet_to as `to`, c.username from orders a left join market b on a.market_id=b.id left join users c on a.user_id=c.id";
                $select_count = "select count(*) as total from orders a";
                $where = '';
                if (isset($_GET['do_filter'])) {
                    if ($where=='') {
                        if (!empty($_GET['market'])) {
                            $where = $where==''? " Where a.market_id='".$_GET['market']."'": $where." AND a.market_id='".$_GET['market']."'";
                        }
                    }
                    if ($_GET['status']!='') {
                        $where = $where==''? " Where a.status='".$_GET['status']."'": $where." AND a.status='".$_GET['status']."'";
                    }
                    if ($_GET['type']!='') {
                        $where = $where==''? " Where a.type='".$_GET['type']."'": $where." AND a.type='".$_GET['type']."'";
                    }

                }
                $select_count = $select_count." ".$where." order by `created_at` desc";
                $total_records = DB::select($select_count);
                //echo "<pre>total_records: "; print_r($total_records); echo "</pre>"; exit;
               
                $data['total_pages'] = ceil($total_records[0]->total/$record_per_page);
                
                $select .= " ".$where." order by `created_at` desc limit ".$offset_start.",".$record_per_page;
                $ordershistory = DB::select($select);
                $data['ordershistories'] = $ordershistory;
                
                return view('admin.orders', $data);
                break;
            case 'trade-histories':
                $record_per_page = 15;
                if ($pager_page=='') {
                    $pager_page = 1;
                }
                $data['cur_page'] = $pager_page;
                $offset_start = ($pager_page-1)*$record_per_page;
                $select = "select a.*, b.wallet_from as `from`, b.wallet_to as `to`,c.username as seller, d.username as buyer from trade_history a left join market b on a.market_id=b.id left join users c on a.seller_id=c.id left join users d on a.buyer_id=d.id";
                $select_count = "select count(*) as total from trade_history a";
                $where = '';
                if (isset($_GET['do_filter'])) {
                    if ($where=='') {
                        if (!empty($_GET['market'])) {
                            $where = $where==''? " Where a.market_id='".$_GET['market']."'": $where." AND a.market_id='".$_GET['market']."'";
                        }
                    }
                    if ($_GET['type']!='') {
                        $where = $where==''? " Where a.type='".$_GET['type']."'": $where." AND a.type='".$_GET['type']."'";
                    }
                    if ($_GET['time']!='') {
                        switch ($_GET['time']) {
                            case 'today':
                                $where = $where==''? " Where a.created_at>='".date("Y-m-d")."'": $where." Where a.created_at>='".date("Y-m-d")."'";
                                break;
                            case 'thisweek':
                                $thisweek=date('Y-m-d', strtotime('this week'));
                                $where = $where==''? " Where a.created_at>='".$thisweek."'": $where." Where a.created_at>='".$thisweek."'";
                                break;
                            case 'thismonth':
                                $thismonth=date('Y-m-1', strtotime('this month'));
                                $where = $where==''? " Where a.created_at>='".$thismonth."'": $where." Where a.created_at>='".$thismonth."'";
                                break;
                        }
                    }

                }
                $select_count = $select_count." ".$where." order by `created_at` desc";
                $total_records = DB::select($select_count);
                //echo "<pre>total_records: "; print_r($total_records); echo "</pre>"; exit;
               
                $data['total_pages'] = ceil($total_records[0]->total/$record_per_page);
                
                $select .= " ".$where." order by `created_at` desc limit ".$offset_start.",".$record_per_page;
                $trades = DB::select($select);
                $data['tradehistories'] = $trades;
                return view('admin.trade_histories', $data);
                break;
            case 'coins-voting':
                $coinvotes = DB::table('coin_votes')->get();
                $wallet = Wallet::where('type', 'BTC')->first();
                if (isset($wallet->id)) {
                    try {
                        $wallet->connectJsonRPCclient($wallet->wallet_username, $wallet->wallet_password, $wallet->wallet_ip, $wallet->port);
                        foreach ($coinvotes as $key => $value) {
                            $num_vote = Vote::where('coinvote_id', '=', $value->id)->count();
                            //echo "<pre>getreceivedbyaccount"; print_r($wallet->getReceivedByAddress($value->btc_address)); echo "</pre>";//$value->label_address
                            $btc_payment = $wallet->getReceivedByAddress($value->btc_address);//'12X9jVe4S8pAqJ7EoKN7B4YwMQpzfgArtX'
                            $num_payment = floor($btc_payment/0.0001);
                            //echo "btc_payment: ".$btc_payment;
                            //echo "<br>num_payment: ".$num_payment;
                            $coinvotes[$key]->balance = $num_payment;
                            $coinvotes[$key]->num_vote = $num_vote + $num_payment;
                        }
                    } catch (Exception $e) {
                        $data['error_message']= "Caught exception 1 - ASC";
                        //'Caught exception: '.$e->getMessage()."\n";  //
                    }
                     
                     //echo "<pre>coinvotes"; print_r($coinvotes); echo "</pre>";
                    $data['coinvotes'] = $coinvotes;
                } else {
                    $data['not_wallet'] = "Please add BTC wallet before add the vote coin.";
                }
                return view('admin.coins_voting', $data);
                break;
            case 'funds':
                $wallets = Wallet::leftjoin('fee_withdraw', 'fee_withdraw.wallet_id', '=', 'wallets.id')->orderby('type')->get();
                //$wallets = Wallet::orderby('type')->get();
                $balances = array();
                $fee_withdraws = array();
                
                $check_wallet_id = 0;
                foreach ($wallets as $wallet) {
                    try {
                        if ($check_wallet_id != $wallet->id) {
                            $check_wallet_id = $wallet->id;
                            $wallet->connectJsonRPCclient($wallet->wallet_username, $wallet->wallet_password, $wallet->wallet_ip, $wallet->port);
                            $balances[$wallet->id] = $wallet->getBalance();
                            $fee_withdraws[$wallet->id] = $wallet->getTxFee();
                            UserAddressDeposit::insert(array('user_id' => $user->id, 'wallet_id' => $wallet->id, 'addr_deposit'=>$address));
                        }
                    } catch (Exception $e) {
                        $data['error_message']= 'Caught exception 2 - ASC: ';
                        //"Not connect to this wallet";//'Caught exception: '.$e->getMessage()."\n";
                    }
                }
                $data['wallets'] = $wallets;
                $data['balances'] = $balances;
                $data['fee_withdraws'] = $fee_withdraws;
                //echo "<pre>fee_withdraws"; print_r($fee_withdraws); echo "</pre>";
                return view('admin.funds', $data);
                break;
            case 'withdraws-queue':
                $record_per_page = 20;
                if ($pager_page=='') {
                    $pager_page = 1;
                }
                $data['cur_page'] = $pager_page;
                $offset_start = ($pager_page-1)*$record_per_page;

                if (isset($_GET['do_filter']) && $_GET['wallet_id']!='') {
                    $withdraws = Withdraw::leftjoin('users', 'users.id', '=', 'withdraws.user_id')->where('wallet_id', $_GET['wallet_id'])->select('withdraws.*', 'users.username');
                } else {
                    $withdraws = Withdraw::leftjoin('users', 'users.id', '=', 'withdraws.user_id')->select('withdraws.*', 'users.username');
                }
                $total_records = $withdraws->get();
                //echo "<br>total_records: ".count($total_records);
                $data['total_pages'] = ceil(count($total_records)/$record_per_page);
                //echo "<br>total_records: ".$data['total_pages'];
                $withdraws= $withdraws->skip($offset_start)->take($record_per_page)->orderby("created_at", "desc")->get();
                $wallets = Wallet::orderby('type')->get()->toArray();
                $new_wallet = array();
                foreach ($wallets as $key => $value) {
                    $new_wallet[$value['id']] = $value;
                }
                $data['wallets'] = $new_wallet;
                $data['withdraws'] = $withdraws;
                return view('admin.withdraws_queue', $data);
                break;
            case 'deposits-queue':
                $record_per_page = 20;
                if ($pager_page=='') {
                    $pager_page = 1;
                }
                $data['cur_page'] = $pager_page;
                $offset_start = ($pager_page-1)*$record_per_page;

                if (isset($_GET['do_filter']) && $_GET['wallet_id']!='') {
                    $deposits = Deposit::leftjoin('users', 'users.id', '=', 'deposits.user_id')->where('wallet_id', $_GET['wallet_id'])->select('deposits.*', 'users.username');
                } else {
                    $deposits = Deposit::leftjoin('users', 'users.id', '=', 'deposits.user_id')->select('deposits.*', 'users.username');
                }
                $total_records = $deposits->get();
                //echo "<br>total_records: ".count($total_records);
                $data['total_pages'] = ceil(count($total_records)/$record_per_page);
                //echo "<br>total_records: ".$data['total_pages'];
                $deposits= $deposits->skip($offset_start)->take($record_per_page)->orderby("created_at", "desc")->get();
                $wallets = Wallet::orderby('type')->get()->toArray();
                $new_wallet = array();
                foreach ($wallets as $key => $value) {
                    $new_wallet[$value['id']] = $value;
                }
                $data['wallets'] = $new_wallet;
                $data['deposits'] = $deposits;
                return view('admin.deposits_queue', $data);
                break;
            case 'wallets':
                $record_per_page = 15;
                $total_users = Wallet::count();
                if ($pager_page=='') {
                    $pager_page = 1;
                }
                $data['total_pages'] = ceil($total_users/$record_per_page);//15 user per page
                $data['cur_page'] = $pager_page;
                $offset_start = ($pager_page-1)*$record_per_page;

                $wallets = Wallet::skip($offset_start)->take($record_per_page)->orderby('name')->get();
                $data['wallets'] = $wallets;
                return view('admin.wallet.manage_wallets', $data);
                break;
            case 'markets':
                $record_per_page = 15;
                $total_users = Market::count();
                if ($pager_page=='') {
                    $pager_page = 1;
                }
                $data['total_pages'] = ceil($total_users/$record_per_page);//15 user per page
                $data['cur_page'] = $pager_page;
                $offset_start = ($pager_page-1)*$record_per_page;

                $markets = Market::skip($offset_start)->take($record_per_page)->get();
                $data['markets'] = $markets;
                $wallets_temp = Wallet::get();
                $wallets = array();
                foreach ($wallets_temp as $wallet) {
                    $wallets[$wallet->id] = $wallet;
                }
                $data['wallets'] = $wallets;
                
                return view('admin.manage_markets', $data);
                break;
            case 'balance-wallets':
                $record_per_page = 15;
                $total_users = Wallet::count();
                if ($pager_page=='') {
                    $pager_page = 1;
                }
                $data['total_pages'] = ceil($total_users/$record_per_page);//15 user per page
                $data['cur_page'] = $pager_page;
                $offset_start = ($pager_page-1)*$record_per_page;

                $wallets = Wallet::skip($offset_start)->take($record_per_page)->orderby('name')->get();
                $amount_transaction=array();
                $balances=array();
                foreach ($wallets as $wallet) {
                    //get total deposit
                    $total_deposit=Deposit::where('wallet_id', $wallet->id)->where('paid', 1)->sum('amount');
                    $total_withdraw=Withdraw::where('wallet_id', $wallet->id)->where('status', 1)->sum('receive_amount');
                    $amount_transaction[$wallet->id]=array(
                        'total_amount_deposit' => $total_deposit,
                        'total_amount_withdraw' => $total_withdraw
                        );
                    try {
                        $wallet->connectJsonRPCclient($wallet->wallet_username, $wallet->wallet_password, $wallet->wallet_ip, $wallet->port);
                        $balances[$wallet->id]=$wallet->getBalance();
                    } catch (Exception $e) {
                        $balances[$wallet->id]= Lang::get('messages.cannot_connect');
                        
                    }
                }
                $data['wallets'] = $wallets;
                $data['amount_transaction']=$amount_transaction;
                $data['balances']=$balances;
                
                return view('admin.wallet.manage_wallets_balance', $data);
                break;
            default:
                $setting = new Setting();
                //$data['bg_color']=$setting->getSetting('bg_color','');
                $data['site_mode']=$setting->getSetting('site_mode', 0);
                //$data['bg_file']=$setting->getSetting('bg_file','');
                $data['disable_withdraw']=$setting->getSetting('disable_withdraw', 0);
                $data['recaptcha_publickey']=$setting->getSetting('recaptcha_publickey', '');
                $data['recaptcha_privatekey']=$setting->getSetting('recaptcha_privatekey', '');
                $data['amount_btc_per_vote']=$setting->getSetting('amount_btc_per_vote', 0.0001);

                //pusher app
                $data['pusher_app_id']=$setting->getSetting('pusher_app_id', '');
                $data['pusher_app_key']=$setting->getSetting('pusher_app_key', '');
                $data['pusher_app_secret']=$setting->getSetting('pusher_app_secret', '');

                //default_market
                $m_default = Market::orderBy('id')->first();
                $data['default_market']=$setting->getSetting('default_market', $m_default);

                //setting points
                $data['disable_points']=$setting->getSetting('disable_points', 0);
                $data['point_per_btc']=$setting->getSetting('point_per_btc', 1);
                $data['percent_point_reward_trade']=$setting->getSetting('percent_point_reward_trade', 0);
                $data['percent_point_reward_referred_trade']=$setting->getSetting('percent_point_reward_referred_trade', 0);

                //echo "<pre>data: "; print_r($data); echo "</pre>"; exit;
                return view('admin.setting', $data);
                break;
        }
    }

    
    public function addWithdrawLimit()
    {
        $wallet_id = Request::get('wallet_id');
        $amount = floatval(Request::get('amount'));

        $wallet = Wallet::where('id', $wallet_id)->first();

        $wallet_name= isset($wallet->name) ? $wallet->name : '';
        $wallet_type= isset($wallet->type) ? $wallet->type : '';

        if (!empty($wallet_id) && !empty($amount)) {
            $limits = new Limits();
            $limits->amount = $amount;
            $limits->wallet_id = $wallet_id;
            $limits->wallet_type = $wallet_type;
            $limits->wallet_name = $wallet_name;
            $limits->save();
            if ($limits->id) {
                return Redirect::to('admin/manage/withdraw-limits')->with('success', Lang::get('messages.created_success_param', array('object'=>'Withdraw Limits')));
            } else {
                return Redirect::to('admin/manage/withdraw-limits')->with('error', $error);
            }
        } else {
            return Redirect::to('admin/manage/add-withdraw-limit')->with('error', Lang::get('messages.fill_all_fields'));
        }
    }
    public function editWithdrawLimit($limit_id)
    {
        $wallets = Wallet::orderby('type')->get();
        $wallet_list = array();
        foreach ($wallets as $wallet) {
            $wallet_list[$wallet->id] = $wallet->type;
        }
        $data['wallet_list'] = $wallet_list;

        $data['limit'] = Limits::find($limit_id);
        return view('admin.limits.edit_withdraw_limit', $data);
    }
    public function doEditWithdrawLimit()
    {
        $wallet_id = Request::get('wallet_id');
        $amount = floatval(Request::get('amount'));
        $limit_id = Request::get('limit_id');

        $wallet = Wallet::where('id', $wallet_id)->first();

        $wallet_name= isset($wallet->name) ? $wallet->name : '';
        $wallet_type= isset($wallet->type) ? $wallet->type : '';

        if (!empty($limit_id) && !empty($amount) && !empty($wallet_id)) {
            $limits = Limits::find($limit_id);
            $limits->amount = $amount;
            $limits->wallet_id = $wallet_id;
            $limits->wallet_type = $wallet_type;
            $limits->wallet_name = $wallet_name;
            $limits->save();
            if ($limits->id) {
                return Redirect::to('admin/manage/withdraw-limits')->with('success', Lang::get('messages.updated_success_param', array('object'=>'Coin News')));
            } else {
                return Redirect::to('admin/manage/withdraw-limits')->with('error', $error);
            }
        } else {
            return Redirect::to('admin/manage/withdraw-limits')->with('error', Lang::get('messages.fill_all_fields'));
        }
    }
    public function deleteWithdrawLimit()
    {
        $limit_id = Request::get('limit_id');
        $limits = Limits::find($limit_id);
        if (isset($limits->id)) {
            Limits::where('id', $limit_id)->delete();
            $message = $limits->wallet_type." withdraw limit ".Lang::get('messages.delete_success');
            if (Request::get('isAjax')) {
                echo json_encode(array('status'=>'success', 'message'=>$message ));
                exit;
            } else {
                return Redirect::to('admin/manage/withdraw-limits')->with('success', $message);
            }
        } else {
            $message = "Entry does not exist";
            if (Request::get('isAjax')) {
                echo json_encode(array('status'=>'error', 'message'=>$message ));
                exit;
            } else {
                return Redirect::to('admin/manage/withdraw-limits')->with('error', $message);
            }
        }
    }

    public function addCoinGiveaway()
    {
        $wallet_id = Request::get('wallet_id');
        $amount = floatval(Request::get('amount'));
        $time_interval = intval(Request::get('time_interval'));

        $wallet = Wallet::where('id', $wallet_id)->first();

        $wallet_name= isset($wallet->name) ? $wallet->name : '';
        $wallet_type= isset($wallet->type) ? $wallet->type : '';

        if (!empty($wallet_id) && !empty($amount)) {
            $giveaways = new Giveaways();
            $giveaways->amount = $amount;
            $giveaways->time_interval = $time_interval;
            $giveaways->wallet_id = $wallet_id;
            $giveaways->wallet_type = $wallet_type;
            $giveaways->wallet_name = $wallet_name;
            $giveaways->save();
            if ($giveaways->id) {
                return Redirect::to('admin/manage/coin-giveaways')->with('success', Lang::get('messages.created_success_param', array('object'=>'Coin Giveaways')));
            } else {
                return Redirect::to('admin/manage/coin-giveaways')->with('error', $error);
            }
        } else {
            return Redirect::to('admin/manage/add-coin-giveaway')->with('error', Lang::get('messages.fill_all_fields'));
        }
    }
    public function editCoinGiveaway($giveaway_id)
    {
        $wallets = Wallet::orderby('type')->get();
        $wallet_list = array();
        foreach ($wallets as $wallet) {
            $wallet_list[$wallet->id] = $wallet->type;
        }
        $data['wallet_list'] = $wallet_list;

        $data['giveaway'] = Giveaways::find($giveaway_id);
        return view('admin.giveaways.edit_coin_giveaway', $data);
    }
    public function doEditCoinGiveaway()
    {
        $wallet_id = Request::get('wallet_id');
        $amount = floatval(Request::get('amount'));
        $time_interval = intval(Request::get('time_interval'));
        $giveaway_id = Request::get('giveaway_id');

        $wallet = Wallet::where('id', $wallet_id)->first();

        $wallet_name= isset($wallet->name) ? $wallet->name : '';
        $wallet_type= isset($wallet->type) ? $wallet->type : '';

        if (!empty($giveaway_id) && !empty($amount) && !empty($time_interval) && !empty($wallet_id)) {
            $giveaways = Giveaways::find($giveaway_id);
            $giveaways->amount = $amount;
            $giveaways->wallet_id = $wallet_id;
            $giveaways->wallet_type = $wallet_type;
            $giveaways->wallet_name = $wallet_name;
            $giveaways->time_interval = $time_interval;
            if ($giveaways->save()) {
                return Redirect::to('admin/manage/coin-giveaways')->with('success', Lang::get('messages.updated_success_param', array('object'=>'Coin Giveaway')));
            } else {
                return Redirect::to('admin/manage/coin-giveaways')->with('error', $error);
            }
        } else {
            return Redirect::to('admin/manage/coin-giveaways')->with('error', Lang::get('messages.fill_all_fields'));
        }
    }
    public function deleteCoinGiveaway()
    {
        $giveaway_id = Request::get('giveaway_id');
        $giveaways = Giveaways::find($giveaway_id);
        if (isset($giveaways->id)) {
            Giveaways::where('id', $giveaway_id)->delete();
            $message = $giveaways->wallet_type." coin giveaway ".Lang::get('messages.delete_success');
            if (Request::get('isAjax')) {
                echo json_encode(array('status'=>'success', 'message'=>$message ));
                exit;
            } else {
                return Redirect::to('admin/manage/coin-giveaways')->with('success', $message);
            }
        } else {
            $message = "Entry does not exist";
            if (Request::get('isAjax')) {
                echo json_encode(array('status'=>'error', 'message'=>$message ));
                exit;
            } else {
                return Redirect::to('admin/manage/coin-giveaways')->with('error', $message);
            }
        }
    }
    
    public function updateSetting()
    {
        $setting = new Setting();
        $site_mode = Request::get('site_mode');
        //$bg_color = Request::get('bg_color');
        //$bg_file = Request::file('bg_file');
        $disable_withdraw = Request::get('disable_withdraw');
        $disable_points = Request::get('disable_points');
        
        //$setting->putSetting('bg_color',$bg_color);
        $setting->putSetting('site_mode', $site_mode);
        $setting->putSetting('disable_withdraw', $disable_withdraw);
        $setting->putSetting('disable_points', $disable_points);

        $recaptcha_publickey = Request::get('recaptcha_publickey');
        $recaptcha_privatekey = Request::get('recaptcha_privatekey');
        $setting->putSetting('recaptcha_publickey', $recaptcha_publickey);
        $setting->putSetting('recaptcha_privatekey', $recaptcha_privatekey);

        $amount_btc_per_vote = is_numeric(Request::get('amount_btc_per_vote'))? Request::get('amount_btc_per_vote'):0.0001;
        $setting->putSetting('amount_btc_per_vote', $amount_btc_per_vote);

        $pusher_app_id = Request::get('pusher_app_id');
        $pusher_app_key = Request::get('pusher_app_key');
        $pusher_app_secret = Request::get('pusher_app_secret');
        //echo "pusher_app_id: ".$pusher_app_id." -- pusher_app_key: ".$pusher_app_key." -- pusher_app_secret: ".$pusher_app_secret;
        //exit;
        $setting->putSetting('pusher_app_id', $pusher_app_id);
        $setting->putSetting('pusher_app_key', $pusher_app_key);
        $setting->putSetting('pusher_app_secret', $pusher_app_secret);

        //points
        $point_per_btc = Request::get('point_per_btc');
        $percent_point_reward_trade = Request::get('percent_point_reward_trade');
        $percent_point_reward_referred_trade = Request::get('percent_point_reward_referred_trade');
        $setting->putSetting('point_per_btc', $point_per_btc);
        $setting->putSetting('percent_point_reward_trade', $percent_point_reward_trade);
        $setting->putSetting('percent_point_reward_referred_trade', $percent_point_reward_referred_trade);

        $setting->putSetting('default_market', Request::get('default_market'));
        /*if(!empty($bg_file)){
        	$extension = $bg_file->getClientOriginalExtension();
	        $destinationPath = 'upload/images/';
	        $fileName = time().'.'.$extension;
	        if(in_array($extension, array('jpg','png','gif'))) {
	            if($bg_file->move($destinationPath, $fileName)) {
	            	$setting->putSetting('bg_file',$destinationPath.$fileName,'');
	            }
	        } else {
	            return Redirect::to('admin')->with('notice', 'The extension of image invalid');
	        }
        }*/
        
        return Redirect::to('admin')->with('success', Lang::get('messages.update_success'));
    }
    public function setFeeTrade()
    {
        $fee_buy = Request::get('buy_fee');
        $fee_sell = Request::get('sell_fee');
        $market_id = Request::get('market_id');
        FeeTrade::where('market_id', $market_id)->update(array('fee_buy'=>$fee_buy,'fee_sell'=>$fee_sell));
        return Redirect::to('admin/setting/fee')->with('success', Lang::get('messages.update_success'));
    }
    public function setFeeWithdraw()
    {
        $fee_withdraw = Request::get('fee_withdraw');
        $coin = Request::get('coin');
        FeeWithdraw::where('wallet_id', $coin)->update(array('percent_fee'=>$fee_withdraw));
        return Redirect::to('admin/setting/fee-withdraw')->with('success', Lang::get('messages.update_success'));
    }

    public function addNewCoinVote()
    {
        $code = strtoupper(Request::get('code'));
        $name = Request::get('name');
        //$btc_address = Request::get('btc_address');
        //$check_coinvote = CoinVote::where('code',$code)->orwhere('btc_address',$btc_address)->first();
        $check_coinvote = CoinVote::where('code', $code)->first();
        if (isset($check_coinvote->id)) {
            return Redirect::to('admin/manage/coins-voting')->with('error', Lang::get('messages.not_exist_coin_vote'));
        } else {
            $wallet = Wallet::where('type', 'BTC')->first();
            if (isset($wallet->id)) {
                try {
                    $wallet->connectJsonRPCclient($wallet->wallet_username, $wallet->wallet_password, $wallet->wallet_ip, $wallet->port);
                    $address = $wallet->getNewDepositReceiveAddress('cryptocoin_btc_payment_vote');
                    $coin = new CoinVote();
                    $coin->code = $code;
                    $coin->name = $name;
                    $coin->btc_address = $address;
                    $coin->save();
                    return Redirect::to('admin/manage/coins-voting')->with('success', Lang::get('messages.add_coin_vote_success'));
                } catch (Exception $e) {
                    return Redirect::to('admin/manage/coins-voting')->with('error', 'Caught exception 4 - ASC: ');
                    //return Redirect::to('admin/manage/coins-voting')->with('error', 'Caught exception: '.$e->getMessage()."\n");
                }
            } else {
                return Redirect::to('admin/manage/coins-voting')->with('error', Lang::get('messages.not_found_btc_wallet'));
            }
        }
    }

    public function deleteCoinVote()
    {
        $coinvote_id = $_POST['coinvote_id'];
        $coin = CoinVote::find($coinvote_id);
        $coin->delete();
        echo json_encode(array('status'=>'success','message'=> Lang::get('messages.delete_success') ));
        exit;
    }

    public function addNewUser()
    {
        $user = new User;

        $user->fullname = Request::get('fullname');
        $user->username = Request::get('username');
        $user->email = Request::get('email');
        $user->password = Request::get('password');
        $user->banned = 0;
        $user->confirmed = 1;
        $roles = Request::get('roles');
        //echo "<pre>roles"; print_r($roles); echo "</pre>"; exit;
        // The password confirmation will be removed from model
        // before saving. This field will be used in Ardent's
        // auto validation.
        $user->password_confirmation = Request::get('password_confirmation');
        $user_email = User::where('email', $user->email)->first();
        if (isset($user_email->id)) {
            return Redirect::to('admin/manage/users')->with('error', Lang::get('messages.email_exist'));
        }
        $user_username = User::where('username', $user->username)->first();
        if (isset($user_username->id)) {
            return Redirect::to('admin/manage/users')->with('error', Lang::get('messages.username_exist'));
        }
        // Save if valid. Password field will be hashed before save
        $user->save();

        if ($user->id) {
            if ($roles) {
                foreach ($roles as $role) {
                    $user->addRole($role);
                }
            } else {
                $user->addRole('User');
            }
            
            
            $notice = Lang::get('confide::confide.alerts.account_created');
            // Redirect with success message, You may replace "Lang::get(..." for your custom message.
            return Redirect::to('admin/manage/users')->with('success', $notice);
        } else {
            // Get validation errors (see Ardent package)
            $error = $user->errors()->all(':message');
            return Redirect::to('admin/manage/users')->withInput(Request::except('password'))->with('error', $error);
        }
    }

    public function editUSer($user_id = '')
    {
        if ($user_id=='') {
            return Redirect::to('admin/manage/users');
        }
        $user = User::find($user_id);
        if (!isset($user->id)) {
            return Redirect::to('admin/manage/users')->with('error', Lang::get('messages.user_not_exist'));
        }
        $data['user_roles'] = $user->roles->toArray();
        $data['user'] = $user;
        $roles = Role::get()->toArray();
        $data['roles'] = $roles;
        //echo "<pre>roles: "; print_r($data['roles']); echo "</pre>";
        //echo "<pre>user_roles: "; print_r($data['user_roles']); echo "</pre>";
        return view('admin.user.edit_user', $data);
    }

    public function doEditUSer()
    {
        $update= array('updated_at'=>date("Y-m-d H:i:s"));
        $fullname = Request::get('fullname');
        //$username = Request::get( 'username' );
        $email = Request::get('email');
        $password = Request::get('password');
        $confirmed = Request::get('confirmed');
        $banned = Request::get('banned');
        $user_id = Request::get('user_id');
        $user_email = User::where('email', $email)->where('id', '!=', $user_id)->first();
        if (isset($user_email->id)) {
            return Redirect::to('admin/edit-user/'.$user_id)->with('error', Lang::get('messages.email_exist'));
        }
        $user = User::find($user_id);
        if ($password!='' && !Hash::check($password, $user->password)) {
            $update['password'] = Hash::make($password);
        }
        $update['fullname'] = $fullname;
        $update['email'] = $email;
        $update['confirmed'] = $confirmed;
        $update['banned'] = $banned;
        User::where('id', $user_id)->update($update);
        $roles = Request::get('roles');
        DB::table('users_roles')->where('user_id', $user_id)->delete();
        foreach ($roles as $role) {
            $user->addRole($role);
        }
        //echo "confirmed: ".var_dump($confirmed);
        return Redirect::to('admin/manage/users')->with('success', $user->username." ".Lang::get('messages.update_success'));
    }

    public function deleteUSer()
    {
        $user_id = Request::get('user_id');
        $user = User::find($user_id);
        if (isset($user->id)) {
            DB::table('users_roles')->where('user_id', $user_id)->delete();
            User::where('id', $user_id)->delete();
            $message = $user->username." ".Lang::get('messages.delete_success');
            if (Request::get('isAjax')) {
                echo json_encode(array('status'=>'success', 'message'=>$message ));
                exit;
            } else {
                return Redirect::to('admin/manage/users')->with('success', $message);
            }
        } else {
            $message = Lang::get('messages.user_not_exist');
            if (Request::get('isAjax')) {
                echo json_encode(array('status'=>'error', 'message'=>$message ));
                exit;
            } else {
                return Redirect::to('admin/manage/users')->with('error', $message);
            }
        }
        
    }
    public function banUSer()
    {
        $user_id = Request::get('user_id');
        $user = User::find($user_id);
        if (isset($user->id)) {
            User::where('id', $user_id)->update(array('banned'=>1));
            $message = Lang::get('messages.banned_success') ." ".$user->username.".";
            if (Request::get('isAjax')) {
                echo json_encode(array('status'=>'success', 'message'=>$message ));
                exit;
            } else {
                return Redirect::to('admin/manage/users')->with('success', $message);
            }
        } else {
            $message = Lang::get('messages.user_not_exist');
            if (Request::get('isAjax')) {
                echo json_encode(array('status'=>'error', 'message'=>$message ));
                exit;
            } else {
                return Redirect::to('admin/manage/users')->with('error', $message);
            }
        }
        
    }

    public function addNewWallet()
    {
        $type = strtoupper(Request::get('type'));
        $name = Request::get('name');
        $wallet_username = Request::get('wallet_username');
        $password = Request::get('password');
        $ip = Request::get('ip');
        $port = Request::get('port');
        //$confirm_count = ( !empty(Request::get('confirm_count')) ) ? Request::get('confirm_count') : 1;
        $confirm_count = Request::get('confirm_count');
        $confirm_count = (!empty($confirm_count)) ? (int)$confirm_count : 1;

        
        $enable_deposit = Request::get('enable_deposit');
        $enable_deposit = (!empty($enable_deposit)) ? (int)$enable_deposit : 0;
        
        $enable_withdraw = Request::get('enable_withdraw');
        $enable_withdraw = (!empty($enable_withdraw)) ? (int)$enable_withdraw : 0;

        $enable_trading = Request::get('enable_trading');
        $enable_trading = (!empty($enable_trading)) ? (int)$enable_trading : 0;
        
        $download_wallet_client = Request::get('download_wallet_client');
        $check_wallet = Wallet::where('type', '=', $type)->first();
        if (isset($check_wallet->id)) {
            return Redirect::to('admin/manage/wallets')->with('error', Lang::get('messages.wallet_exist'));
        }

        $logo_coin=Request::file('logo_coin');
        $logo='';
        if (!empty($logo_coin)) {
            $extension = $logo_coin->getClientOriginalExtension();
            $destinationPath = 'upload/images/';
            $fileName = time().'.'.$extension;
            if (in_array($extension, array('jpg','png','gif'))) {
                if ($logo_coin->move($destinationPath, $fileName)) {
                    $logo=$destinationPath.$fileName;
                }
            } else {
                return Redirect::to('admin')->with('notice', 'The extension of image invalid');
            }
        }

        $wallet = new Wallet();
        $wallet->type = $type;
        $wallet->name = $name;
        $wallet->wallet_username = $wallet_username;
        $wallet->wallet_password = $password;
        $wallet->wallet_ip = $ip;
        $wallet->port = $port;
        $wallet->confirm_count = $confirm_count;
        $wallet->download_wallet_client=$download_wallet_client;
        $wallet->logo_coin=$logo;
        $wallet->enable_deposit=$enable_deposit;
        $wallet->enable_withdraw=$enable_withdraw;
        $wallet->enable_trading=$enable_trading;
        $wallet->save();
        if ($wallet->id) {
            $fee_withdraw = new FeeWithdraw();
            $fee_withdraw->wallet_id = $wallet->id;
            $fee_withdraw->percent_fee = 0;
            $fee_withdraw->save();
            return Redirect::to('admin/manage/wallets')->with('success', Lang::get('messages.created_success_param', array('object'=>$wallet->name)));
        } else {
            return Redirect::to('admin/manage/wallets')->with('error', Lang::get('messages.not_create_wallet'));
        }
    }

    public function editWallet($wallet_id = '')
    {
        if ($wallet_id=='') {
            return Redirect::to('admin/manage/wallets');
        }
        $wallet = Wallet::find($wallet_id);
        if (!isset($wallet->id)) {
            return Redirect::to('admin/manage/wallets')->with('error', Lang::get('messages.wallet_not_exist'));
        }
        $data['wallet'] = $wallet;
        return view('admin.wallet.edit_wallet', $data);
    }

    public function doEditWallet()
    {
        $wallet_id = Request::get('wallet_id');
        $wallet = Wallet::find($wallet_id);
        
        $logo_coin=Request::file('logo_coin');
        $logo='';
        if (!empty($logo_coin)) {
            $extension = $logo_coin->getClientOriginalExtension();
            $destinationPath = 'upload/images/';
            $fileName = time().'.'.$extension;
            if (in_array($extension, array('jpg','png','gif'))) {
                if ($logo_coin->move($destinationPath, $fileName)) {
                    $logo=$destinationPath.$fileName;
                    $wallet->logo_coin=$logo;
                }
            } else {
                return Redirect::to('admin')->with('notice', 'The extension of image invalid');
            }
        }
        //$enable_deposit = !empty(Request::get('eanble_withdraw'))? Request::get('eanble_withdraw'):0;
        //$enable_withdraw = !empty(Request::get('eanble_withdraw'))? Request::get('eanble_withdraw'):0;

        $wallet->name = Request::get('name');
        $wallet->wallet_username = Request::get('wallet_username');
        $wallet->wallet_password = Request::get('password');
        $wallet->wallet_ip = Request::get('ip');
        $wallet->port = Request::get('port');
        //$wallet->confirm_count = !empty(Request::get('confirm_count'))? Request::get('confirm_count'):1;
        if ((Request::get('confirm_count'))=='') {
            $wallet->confirm_count=1;
        } else {
            $wallet->confirm_count=Request::get('confirm_count');
        }
        //$wallet->enable_deposit = !empty(Request::get('enable_deposit'))? Request::get('enable_deposit'):0;
        if ((Request::get('enable_deposit'))=='') {
            $wallet->enable_deposit=0;
        } else {
            $wallet->enable_deposit=Request::get('enable_deposit');
        }
        //$wallet->enable_withdraw = !empty(Request::get('enable_withdraw'))? Request::get('enable_withdraw'):0;
        if ((Request::get('enable_withdraw'))=='') {
            $wallet->enable_withdraw=0;
        } else {
            $wallet->enable_withdraw=Request::get('enable_withdraw');
        }

        if ((Request::get('enable_trading'))=='') {
            $wallet->enable_trading=0;
        } else {
            $wallet->enable_trading=Request::get('enable_trading');
        }
        
        $wallet->download_wallet_client=Request::get('download_wallet_client');
        
        $wallet->save();
        //echo "<pre>Input"; print_r(Request::all()); echo "</pre>";
        //echo "<pre>wallet"; print_r($wallet); echo "</pre>";
        return Redirect::to('admin/manage/wallets')->with('success', Lang::get('messages.update_success'));
    }

    public function deleteWallet()
    {
        $wallet_id = Request::get('wallet_id');
        $wallet = Wallet::find($wallet_id);
        if (isset($wallet->id)) {
            Wallet::where('id', $wallet_id)->delete();
            FeeWithdraw::where("wallet_id", $wallet_id)->delete();
            WalletLimitTrade::where("wallet_id", $wallet_id)->delete();
            $message = $wallet->type." ".Lang::get('messages.delete_success');
            if (Request::get('isAjax')) {
                echo json_encode(array('status'=>'success', 'message'=>$message ));
                exit;
            } else {
                return Redirect::to('admin/manage/wallets')->with('success', $message);
            }
        } else {
            $message = Lang::get('messages.wallet_not_exist');
            if (Request::get('isAjax')) {
                echo json_encode(array('status'=>'error', 'message'=>$message ));
                exit;
            } else {
                return Redirect::to('admin/manage/wallets')->with('error', $message);
            }
        }
        
    }

    public function addNewMarket()
    {
        $wallet_from = Request::get('wallet_from');
        $wallet_to = Request::get('wallet_to');
        if ($wallet_from == $wallet_to) {
            return Redirect::to('admin/manage/markets')->with('error', Lang::get('messages.walletfrom_different_walletto'));
        }
        $check_market = Market::where('wallet_from', '=', $wallet_from)->where('wallet_to', '=', $wallet_to)->first();
        if (isset($check_market->id)) {
            return Redirect::to('admin/manage/markets')->with('error', Lang::get('messages.market_exist'));
        }
        $market = new Market();
        $market->wallet_from = $wallet_from;
        $market->wallet_to = $wallet_to;
        $market->save();
        if ($market->id) {
            //add fee
            $fee_trade = new FeeTrade();
            $fee_trade->market_id = $market->id;
            $fee_trade->fee_sell = 0;
            $fee_trade->fee_buy = 0;
            $fee_trade->save();
            return Redirect::to('admin/manage/markets')->with('success', Lang::get('messages.market_created_success'));
        } else {
            $error = $user->errors()->all(':message');
            return Redirect::to('admin/manage/markets')->with('error', $error);
        }
    }
    public function deleteMarket()
    {
        $market_id = Request::get('market_id');
        $market = Market::find($market_id);
        if (isset($market->id)) {
            Market::where('id', $market_id)->delete();
            FeeTrade::where('market_id', $market_id)->delete();
            $message = Lang::get('messages.delete_success');
            if (Request::get('isAjax')) {
                echo json_encode(array('status'=>'success', 'message'=>$message ));
                exit;
            } else {
                return Redirect::to('admin/manage/markets')->with('success', $message);
            }
        } else {
            $message = Lang::get('messages.market_not_exist');
            if (Request::get('isAjax')) {
                echo json_encode(array('status'=>'error', 'message'=>$message ));
                exit;
            } else {
                return Redirect::to('admin/manage/markets')->with('error', $message);
            }
        }
        
    }

    public function addNewPost()
    {
        $type = Request::get('type');
        $title = Request::get('title');
        $body = Request::get('body');
        $show_menu = Request::get('show_menu');
        if (!empty($type) && !empty($title) && !empty($body)) {
            $post = new Post();
            $post->title = $title;
            $post->permalink = $post->createPermalink($title);
            $post->body = $body;
            $post->type = $type;
            $post->show_menu = $show_menu;
            $post->save();
            if ($post->id) {
                return Redirect::to('admin/content/all-'.$post->type)->with('success', Lang::get('messages.created_success_param', array('object'=>$type)));
            } else {
                $error = $user->errors()->all(':message');
                return Redirect::to('admin/content/add-'.$post->type)->with('error', $error);
            }
        } else {
            return Redirect::to('admin/content/add-'.$post->type)->with('error', Lang::get('messages.fill_all_fields'));
        }
    }
    public function editPost($post_id)
    {
        $data['post'] = Post::find($post_id);
        return view('admin.pages.edit_post', $data);
    }
    public function doEditPost()
    {
        $type = Request::get('type');
        $title = Request::get('title');
        $body = Request::get('body');
        $permalink = Request::get('permalink');
        $post_id = Request::get('post_id');
        $show_menu = Request::get('show_menu');
        if (!empty($type) && !empty($title) && !empty($body)) {
            $post = Post::find($post_id);
            $post->title = $title;
            //$post->permalink = $post->createPermalink($title);
            $post->permalink =  $post->cleanText($permalink);
            $post->body = $body;
            $post->type = $type;
            $post->show_menu = $show_menu;
            $post->save();
            if ($post->id) {
                return Redirect::to('admin/content/all-'.$type)->with('success', Lang::get('messages.updated_success_param', array('object'=>$type)));
            } else {
                $error = $user->errors()->all(':message');
                return Redirect::to('admin/content/add-news')->with('error', $error);
            }
        } else {
            return Redirect::to('admin/content/add-news')->with('error', Lang::get('messages.fill_all_fields'));
        }
    }
    public function deletePost()
    {
        $post_id = Request::get('post_id');
        $post = Post::find($post_id);
        if (isset($post->id)) {
            Post::where('id', $post_id)->delete();
            $message = $post->type." ".$post->title." ".Lang::get('messages.delete_success');
            if (Request::get('isAjax')) {
                echo json_encode(array('status'=>'success', 'message'=>$message ));
                exit;
            } else {
                return Redirect::to('admin/content/all-'.$post->type)->with('success', $message);
            }
        } else {
            $message =Lang::get('messages.article_not_exist');
            if (Request::get('isAjax')) {
                echo json_encode(array('status'=>'error', 'message'=>$message ));
                exit;
            } else {
                return Redirect::to('admin/content')->with('error', $message);
            }
        }
    }

    public function addCoinNews()
    {
        $market_id = Request::get('market_id');
        $title = Request::get('title');
        $content = Request::get('content');

        $market = Market::find($market_id);

        $wallet_from = Wallet::where('id', $market->wallet_from)->first();
        $wallet_to = Wallet::where('id', $market->wallet_to)->first();

        $market_from_name= isset($wallet_from->name) ? $wallet_from->name : '';
        $market_to_name= isset($wallet_to->name) ? $wallet_to->name : '';

        $market_from_type= isset($wallet_from->type) ? $wallet_from->type : '';
        $market_to_type= isset($wallet_to->type) ? $wallet_to->type : '';

        $market_type = "{$market_from_type}/{$market_to_type}";
        $market_name = "{$market_from_name}/{$market_to_name}";

        if (!empty($market_id) && !empty($title) && !empty($content)) {
            $news = new News();
            $news->title = $title;
            $news->content = $content;
            $news->market_id = $market_id;
            $news->market_type = $market_type;
            $news->market_name = $market_name;
            $news->save();
            if ($news->id) {
                return Redirect::to('admin/content/all-coin-news')->with('success', Lang::get('messages.created_success_param', array('object'=>'Coin News')));
            } else {
                $error = $user->errors()->all(':message');
                return Redirect::to('admin/content/add-coin-news')->with('error', $error);
            }
        } else {
            return Redirect::to('admin/content/add-coin-news')->with('error', Lang::get('messages.fill_all_fields'));
        }
    }
    public function editCoinNews($news_id)
    {
        $markets = Market::get();

        $wallets = Wallet::orderby('type')->get();
        $market_list = array();
        foreach ($markets as $market) {
            $from = $to = "";
            foreach ($wallets as $wallet) {
                if ($market->wallet_from == $wallet->id) {
                    $from = $wallet->type;
                }
                if ($market->wallet_to == $wallet->id) {
                    $to = $wallet->type;
                }
            }
            $market_list[$market->id] = "{$from}/{$to}";
        }
        $data['market_list'] = $market_list;

        $data['news'] = News::find($news_id);
        return view('admin.pages.edit_coin_news', $data);
    }
    public function doEditCoinNews()
    {
        $market_id = Request::get('market_id');
        $title = Request::get('title');
        $content = Request::get('content');
        $news_id = Request::get('news_id');

        $market = Market::find($market_id);

        $wallet_from = Wallet::where('id', $market->wallet_from)->first();
        $wallet_to = Wallet::where('id', $market->wallet_to)->first();

        $market_from_name= isset($wallet_from->name) ? $wallet_from->name : '';
        $market_to_name= isset($wallet_to->name) ? $wallet_to->name : '';

        $market_from_type= isset($wallet_from->type) ? $wallet_from->type : '';
        $market_to_type= isset($wallet_to->type) ? $wallet_to->type : '';

        $market_type = "{$market_from_type}/{$market_to_type}";
        $market_name = "{$market_from_name}/{$market_to_name}";

        if (!empty($news_id) && !empty($title) && !empty($content)) {
            $news = News::find($news_id);
            $news->title = $title;
            $news->content = $content;
            $news->market_id = $market_id;
            $news->market_type = $market_type;
            $news->market_name = $market_name;
            $news->save();
            if ($news->id) {
                return Redirect::to('admin/content/all-coin-news')->with('success', Lang::get('messages.updated_success_param', array('object'=>'Coin News')));
            } else {
                $error = $user->errors()->all(':message');
                return Redirect::to('admin/content/add-coin-news')->with('error', $error);
            }
        } else {
            return Redirect::to('admin/content/add-coin-news')->with('error', Lang::get('messages.fill_all_fields'));
        }
    }
    public function deleteCoinNews()
    {
        $news_id = Request::get('news_id');
        $news = News::find($news_id);
        if (isset($news->id)) {
            News::where('id', $news_id)->delete();
            $message = $news->title." ".Lang::get('messages.delete_success');
            if (Request::get('isAjax')) {
                echo json_encode(array('status'=>'success', 'message'=>$message ));
                exit;
            } else {
                return Redirect::to('admin/content/all-coin-news')->with('success', $message);
            }
        } else {
            $message = "Entry does not exist";
            if (Request::get('isAjax')) {
                echo json_encode(array('status'=>'error', 'message'=>$message ));
                exit;
            } else {
                return Redirect::to('admin/content')->with('error', $message);
            }
        }
    }

    public function doSendCoin()
    {
        $amount = Request::get('amount');
        $address = Request::get('address');
        $wallet_type =Request::get('wallet_type');

        $wallet = Wallet::where('type', $wallet_type)->first();
        //echo "<pre>wallet: "; print_r($wallet->toArray()); echo "</pre>";
  
        $user = Confide::user();
        if ($user->hasRole('Admin')) {
            try {
                $wallet->connectJsonRPCclient($wallet->wallet_username, $wallet->wallet_password, $wallet->wallet_ip, $wallet->port);
                $txid=$wallet->sendToAddress($address, $amount);
                $fee = $wallet->getTxFee();
                $net_total = $amount-$fee;
                if ($txid) {
                    Withdraw::insert(array('user_id' => $user->id, 'wallet_id' => $wallet->id, 'to_address'=>$address, 'amount'=>$amount, 'fee_amount'=>$fee,'receive_amount'=>$net_total,'created_at'=>date('Y-m-d H:i:s'),'status'=>1,'transaction_id'=>$txid));
                    return Redirect::to('admin/manage/funds')
                        ->with('success', "You withdrawed ".sprintf('%.8f', $net_total)." ".$wallet->type." to address: ".$address.".");
                } else {
                    return Redirect::to('admin/manage/funds')
                        ->with('notice', "Can not ".$wallet->type.".");
                }
                
            } catch (Exception $e) {
                return Redirect::to('admin/manage/funds')
                        ->with('notice', 'Caught exception 4 - ASC: '); //"Not connect to this wallet."
                        //->with( 'notice', 'Caught exception: '.$e->getMessage() ); //"Not connect to this wallet."
            }
        }
    }

    public function formRestore()
    {
        require app_path().'/libraries/bigdump.php';
        $data['version'] = '0.35b';
        $error = false;
        $file  = false;
        $message_error='';
        // Check PHP version

        if (!$error && !function_exists('version_compare')) {
            $message_error .= "<p class=\"error\">PHP version 4.1.0 is required for BigDump to proceed. You have PHP ".phpversion()." installed. Sorry!</p>\n";
            $error=true;
        }
        // Check if mysql extension is available

        if (!$error && !function_exists('mysql_connect')) {
            $message_error .="<p class=\"error\">There is no mySQL extension available in your PHP installation. Sorry!</p>\n";
            $error=true;
        }
        if ($error) {
            $data['message_error'] = $message_error;
            return view('admin.backup_restore.form_restore', $data);
        } else {
            $upload_max_filesize=ini_get("upload_max_filesize");
            if (preg_match("/([0-9]+)K/i", $upload_max_filesize, $tempregs)) {
                $upload_max_filesize=$tempregs[1]*1024;
            }
            if (preg_match("/([0-9]+)M/i", $upload_max_filesize, $tempregs)) {
                $upload_max_filesize=$tempregs[1]*1024*1024;
            }
            if (preg_match("/([0-9]+)G/i", $upload_max_filesize, $tempregs)) {
                $upload_max_filesize=$tempregs[1]*1024*1024*1024;
            }
            $data['upload_max_filesize'] = $upload_max_filesize;

            $charset = DB::select("SHOW VARIABLES LIKE 'character_set_connection'");
            $data['charset'] = $charset[0]->Value;
          //echo "<pre>charset: "; print_r($charset); echo "</pre>";
        }
        return view('admin.backup_restore.form_restore', $data);
    }

    public function doRestore()
    {
    }

    public function formBackup()
    {
        require app_path().'/libraries/bigdump.php';
        $data['version'] = '0.35b';
        return view('admin.backup_restore.backup', $data);
    }

    public function doBackup()
    {
        
    }

    public function addNewLimitTrade()
    {
        $wallet_id = strtoupper(Request::get('wallet_id'));
        $min_amount = Request::get('min_amount');
        $max_amount = Request::get('max_amount');
        $find_record=WalletLimitTrade::where('wallet_id', $wallet_id)->first();
        if (!isset($find_record->wallet_id)) {
            $limitTrade=new WalletLimitTrade();
            $limitTrade->wallet_id=$wallet_id;
            $limitTrade->min_amount=$min_amount;
            $limitTrade->max_amount=$max_amount;
            $limitTrade->save();
            if ($limitTrade->id) {
                return Redirect::to('admin/setting/limit-trade')->with('success', Lang::get('messages.limit_trade_created'));
            } else {
                return Redirect::to('admin/setting/limit-trade')->with('error', Lang::get('messages.cannot_create_limit_trade'));
            }
        } else {
            return Redirect::to('admin/setting/limit-trade')->with('error', Lang::get('messages.limit_trade_already_set'));
        }
    }

    public function editLimitTrade($wallet_id = '')
    {
        if ($wallet_id=='') {
            return Redirect::to('admin/setting/limit-trade');
        }
        $limit_trade = WalletLimitTrade::leftjoin('wallets', 'wallet_limittrade.wallet_id', '=', 'wallets.id')->select('wallet_limittrade.*', 'wallets.type as wallet_type', 'wallets.name as wallet_name')->where('wallet_id', $wallet_id)->first();
        if (!isset($limit_trade->id)) {
            return Redirect::to('admin/setting/limit-trade')->with('error', Lang::get('messages.limit_trade_not_exist'));
        }
        $data['limit_trade'] = $limit_trade;
        return view('admin.limittrade.edit_limittrade', $data);
    }
    public function doEditLimitTrade()
    {
        $wallet_id = strtoupper(Request::get('wallet_id'));
        $min_amount = Request::get('min_amount');
        $max_amount = Request::get('max_amount');
        $limitTrade=WalletLimitTrade::where('wallet_id', $wallet_id)->first();
        if (isset($limitTrade->wallet_id)) {
            $limitTrade->min_amount=$min_amount;
            $limitTrade->max_amount=$max_amount;
            $limitTrade->save();
            if ($limitTrade->id) {
                return Redirect::to('admin/setting/limit-trade')->with('success', Lang::get('messages.limit_trade_updated'));
            } else {
                return Redirect::to('admin/setting/limit-trade')->with('error', Lang::get('messages.cannot_update_limit_trade'));
            }
        } else {
            return Redirect::to('admin/setting/limit-trade')->with('error', Lang::get('messages.limit_trade_not_exist'));
        }
    }

    public function addFee()
    {
        $re = Request::except('_token');
        try {
            $a = FeeTrade::insert(
                [
                    "fee_sell" => (double) $re['add_sell_fee'],
                    "fee_buy" => (double) $re['add_buy_fee'],
                    "market_id" => (int)$re['fee_market_id']
                ]
            );
        } catch (\Exception $e) {
            preg_match('/Duplicate entry (.*) for key \'market_id\'/i', $e->getMessage(), $n);
            return Redirect::to('/admin/setting/fee')->with('error', $n[0]);
        }
        return Redirect::to('/admin/setting/fee')->with('success', 'Success');
    }
}
