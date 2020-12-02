<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBodiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bodies', function (Blueprint $table) {
            $table->increments('id');
            $table->string('user_id', 255);
            $table->string('site_id', 255);
            $table->string('name', 255);
            $table->string('slug', 255);
            $table->string('type', 255);
            $table->string('meta_title', 255);
            $table->string('meta_keywords', 255);
            $table->string('meta_description', 255);
            $table->string('path', 255);
            $table->json('data');
            $table->boolean('searchable')->default(true);
            $table->boolean('subscribed')->default(true);
            $table->timestamps();
            $table->unique(['site_id', 'slug']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('bodies');
    }
}
