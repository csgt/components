<?php

namespace Csgt\Components;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;

class Authusuario extends Model implements AuthenticatableContract, CanResetPasswordContract {
	
	use Authenticatable, CanResetPassword;

	protected $table      = 'authusuarios';
	protected $primaryKey = 'usuarioid';
	protected $hidden     = ['password','remember_token'];
	protected $guarded    = ['usuarioid'];
}