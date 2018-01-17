<?php

namespace App\Models;

use DB;
use Illuminate\Database\Eloquent\Model as Eloquent;

class Giveaways extends Eloquent
{
    protected $table = 'giveaways';
    public function cleanText($text)
    {
        return preg_replace("/[^a-zA-Z0-9\-]/", "", strtolower($text));
    }
}
