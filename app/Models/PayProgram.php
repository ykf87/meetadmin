<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PayProgram extends Model{
	use HasFactory;
	protected $connection 	= 'ucenter';
	public $timestamps = false;
}
