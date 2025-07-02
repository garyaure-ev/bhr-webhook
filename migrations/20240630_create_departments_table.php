<?php
// migrations/20240630_create_departments_table.php

use Illuminate\Database\Capsule\Manager as Capsule;

if (!Capsule::schema()->hasTable('EVNeoDepartments')) {
    Capsule::schema()->create('EVNeoDepartments', function ($table) {
        $table->increments('Id');
        $table->string('Name')->unique();
        $table->timestamps();
    });

}
