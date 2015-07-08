<?php 
namespace Csgt\Components;

use Illuminate\Support\ServiceProvider;
use Illuminate\Foundation\AliasLoader;
use Illuminate\Routing\Router;

class ComponentsServiceProvider extends ServiceProvider {

	protected $defer = false;

	public function boot(Router $router) {
		AliasLoader::getInstance()->alias('CSGTMenu','Csgt\Components\CSGTMenu');
		AliasLoader::getInstance()->alias('Components','Csgt\Components\Components');

		$this->mergeConfigFrom(__DIR__ . '/config/csgtcomponents.php', 'csgtcomponents');
    $this->loadViewsFrom(__DIR__ . '/resources/views/','csgtcomponents');

    if (!$this->app->routesAreCached()) {
      require __DIR__.'/Http/routes.php';
    }
    $router->middleware('menu', '\Csgt\Components\Http\Middleware\MenuMW');
    $router->middleware('god', '\Csgt\Components\Http\Middleware\GodMW');
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
