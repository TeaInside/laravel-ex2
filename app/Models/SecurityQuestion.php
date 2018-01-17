<?php

namespace App\Models;

use DB;
use Illuminate\Database\Eloquent\Model as Eloquent;

class SecurityQuestion extends Eloquent
{
    protected $table = 'security_questions';
    public $timestamps = false;
}
