<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrganizationRelationshipsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('organization_relationships', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->integer('org_id')->unsigned();
            $table->enum('type',['parent','related']);
            $table->integer('linked_org_id')->unsigned();
        });

        Schema::table('organization_relationships', function($table) {
          
            $table->foreign('linked_org_id')->references('id')->on('organizations');
            $table->foreign('org_id')->references('id')->on('organizations');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('organization_relationships');
    }
}
