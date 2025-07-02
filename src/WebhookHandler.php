<?php

require_once __DIR__ . '/Logger.php';

require_once __DIR__ . '/models/User.php';
require_once __DIR__ . '/models/Department.php';

use Carbon\Carbon;
use Illuminate\Support\Str;
use Illuminate\Database\Capsule\Manager as DB;

function handleWebhook(array $payload, $logger): string
{
    try {
        $logger->info('BambooHR Webhook received', ['payload' => $payload]);

        if (!isset($payload['employees']) || !is_array($payload['employees'])) {
            $logger->warning('Missing or invalid "employees" array in payload');
            return 'invalid_payload';
        }

        foreach ($payload['employees'] as $employee) {
            if (!isset($employee['fields']) || !is_array($employee['fields'])) {
                $logger->warning('Missing or invalid "fields" in employee data', ['employee' => $employee]);
                continue;
            }

            $fields = $employee['fields'];

            $departmentName = $fields['department'] ?? null; // Optional, may not exist in this payload
            $departmentId = null;

            if ($departmentName) {
                $department = Department::firstOrCreate(['Name' => $departmentName]);
                $departmentId = $department->id;
                $logger->info('Department', [$department]);
            }

            $userData = [
                'BhrNumber' => $employee['id'] ?? null,
                'FirstName' => $fields['first_name'] ?? '',
                'MiddleName' => $fields['middle_name'] ?? '',
                'LastName' => $fields['last_name'] ?? '',
                'PreferredName' => $fields['preferred_name'] ?? '',
                'BirthDate' => isset($fields['birth_date']) ? Carbon::parse($fields['birth_date']) : null,
                'Gender' => $fields['gender'] ?? '',
                'MaritalStatus' => $fields['marital_status'] ?? '',
                'AddressLine1' => $fields['address_line_1'] ?? '',
                'AddressLine2' => $fields['address_line_2'] ?? '',
                'City' => $fields['city'] ?? '',
                'ProvinceState' => $fields['state'] ?? '',
                'ZipCode' => $fields['zip_code'] ?? null,
                'Country' => $fields['country'] ?? '',
                'MobileNumber' => $fields['mobile_phone'] ?? '',
                'HomePhone' => $fields['home_phone'] ?? '',
                'PersonalEmail' => $fields['home_email'] ?? '',
                'DepartmentId' => $departmentId,
                'Workemail' => $fields['work_email'] ?? '',
                'DateHired' => isset($fields['hire_date']) ? Carbon::parse($fields['hire_date']) : null,
                'GDriveFolderId' => $fields['gdrive_folder_id'] ?? '',
                'CurrentStep' => $fields['current_step'] ?? 0,
                'IsCompleted' => $fields['is_completed'] ?? 0,
                'Origin' => $fields['origin'] ?? null,
                'IsFirstJob' => $fields['is_first_job'] ?? null,
                'TermsAndConditionsTickedAt' => isset($fields['terms_ticked_at']) ? Carbon::parse($fields['terms_ticked_at']) : null,
                'PrivacyPolicyTickedAt' => isset($fields['privacy_ticked_at']) ? Carbon::parse($fields['privacy_ticked_at']) : null,
                'DeletedAt' => isset($fields['deleted_at']) ? Carbon::parse($fields['deleted_at']) : null,
                'CreatedAt' => isset($fields['created_at']) ? Carbon::parse($fields['created_at']) : null,
                'UpdatedAt' => isset($fields['updated_at']) ? Carbon::parse($fields['updated_at']) : null,
                'CountryId' => $fields['country_id'] ?? null,
            ];

            // Create or update the user
            $createOnlyFields = [
                'Guid' => Str::uuid()->toString(),
                'IsFormSubmited' => 0,
                'SubmitedCount' => 0,
                'IsApproved' => 0,
            ];

            $logger->info('New Data', [$userData]);

            if (empty($userData['BhrNumber'])) {
                $logger->warning('Skipping user without BhrNumber', ['userData' => $userData]);
                continue;
            }

            // Merge into $userData only if we're creating
            $user = User::firstOrNew(['BhrNumber' => $userData['BhrNumber']]);

            $logger->info('User Instance', [$user]);

            // If it's a new record, assign create-only fields
            if (!$user->exists) {
                $user->fill($createOnlyFields);
            } else {
                $logger->info('Updating existing user.', ['user_id' => $user->id]);
            }

            // Fill shared/updateable fields
            $user->fill($userData);

            // Enable query log
            DB::connection()->enableQueryLog();

            // Perform actions (e.g., saving)
            $user->save();

            // Get the queries
            $queries = DB::getQueryLog();
            $logger->info('SQL Queries', $queries);

            $logger->info('User Updated Instance', [$user]);

            $logger->info('User record created/updated.', ['user_id' => $user->Id]);
        }
        return 'received';
    } catch (\Exception $e) {
        $logger->error('Webhook error: ' . $e->getMessage());
        return 'error';
    }
}