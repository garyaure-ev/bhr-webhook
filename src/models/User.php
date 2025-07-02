<?php

use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    protected $table = 'EVNeoUsers';
    protected $primaryKey = 'Id';

    protected $fillable = [
        'BhrNumber',
        'FirstName',
        'MiddleName',
        'LastName',
        'PreferredName',
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
        'DepartmentId',
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
        'IsApproved',
    ];

    public $timestamps = true;

    const CREATED_AT = 'CreatedAt';
    const UPDATED_AT = 'UpdatedAt';

    public function department()
    {
        return $this->belongsTo(Department::class, 'DepartmentId');
    }
}
