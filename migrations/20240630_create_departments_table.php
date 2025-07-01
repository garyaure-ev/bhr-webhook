<?php
// migrations/20240630_create_departments_table.php

use Illuminate\Database\Capsule\Manager as Capsule;

if (!Capsule::schema()->hasTable('departments')) {
    Capsule::schema()->create('departments', function ($table) {
        $table->increments('id');
        $table->string('name')->unique();
        $table->timestamps();
    });
}
