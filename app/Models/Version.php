<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Version extends Model{
    use HasFactory;
    public $timestamps      = false;
    protected $connection   = 'ucenter';

    public function getUptimeAttribute($val){
        if($val){
            $val    = date('Y-m-d H:i:s', $val);
        }else{
            $val    = null;
        }
        return $val;
    }
    public function setUptimeAttribute($val){
        if($val && strpos($val, '-')){
            $val        = strtotime($val);
        }
        $this->attributes['uptime']   = $val;
    }

    public function getAddtimeAttribute($val){
        if($val){
            $val    = date('Y-m-d H:i:s', $val);
        }
        return $val;
    }
    public function setAddtimeAttribute($val){
        if($val && strpos($val, '-')){
            $val        = strtotime($val);
        }
        $this->attributes['addtime']   = $val;
    }
}
