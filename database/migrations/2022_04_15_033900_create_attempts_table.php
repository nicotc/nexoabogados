<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAttemptsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

            Schema::create('attempts', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('subscription_id');
                $table->boolean('success')->nullable();
                $table->integer('attempts')->nullable();
                $table->timestamps();
                $table->softDeletes();
                $table->foreign('subscription_id')->references('id')->on('subscriptions');
            });


    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('attempts');
    }
}
