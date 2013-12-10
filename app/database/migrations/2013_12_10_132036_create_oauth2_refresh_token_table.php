<?php

use Illuminate\Database\Migrations\Migration;

class CreateOauth2RefreshTokenTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
        Schema::create('oauth2_refresh_token', function($table)
        {
            $table->bigIncrements('id')->unsigned();
            $table->string('value',255)->unique();
            $table->string('associated_access_token',255)->unique();
            $table->integer('lifetime');
            $table->text('scope');
            $table->timestamps();
            $table->bigInteger("client_id")->unsigned();
            $table->index('client_id');
            $table->foreign('client_id')->references('id')->on('oauth2_client');
        });

	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
        Schema::table('oauth2_refresh_token', function($table)
        {
            $table->dropForeign('client_id');
        });
        Schema::dropIfExists('oauth2_refresh_token');
	}

}