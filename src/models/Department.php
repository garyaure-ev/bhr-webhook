<?php
// src/Department.php

use Illuminate\Database\Eloquent\Model;

class Department extends Model
{
    protected $table = 'departments';

    protected $fillable = ['name'];

    public $timestamps = true;

    public function users()
    {
        return $this->hasMany(User::class, 'department_id');
    }
}
