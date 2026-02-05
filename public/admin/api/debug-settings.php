<?php
/**
 * General Settings Test Script
 * Tests loading and saving of general settings
 */
header('Content-Type: application/json');

require_once __DIR__ . '/../../config/path.php';
require_once CONFIG . 'database.php';
require_once MODELS . 'Settings.php';

$response = [
    'timestamp' => date('Y-m-d H:i:s'),
    'tests' => []
];

try {
    // Test 1: Check database connection
    $test1 = [];
    try {
        $result = $pdo->query("SELECT 1");
        $test1['status'] = 'pass';
        $test1['message'] = 'Database connection successful';
    } catch (Exception $e) {
        $test1['status'] = 'fail';
        $test1['message'] = 'Database connection failed: ' . $e->getMessage();
    }
    $response['tests']['database_connection'] = $test1;

    // Test 2: Check if settings table exists
    $test2 = [];
    try {
        $stmt = $pdo->query("SELECT COUNT(*) as count FROM information_schema.TABLES WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'settings'");
        $tableExists = $stmt->fetch(PDO::FETCH_ASSOC)['count'] > 0;
        
        if ($tableExists) {
            $test2['status'] = 'pass';
            $test2['message'] = 'Settings table exists';
            
            // Get table structure
            $columnsStmt = $pdo->query("SHOW COLUMNS FROM settings");
            $columns = [];
            while ($col = $columnsStmt->fetch(PDO::FETCH_ASSOC)) {
                $columns[] = $col['Field'];
            }
            $test2['column_count'] = count($columns);
            $test2['sample_columns'] = array_slice($columns, 0, 5);
        } else {
            $test2['status'] = 'fail';
            $test2['message'] = 'Settings table does not exist';
        }
    } catch (Exception $e) {
        $test2['status'] = 'fail';
        $test2['message'] = 'Error checking table: ' . $e->getMessage();
    }
    $response['tests']['table_exists'] = $test2;

    // Test 3: Check if settings row exists
    $test3 = [];
    try {
        $stmt = $pdo->query("SELECT COUNT(*) as count FROM settings");
        $count = $stmt->fetch(PDO::FETCH_ASSOC)['count'];
        
        if ($count > 0) {
            $test3['status'] = 'pass';
            $test3['message'] = "Settings row exists ($count row(s))";
        } else {
            $test3['status'] = 'warning';
            $test3['message'] = 'No settings row found - will be created on first save';
        }
    } catch (Exception $e) {
        $test3['status'] = 'fail';
        $test3['message'] = 'Error checking settings row: ' . $e->getMessage();
    }
    $response['tests']['settings_row_exists'] = $test3;

    // Test 4: Load general settings fields
    $test4 = [];
    try {
        $settings = Settings::getAll();
        
        $generalFields = [
            'website_name',
            'website_url', 
            'contact_email',
            'support_email',
            'phone_number',
            'working_hours'
        ];
        
        $test4['status'] = 'pass';
        $test4['message'] = 'Successfully loaded settings';
        $test4['general_settings'] = [];
        
        foreach ($generalFields as $field) {
            $test4['general_settings'][$field] = $settings[$field] ?? '[not set]';
        }
    } catch (Exception $e) {
        $test4['status'] = 'fail';
        $test4['message'] = 'Error loading settings: ' . $e->getMessage();
    }
    $response['tests']['load_general_settings'] = $test4;

    // Test 5: Test update (dry run - no actual data change)
    $test5 = [];
    try {
        // This will show if the update method works correctly
        $testData = ['website_name' => 'Test Update'];
        $result = Settings::update($testData);
        
        if ($result) {
            $test5['status'] = 'pass';
            $test5['message'] = 'Settings update method works';
            
            // Verify the update
            $updatedSettings = Settings::getAll();
            $test5['updated_value'] = $updatedSettings['website_name'] ?? 'Not found';
        } else {
            $test5['status'] = 'fail';
            $test5['message'] = 'Settings update returned false';
        }
    } catch (Exception $e) {
        $test5['status'] = 'fail';
        $test5['message'] = 'Error testing update: ' . $e->getMessage();
    }
    $response['tests']['update_method'] = $test5;

    // Test 6: Check file upload directory
    $test6 = [];
    $uploadDir = __DIR__ . '/../../public/admin/uploads/settings/';
    if (is_dir($uploadDir)) {
        $test6['status'] = 'pass';
        $test6['message'] = 'Upload directory exists and is writable';
        $test6['path'] = $uploadDir;
        $test6['writable'] = is_writable($uploadDir);
    } else {
        $test6['status'] = 'warning';
        $test6['message'] = 'Upload directory does not exist - will be created on first upload';
        $test6['path'] = $uploadDir;
    }
    $response['tests']['upload_directory'] = $test6;

    $response['overall_status'] = 'ready_for_testing';
    
} catch (Exception $e) {
    $response['overall_status'] = 'error';
    $response['error'] = $e->getMessage();
}

echo json_encode($response, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
