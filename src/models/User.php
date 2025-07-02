<?php

use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    protected $table = 'EVNeoUsers';

    protected $fillable = [
        'BhrNumber',
        'FirstName',
        'MiddleName',
        'Nickname',
        'BirthDate',
        'Gender',
        'MaritalStatus',
        'AddressLine1',
        'AddressLine2',
        'City',
        'ProvinceState',
        'ZipCode',
        'Country',
        'MobileNumber',
        'HomePhone',
        'PersonalEmail',
        'LastName',
        'Department',
        'Workemail',
        'DateHired',
        'GDriveFolderId',
        'CurrentStep',
        'IsCompleted',
        'Origin',
        'IsFirstJob',
        'TermsAndConditionsTickedAt',
        'PrivacyPolicyTickedAt',
        'DeletedAt',
        'CreatedAt',
        'UpdatedAt',
        'CountryId',
        'Guid',
        'IsFormSubmited',
        'SubmitedCount',
        'PreferredName',
        'IsApproved',
    ];

    public $timestamps = false;

    public function department()
    {
        return $this->belongsTo(Department::class, 'department_id');
    }
}
