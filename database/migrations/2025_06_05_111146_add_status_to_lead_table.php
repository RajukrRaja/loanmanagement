<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddStatusToLeadTable extends Migration
{
    public function up()
    {
        Schema::table('lead', function (Blueprint $table) {
            $table->string('status')->default('Pending')->after('loan_demand_amount');
        });
    }

    public function down()
    {
        Schema::table('lead', function (Blueprint $table) {
            $table->dropColumn('status');
        });
    }
}