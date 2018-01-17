<?php

namespace App\Models;

use DB;
use Illuminate\Database\Eloquent\Model as Eloquent;

class Deposit extends Eloquent
{
    protected $table = 'deposits';
    public function addressIsDesposited($address)
    {
        $deposit = Deposit::where('address', $address)->first();
        if (isset($deposit->address)) {
            return 1;
        } else {
            return 0;
        }
    }
}
