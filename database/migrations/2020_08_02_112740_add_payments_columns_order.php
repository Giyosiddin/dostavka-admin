<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddPaymentsColumnsOrder extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
       Schema::table('products', function (Blueprint $table) {
            $table->string('address')->default('');
            $table->enum('payment_type', ['cash', 'click', 'payme'])->default('cash');
            $table->enum('payment_status', ['waiting', 'processing', 'completed'])->default('waiting');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
       $table->dropColumn('address');
       $table->dropColumn('payment_type');
       $table->dropColumn('payment_status');
    }
}
