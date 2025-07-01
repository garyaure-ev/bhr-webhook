<?php

use Illuminate\Database\Capsule\Manager as Capsule;

if (!Capsule::schema()->hasTable('users')) {
    Capsule::schema()->create('users', function ($table) {
        $table->increments('id');
        $table->string('bhr_number')->unique();
        $table->string('first_name')->nullable();
        $table->string('middle_name')->nullable();
        $table->string('last_name')->nullable();
        $table->unsignedInteger('department_id')->nullable();
        $table->string('email')->nullable();
        $table->string('country')->nullable();
        $table->timestamp('date_hired')->nullable();
        $table->string('drive_folder_id')->nullable();
        $table->integer('origin')->nullable();
        $table->boolean('is_first_job')->nullable();
        $table->timestamp('terms_and_conditions_ticked_at')->nullable();
        $table->timestamp('privacy_policy_ticked_at')->nullable();
        $table->boolean('is_completed')->default(false);
        $table->timestamp('deleted_at')->nullable();
        $table->timestamp('created_at')->nullable();
        $table->timestamp('updated_at')->nullable();
    });
}