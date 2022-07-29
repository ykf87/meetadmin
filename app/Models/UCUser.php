<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UCUser extends Model{
	use HasFactory;
	protected $connection 	= 'ucenter';
	protected $table 		= 'users';
}
