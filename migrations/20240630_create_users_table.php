<?php

use Illuminate\Database\Capsule\Manager as Capsule;

if (!Capsule::schema()->hasTable('EVNeoUsers')) {
    Capsule::schema()->create('EVNeoUsers', function ($table) {
        $table->increments('Id');
        $table->integer('BhrNumber');
        $table->longText('FirstName');
        $table->longText('MiddleName');
        $table->longText('LastName');
        $table->longText('PreferredName');
        $table->dateTime('BirthDate', 6)->nullable();
        $table->longText('Gender');
        $table->longText('MaritalStatus');
        $table->longText('AddressLine1');
        $table->longText('AddressLine2');
        $table->longText('City');
        $table->longText('ProvinceState');
        $table->integer('ZipCode')->nullable();
        $table->longText('Country');
        $table->longText('MobileNumber');
        $table->longText('HomePhone');
        $table->longText('PersonalEmail');
        $table->integer('DepartmentId')->nullable();
        $table->longText('Workemail');
        $table->dateTime('DateHired', 6)->nullable();
        $table->longText('GDriveFolderId');
        $table->integer('CurrentStep');
        $table->boolean('IsCompleted');
        $table->integer('Origin')->nullable();
        $table->boolean('IsFirstJob')->nullable();
        $table->dateTime('TermsAndConditionsTickedAt', 6)->nullable();
        $table->dateTime('PrivacyPolicyTickedAt', 6)->nullable();
        $table->dateTime('DeletedAt', 6)->nullable();
        $table->dateTime('CreatedAt', 6)->nullable();
        $table->dateTime('UpdatedAt', 6)->nullable();
        $table->integer('CountryId')->nullable();
        $table->char('Guid', 36);
        $table->boolean('IsFormSubmited');
        $table->integer('SubmitedCount');
        $table->boolean('IsApproved');
    });
}
