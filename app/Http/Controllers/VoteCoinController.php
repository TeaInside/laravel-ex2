<?php

namespace App\Http\Controllers;

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

/*
 *
 * A class for coin voting
 *
*/
class VoteCoinController extends Controller
{
    public function doVoting()
    {
        if (Auth::guest()) {
            echo json_encode(array('status'=>'error','message'=> Lang::get('texts.request_login')));
            exit;
        }
        $user = Confide::user();
        $user_id = $user->id;
        $coinvote_id = $_POST['coinvote_id'];
        $user_vote = Vote::where('user_id', $user_id)->where('coinvote_id', $coinvote_id)->first();
        /*
		if(isset($user_vote->user_id)){
			echo json_encode(array('status'=>'error','message'=> Lang::get('texts.you_voted')));
			exit;
		}else{
		*/
            /*$trade = Trade::where('seller_id',$user_id)->orwhere('buyer_id',$user_id)->first();
			if(!isset($trade->id)){
				echo json_encode(array('status'=>'error','message'=> Lang::get('texts.must_have_trade')));
				exit; 
			}*/
            $date = date("Y-m-d");
            $times_vote = Vote::where('created_at', '>=', $date)->where('user_id', $user_id)->orderby('created_at', 'desc')->get()->toArray();
            $count = count($times_vote);
        if ($count>=2) {
            echo json_encode(array('status'=>'error','message'=> Lang::get('texts.over_perday'),'count'=>$count, 'times_vote'=>$times_vote));
            exit;
        } else {
            $vote = new Vote();
            //Vote::insert();
            $vote->coinvote_id = $coinvote_id;
            $vote->user_id = $user_id;
            $vote->save();
            echo json_encode(array('status'=>'success','message'=> Lang::get('texts.vote_success'),'count'=>$count, 'times_vote'=>$times_vote,'vote_id'=>$vote->id, 'coinvote_id' => $coinvote_id));
            exit;
        }
            
        //}
    }
}
