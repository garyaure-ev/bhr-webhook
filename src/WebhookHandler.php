<?php

require_once __DIR__ . '/Logger.php';

require_once __DIR__ . '/models/User.php';
require_once __DIR__ . '/models/Department.php';

use Carbon\Carbon;

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

            $departmentName = $fields['Job Information - Department'] ?? null; // Optional, may not exist in this payload
            $departmentId = null;

            if ($departmentName) {
                $department = Department::firstOrCreate(['name' => $departmentName]);
                $departmentId = $department->id;
            }

            $userData = [
                'bhr_number' => $employee['id'] ?? null,
                'first_name' => $fields['first_name'] ?? null,
                'middle_name' => $fields['middle_name'] ?? null,
                'last_name' => $fields['last_nName'] ?? null,
                'department_id' => $departmentId,
                'email' => $fields['home_email'] ?? null,
                'country' => $fields['country'] ?? null,
                //'preferred_name' => $fields['preferred_name'] ?? null,
                //'gender' => $fields['gender'] ?? null,
                'date_hired' => isset($fields['hire_date']) ? Carbon::parse($fields['hire_date']) : null,
                //'phone_mobile' => $fields['mobile_phone'] ?? null,
                //'phone_home' => $fields['home_phone'] ?? null,
                //'address' => trim(($fields['address_line_1'] ?? '') . ' ' . ($fields['address_line_2'] ?? '')),
                //'city' => $fields['city'] ?? null,
                //'state' => $fields['state'] ?? null,
                //'zip' => $fields['zip_code'] ?? null,
            ];

            if (empty($userData['bhr_number'])) {
                $logger->warning('Skipping user without bhr_number', ['userData' => $userData]);
                continue;
            }

            // Create or update the user
            $user = User::updateOrCreate(
                ['bhr_number' => $userData['bhr_number']],
                $userData
            );

            $logger->info('User record created/updated.', ['user_id' => $user->id]);
        }

        return 'received';
    } catch (\Exception $e) {
        $logger->error('Webhook error: ' . $e->getMessage());
        return 'error';
    }
}