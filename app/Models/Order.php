<?php

namespace App\Models;

use DB;
use Auth;
use Illuminate\Database\Eloquent\Model as Eloquent;

class Order extends Eloquent
{
    protected $table = 'orders';
    protected $status_active = array('active', 'partly filled');
    //protected $status_active = array('active', 'partially ');

    public function getStatusActive()
    {
        return $this->status_active;
    }
    /*
    ** getting the price lowest of sell orders
    */
    public function getSellLowest($market_id)
    {
        $order_sell = Order::leftJoin('market', $this->table.'.market_id', '=', 'market.id')
                    ->select($this->table.'.*', 'market.wallet_from', 'market.wallet_to')
                    ->where('market.id', '=', $market_id)
                    ->where('type', '=', 'sell')
                    ->whereIn('status', $this->status_active)
                    ->orderBy('price', 'asc')
                    ->first();
        return $order_sell;
    }

    /*
    ** getting the price highest of sell orders
    */
    public function getBuyHighest($market_id)
    {
        $order_buy = Order::leftJoin('market', $this->table.'.market_id', '=', 'market.id')
                    ->select($this->table.'.*', 'market.wallet_from', 'market.wallet_to')
                    ->where('market.id', '=', $market_id)
                    ->where('type', '=', 'buy')
                    ->whereIn('status', $this->status_active)
                    ->orderBy('price', 'desc')
                    ->first();
        return $order_buy;
    }

    /*
    ** get list of the active buy/sell orders
    ** $type: 'sell' / 'buy'
    */
    public function getOrders($market_id, $type = 'sell', $limit = 0)
    {
        if ($type == 'sell') {
            $desc = 'asc';
        } else {
            $desc = 'desc';
        }
        $str_limit = '';
        if ($limit > 0) {
            $str_limit = " limit ".$limit;
        }
        $status = "'".implode("','", $this->status_active)."'";
        $a = DB::connection()->getPdo();
        $a->exec("SET sql_mode = ''; ");
        $a->exec("SET GLOBAL sql_mode=(SELECT REPLACE(@@sql_mode,'ONLY_FULL_GROUP_BY',''));");
        $orders = DB::select("select * ,sum(`from_value`) as total_from_value, sum(`to_value`) as total_to_value from `".$this->table."` where `market_id` = '".$market_id."' and `type` = '".$type."' and `status` in (".$status.") group by `price` order by `price` ".$desc.$str_limit);
        return $orders;
    }

    public function getTotalCoin($market_id, $type = 'sell')
    {
        $status = "'".implode("','", $this->status_active)."'";
        if ($type=='sell') {
            $total = DB::select("select sum(`from_value`) as total_from_value from `".$this->table."` where `market_id` = '".$market_id."' and `type` = 'sell' and `status` in (".$status.")");
        } else {
            $total= DB::select("select sum(`to_value`) as total_to_value from `".$this->table."` where `market_id` = '".$market_id."' and `type` = 'buy' and `status` in (".$status.")");
        }
        if (isset($total[0])) {
            if ($type=='sell') {
                $total = $total[0]->total_from_value;
            } else {
                $total = $total[0]->total_to_value;
            }
        } else {
            $total = 0;
        }
        return $total;
    }

    /*
    ** get list of the active buy/sell orders of a user
    */
    public function getCurrentOrdersUser($market_id, $user_id = '')
    {
        if (Auth::guest()) {
            return false;
        }
        $user = Confide::user();
        if ($user_id == '') {
            $user_id = $user->id;
        }
        $orders = Order::where('market_id', '=', $market_id)
            ->where('user_id', '=', $user_id)
            ->whereIn('status', $this->status_active)
            //->orderBy('created_at', 'desc')
            ->orderBy('type', 'desc')
            ->orderBy('price', 'desc')
            ->get();
        return $orders;
    }

    /*
    ** get list of the active sell orders matching
    */
    public function getSellOrdersMatching($market_id, $price)
    {
        $sell_orders = Order::where('market_id', '=', $market_id)
            ->where('price', '<=', $price)
            ->where('type', '=', 'sell')
            ->whereIn('status', $this->status_active)
            ->orderBy('price', 'asc')
            ->orderBy('created_at', 'asc')
            ->get();
        /*echo "<pre>sell_orders"; print_r($sell_orders); echo "</pre>";
        echo "<pre>getQueryLog: ".dd(DB::getQueryLog())."</pre>";*/
        return $sell_orders;
    }

    /*
    ** get list of the active sell orders matching
    */
    public function getBuyOrdersMatching($market_id, $price)
    {
        /*
		$buy_orders = Order::where('market_id', '=', $market_id)
            ->where('price', '>=', $price)
            ->where('type', '=', 'buy')
            ->whereIn('status', $this->status_active)
            ->groupBy('price')
			->orderBy(DB::raw('sum(from_value) AS from_value_total'))
			->orderBy('price', 'desc')
            ->orderBy('created_at','asc')
            ->get();
		
$buy_orders = DB::table('orders')
			->select(DB::raw('sum(from_value) AS from_value_total'))
			->where('market_id', '=', $market_id)
            ->where('price', '>=', $price)
            ->where('type', '=', 'buy')
            ->whereIn('status', $this->status_active)
            ->groupBy('price')
			->orderBy('price', 'desc')
            ->orderBy('created_at','asc')
            ->get();
		*/
        
        $buy_orders = Order::where('market_id', '=', $market_id)
            ->where('price', '>=', $price)
            ->where('type', '=', 'buy')
            ->whereIn('status', $this->status_active)
            ->orderBy('price', 'desc')
            ->orderBy('created_at', 'asc')
            ->get();
            
            /*
 $mRows = DB:query( "SELECT *, sum(from_value) as from_value_total FROM `orders` WHERE `market_id` ='".$market_id."' AND price >= '".$price."' AND `type` = 'buy' AND `status` IN ( '".$this->status_active."' ) group by `price` ORDER BY `price` DESC, created_at ASC" );
    // Convert results to a user::Eloquent model
    $buy_orders = array();
    foreach( $mRows as $mRow )
    {
      $mR = ( array ) $mRow;
      $buy_orders[] = new user( $mR, true );
    }
    */
    

        //$buy_orders_sql = "SELECT *, sum(from_value) as from_value_total FROM `orders` WHERE `market_id` ='".$market_id."' AND price >= '".$price."' AND `type` = 'buy' AND `status` IN ( '".$this->status_active."' ) group by `price` ORDER BY `price` DESC, created_at ASC";
        //$buy_orders_sql = 'select *, sum(from_value) as from_value_total from `orders` where `market_id` = 59 and price >= "0.00000008" and `type` = "buy" and `status` in ("active", "partly filled") order by `price` asc;';
        //$buy_orders = DB::select($buy_orders_sql)->toArray();
        //$buy_orders = DB::select($buy_orders_sql, array() );
        
        //echo "<pre>buy_orders"; print_r($buy_orders->toArray()); echo "</pre>";
        //dd(DB::getQueryLog());
        //echo "<pre>getQueryLog: ".dd(DB::getQueryLog())."</pre>";
        return $buy_orders;
                /*
		 { ["query"]=> string(106) "select * from `orders` where `market_id` = ? and `user_id` = ? and `status` in (?, ?) order by `price` asc" 
		 
		 ["bindings"]=> array(4) { [0]=> string(2) "59" [1]=> string(3) "194" [2]=> string(6) "active" [3]=> string(13) "partly filled" } ["time"]=> float(2.19) } [20]=> array(3)
		 
		 select * from `orders` where `market_id` = 59 and `user_id` = 194 and `status` in ("active", "partly filled") order by `price` asc;
		 
		 select sum(from_value) as from_value_total, * from `orders` where `market_id` = 59 and price >= '0.00000009' and `type` = 'buy' and `user_id` = 194 and `status` in ("active", "partly filled") order by `price` asc;select * from `orders` where `market_id` = 59 and price >= '0.00000009' and `user_id` = 194 and `status` in ("active", "partly filled") group by `price` order by `price` asc;
		 
			/////////////// normal
SELECT *
FROM `orders`
WHERE `market_id` =59
AND price >= '0.00000009'
AND `user_id` =194
AND `type` = 'buy'
AND `status`
IN (
"active", "partly filled"
)
ORDER BY `price` ASC
*****************
SELECT *, sum(from_value) as from_value_total
FROM `orders`
WHERE `market_id` =59
AND price >= '0.00000004'
AND `user_id` =194
AND `type` = 'buy'
AND `status`
IN (
"active", "partly filled"
)
group by `price`
ORDER BY `price` ASC

SELECT *, sum(from_value) as from_value_total FROM `orders` WHERE `market_id` =59 AND price >= '0.00000004' AND `user_id` =194 AND `type` = 'buy' AND `status` IN ( "active", "partly filled" ) group by `price` ORDER BY `price` ASC

||-> works


	select *, sum(from_value) as from_value_total from `orders` where `market_id` = 59 and price >= '0.00000004' and `type` = 'buy' and `user_id` = 194 and `status` in ("active", "partly filled") order by `price` asc;
		*/
        
    }
}
