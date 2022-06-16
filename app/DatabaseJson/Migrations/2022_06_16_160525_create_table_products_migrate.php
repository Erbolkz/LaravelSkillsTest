<?php

namespace App\DatabaseJson\Migrations;

use DatabaseJson\DatabaseJson;
use DatabaseJson\Migration;

class CreateTableProductsMigrate extends Migration
{
    /**
     * How to create table
     *
     * DatabaseJson::table('NameTable',array(
     *  {field_name} => {field_type} More information about field types and usage in PHPDoc
     * ));
     */

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DatabaseJson::create('products', array(
          'name' => 'string',
          'quantity' => 'integer',
          'price' => 'double',
          'created_at' => 'string',
          'updated_at' => 'string',
        ));
    }
}
