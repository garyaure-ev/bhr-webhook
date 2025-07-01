<?php

use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    protected $table = 'users';

    protected $fillable = [
        'bhr_number',
        'first_name',
        'middle_name',
        'last_name',
        'department_id',
        'email',
        'country',
        'date_hired',
        'drive_folder_id',
        'origin',
        'is_first_job',
        'terms_and_conditions_ticked_at',
        'privacy_policy_ticked_at',
        'is_completed',
        'deleted_at',
        'created_at',
        'updated_at'
    ];

    public $timestamps = false;

    public function department()
    {
        return $this->belongsTo(Department::class, 'department_id');
    }
}
