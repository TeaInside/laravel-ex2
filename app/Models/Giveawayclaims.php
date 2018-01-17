<?php

namespace App\Models;

use DB;
use Illuminate\Database\Eloquent\Model as Eloquent;

class Giveawayclaims extends Eloquent
{
    protected $table = 'giveaway_claims';
    public function cleanText($text)
    {
        return preg_replace("/[^a-zA-Z0-9\-]/", "", strtolower($text));
    }
}
