<?php


namespace App\Http\Controllers;

use App\Models\Authentication;
use App\Models\Balance;
use App\Models\CoinVote;
use App\Models\Deposit;
use App\Models\FeeTrade;
use App\Models\FeeWithdraw;
use App\Models\Giveawayclaims;
use App\Models\Giveaways;
use App\Models\Limits;
use App\Models\Market;
use App\Models\News;
use App\Models\Notifications;
use App\Models\Order;
use App\Models\Post;
use App\Models\Role;
use App\Models\SecurityQuestion;
use App\Models\Setting;
use App\Models\Trade;
use App\Models\Trade;
use App\Models\Transfer;
use App\Models\User;
use App\Models\UserAddressDeposit;
use App\Models\UserSecurityQuestion;
use App\Models\Vote;
use App\Models\Wallet;
use App\Models\WalletLimitTrade;
use App\Models\Withdraw;

class HomeController extends Controller {

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

	public function index($market_id=''){	
		$data['show_all_markets'] = ($market_id == "") ? true : false;

		$wallets_temp = Wallet::get();
		$wallets = array();
		foreach ($wallets_temp as $wallet) {
			$wallets[$wallet->id] = $wallet;
		}
		$data['wallets'] = $wallets;

		$setting = new Setting;
		$wallet = new Wallet();
		if($market_id == ''){	
			$m = Market::orderBy('id')->first();		
			$market_id = $setting->getSetting('default_market',$m->id); 		
		}
		Session::put('market_id', $market_id);
		$market_default = Market::find($market_id);

		if (!$market_default) {
			return View::make('404',$data);
			exit();
		}

		$wallet_from = isset($market_default->wallet_from) ? $market_default->wallet_from : '';
		$wallet_to = isset($market_default->wallet_to) ? $market_default->wallet_to : '';
		$from = strtoupper($wallet->getType($wallet_from));
		$to = strtoupper($wallet->getType($wallet_to));

		//get name of wallet
		$wallet1=Wallet::where('id',$wallet_from)->first();
		$wallet2=Wallet::where('id',$wallet_to)->first();
		$data['market_from']= isset($wallet1->name) ? $wallet1->name : '';
		$data['market_to']= isset($wallet2->name) ? $wallet2->name : '';

		$data['coinmain'] = $from;
		$data['coinsecond'] = $to;
		$data['coinmain_logo'] = $wallets[$wallet_from]->logo_coin;
		$data['coinsecond_logo'] = $wallets[$wallet_to]->logo_coin;

		//get balance
		$balance = new Balance();
		$data['balance_coinmain'] = sprintf('%.8f',$balance->getBalance($wallet_from,0));
		$data['balance_coinsecond'] = sprintf('%.8f',$balance->getBalance($wallet_to,0));

		//get Sell Lowest
		$data['sell_lowest'] = sprintf('%.8f',0);
		$data['buy_highest'] = sprintf('%.8f',0);
		$order = new Order();
				
		$sell_lowest = $order->getSellLowest($market_id);
		$buy_highest = $order->getBuyHighest($market_id);
		if(isset($sell_lowest->price)) $data['sell_lowest'] = sprintf('%.8f',$sell_lowest->price);
		if(isset($buy_highest->price)) $data['buy_highest'] = sprintf('%.8f',$buy_highest->price);

		//fee_buy, fee_sell
		$fee_trade = new FeeTrade();
		$fee = $fee_trade->getFeeTrade($market_id);
		$data['fee_buy'] = $fee['fee_buy'];
		$data['fee_sell'] = $fee['fee_sell'];

		//get list orders
		$num_transaction_display = $setting->getSetting('num_transaction_display',25);
		$list_sell_orders = $order->getOrders($market_id,'sell',$num_transaction_display);
		$list_buy_orders = $order->getOrders($market_id,'buy',$num_transaction_display);
		$data['sell_orders'] = $list_sell_orders;
		$data['buy_orders'] = $list_buy_orders;
		//echo "<pre>list_buy_orders: "; print_r($list_buy_orders); echo "</pre>";

		$data['market_id']=$market_id;

		$trade_history =Trade::where('market_id', '=', $market_id)->orderBy('id', 'desc')->take($num_transaction_display)->get();
		$data['trade_history'] = $trade_history;


		$current_orders_user = $order->getCurrentOrdersUser($market_id);
		if($current_orders_user){
			$data['current_orders_user'] = $current_orders_user;
		}

		$trade = new Trade();
		$datachart = $trade->getDatasChart($market_id,'6 hour');
		$news = Post::where('type','news')->orderby('created_at','desc')->get();
		$data['news'] = $news;

		//price
		$data_price = $trade->getBlockPrice($market_id);		
		$data["get_prices"] = $data_price['get_prices'];
		$data['latest_price'] = $data_price['latest_price'];

		//limit trade amount
		$limit_trade = WalletLimitTrade::where('wallet_id',$wallet_from)->first();
		//echo "<pre>limit_trade: "; print_r($limit_trade ); echo "</pre>"; exit;
		if($limit_trade) $data['limit_trade']=$limit_trade->toArray();		
		else $data['limit_trade']=array('min_amount'=>0.0001,'max_amount'=>1000);

		//markets


		$_markets = Market::get();
		$all_markets = array();
		foreach($_markets as $m) {
			$market_prices = $trade->getBlockPrice($m->id);
			$all_markets[] = array(
				'latest_price' => (empty($market_prices['latest_price'])) ? sprintf('%.8f',0) : sprintf('%.8f',$market_prices['latest_price']),
				'from' => $wallets[$m->wallet_from]->type,
				'to' => $wallets[$m->wallet_to]->type,
				'from_name' => $wallets[$m->wallet_from]->name,
				'to_name' => $wallets[$m->wallet_to]->name,
				'logo' => $wallets[$m->wallet_from]->logo_coin,
				'market' => $m,
				'prices' => $market_prices['get_prices'],
				'volume' => floatval($market_prices['get_prices']->volume),
			);
		}
		usort($all_markets, function($a, $b) { // anonymous function
			if ($a['volume'] == $b['volume']) {
			    return 0;
			}
			return ($a['volume'] > $b['volume']) ? -1 : 1;
		});
		//print_r($all_markets);
		$data['all_markets'] = $all_markets;

		//news 
		$data['news'] = false;
		if ($data['show_all_markets'] === false) {
			$data['news'] = News::where('market_id',intval($market_id))->orderBy('created_at','desc')->first();
		}

		return View::make('index',$data);
	}

	public function sendEmail() {
		$user = Confide::user();

        $message = Input::get( 'message' );
        $email = ($user) ? $user->email : Input::get( 'email' );
        $name = ($user) ? "{$user->fullname} ({$user->username})" : "Unregistered User";

		$_message = "Name: $name<br />";
		$_message .= "Email Address: $email<br />";
		$_message .= "IP Address: {$_SERVER['REMOTE_ADDR']}<br />";
		$_message .= "Message: ". nl2br(strip_tags($message)) . "<br />";
		
		require_once(base_path()."/phpmailer/class.phpmailer.php");
		$mail = new PHPMailer();
		
		$mail->AddAddress('support@cryptex.biz', 'CrypTex');
		$mail->AddReplyTo($email, $name);
		$mail->SetFrom($email, $name);
		
		$mail->Subject = "Cryptex Support: $name";
		$mail->MsgHTML($_message);
		$mail->AltBody = nl2br(strip_tags($_message));
		$result = $mail->Send();

		// Redirect with success message
		return Redirect::to('page/contact')->with( 'notice', 'Your message has been sent!' );
	} 

	public function submitCoin() {
		$user = Confide::user();

        $message = Input::get( 'comments' );
        $email = ($user) ? $user->email : Input::get( 'email' );
        $name = ($user) ? $user->fullname : "Unregistered User";

		$_message = "Name: $name<br />";
		$_message .= "Email Address: $email<br />";
		$_message .= "IP Address: {$_SERVER['REMOTE_ADDR']}<br />";
		
		$_message .= "Name of Coin: ". trim(strip_tags( Input::get( 'coin_name' ) )) . "<br />";
		$_message .= "3 digit Ticker: ". trim(strip_tags( Input::get( 'coin_ticker' ) )) . "<br />";
		$_message .= "Forum Thread: ". trim(strip_tags( Input::get( 'coin_thread' ) )) . "<br />";
		$_message .= "Are you the Developer?: ". trim(strip_tags( Input::get( 'coin_dev' ) )) . "<br />";

		$_message .= "Comments: ". nl2br(strip_tags( Input::get( 'comments' ) )) . "<br />";
		
		require_once(base_path()."/phpmailer/class.phpmailer.php");
		$mail = new PHPMailer();
		
		$mail->AddAddress('support@cryptex.biz', 'CrypTex');
		$mail->AddReplyTo($email, $name);
		$mail->SetFrom($email, $name);

		$is_dev = (strtolower(trim(strip_tags( Input::get( 'coin_dev' ) ))) == 'yes') ? ' from dev' : '';
		$mail->Subject = trim(strip_tags( Input::get( 'coin_ticker' ) )) . " Coin Submission $is_dev";
		$mail->MsgHTML($_message);
		$mail->AltBody = nl2br(strip_tags($_message));
		$result = $mail->Send();

		// Redirect with success message
		return Redirect::to('page/submit-coin')->with( 'notice', 'Your message has been sent!' );
	}
	
	public function routePage($page=''){
		//echo "<pre>user: "; print_r(Confide::user() ); echo "</pre>";
		switch ($page) {
			case "fees":
				$market = new Market();
				$wallet = new Wallet();
				$fees_trade = FeeTrade::get()->toArray();
				//echo "<pre>list_buy_orders: "; print_r($list_buy_orders); echo "</pre>";
				$fees_withdraw = FeeWithdraw::leftJoin('wallets', 'fee_withdraw.wallet_id', '=', 'wallets.id')
            		->select('fee_withdraw.*', 'wallets.type', 'wallets.name')->get();
				
				foreach ($fees_trade as $key => $value) {
					$wallet_type=$market->getWalletType($value['market_id']);
					$fees_trade[$key]['wallet_from'] = $wallet_type['wallet_from'];
					$fees_trade[$key]['wallet_to'] = $wallet_type['wallet_to'];
				}
				
				$fee['fees_trade'] = $fees_trade;
				$fee['fees_withdraw'] = $fees_withdraw;
				return View::make('fees',$fee);
				break;	
			case "voting":
				$setting=new Setting();				
				$coinvotes = DB::table('coin_votes')
                 ->get();
                try{
		            $wallet = Wallet::where('type','BTC')->first();
	                $wallet->connectJsonRPCclient($wallet->wallet_username,$wallet->wallet_password,$wallet->wallet_ip,$wallet->port);	         
	                $_coinvoites = array();
	                foreach ($coinvotes as $key => $value) {
	                 	$num_vote = Vote::where('coinvote_id','=', $value->id)->count();
	                 	
	                 	//echo "<pre>getreceivedbyaccount"; print_r($wallet->getReceivedByAddress($value->btc_address)); echo "</pre>";//$value->label_address
	                 	$btc_payment = $wallet->getReceivedByAddress($value->btc_address);//'12X9jVe4S8pAqJ7EoKN7B4YwMQpzfgArtX'
	                 	$amount_btc_per_vote=$setting->getSetting('amount_btc_per_vote',0.0001);
	                 	$num_payment = floor($btc_payment/$amount_btc_per_vote);
	                 	//$num_payment = 0;
	                 	//echo "btc_payment: ".$btc_payment;
	                 	//echo "<br>num_payment: ".$num_payment;
	                 	$coinvotes[$key]->num_vote = $num_vote + $num_payment;
	                 	$_coinvoites[] = array(
	                 		'id' => $value->id,
	                 		'code' => $value->code,
	                 		'name' => $value->name,
	                 		'btc_address' => $value->btc_address,
	                 		'label_address' => $value->label_address,
	                 		'num_vote' => $num_vote + $num_payment,
	                 	);
	                }
	                usort($coinvotes, create_function('$a, $b',
   							'if ($a->num_vote == $b->num_vote) return 0; return ($a->num_vote > $b->num_vote) ? -1 : 1;'));
	            }catch (Exception $e) {
	                $data['error_message']= 'Caught exception: '.$e->getMessage()."\n";  //"Not connect to this       
	           		die('Page is in maintenence mode : '.$data['error_message']);
	            }

                //echo "<pre>coinvotes"; print_r($coinvotes); echo "</pre>";
                $data['coinvotes'] = $coinvotes;
				return View::make('voting',$data);
				break;
			case "about":
				return View::make('about');
				break;
			case "security":
				return View::make('security');
				break;
			case "terms":
				return View::make('terms');
				break;
			case "api":
				if(isset($_REQUEST['method'])){
					$method = $_REQUEST['method'];
					$value = $this->api($method);	
				} 
				else{
					$setting=new Setting();
					$data['pusher_app_key']=$setting->getSetting('pusher_app_key','');
					return View::make('api',$data);
				} 
				print_r($value);
				// $result = $this->api_query("getmarkets");
				// echo "<pre>".print_r($result, true)."</pre>";
				break;	
			case "apiprivate":
				$value = $this->apiprivate(); 
				break;	
			case "contact":
				$user = Confide::user();
				$data = array();
		        $setting = new Setting();

		        $data['recaptcha_publickey'] = $setting->getSetting('recaptcha_publickey','');
		        $data['email'] = ($user) ? $user->email : Input::get( 'email' );
		        $data['name'] = ($user) ? $user->fullname : "Unregistered User";

				return View::make('contact',$data);
				break;	
			case "submit-coin":
				$user = Confide::user();
				$data = array();
		        $setting = new Setting();

		        $data['recaptcha_publickey'] = $setting->getSetting('recaptcha_publickey','');
		        $data['email'] = ($user) ? $user->email : Input::get( 'email' );
		        $data['name'] = ($user) ? $user->fullname : "Unregistered User";

				return View::make('submit-coin',$data);
				break;					
			default:				
				return View::make('index');
				break; 
		} 
	}
	public function maintenanceMode(){
		return View::make('maintenance');
	}
	public function getChart(){
		$market_id = $_POST['market_id'];
		$timeSpan = $_POST['timeSpan'];
		$trade = new Trade();
		$datachart = $trade->getDatasChart($market_id,$timeSpan);
		echo $datachart;
		exit;
	}

	public function viewPost($permalink){
    	$post = Post::where('permalink',$permalink)->first();
    	if(!isset($post->id)) return Redirect::to('/')->with( 'error', Lang::get('messages.page_not_found') ); 
    	$data['post'] = $post;
    	return View::make('post',$data);
    }

	public function doTest(){
		//BrainSocket::message('doTest', array('message'=>Wallet::get()->toArray()));
		echo json_encode(Wallet::get()->toArray());	
		exit;
	}

	public function api($method=''){ 
		$setting = new Setting;
		$num_transaction_display = $setting->getSetting('num_transaction_display',0);

		//24hr stats
		if($method=='singlemarket24h' || $method=='allmarket24h'){
			if($method=='singlemarket24h'){
				if(isset($_REQUEST['marketid']) && is_numeric($_REQUEST['marketid'])){
				$market_sql = 'select * from market where id='.$_REQUEST['marketid'];}
				else {$output = json_encode(array('success' => 0,'error'=>Lang::get('messages.link_not_correct')));echo $output;exit;}
			}
			else $market_sql = 'select * from market';
			
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
			foreach($markets as $m) {
				$market_prices = $trade->getBlockPrice($m->id);

				$getarray[$i] = array(
					'id' => $m->id,
					'market' => $wallets[$m->wallet_from]->type,
					'LastPrice' => (empty($market_prices['latest_price'])) ? sprintf('%.8f',0) : sprintf('%.8f',$market_prices['latest_price']),
					'24HourHigh' => (empty($market_prices['get_prices']->max)) ? sprintf('%.8f',0) : sprintf('%.8f',$market_prices['get_prices']->max),
					'24HourHigh' => (empty($market_prices['get_prices']->min)) ? sprintf('%.8f',0) : sprintf('%.8f',$market_prices['get_prices']->min),
					'24HourVolume' => (empty($market_prices['get_prices']->volume)) ? sprintf('%.8f',0) : sprintf('%.8f',$market_prices['get_prices']->volume),
				);
				$i++;
			}

			if(count($getarray)==0) {
				$output = json_encode(array('success' => 0,'value'=>'null'));	
			} else {
				$output = json_encode(array('success' => 1,'return' => $getarray));
			}	
			echo $output;
		}

   		//market trade
		if($method=='singlemarket' || $method=='allmarket'){
			if($method=='singlemarket'){
				if(isset($_REQUEST['marketid']) && is_numeric($_REQUEST['marketid'])){
				$market_sql = 'select * from market where id='.$_REQUEST['marketid'];}
				else {$output = json_encode(array('success' => 0,'error'=>Lang::get('messages.link_not_correct')));echo $output;exit;}
			}
			else $market_sql = 'select * from market';
			$markets = DB::select($market_sql);
			//print_r($markets);
			$getarray = array();
			$i=0;
			$output = json_encode(array('return' => $markets));
			//echo $output.'<br>';
			foreach($markets as $item){
				$wallet_f = $item->wallet_from;
				$wallet_sql = 'select type,name from wallets where id='.$wallet_f;
				$wallet = DB::select($wallet_sql);
				foreach($wallet as $w){$wf_type=$w->type;$wf_name=$w->name;}
				
				$wallet_t = $item->wallet_to;
				$wallet_sql = 'select type,name from wallets where id='.$wallet_t;
				$wallet = DB::select($wallet_sql);
				foreach($wallet as $w){$wt_type=$w->type;$wt_name=$w->name;}
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

				if(count($market)==0){
					$lasttradeprice = '0.00000000';
					$lasttradetime = '0000-00-00 00:00:00';
				}else{
					//get last info
					$market_last_sql = 'select max(updated_at) as updated_at, price  from trade_history where market_id='.$market_id;
					$market_last = DB::select($market_last_sql);
					foreach($market_last as $m){
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
				$_sellorders = $order->getOrders($market_id,'sell',$num_transaction_display);
				$_buyorders = $order->getOrders($market_id,'buy',$num_transaction_display);
				
				$buyorders = array();
				if (isset($_buyorders)) {
					foreach($_buyorders as $bo) {
						$buyorders[] = array(
							'price' => sprintf('%.8f',$bo->price),
							'amount' => sprintf('%.8f',$bo->total_from_value),
							'total' => sprintf('%.8f',$bo->total_to_value),
						);
					}
				}
				$sellorders = array();
				if (isset($_sellorders)) {
					foreach($_sellorders as $so) {
						$sellorders[] = array(
							'price' => sprintf('%.8f',$so->price),
							'amount' => sprintf('%.8f',$so->total_from_value),
							'total' => sprintf('%.8f',$so->total_to_value),
						);
					}
				}

				$getarray[$i] = array(
					'marketid' => $item->id,
					'label' => $label,
					'lasttradeprice'=>sprintf('%.8f',$lasttradeprice),
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
			if(count($getarray)==0) $output = json_encode(array('success' => 0,'value'=>'null'));	
			else	
			$output = json_encode(array('success' => 1,'return' => $getarray));
			echo $output; 
			 
			//$json = file_get_contents('http://pubapi.cryptsy.com/api.php?method=marketdatav2'); 
			//$data = json_decode($json);
			//print_r ($data); 
			//http://www.ecoinstrader.com/page/api?method=allmarket
		}
		
		//orders
		
		if($method=='singleorder' || $method=='allorder'){
			if($method=='singleorder'){
				if(isset($_REQUEST['marketid']) && is_numeric($_REQUEST['marketid'])){
				$market_sql = 'select * from market where id='.$_REQUEST['marketid'];}
				else {$output = json_encode(array('success' => 0,'error'=>Lang::get('messages.link_not_correct')));echo $output;exit;}
			} 
			else $market_sql = 'select * from market';
			$market = DB::select($market_sql);
			//print_r($market);
			$getarray = array();
			$i=0;
			$output = json_encode(array('return' => $market));
			//echo $output.'<br>';

			foreach($market as $item){
				$wallet_f = $item->wallet_from;
				$wallet_sql = 'select type,name from wallets where id='.$wallet_f;
				$wallet = DB::select($wallet_sql);
				foreach($wallet as $w){$wf_type=$w->type;$wf_name=$w->name;}
				
				$wallet_t = $item->wallet_to;
				$wallet_sql = 'select type,name from wallets where id='.$wallet_t;
				$wallet = DB::select($wallet_sql);
				foreach($wallet as $w){$wt_type=$w->type;$wt_name=$w->name;}
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
				$_sellorders = $order->getOrders($market_id,'sell',$num_transaction_display);
				$_buyorders = $order->getOrders($market_id,'buy',$num_transaction_display);
				
				$buyorders = array();
				if (isset($_buyorders)) {
					foreach($_buyorders as $bo) {
						$buyorders[] = array(
							'price' => sprintf('%.8f',$bo->price),
							'amount' => sprintf('%.8f',$bo->total_from_value),
							'total' => sprintf('%.8f',$bo->total_to_value),
						);
					}
				}
				$sellorders = array();
				if (isset($_sellorders)) {
					foreach($_sellorders as $so) {
						$sellorders[] = array(
							'price' => sprintf('%.8f',$so->price),
							'amount' => sprintf('%.8f',$so->total_from_value),
							'total' => sprintf('%.8f',$so->total_to_value),
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
			if(count($getarray)==0) $output = json_encode(array('success' => 0));	
			else
			$output = json_encode(array('success' => 1,'return' => $getarray));
			echo $output;
			//https://www.sweedx.com/page/api?method=singleorder&marketid
		}

		if ($method=='lastprice') {
			if(isset($_REQUEST['marketid']) && is_numeric($_REQUEST['marketid'])){
				$market_id = $_REQUEST['marketid'];
				$market_sql = 'select * from market where id='.$market_id;
				$market = DB::select($market_sql);

				$trade = new Trade();
				$data_price = $trade->getBlockPrice($market_id);	

				echo json_encode(array(
					'latest_price' => sprintf('%.8f',$data_price['latest_price']),
				));
				exit();
			} else {
				$output = json_encode(array('success' => 0,'error'=>Lang::get('messages.link_not_correct')));
				echo $output;
				exit();
			}
		}

		if($method=='getmarkets'){
			if(isset($_REQUEST['key']) && !empty($_REQUEST['key']) && isset($_REQUEST['sign']) && !empty($_REQUEST['sign'])){
				$market_sql = 'select * from market';
				$market = DB::select($market_sql);
				//print_r($market);
				$getarray = array();
				$i=0;
				$output = json_encode(array('return' => $market));
				//echo $output.'<br>';
				foreach($market as $item){
					$wallet_f = $item->wallet_from;
					$wallet_sql = 'select type,name from wallets where id='.$wallet_f;
					$wallet = DB::select($wallet_sql);
					foreach($wallet as $w){$wf_type=$w->type;$wf_name=$w->name;}
					
					$wallet_t = $item->wallet_to;
					$wallet_sql = 'select type,name from wallets where id='.$wallet_t;
					$wallet = DB::select($wallet_sql);
					foreach($wallet as $w){$wt_type=$w->type;$wt_name=$w->name;}
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
					if(count($market)==0){
						$lasttradeprice = '0.00000000';
						$lasttradetime = '0000-00-00 00:00:00';
						$recenttrades = 'null';
						$highttradeprice = 'null';
						$lowtradeprice = 'null';
					}else{
						//get last trade info
						$market_last_sql = 'select max(updated_at) as updated_at, price  from trade_history where market_id='.$market_id;
						$market_last = DB::select($market_last_sql);
						foreach($market_last as $m){
							$lasttradeprice = $m->price; 
							$lasttradetime = $m->updated_at;
						}
						//get hight trade
						$market_hight_sql = 'select distinct max(price) as price  from trade_history where market_id='.$market_id;
						$market_hight = DB::select($market_hight_sql);
						foreach($market_hight as $m){
							$highttradeprice = $m->price; 
						}
						//get low trade
						$market_low_sql = 'select min(price) as price  from trade_history where market_id='.$market_id;
						$market_low = DB::select($market_low_sql);
						foreach($market_low as $m){
							$lowtradeprice = $m->price; 
						}
					}

					$order = new Order();
					$_sellorders = $order->getOrders($market_id,'sell',$num_transaction_display);
					$_buyorders = $order->getOrders($market_id,'buy',$num_transaction_display);
					
					$buyorders = array();
					if (isset($_buyorders)) {
						foreach($_buyorders as $bo) {
							$buyorders[] = array(
								'price' => sprintf('%.8f',$bo->price),
								'amount' => sprintf('%.8f',$bo->total_from_value),
								'total' => sprintf('%.8f',$bo->total_to_value),
							);
						}
					}
					$sellorders = array();
					if (isset($_sellorders)) {
						foreach($_sellorders as $so) {
							$sellorders[] = array(
								'price' => sprintf('%.8f',$so->price),
								'amount' => sprintf('%.8f',$so->total_from_value),
								'total' => sprintf('%.8f',$so->total_to_value),
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
				if(count($getarray)==0) $output = json_encode(array('success' => 0,'value'=>'null'));	
				else	
				$output = json_encode(array('success' => 1,'return' => $getarray));
				//check user account
				$sign = $_REQUEST['sign'];
				$password = $_REQUEST['key'];
				$account_sql = 'select * from users where username="'.$sign.'"';
				$account = DB::select($account_sql);
				if(count($account)!=0){
					foreach($account as $a){$u_pass = $a->password;}
					if(Hash::check($password, $u_pass)) {$ch='ok';}
					else {$output = json_encode(array('success' => 0,'error'=>Lang::get('messages.api_cannot_authorize_check_key')));echo $output;exit;}
				} 
				else{$output = json_encode(array('success' => 0,'error'=>Lang::get('messages.api_cannot_authorize_check_sign_data')));echo $output;exit;}
				
				echo $output; 
			}
			else {$output = json_encode(array('success' => 0,'error'=>Lang::get('messages.link_not_correct')));echo $output;exit;}
		}
		if($method=='getwallets'){
			if(isset($_REQUEST['key']) && !empty($_REQUEST['key']) && isset($_REQUEST['sign']) && !empty($_REQUEST['sign'])){
				$wallets_sql = 'select b.percent_fee, a.id, a.type, a.name from wallets a, fee_withdraw b where a.id=b.wallet_id';
				$wallets = DB::select($wallets_sql);
				//print_r($market);
				$getarray = array();
				$i=0;
				$output = json_encode(array('return' => $wallets));
				//echo $output.'<br>';
				foreach($wallets as $item){
					$getarray[$i] = array('currencyid' => $item->id,'name'=>$item->name,'code'=>$item->type,'withdrawfee'=>$item->percent_fee);
					$i++;
				}  
				if(count($getarray)==0) $output = json_encode(array('success' => 0,'value'=>'null'));	
				else 	
				$output = json_encode(array('success' => 1,'return' => $getarray));
				//check user account
				$sign = $_REQUEST['sign'];
				$password = $_REQUEST['key'];
				$account_sql = 'select * from users where username="'.$sign.'"';
				$account = DB::select($account_sql);
				if(count($account)!=0){
					foreach($account as $a){$u_pass = $a->password;}
					if(Hash::check($password, $u_pass)) {$ch='ok';}
					else {$output = json_encode(array('success' => 0,'error'=>Lang::get('messages.api_cannot_authorize_check_key')));echo $output;exit;}
				} 
				else{$output = json_encode(array('success' => 0,'error'=>Lang::get('messages.api_cannot_authorize_check_sign_data')));echo $output;exit;}
				
				echo $output; 
			}
			else {$output = json_encode(array('success' => 0,'error'=>Lang::get('messages.link_not_correct')));echo $output;exit;}
		}
		if($method=='mydeposits'){
			if(isset($_REQUEST['key']) && !empty($_REQUEST['key']) && isset($_REQUEST['sign']) && !empty($_REQUEST['sign'])){
				
				//check user account
				$sign = $_REQUEST['sign'];
				$password = $_REQUEST['key'];
				$account_sql = 'select * from users where username="'.$sign.'"';
				$account = DB::select($account_sql);
				if(count($account)!=0){
					foreach($account as $a){$u_pass = $a->password;$u_id=$a->id;}
					if(Hash::check($password, $u_pass)) {
					
						$deposit_sql = 'select * from deposits d, wallets w where d.wallet_id=w.id and d.user_id="'.$u_id.'"';
						$deposit = DB::select($deposit_sql);
						//print_r($market);
						$getarray = array();
						$i=0;
						
						foreach($deposit as $item){
							$getarray[$i] = array('currencyid' => $item->id,'created'=>$item->created_at,'updated'=>$item->updated_at,'address'=>$item->address,
							'amount'=>$item->amount,'transactionid'=>$item->transaction_id);
							$i++;
						}  
						if(count($getarray)==0) $output = json_encode(array('success' => 0,'value'=>'null'));	
						else 	
						$output = json_encode(array('success' => 1,'return' => $getarray));
					
					
					}
					else {$output = json_encode(array('success' => 0,'error'=>Lang::get('messages.api_cannot_authorize_check_key')));echo $output;exit;}
				} 
				else{$output = json_encode(array('success' => 0,'error'=>Lang::get('messages.api_cannot_authorize_check_sign_data')));echo $output;exit;}
				
				echo $output; 
			}
			else {$output = json_encode(array('success' => 0,'error'=>Lang::get('messages.link_not_correct')));echo $output;exit;}
		}
		if($method=='mywithdraws'){
			if(isset($_REQUEST['key']) && !empty($_REQUEST['key']) && isset($_REQUEST['sign']) && !empty($_REQUEST['sign'])){
				
				//check user account
				$sign = $_REQUEST['sign'];
				$password = $_REQUEST['key'];
				$account_sql = 'select * from users where username="'.$sign.'"';
				$account = DB::select($account_sql);
				if(count($account)!=0){
					foreach($account as $a){$u_pass = $a->password;$u_id=$a->id;}
					if(Hash::check($password, $u_pass)) {
					
						$deposit_sql = 'select * from withdraws d, wallets w where d.wallet_id=w.id and d.user_id="'.$u_id.'"';
						$deposit = DB::select($deposit_sql);
						//print_r($market);
						$getarray = array();
						$i=0;
						
						foreach($deposit as $item){
							$getarray[$i] = array('currencyid' => $item->id,'created'=>$item->created_at,'toaddress'=>$item->to_address,
							'amount'=>$item->amount,'feeamount'=>$item->fee_amount,'receiveamount'=>$item->receive_amount,'transactionid'=>$item->transaction_id);
							$i++;
						}  
						if(count($getarray)==0) $output = json_encode(array('success' => 0,'value'=>'null'));	
						else 	
						$output = json_encode(array('success' => 1,'return' => $getarray));
					}
					else {$output = json_encode(array('success' => 0,'error'=>Lang::get('messages.api_cannot_authorize_check_key')));echo $output;exit;}
				} 
				else{$output = json_encode(array('success' => 0,'error'=>Lang::get('messages.api_cannot_authorize_check_sign_data')));echo $output;exit;}
				
				echo $output; 
			}
			else {$output = json_encode(array('success' => 0,'error'=>Lang::get('messages.link_not_correct')));echo $output;exit;}
		}
		if($method=='mytransfers'){
			if(isset($_REQUEST['key']) && !empty($_REQUEST['key']) && isset($_REQUEST['sign']) && !empty($_REQUEST['sign'])){
				
				//check user account
				$sign = $_REQUEST['sign'];
				$password = $_REQUEST['key'];
				$account_sql = 'select * from users where username="'.$sign.'"';
				$account = DB::select($account_sql);
				if(count($account)!=0){
					foreach($account as $a){$u_pass = $a->password;$u_id=$a->id;}
					if(Hash::check($password, $u_pass)) {
					
						$deposit_sql = 'select * from transfer_history where sender='.$u_id.' or receiver='.$u_id;
						$deposit = DB::select($deposit_sql);
						//print_r($market);
						$getarray = array();
						$i=0;
						
						foreach($deposit as $item){
							$sender = $item->sender;
							$sender_sql = 'select * from users where id='.$sender;
							$sen = DB::select($sender_sql);
							foreach($sen as $s){$sender_name=$s->username;}
							
							$receiver = $item->receiver;
							$receiver_sql = 'select * from users where id='.$receiver;
							$receive = DB::select($receiver_sql);
							foreach($receive as $r){$receive_name=$r->username;}
							
							$wallet = $item->wallet_id;
							$wallet_sql = 'select * from wallets where id='.$wallet;
							$wa = DB::select($wallet_sql);
							foreach($wa as $w){$wallet_name=$w->name;}
							
							
							$getarray[$i] = array('currency'=>$wallet_name,'time'=>$item->created_at,'sender'=>$sender_name,
							'receiver'=>$receive_name,'amount'=>$item->amount);
							$i++;
						}  
						if(count($getarray)==0) $output = json_encode(array('success' => 0,'value'=>'null'));	
						else 	
						$output = json_encode(array('success' => 1,'return' => $getarray));
					}
					else {$output = json_encode(array('success' => 0,'error'=>Lang::get('messages.api_cannot_authorize_check_key')));echo $output;exit;}
				} 
				else{$output = json_encode(array('success' => 0,'error'=>Lang::get('messages.api_cannot_authorize_check_sign_data')));echo $output;exit;}
				
				echo $output; 
			}
			else {$output = json_encode(array('success' => 0,'error'=>Lang::get('messages.link_not_correct')));echo $output;exit;}
		}
		if($method=='getmydepositaddresses'){
			if(isset($_REQUEST['key']) && !empty($_REQUEST['key']) && isset($_REQUEST['sign']) && !empty($_REQUEST['sign'])){
				
				//check user account
				$sign = $_REQUEST['sign'];
				$password = $_REQUEST['key'];
				$account_sql = 'select * from users where username="'.$sign.'"';
				$account = DB::select($account_sql);
				if(count($account)!=0){
					foreach($account as $a){$u_pass = $a->password;$u_id=$a->id;}
					if(Hash::check($password, $u_pass)) {
					
						$deposit_sql = 'select * from user_address_deposit u, wallets w where u.wallet_id=w.id and u.user_id='.$u_id;
						$deposit = DB::select($deposit_sql);
						
						$getarray = array(); 
						$i=0;
						
						foreach($deposit as $item){
							
							$getarray[$i] = array('coincode'=>$item->type,'despositaddress'=>$item->addr_deposit);
							$i++;
						}   
						if(count($getarray)==0) $output = json_encode(array('success' => 0,'value'=>'null'));	
						else 	
						$output = json_encode(array('success' => 1,'return' => $getarray));
					}
					else {$output = json_encode(array('success' => 0,'error'=>Lang::get('messages.api_cannot_authorize_check_key')));echo $output;exit;}
				} 
				else{$output = json_encode(array('success' => 0,'error'=>Lang::get('messages.api_cannot_authorize_check_sign_data')));echo $output;exit;}
				
				echo $output; 
			}
			else {$output = json_encode(array('success' => 0,'error'=>Lang::get('messages.link_not_correct')));echo $output;exit;}
		}
		if($method=='allmyorders'){
			if(isset($_REQUEST['key']) && !empty($_REQUEST['key']) && isset($_REQUEST['sign']) && !empty($_REQUEST['sign'])){
				
				//check user account
				$sign = $_REQUEST['sign'];
				$password = $_REQUEST['key'];
				$account_sql = 'select * from users where username="'.$sign.'"';
				$account = DB::select($account_sql);
				if(count($account)!=0){
					foreach($account as $a){$u_pass = $a->password;$u_id=$a->id;}
					if(Hash::check($password, $u_pass)) {
					
						$deposit_sql = 'select * from orders where user_id='.$u_id;
						$deposit = DB::select($deposit_sql);
						
						$getarray = array(); 
						$i=0;
						
						foreach($deposit as $item){
							
							$getarray[$i] = array('orderid'=>$item->id,'marketid'=>$item->market_id,'created'=>$item->created_at,'ordertype'=>$item->type,
							'price'=>$item->price,'fromvalue'=>$item->from_value,'tovalue'=>$item->to_value);
							$i++;
						}   
						if(count($getarray)==0) $output = json_encode(array('success' => 0,'value'=>'null'));	
						else 	
						$output = json_encode(array('success' => 1,'return' => $getarray));
					}
					else {$output = json_encode(array('success' => 0,'error'=>Lang::get('messages.api_cannot_authorize_check_key')));echo $output;exit;}
				} 
				else{$output = json_encode(array('success' => 0,'error'=>Lang::get('messages.api_cannot_authorize_check_sign_data')));echo $output;exit;}
				
				echo $output; 
			}
			else {$output = json_encode(array('success' => 0,'error'=>Lang::get('messages.link_not_correct')));echo $output;exit;}
		}
		if($method=='myorders'){
			if(isset($_REQUEST['key']) && !empty($_REQUEST['key']) && isset($_REQUEST['sign']) && !empty($_REQUEST['sign']) && isset($_REQUEST['marketid']) && !empty($_REQUEST['marketid'])){
				
				//check user account
				$sign = $_REQUEST['sign'];
				$password = $_REQUEST['key'];
				$marketid = $_REQUEST['marketid'];
				$account_sql = 'select * from users where username="'.$sign.'"';
				$account = DB::select($account_sql);
				if(count($account)!=0){
					foreach($account as $a){$u_pass = $a->password;$u_id=$a->id;}
					if(Hash::check($password, $u_pass)) {
					
						$deposit_sql = 'select * from orders where market_id="'.$marketid.'" and user_id='.$u_id;
						$deposit = DB::select($deposit_sql);
						
						$getarray = array(); 
						$i=0;
						
						foreach($deposit as $item){
							
							$getarray[$i] = array('orderid'=>$item->id,'created'=>$item->created_at,'ordertype'=>$item->type,
							'price'=>$item->price,'fromvalue'=>$item->from_value,'tovalue'=>$item->to_value);
							$i++;
						}   
						if(count($getarray)==0) $output = json_encode(array('success' => 0,'value'=>'null'));	
						else 	
						$output = json_encode(array('success' => 1,'return' => $getarray));
					}
					else {$output = json_encode(array('success' => 0,'error'=>Lang::get('messages.api_cannot_authorize_check_key')));echo $output;exit;}
				} 
				else{$output = json_encode(array('success' => 0,'error'=>Lang::get('messages.api_cannot_authorize_check_sign_data')));echo $output;exit;}
				
				echo $output; 
			}
			else {$output = json_encode(array('success' => 0,'error'=>Lang::get('messages.link_not_correct')));echo $output;exit;}
		}
		
   }  
   
	function apiprivate() { 
		//$va = $_REQUEST['method'];
		$val=array('success' => 'aaaaaa');
		return $val;
	}
/*
	function api_query($method, array $req = array()) {
        // API settings
        $key = '22f9c4becc188e0aace65b0860d60efe21e36119'; // your API-key
        $secret = 'f0c1d1f0513c167a44d3bab6d18bac72abb0270f23c65d29ce19bba0348aa1db479876a0a968047e'; // your Secret-key
 
        $req['method'] = $method;
        $mt = explode(' ', microtime());
        $req['nonce'] = $mt[1];
       
        // generate the POST data string
        $post_data = http_build_query($req, '', '&');

        $sign = hash_hmac("sha512", $post_data, $secret);
 
        // generate the extra headers
        $headers = array(
                'Sign: '.$sign,
                'Key: '.$key,
        );
 
        // our curl handle (initialize if required)
        static $ch = null;
        if (is_null($ch)) {
                $ch = curl_init();
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/4.0 (compatible; Cryptsy API PHP client; '.php_uname('s').'; PHP/'.phpversion().')');
        }
        curl_setopt($ch, CURLOPT_URL, 'https://api.cryptsy.com/api');
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
 
        // run the query
        $res = curl_exec($ch);

        if ($res === false) throw new Exception('Could not get reply: '.curl_error($ch));
        $dec = json_decode($res, true);
        if (!$dec) throw new Exception(Lang::get('messages.api_invalid_data_recieved'));
        return $dec;
	}
*/
 
}