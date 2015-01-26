<?php namespace Csgt\Components;

use Illuminate\Support\ServiceProvider;
use Illuminate\Foundation\AliasLoader;

class ComponentsServiceProvider extends ServiceProvider {

	protected $defer = false;

	public function boot() {
		$this->package('csgt/components');
		AliasLoader::getInstance()->alias('CSGTMenu','Csgt\Components\CSGTMenu');
		include __DIR__.'/../../filters.php';
		include __DIR__.'/../../routes.php';
	}

	public function register() {
		$this->app['components'] = $this->app->share(function($app) {
    	return new Components;
  	});
	}

	public function provides() {
		return array('components');
	}

}
