<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Platform extends Model{
    use HasFactory;
    public $timestamps      = false;

    public static function list(){
        return [
            1   => '安卓',
            2   => '苹果',
            3   => 'Web',
        ];
    }
}
