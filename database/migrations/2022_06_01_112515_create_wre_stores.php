<?php

use App\Models\Warehouse\Store;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWreStores extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('wre_stores', function (Blueprint $table) {
            $table->id();

            $table->string('name', 255)->nullable();
            $table->string('address', 255)->nullable();
            $table->unsignedInteger('commune_id')->nullable();
            $table->foreign('commune_id')->references('id')->on('communes');

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
        Schema::dropIfExists('wre_stores');
    }
}
