<?php

namespace App\Models;

use DB;
use Illuminate\Database\Eloquent\Model as Eloquent;

class CoinVote extends Eloquent
{
    protected $table = 'coin_votes';
    public $timestamps = false;
}
