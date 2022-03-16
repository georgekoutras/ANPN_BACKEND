<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;

class CreateAccountsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('accounts', function (Blueprint $table) {
            $table->id();
            $table->string('first_name');
            $table->string('last_name');
            $table->string('second_name')->nullable();
            $table->string('email')->unique()->index('users_email_index');
            $table->string('password');
            $table->boolean('enabled')->default(true);
            $table->boolean('notification_enabled')->default(false);
            $table->string('mobile');
            $table->enum('role', ['administrator', 'doctor','patient']);
            $table->enum('notification_mode', ['sms', 'email','push']);
            $table->dateTime('reminder_time')->nullable();

            $table->timestamps();
            $table->softDeletes();
        });

        DB::table('accounts')->insert([
            [
                'first_name' => 'Admin', 'last_name' => 'Admin', 'second_name' => 'Giannis', 'email' => 'admin@admin.gr',
                'password' => Hash::make('sysadm123'), 'enabled' => true,
                'notification_enabled' => false, 'mobile' => '+1-202-555-0146', 'role' => 'administrator',
                'notification_mode' => 'sms'
            ]
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('accounts');
    }
}
