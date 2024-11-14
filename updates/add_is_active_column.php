<?php

use October\Rain\Database\Schema\Blueprint;
use October\Rain\Database\Updates\Migration;

return new class extends Migration
{
    public function up()
    {
        Schema::table('getbyte_whatsapp_accounts', function (Blueprint $table) {
            $table->boolean('is_active')->default(true)->after('name');
        });
    }

    public function down()
    {
        Schema::table('getbyte_whatsapp_accounts', function (Blueprint $table) {
            $table->dropColumn('is_active');
        });
    }
};
