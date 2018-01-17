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

class ClefController extends Controller
{

    /**
     * First Connection with Clef account
     *
     * @return Response
     */
    public function first_authentication()
    {
        $response = Clef::authentication($_GET['code']);
        
        

        // error
        $error = '';
        if (!isset($response)) {
            $error = 'Error';
        // error
        } elseif (isset($response['error'])) {
            $error = $response['error'];
        // success
        } elseif (isset($response['success'])) {
            // verif if exists account in Authentication table
                $verif = Authentication::whereprovider("clef")->whereprovider_uid($response['info']['id'])->first();
                //$verif = Authentication::where('provider','=','clef')->where('provider_uid','=',$response['info']['id'])->first();


                    //var_dump($verif);
                    //exit();
                
                // no account, register the account
            if (empty($verif)) {
                    
                //$error = 'There is no user account related to this Clef account';
                    
                //Add Clef account to the users 2fa column:
                    
                User::where('id', Auth::id())->update(array('two_factor_auth' => $response['info']['id']));
                    
                //if (Auth::user()->email == $response['info']['email'])
                    
                    //Connect Clef to the users email
                /*
                $user = User::whereEmail($response['info']['id'])->first();

                  if($user instanceof User) {
                    $user->clef_id = $info->id;
                    $user->save();
						
                    return $user;
                  }
                 */
                $auth2fa = new Authentication;
                    
                $auth2fa->provider = 'clef';
                $auth2fa->provider_uid = $response['info']['id'];
                $auth2fa->email = $response['info']['email'];
                $auth2fa->user_id = Auth::id();

                // Save if valid
                $isTwoFa_saved = $auth2fa->save();
                    
                if ($isTwoFa_saved) {
                    $error = 'Two Factor Authentication enabled successfully.';
                } else {
                    $error = 'ERROR: Two Factor Authentication not enabled!';
                }
                    

                /*
					
					
                $user = User::find( $verif->user_id );
					
                //echo $user->username;
                //exit( $verif->user_id );
                //$user = User::whereid( $verif->user_id )->first();
                //$user = User::where('id','=', $verif->user_id )->first();


					
                /*
                var_dump($response);
                echo 'clef user id: ' .$response['info']['id'];
                echo '<br />';
                echo 'clef email: ' .$response['info']['email'];
                exit($error);
                */
                    
                // Find account
            } else {
                //Account is already connected with Clef, update user table
                    
                User::where('id', Auth::id())->update(array('two_factor_auth' => $response['info']['id']));
                    
                //$error = 'Account is already connected with Clef';
                $error = 'Two Factor Authentication enabled successfully.';

            }
        // error
        } else {
            $error = 'Clef Connect Unknown error';
        }
        /*
		echo 'first_auth';
		var_dump($response);
		exit('..........'.$error);
		*/
        //return Redirect::to("login")->withErrors($error);
        return Redirect::to("user/profile/two-factor-auth")->with('notice', $error);
    
    }
    
    /**
     * Authentication with Clef account, Account Login
     *
     * @return Response
     */
    public function authentication()
    {
        //$response = Clef::icee($_GET['code']);
        //$Clef_ = new Clef();
        //$response = $Clef_->authentication($_GET['code']);
        
        //$Clef_ = new Clef;
        //$response = $Clef_->icee($_GET['code']);
        //echo 'testing:  '.$_GET['code'];
        //echo '<br/>';
        
        $response = Clef::authentication($_GET['code']);

        
        /*
		var_dump($response);
		echo '<br />';
		//$response['info']['email'];
		if (isset($response['success']))
			echo ' success: '.$response['success'];
		
		echo '<hr />';
		*/

        /*
		if ($response) {
			
			if (isset($response['error'])) 
			{
				// error
				$error = $response['error'];
			}elseif (isset($response['success'])) {
				// success
				
				
			}else{
				// error
				$error = 'Unknown Error';
			}
		}else{
			// error
			$error = 'Clef Error';
		}
		*/
        
        // error
        $error = '';
        if (!isset($response)) {
            $error = 'Error';
        // error
        } elseif (isset($response['error'])) {
            $error = $response['error'];
        // success
        } elseif (isset($response['success'])) {
            // verif if exists account in Authentication table
                //$verif = Authentication::whereprovider("clef")->whereprovider_uid($response['info']['id'])->first();
                //$verif = Authentication::where('provider','=','clef')->where('provider_uid','=',$response['info']['id'])->first();
                
                $verif = Authentication::where('provider', '=', 'clef')->where('provider_uid', '=', $response['info']['id'])->first();


                    //var_dump($verif);
                    //exit();
                
                // no account
            if (empty($verif)) {
                $error = 'There is no user account related to this Clef account';
                // Find account
            } else {
                    
                    
                    
                // Find the user using the user id
                    
                    
                $user = User::find($verif->user_id);
                    
                //echo $user->username;
                //exit( $verif->user_id );
                //$user = User::whereid( $verif->user_id )->first();
                //$user = User::where('id','=', $verif->user_id )->first();


                    
                /*
                // RAZ logout
                if ($user->logout == 1) {
                    $user->logout = 0;
                    $user->save();
                }
                */
                //Auth::logout();
                //Session::flush();

                    
                if (!empty($user)) {
                    try {
                        Auth::login($user);
                            
                        //$UserController_ = (new UserController);
                        //$ip = $UserController_->get_client_ip();
                        $ip= (new UserController)->get_client_ip();
                            
                        $cur_date = date("Y-m-d H:i:s");
                        User::where('id', $user->id)->update(array('lastest_login' => $cur_date, 'ip_lastlogin'=>$ip));
                            
                            
                            
                        //echo $user->username;
                        //exit();
                        return Redirect::intended('/');
                    } catch (\Exception $e) {
                        dd($e);
                    }
                } else {
                    $error = 'Something went wrong with the 2fa login, please contact admin...';
                    return Redirect::to("login")->with('error', $error);
                }
                      
    
                          
                          
                //exit( $user->id );
                // Log the user in
                /*
                Auth::login($user);
                echo $user->id;
                echo $user->username;
                echo Auth::basic('username');
                exit('die');
                */
                    
                //exit( Auth::id() );
                //Auth::loginUsingId($user->id);
                //return Redirect::intended('/home');
                //return Redirect::to('/',302, array(), true);
                    

                    
            }
        // error
        } else {
            $error = 'Clef Login Unknown error';
        }
        /*
		echo $error;
		echo 'clefcontroler';
		var_dump($response);
		exit();
		*/
        
        //return Redirect::to("login")->withErrors($error);
        return Redirect::to("login")->with('error', $error);
    }

    /**
     * Logout by WebHook
     *
     * @access public
     * @return Response
     */
    public function logout()
    {
        // Token from Clef.io
        if (isset($_POST['logout_token'])) {
            // Verif token
            $clef = Clef::logout($_POST['logout_token']);

            if (!$clef) {
                // Verif in Authentication table
                $auth = Authentication::whereprovider("clef")->whereprovider_uid($clef)->first();

                if (!empty($auth)) {
                    $user = User::find($auth->user_id);

                    if (!empty($user)) {
                        $user->logout = 1;
                        $user->save();
                    }
                }
            }
        }
    }
    
    ///////////////////////////
    
    public function getLoginClef()
    {
        try {
            $clef = App::make('clef');
            $authorization = $clef->authenticate(Input::get('code'));

            if ($authorization->success) {
                $user = $this->service->findByClef($clef->getUser());

                Auth::login($user);

                return Redirect::intended('/admin');
            }
        } catch (\Exception $e) {
            dd($e);
        }
    }
    
    public function findByClef($clefUser)
    {
        $info = $clefUser->info;

        $user = User::whereClefId($info->id)->first();

        if ($user instanceof User) {
            return $user;
        }

        $user = User::whereEmail($info->email)->first();

        if ($user instanceof User) {
            $user->clef_id = $info->id;
            $user->save();
        
            return $user;
        }

        throw new \Exception;
    }
}
