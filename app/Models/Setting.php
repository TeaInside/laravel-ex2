<?php

namespace App\Models;

use DB;
use Illuminate\Database\Eloquent\Model as Eloquent;

class Setting extends Eloquent
{
    protected $table = 'settings';
    public $timestamps = false;

    public function getSettingCheck($name, $default_value = 0)
    {
        $setting = Setting::where('name', '=', $name)->first();
        if (isset($setting->value) && $setting->value->id != 0) {
            return $setting->value->id;
        } else {
            return $default_value;
        }
    }
    
    public function getSetting($name, $default_value = 0)
    {
        $setting = Setting::where('name', '=', $name)->first();
        if (isset($setting->value)) {
            return $setting->value;
        } else {
            return $default_value;
        }
    }

    public function putSetting($name, $value = 0)
    {
        $setting = Setting::where('name', '=', $name)->first();
        if (isset($setting->id)) {
            Setting::where('name', $name)->update(array('value' => $value));
        } else {
            $this->name = $name;
            $this->value = $value;
            $this->save();
        }
    }
}
