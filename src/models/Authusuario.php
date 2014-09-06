<?php
/*
--------------------------------------------------------
Compsuervice, S.A.
Este archivo es parte del core. No modificar
ver 1.0
ver 1.1 - Se agrego $guarded
--------------------------------------------------------
*/
use Illuminate\Auth\UserInterface;
use Illuminate\Auth\Reminders\RemindableInterface;

class Authusuario extends Eloquent implements UserInterface, RemindableInterface 
{
	protected $table      = 'authusuarios';
	protected $primaryKey = 'usuarioid';
	protected $hidden     = array('password');
	protected $guarded    = array('usuarioid');

	public function getAuthIdentifier()
	{
		return $this->getKey();
	}

	public function getAuthPassword()
	{
		return $this->password;
	}

	public function getReminderEmail()
	{
		return $this->email;
	}

	public function getRememberToken()
	{
    	return $this->remember_token;
	}

	public function setRememberToken($value)
	{
	    $this->remember_token = $value;
	}

	public function getRememberTokenName()
	{
	    return 'remember_token';
	}
}