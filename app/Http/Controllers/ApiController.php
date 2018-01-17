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

class ApiController extends Controller
{
    /*
	|--------------------------------------------------------------------------
	| API  Controller
	|--------------------------------------------------------------------------
	|
	| You may wish to use controllers instead of, or in addition to, Closure
	| based routes. That's great! Here is an example controller method to
	| get you started. To route to this controller, just add the route:
	|
	|	Route::get('/', 'ApiController@function');
	|
	*/
   /*
   public function api($method=''){
   		echo 'aaaaaaa';
   }
   */
   
    public static function api($method = '')
    {
        $setting = new Setting;
        $num_transaction_display = $setting->getSetting('num_transaction_display', 0);

        //24hr stats
        if ($method=='singlemarket24h' || $method=='allmarket24h') {
            if ($method=='singlemarket24h') {
                if (isset($_REQUEST['marketid']) && is_numeric($_REQUEST['marketid'])) {
                    $market_sql = 'select * from market where id='.$_REQUEST['marketid'];
                } else {
                    $output = json_encode(array('success' => 0,'error'=>Lang::get('messages.link_not_correct')));
                    echo $output;
                    exit;
                }
            } else {
                $market_sql = 'select * from market';
            }
            
            $markets = DB::select($market_sql);
            $getarray = array();
            $i=0;
            $output = json_encode(array('return' => $markets));

            $trade = new Trade();
            $wallets_temp = Wallet::get();
            $wallets = array();
            foreach ($wallets_temp as $wallet) {
                $wallets[$wallet->id] = $wallet;
            }
            
            
            $i=0;
            foreach ($markets as $m) {
                $market_prices = $trade->getBlockPrice($m->id);

                $getarray[$i] = array(
                    'market_id' => $m->id,
                    'currency' => $wallets[$m->wallet_from]->type,
                    'currency_long' => $wallets[$m->wallet_from]->name,
                    'base_currency' => $wallets[$m->wallet_to]->type,
                    'base_currency_long' => $wallets[$m->wallet_to]->name,
                    
                    'last_price' => (empty($market_prices['latest_price'])) ? sprintf('%.8f', 0) : sprintf('%.8f', $market_prices['latest_price']),
                    'previous_day_price' => sprintf('%.8f', 0),
                    '24h_high' => (empty($market_prices['get_prices']->max)) ? sprintf('%.8f', 0) : sprintf('%.8f', $market_prices['get_prices']->max),
                    '24h_low' => (empty($market_prices['get_prices']->min)) ? sprintf('%.8f', 0) : sprintf('%.8f', $market_prices['get_prices']->min),
                    '24h_volume' => (empty($market_prices['get_prices']->volume)) ? sprintf('%.8f', 0) : sprintf('%.8f', $market_prices['get_prices']->volume),
                    '24h_volume_base' => sprintf('%.8f', 0),
                );
                $i++;
            }

            if (count($getarray)==0) {
                $output = json_encode(array('success' => 0,'value'=>'null'));
            } else {
                $output = json_encode(array('success' => 1,'return' => $getarray));
            }
            echo $output;
        }

        //market trade
        if ($method=='singlemarket' || $method=='allmarket') {
            if ($method=='singlemarket') {
                if (isset($_REQUEST['marketid']) && is_numeric($_REQUEST['marketid'])) {
                    $market_sql = 'select * from market where id='.$_REQUEST['marketid'];
                } else {
                    $output = json_encode(array('success' => 0,'error'=>Lang::get('messages.link_not_correct')));
                    echo $output;
                    exit;
                }
            } else {
                $market_sql = 'select * from market';
            }
            $markets = DB::select($market_sql);
            //print_r($markets);
            $getarray = array();
            $i=0;
            $output = json_encode(array('return' => $markets));
            //echo $output.'<br>';
            foreach ($markets as $item) {
                $wallet_f = $item->wallet_from;
                $wallet_sql = 'select type,name from wallets where id='.$wallet_f;
                $wallet = DB::select($wallet_sql);
                foreach ($wallet as $w) {
                    $wf_type=$w->type;
                    $wf_name=$w->name;
                }
                
                $wallet_t = $item->wallet_to;
                $wallet_sql = 'select type,name from wallets where id='.$wallet_t;
                $wallet = DB::select($wallet_sql);
                foreach ($wallet as $w) {
                    $wt_type=$w->type;
                    $wt_name=$w->name;
                }
                $label = $wf_type.'/'.$wt_type ;
                
                //get trade from market
                $market_id = $item->id;
                $market_sql = 'select * from trade_history where market_id='.$market_id;
                $market = DB::select($market_sql);
                
                /*
				$market_sql_buy = 'select * from trade_history where market_id='.$market_id.' and type="buy"';
				$market_buy = DB::select($market_sql_buy);
				if(count($market_buy)==0)$buyorders = 'null';
				
				$market_sql_sell = 'select * from trade_history where market_id='.$market_id.' and type="sell"';
				$market_sell = DB::select($market_sql_sell);
				if(count($market_sell)==0)$sellorders = 'null';
				*/

                $recenttrades = 'null';

                if (count($market)==0) {
                    $lasttradeprice = '0.00000000';
                    $lasttradetime = '0000-00-00 00:00:00';
                } else {
                    //get last info
                    //$market_last_sql = 'select max(updated_at) as updated_at, price  from trade_history where market_id='.$market_id;
                    $market_last_sql = 'select updated_at, price  from trade_history where market_id='.$market_id.' order by updated_at desc limit 1';
                    $market_last = DB::select($market_last_sql);
                    foreach ($market_last as $m) {
                        $lasttradeprice = $m->price;
                        $lasttradetime = $m->updated_at;
                    }

                    /*
					$recenttrades = array();
					$sellorders = array();
					$buyorders = array();
					$j=0;
					foreach($market as $m){
						$recenttrades[$j] = array('id'=>$m->id,'time'=>$m->updated_at,'price'=>sprintf('%.8f',$m->price),'amount'=>$m->amount);						
						$j++;
					} 
					if(count($market_buy)!=0){$j=0;
						foreach($market_buy as $m){
							$buyorders[$j] = array('price'=>sprintf('%.8f',$m->price),'amount'=>$m->amount);
							$j++;
						}
					}
					if(count($market_sell)!=0){$j=0;
						foreach($market_sell as $m){
							$sellorders[$j] = array('price'=>sprintf('%.8f',$m->price),'amount'=>$m->amount);
							$j++;
						}
					}
					*/
                }
                
                $order = new Order();
                $_sellorders = $order->getOrders($market_id, 'sell', $num_transaction_display);
                $_buyorders = $order->getOrders($market_id, 'buy', $num_transaction_display);
                
                $buyorders = array();
                if (isset($_buyorders)) {
                    foreach ($_buyorders as $bo) {
                        $buyorders[] = array(
                            'price' => sprintf('%.8f', $bo->price),
                            'amount' => sprintf('%.8f', $bo->total_from_value),
                            'total' => sprintf('%.8f', $bo->total_to_value),
                        );
                    }
                }
                $sellorders = array();
                if (isset($_sellorders)) {
                    foreach ($_sellorders as $so) {
                        $sellorders[] = array(
                            'price' => sprintf('%.8f', $so->price),
                            'amount' => sprintf('%.8f', $so->total_from_value),
                            'total' => sprintf('%.8f', $so->total_to_value),
                        );
                    }
                }

                $getarray[$i] = array(
                    'marketid' => $item->id,
                    'label' => $label,
                    'lasttradeprice'=>sprintf('%.8f', $lasttradeprice),
                    'lasttradetime'=>$lasttradetime,
                    'primaryname'=>$wf_name,
                    'primarycode'=>$wf_type,
                    'secondaryname'=>$wt_type,
                    'secondarycode'=>$wt_name,
                    'recenttrades'=>$recenttrades,
                    'sellorders'=>$sellorders,
                    'buyorders'=>$buyorders
                );
                $i++;
            }
            if (count($getarray)==0) {
                $output = json_encode(array('success' => 0,'value'=>'null'));
            } else {
                $output = json_encode(array('success' => 1,'return' => $getarray), JSON_PRETTY_PRINT);
            }
            echo $output;
             
            //$json = file_get_contents('http://pubapi.cryptsy.com/api.php?method=marketdatav2');
            //$data = json_decode($json);
            //print_r ($data);
            //http://www.domain.com/page/api?method=allmarket
        }
        
        //orders
        
        if ($method=='singleorder' || $method=='allorder') {
            if ($method=='singleorder') {
                if (isset($_REQUEST['marketid']) && is_numeric($_REQUEST['marketid'])) {
                    $market_sql = 'select * from market where id='.$_REQUEST['marketid'];
                } else {
                    $output = json_encode(array('success' => 0,'error'=>Lang::get('messages.link_not_correct')));
                    echo $output;
                    exit;
                }
            } else {
                $market_sql = 'select * from market';
            }
            $market = DB::select($market_sql);
            //print_r($market);
            $getarray = array();
            $i=0;
            $output = json_encode(array('return' => $market));
            //echo $output.'<br>';

            foreach ($market as $item) {
                $wallet_f = $item->wallet_from;
                $wallet_sql = 'select type,name from wallets where id='.$wallet_f;
                $wallet = DB::select($wallet_sql);
                foreach ($wallet as $w) {
                    $wf_type=$w->type;
                    $wf_name=$w->name;
                }
                
                $wallet_t = $item->wallet_to;
                $wallet_sql = 'select type,name from wallets where id='.$wallet_t;
                $wallet = DB::select($wallet_sql);
                foreach ($wallet as $w) {
                    $wt_type=$w->type;
                    $wt_name=$w->name;
                }
                $label = $wf_type.'/'.$wt_type ;
                
                //get trade from market
                $market_id = $item->id;

                /*
				$market_sql = 'select * from orders where market_id='.$market_id;
				$market = DB::select($market_sql);
				
				$market_sql_buy = 'select * from orders where market_id='.$market_id.' and type="buy"';
				$market_buy = DB::select($market_sql_buy);
				if(count($market_buy)==0)$buyorders = 'null';
				
				$market_sql_sell = 'select * from orders where market_id='.$market_id.' and type="sell"';
				$market_sell = DB::select($market_sql_sell);
				if(count($market_sell)==0)$sellorders = 'null';
	
				if(count($market)==0){
					$lasttradeprice = '0.00000000';
					$lasttradetime = '0000-00-00 00:00:00';
				}else{
					//get last info
					$market_last_sql = 'select max(updated_at) as updated_at, price  from orders where market_id='.$market_id;
					$market_last = DB::select($market_last_sql);
					foreach($market_last as $m){
						$lasttradeprice = sprintf('%.8f',$m->price); 
						$lasttradetime = $m->updated_at;
					}
					
					$sellorders = array();
					$buyorders = array();
					$j=0;
					 
					if(count($market_buy)!=0){$j=0;
						foreach($market_buy as $m){
							$buyorders[$j] = array('price'=>sprintf('%.8f',$m->price),'amount'=>sprintf('%.8f',$m->from_value));
							$j++;
						}
					}
					if(count($market_sell)!=0){$j=0;
						foreach($market_sell as $m){
							$sellorders[$j] = array('price'=>sprintf('%.8f',$m->price),'amount'=>sprintf('%.8f',$m->from_value));
							$j++;
						}
					}
				}
				*/

                $order = new Order();
                $_sellorders = $order->getOrders($market_id, 'sell', $num_transaction_display);
                $_buyorders = $order->getOrders($market_id, 'buy', $num_transaction_display);
                
                $buyorders = array();
                if (isset($_buyorders)) {
                    foreach ($_buyorders as $bo) {
                        $buyorders[] = array(
                            'price' => sprintf('%.8f', $bo->price),
                            'amount' => sprintf('%.8f', $bo->total_from_value),
                            'total' => sprintf('%.8f', $bo->total_to_value),
                        );
                    }
                }
                $sellorders = array();
                if (isset($_sellorders)) {
                    foreach ($_sellorders as $so) {
                        $sellorders[] = array(
                            'price' => sprintf('%.8f', $so->price),
                            'amount' => sprintf('%.8f', $so->total_from_value),
                            'total' => sprintf('%.8f', $so->total_to_value),
                        );
                    }
                }

                $getarray[$i] = array(
                    'marketid' => $item->id,
                    'label' => $label,
                    'primaryname'=>$wf_name,
                    'primarycode'=>$wf_type,
                    'secondaryname'=>$wt_type,
                    'secondarycode'=>$wt_name,
                    'sellorders'=>$sellorders,
                    'buyorders'=>$buyorders
                );
                $i++;
            }
            if (count($getarray)==0) {
                $output = json_encode(array('success' => 0));
            } else {
                $output = json_encode(array('success' => 1,'return' => $getarray));
            }
            echo $output;
            //https://www.sweedx.com/page/api?method=singleorder&marketid
        }

        if ($method=='lastprice') {
            if (isset($_REQUEST['marketid']) && is_numeric($_REQUEST['marketid'])) {
                $market_id = $_REQUEST['marketid'];
                $market_sql = 'select * from market where id='.$market_id;
                $market = DB::select($market_sql);

                $trade = new Trade();
                $data_price = $trade->getBlockPrice($market_id);

                echo json_encode(array(
                    'latest_price' => sprintf('%.8f', $data_price['latest_price']),
                ));
                exit();
            } else {
                $output = json_encode(array('success' => 0,'error'=>Lang::get('messages.link_not_correct')));
                echo $output;
                exit();
            }
        }

        if ($method=='getmarkets') {
            if (isset($_REQUEST['key']) && !empty($_REQUEST['key']) && isset($_REQUEST['sign']) && !empty($_REQUEST['sign'])) {
                $market_sql = 'select * from market';
                $market = DB::select($market_sql);
                //print_r($market);
                $getarray = array();
                $i=0;
                $output = json_encode(array('return' => $market));
                //echo $output.'<br>';
                foreach ($market as $item) {
                    $wallet_f = $item->wallet_from;
                    $wallet_sql = 'select type,name from wallets where id='.$wallet_f;
                    $wallet = DB::select($wallet_sql);
                    foreach ($wallet as $w) {
                        $wf_type=$w->type;
                        $wf_name=$w->name;
                    }
                    
                    $wallet_t = $item->wallet_to;
                    $wallet_sql = 'select type,name from wallets where id='.$wallet_t;
                    $wallet = DB::select($wallet_sql);
                    foreach ($wallet as $w) {
                        $wt_type=$w->type;
                        $wt_name=$w->name;
                    }
                    $label = $wf_type.'/'.$wt_type ;
                    
                    //get trade from market
                    $market_id = $item->id;
                    $market_sql = 'select * from trade_history where market_id='.$market_id;
                    $market = DB::select($market_sql);
                    
                    /*
					$market_sql_buy = 'select * from trade_history where market_id='.$market_id.' and type="buy"';
					$market_buy = DB::select($market_sql_buy);
					if(count($market_buy)==0)$buyorders = 'null';
					
					$market_sql_sell = 'select * from trade_history where market_id='.$market_id.' and type="sell"';
					$market_sell = DB::select($market_sql_sell);
					if(count($market_sell)==0)$sellorders = 'null';
					*/
                    if (count($market)==0) {
                        $lasttradeprice = '0.00000000';
                        $lasttradetime = '0000-00-00 00:00:00';
                        $recenttrades = 'null';
                        $highttradeprice = 'null';
                        $lowtradeprice = 'null';
                    } else {
                        //get last trade info
                        $market_last_sql = 'select max(updated_at) as updated_at, price  from trade_history where market_id='.$market_id;
                        $market_last = DB::select($market_last_sql);
                        foreach ($market_last as $m) {
                            $lasttradeprice = $m->price;
                            $lasttradetime = $m->updated_at;
                        }
                        //get hight trade
                        $market_hight_sql = 'select distinct max(price) as price  from trade_history where market_id='.$market_id;
                        $market_hight = DB::select($market_hight_sql);
                        foreach ($market_hight as $m) {
                            $highttradeprice = $m->price;
                        }
                        //get low trade
                        $market_low_sql = 'select min(price) as price  from trade_history where market_id='.$market_id;
                        $market_low = DB::select($market_low_sql);
                        foreach ($market_low as $m) {
                            $lowtradeprice = $m->price;
                        }
                    }

                    $order = new Order();
                    $_sellorders = $order->getOrders($market_id, 'sell', $num_transaction_display);
                    $_buyorders = $order->getOrders($market_id, 'buy', $num_transaction_display);
                    
                    $buyorders = array();
                    if (isset($_buyorders)) {
                        foreach ($_buyorders as $bo) {
                            $buyorders[] = array(
                                'price' => sprintf('%.8f', $bo->price),
                                'amount' => sprintf('%.8f', $bo->total_from_value),
                                'total' => sprintf('%.8f', $bo->total_to_value),
                            );
                        }
                    }
                    $sellorders = array();
                    if (isset($_sellorders)) {
                        foreach ($_sellorders as $so) {
                            $sellorders[] = array(
                                'price' => sprintf('%.8f', $so->price),
                                'amount' => sprintf('%.8f', $so->total_from_value),
                                'total' => sprintf('%.8f', $so->total_to_value),
                            );
                        }
                    }

                    $getarray[$i] = array(
                        'marketid' => $item->id,
                        'label' => $label,
                        'created'=>$lasttradetime,
                        'primaryname'=>$wf_name,
                        'primarycode'=>$wf_type,
                        'secondaryname'=>$wt_type,
                        'secondarycode'=>$wt_name,
                        'last_trade'=>$lasttradeprice,
                        'high_trade'=>$highttradeprice,
                        'low_trade'=>$lowtradeprice,
                        'sellorders'=>$sellorders,
                        'buyorders'=>$buyorders
                    );
                    $i++;
                }
                if (count($getarray)==0) {
                    $output = json_encode(array('success' => 0,'value'=>'null'));
                } else {
                    $output = json_encode(array('success' => 1,'return' => $getarray));
                }
                //check user account
                $sign = $_REQUEST['sign'];
                $password = $_REQUEST['key'];
                $account_sql = 'select * from users where username="'.$sign.'"';
                $account = DB::select($account_sql);
                if (count($account)!=0) {
                    foreach ($account as $a) {
                        $u_pass = $a->password;
                    }
                    if (Hash::check($password, $u_pass)) {
                        $ch='ok';
                    } else {
                        $output = json_encode(array('success' => 0,'error'=>Lang::get('messages.api_cannot_authorize_check_key')));
                        echo $output;
                        exit;
                    }
                } else {
                    $output = json_encode(array('success' => 0,'error'=>Lang::get('messages.api_cannot_authorize_check_sign_data')));
                    echo $output;
                    exit;
                }
                
                echo $output;
            } else {
                $output = json_encode(array('success' => 0,'error'=>Lang::get('messages.link_not_correct')));
                echo $output;
                exit;
            }
        }
        if ($method=='getwallets') {
            if (isset($_REQUEST['key']) && !empty($_REQUEST['key']) && isset($_REQUEST['sign']) && !empty($_REQUEST['sign'])) {
                $wallets_sql = 'select b.percent_fee, a.id, a.type, a.name from wallets a, fee_withdraw b where a.id=b.wallet_id';
                $wallets = DB::select($wallets_sql);
                //print_r($market);
                $getarray = array();
                $i=0;
                $output = json_encode(array('return' => $wallets));
                //echo $output.'<br>';
                foreach ($wallets as $item) {
                    $getarray[$i] = array('currencyid' => $item->id,'name'=>$item->name,'code'=>$item->type,'withdrawfee'=>$item->percent_fee);
                    $i++;
                }
                if (count($getarray)==0) {
                    $output = json_encode(array('success' => 0,'value'=>'null'));
                } else {
                    $output = json_encode(array('success' => 1,'return' => $getarray));
                }
                //check user account
                $sign = $_REQUEST['sign'];
                $password = $_REQUEST['key'];
                $account_sql = 'select * from users where username="'.$sign.'"';
                $account = DB::select($account_sql);
                if (count($account)!=0) {
                    foreach ($account as $a) {
                        $u_pass = $a->password;
                    }
                    if (Hash::check($password, $u_pass)) {
                        $ch='ok';
                    } else {
                        $output = json_encode(array('success' => 0,'error'=>Lang::get('messages.api_cannot_authorize_check_key')));
                        echo $output;
                        exit;
                    }
                } else {
                    $output = json_encode(array('success' => 0,'error'=>Lang::get('messages.api_cannot_authorize_check_sign_data')));
                    echo $output;
                    exit;
                }
                
                echo $output;
            } else {
                $output = json_encode(array('success' => 0,'error'=>Lang::get('messages.link_not_correct')));
                echo $output;
                exit;
            }
        }
        if ($method=='mydeposits') {
            if (isset($_REQUEST['key']) && !empty($_REQUEST['key']) && isset($_REQUEST['sign']) && !empty($_REQUEST['sign'])) {
                
                //check user account
                $sign = $_REQUEST['sign'];
                $password = $_REQUEST['key'];
                $account_sql = 'select * from users where username="'.$sign.'"';
                $account = DB::select($account_sql);
                if (count($account)!=0) {
                    foreach ($account as $a) {
                        $u_pass = $a->password;
                        $u_id=$a->id;
                    }
                    if (Hash::check($password, $u_pass)) {
                    
                        $deposit_sql = 'select * from deposits d, wallets w where d.wallet_id=w.id and d.user_id="'.$u_id.'"';
                        $deposit = DB::select($deposit_sql);
                        //print_r($market);
                        $getarray = array();
                        $i=0;
                        
                        foreach ($deposit as $item) {
                            $getarray[$i] = array('currencyid' => $item->id,'created'=>$item->created_at,'updated'=>$item->updated_at,'address'=>$item->address,
                            'amount'=>$item->amount,'transactionid'=>$item->transaction_id);
                            $i++;
                        }
                        if (count($getarray)==0) {
                            $output = json_encode(array('success' => 0,'value'=>'null'));
                        } else {
                            $output = json_encode(array('success' => 1,'return' => $getarray));
                        }
                    
                    
                    } else {
                        $output = json_encode(array('success' => 0,'error'=>Lang::get('messages.api_cannot_authorize_check_key')));
                        echo $output;
                        exit;
                    }
                } else {
                    $output = json_encode(array('success' => 0,'error'=>Lang::get('messages.api_cannot_authorize_check_sign_data')));
                    echo $output;
                    exit;
                }
                
                echo $output;
            } else {
                $output = json_encode(array('success' => 0,'error'=>Lang::get('messages.link_not_correct')));
                echo $output;
                exit;
            }
        }
        if ($method=='mywithdraws') {
            if (isset($_REQUEST['key']) && !empty($_REQUEST['key']) && isset($_REQUEST['sign']) && !empty($_REQUEST['sign'])) {
                
                //check user account
                $sign = $_REQUEST['sign'];
                $password = $_REQUEST['key'];
                $account_sql = 'select * from users where username="'.$sign.'"';
                $account = DB::select($account_sql);
                if (count($account)!=0) {
                    foreach ($account as $a) {
                        $u_pass = $a->password;
                        $u_id=$a->id;
                    }
                    if (Hash::check($password, $u_pass)) {
                    
                        $deposit_sql = 'select * from withdraws d, wallets w where d.wallet_id=w.id and d.user_id="'.$u_id.'"';
                        $deposit = DB::select($deposit_sql);
                        //print_r($market);
                        $getarray = array();
                        $i=0;
                        
                        foreach ($deposit as $item) {
                            $getarray[$i] = array('currencyid' => $item->id,'created'=>$item->created_at,'toaddress'=>$item->to_address,
                            'amount'=>$item->amount,'feeamount'=>$item->fee_amount,'receiveamount'=>$item->receive_amount,'transactionid'=>$item->transaction_id);
                            $i++;
                        }
                        if (count($getarray)==0) {
                            $output = json_encode(array('success' => 0,'value'=>'null'));
                        } else {
                            $output = json_encode(array('success' => 1,'return' => $getarray));
                        }
                    } else {
                        $output = json_encode(array('success' => 0,'error'=>Lang::get('messages.api_cannot_authorize_check_key')));
                        echo $output;
                        exit;
                    }
                } else {
                    $output = json_encode(array('success' => 0,'error'=>Lang::get('messages.api_cannot_authorize_check_sign_data')));
                    echo $output;
                    exit;
                }
                
                echo $output;
            } else {
                $output = json_encode(array('success' => 0,'error'=>Lang::get('messages.link_not_correct')));
                echo $output;
                exit;
            }
        }
        if ($method=='mytransfers') {
            if (isset($_REQUEST['key']) && !empty($_REQUEST['key']) && isset($_REQUEST['sign']) && !empty($_REQUEST['sign'])) {
                
                //check user account
                $sign = $_REQUEST['sign'];
                $password = $_REQUEST['key'];
                $account_sql = 'select * from users where username="'.$sign.'"';
                $account = DB::select($account_sql);
                if (count($account)!=0) {
                    foreach ($account as $a) {
                        $u_pass = $a->password;
                        $u_id=$a->id;
                    }
                    if (Hash::check($password, $u_pass)) {
                    
                        $deposit_sql = 'select * from transfer_history where sender='.$u_id.' or receiver='.$u_id;
                        $deposit = DB::select($deposit_sql);
                        //print_r($market);
                        $getarray = array();
                        $i=0;
                        
                        foreach ($deposit as $item) {
                            $sender = $item->sender;
                            $sender_sql = 'select * from users where id='.$sender;
                            $sen = DB::select($sender_sql);
                            foreach ($sen as $s) {
                                $sender_name=$s->username;
                            }
                            
                            $receiver = $item->receiver;
                            $receiver_sql = 'select * from users where id='.$receiver;
                            $receive = DB::select($receiver_sql);
                            foreach ($receive as $r) {
                                $receive_name=$r->username;
                            }
                            
                            $wallet = $item->wallet_id;
                            $wallet_sql = 'select * from wallets where id='.$wallet;
                            $wa = DB::select($wallet_sql);
                            foreach ($wa as $w) {
                                $wallet_name=$w->name;
                            }
                            
                            
                            $getarray[$i] = array('currency'=>$wallet_name,'time'=>$item->created_at,'sender'=>$sender_name,
                            'receiver'=>$receive_name,'amount'=>$item->amount);
                            $i++;
                        }
                        if (count($getarray)==0) {
                            $output = json_encode(array('success' => 0,'value'=>'null'));
                        } else {
                            $output = json_encode(array('success' => 1,'return' => $getarray));
                        }
                    } else {
                        $output = json_encode(array('success' => 0,'error'=>Lang::get('messages.api_cannot_authorize_check_key')));
                        echo $output;
                        exit;
                    }
                } else {
                    $output = json_encode(array('success' => 0,'error'=>Lang::get('messages.api_cannot_authorize_check_sign_data')));
                    echo $output;
                    exit;
                }
                
                echo $output;
            } else {
                $output = json_encode(array('success' => 0,'error'=>Lang::get('messages.link_not_correct')));
                echo $output;
                exit;
            }
        }
        if ($method=='getmydepositaddresses') {
            if (isset($_REQUEST['key']) && !empty($_REQUEST['key']) && isset($_REQUEST['sign']) && !empty($_REQUEST['sign'])) {
                
                //check user account
                $sign = $_REQUEST['sign'];
                $password = $_REQUEST['key'];
                $account_sql = 'select * from users where username="'.$sign.'"';
                $account = DB::select($account_sql);
                if (count($account)!=0) {
                    foreach ($account as $a) {
                        $u_pass = $a->password;
                        $u_id=$a->id;
                    }
                    if (Hash::check($password, $u_pass)) {
                    
                        $deposit_sql = 'select * from user_address_deposit u, wallets w where u.wallet_id=w.id and u.user_id='.$u_id;
                        $deposit = DB::select($deposit_sql);
                        
                        $getarray = array();
                        $i=0;
                        
                        foreach ($deposit as $item) {
                            
                            $getarray[$i] = array('coincode'=>$item->type,'despositaddress'=>$item->addr_deposit);
                            $i++;
                        }
                        if (count($getarray)==0) {
                            $output = json_encode(array('success' => 0,'value'=>'null'));
                        } else {
                            $output = json_encode(array('success' => 1,'return' => $getarray));
                        }
                    } else {
                        $output = json_encode(array('success' => 0,'error'=>Lang::get('messages.api_cannot_authorize_check_key')));
                        echo $output;
                        exit;
                    }
                } else {
                    $output = json_encode(array('success' => 0,'error'=>Lang::get('messages.api_cannot_authorize_check_sign_data')));
                    echo $output;
                    exit;
                }
                
                echo $output;
            } else {
                $output = json_encode(array('success' => 0,'error'=>Lang::get('messages.link_not_correct')));
                echo $output;
                exit;
            }
        }
        if ($method=='allmyorders') {
            if (isset($_REQUEST['key']) && !empty($_REQUEST['key']) && isset($_REQUEST['sign']) && !empty($_REQUEST['sign'])) {
                
                //check user account
                $sign = $_REQUEST['sign'];
                $password = $_REQUEST['key'];
                $account_sql = 'select * from users where username="'.$sign.'"';
                $account = DB::select($account_sql);
                if (count($account)!=0) {
                    foreach ($account as $a) {
                        $u_pass = $a->password;
                        $u_id=$a->id;
                    }
                    if (Hash::check($password, $u_pass)) {
                    
                        $deposit_sql = 'select * from orders where user_id='.$u_id;
                        $deposit = DB::select($deposit_sql);
                        
                        $getarray = array();
                        $i=0;
                        
                        foreach ($deposit as $item) {
                            
                            $getarray[$i] = array('orderid'=>$item->id,'marketid'=>$item->market_id,'created'=>$item->created_at,'ordertype'=>$item->type,
                            'price'=>$item->price,'fromvalue'=>$item->from_value,'tovalue'=>$item->to_value);
                            $i++;
                        }
                        if (count($getarray)==0) {
                            $output = json_encode(array('success' => 0,'value'=>'null'));
                        } else {
                            $output = json_encode(array('success' => 1,'return' => $getarray));
                        }
                    } else {
                        $output = json_encode(array('success' => 0,'error'=>Lang::get('messages.api_cannot_authorize_check_key')));
                        echo $output;
                        exit;
                    }
                } else {
                    $output = json_encode(array('success' => 0,'error'=>Lang::get('messages.api_cannot_authorize_check_sign_data')));
                    echo $output;
                    exit;
                }
                
                echo $output;
            } else {
                $output = json_encode(array('success' => 0,'error'=>Lang::get('messages.link_not_correct')));
                echo $output;
                exit;
            }
        }
        if ($method=='myorders') {
            if (isset($_REQUEST['key']) && !empty($_REQUEST['key']) && isset($_REQUEST['sign']) && !empty($_REQUEST['sign']) && isset($_REQUEST['marketid']) && !empty($_REQUEST['marketid'])) {
                
                //check user account
                $sign = $_REQUEST['sign'];
                $password = $_REQUEST['key'];
                $marketid = $_REQUEST['marketid'];
                $account_sql = 'select * from users where username="'.$sign.'"';
                $account = DB::select($account_sql);
                if (count($account)!=0) {
                    foreach ($account as $a) {
                        $u_pass = $a->password;
                        $u_id=$a->id;
                    }
                    if (Hash::check($password, $u_pass)) {
                    
                        $deposit_sql = 'select * from orders where market_id="'.$marketid.'" and user_id='.$u_id;
                        $deposit = DB::select($deposit_sql);
                        
                        $getarray = array();
                        $i=0;
                        
                        foreach ($deposit as $item) {
                            
                            $getarray[$i] = array('orderid'=>$item->id,'created'=>$item->created_at,'ordertype'=>$item->type,
                            'price'=>$item->price,'fromvalue'=>$item->from_value,'tovalue'=>$item->to_value);
                            $i++;
                        }
                        if (count($getarray)==0) {
                            $output = json_encode(array('success' => 0,'value'=>'null'));
                        } else {
                            $output = json_encode(array('success' => 1,'return' => $getarray));
                        }
                    } else {
                        $output = json_encode(array('success' => 0,'error'=>Lang::get('messages.api_cannot_authorize_check_key')));
                        echo $output;
                        exit;
                    }
                } else {
                    $output = json_encode(array('success' => 0,'error'=>Lang::get('messages.api_cannot_authorize_check_sign_data')));
                    echo $output;
                    exit;
                }
                
                echo $output;
            } else {
                $output = json_encode(array('success' => 0,'error'=>Lang::get('messages.link_not_correct')));
                echo $output;
                exit;
            }
        }
        
    }
}
