<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AgregarGoogleIdAUsuarios extends Migration {

	public function up() {
			Schema::table('authusuarios', function($t) {
    	$t->string('googleid',100)->nullable()->after('rolid');
		});
	}

	public function down() {
		Schema::table('authusuarios', function($t) {
			$t->dropColumn('googleid');
		});
	}

}
