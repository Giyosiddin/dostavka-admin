<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddPaymentColumnsToOrders extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn('address');
            $table->dropColumn('payment_type');
            $table->dropColumn('payment_status');
        });
        Schema::table('orders', function (Blueprint $table) {
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
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn('address');
            $table->dropColumn('payment_type');
            $table->dropColumn('payment_status');
        });
        Schema::table('products', function (Blueprint $table) {
            $table->string('address')->default('');
            $table->enum('payment_type', ['cash', 'click', 'payme'])->default('cash');
            $table->enum('payment_status', ['waiting', 'processing', 'completed'])->default('waiting');
        });
    }
}
