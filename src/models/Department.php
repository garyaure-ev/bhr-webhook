<?php
// src/Department.php

use Illuminate\Database\Eloquent\Model;

class Department extends Model
{
    protected $table = 'EVNeoDepartments';

    protected $primaryKey = 'Id';

    protected $fillable = ['Name'];

    public $timestamps = true;

    public function users()
    {
        return $this->hasMany(User::class, 'department_id');
    }
}
