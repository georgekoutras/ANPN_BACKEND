<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNotificationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('notifications', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('account_id')->unsigned()->index('notification_account_id_index');
            $table->foreign('account_id', 'notification_account_id_foreign')
                ->references('id')->on('accounts')->onDelete('cascade');
            $table->dateTime('notification_date')->nullable();
            $table->integer( 'notification_type')->default(0);
            $table->string('notification_message',500);

            $table->timestamps();
            $table->softDeletes();

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('notifications');
    }
}
