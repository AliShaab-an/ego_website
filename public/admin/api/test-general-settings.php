<?php
/**
 * Simple General Settings Save Test
 * Simulates saving general settings data
 */
header('Content-Type: application/json');

require_once __DIR__ . '/../../config/path.php';
require_once CONFIG . 'database.php';
require_once MODELS . 'Settings.php';

$response = [
    'test' => 'general_settings_save',
    'timestamp' => date('Y-m-d H:i:s')
];

try {
    // Simulate form data for general settings
    $_POST = [
        'action' => 'saveSettings',
        'website_name' => 'Ego Luxury Clothing',
        'website_url' => 'https://ego-luxury.com',
        'contact_email' => 'contact@ego-luxury.com',
        'support_email' => 'support@ego-luxury.com',
        'phone_number' => '+961 123 456 789',
        'working_hours' => 'Monday - Friday: 9:00 AM - 6:00 PM\nSaturday: 10:00 AM - 4:00 PM\nSunday: Closed'
    ];
    
    // Test 1: Check if getAll returns empty or existing data
    $response['step1'] = [
        'name' => 'Load current settings',
        'status' => 'pending'
    ];
    
    try {
        $currentSettings = Settings::getAll();
        $response['step1']['status'] = 'success';
        $response['step1']['data'] = [
            'row_exists' => !empty($currentSettings),
            'sample' => [
                'website_name' => $currentSettings['website_name'] ?? null,
                'website_url' => $currentSettings['website_url'] ?? null,
                'contact_email' => $currentSettings['contact_email'] ?? null
            ]
        ];
    } catch (Exception $e) {
        $response['step1']['status'] = 'error';
        $response['step1']['error'] = $e->getMessage();
    }
    
    // Test 2: Prepare data like the controller does
    $response['step2'] = [
        'name' => 'Prepare data for update',
        'status' => 'pending'
    ];
    
    $data = [];
    $textFields = [
        'website_name', 'website_url', 'contact_email', 'support_email', 
        'phone_number', 'working_hours'
    ];
    
    foreach ($textFields as $field) {
        if (isset($_POST[$field])) {
            $data[$field] = trim($_POST[$field]);
        }
    }
    
    $response['step2']['status'] = 'success';
    $response['step2']['data_prepared'] = $data;
    
    // Test 3: Perform the update
    $response['step3'] = [
        'name' => 'Save settings to database',
        'status' => 'pending'
    ];
    
    try {
        $updateResult = Settings::update($data);
        $response['step3']['status'] = $updateResult ? 'success' : 'failed';
        $response['step3']['update_result'] = $updateResult;
        
        // Test 4: Verify the update
        $response['step4'] = [
            'name' => 'Verify saved data',
            'status' => 'pending'
        ];
        
        $savedSettings = Settings::getAll();
        $response['step4']['status'] = 'success';
        $response['step4']['saved_data'] = [
            'website_name' => $savedSettings['website_name'] ?? null,
            'website_url' => $savedSettings['website_url'] ?? null,
            'contact_email' => $savedSettings['contact_email'] ?? null,
            'support_email' => $savedSettings['support_email'] ?? null,
            'phone_number' => $savedSettings['phone_number'] ?? null,
            'working_hours' => substr($savedSettings['working_hours'] ?? '', 0, 50) . '...'
        ];
        
    } catch (Exception $e) {
        $response['step3']['status'] = 'error';
        $response['step3']['error'] = $e->getMessage();
    }
    
    $response['overall_status'] = 'success';
    
} catch (Exception $e) {
    $response['overall_status'] = 'error';
    $response['error'] = $e->getMessage();
}

echo json_encode($response, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
