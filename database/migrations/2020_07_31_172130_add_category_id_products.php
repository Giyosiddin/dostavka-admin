<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddCategoryIdProducts extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('products', function (Blueprint $table) {
            $table->unsignedBigInteger('category_id')->default(1);
        });
        Schema::table('order_product', function (Blueprint $table){
            $table->unsignedInteger('quantity')->default(0);
            $table->unsignedInteger('cost')->default(0);
            $table->unsignedInteger('total')->default(0);
        });
        Schema::table('products', function (Blueprint $table) {
            $table->bigInteger('count')->default(0)->change();
        });
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('profile');
        });
        Schema::table('users', function (Blueprint $table) {
            $table->string('avatar')->default('');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn('category_id');
        });
        
        Schema::table('order_product', function (Blueprint $table){
            $table->dropColumn('quantity');
            $table->dropColumn('cost');
            $table->dropColumn('total');
        });
        
        Schema::table('users', function (Blueprint $table) {
            $table->string('profile')->default('');
        });
        
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('avatar');
        });
    }
}
