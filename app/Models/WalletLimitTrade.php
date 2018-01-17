<?php

namespace App\Models;

use DB;
use Illuminate\Database\Eloquent\Model as Eloquent;

class WalletLimitTrade extends Eloquent
{
    protected $table = 'wallet_limittrade';
    public $timestamps = false;
}
