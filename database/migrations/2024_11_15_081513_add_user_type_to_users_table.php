<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'user_type','username')) {
                $table->string('user_type')->default('cashier')->after('email');
            }
            if (!Schema::hasColumn('users', 'username')) {
                $table->string('username');
            }
        });
    }
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            if (Schema::hasColumn('users', 'user_type')) {
                $table->dropColumn('user_type','username');
            }
        });
    }
};
