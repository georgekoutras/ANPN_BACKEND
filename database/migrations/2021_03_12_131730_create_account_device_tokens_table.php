<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAccountDeviceTokensTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('account_device_tokens', function (Blueprint $table) {
            $table->bigInteger('account_id')->unsigned()->index('device_token_account_id_index');
            $table->foreign('account_id', 'device_token_account_id_foreign')
                ->references('id')->on('accounts')->onDelete('cascade');
            $table->string('device_token');
            $table->string('device_type');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('account_device_tokens');
    }
}
