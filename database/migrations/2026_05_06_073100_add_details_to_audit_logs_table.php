<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
{
    Schema::table('audit_logs', function (Blueprint $table) {
        $table->string('full_name')->after('action')->nullable();
        $table->string('email')->after('full_name')->nullable();
        $table->string('position')->after('email')->nullable();
        $table->string('status')->after('position')->nullable();
    });
}

    public function down()
    {
        Schema::table('audit_logs', function (Blueprint $table) {
            $table->dropColumn(['full_name', 'email', 'position', 'status']);
        });
    }
};
