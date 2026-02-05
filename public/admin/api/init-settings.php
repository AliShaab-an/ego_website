<?php
/**
 * Initialize Settings Table
 * Ensures the settings table has at least one row with default values
 */
header('Content-Type: application/json');

require_once __DIR__ . '/../../config/path.php';
require_once CONFIG . 'database.php';

$response = [
    'action' => 'init_settings',
    'timestamp' => date('Y-m-d H:i:s')
];

try {
    // Check if settings table exists
    $checkTable = $pdo->query("SELECT COUNT(*) as count FROM information_schema.TABLES WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'settings'");
    $tableExists = $checkTable->fetch(PDO::FETCH_ASSOC)['count'] > 0;
    
    if (!$tableExists) {
        $response['status'] = 'error';
        $response['message'] = 'Settings table does not exist. Please run database migration first.';
        echo json_encode($response);
        exit;
    }
    
    // Check if any settings row exists
    $stmt = $pdo->query("SELECT COUNT(*) as count FROM settings");
    $count = $stmt->fetch(PDO::FETCH_ASSOC)['count'];
    
    if ($count == 0) {
        // Create default settings row
        try {
            $insertStmt = $pdo->prepare("
                INSERT INTO settings (
                    website_name, website_url, contact_email, support_email,
                    phone_number, working_hours, primary_color, secondary_color, accent_color,
                    currency, created_at, updated_at
                ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW(), NOW())
            ");
            
            $insertStmt->execute([
                'Ego Clothing',
                'https://ego-luxury.com',
                'contact@ego-luxury.com',
                'support@ego-luxury.com',
                '+961 XXXXXXX',
                'Monday - Friday: 9:00 AM - 6:00 PM' . "\n" . 'Saturday: 10:00 AM - 4:00 PM' . "\n" . 'Sunday: Closed',
                '#b7926f',
                '#9e7e59',
                '#88663d',
                'USD'
            ]);
            
            $response['status'] = 'success';
            $response['message'] = 'Default settings row created successfully';
            $response['row_id'] = $pdo->lastInsertId();
            
        } catch (Exception $e) {
            $response['status'] = 'error';
            $response['message'] = 'Failed to create settings row: ' . $e->getMessage();
        }
    } else {
        $response['status'] = 'success';
        $response['message'] = "Settings table already has {$count} row(s)";
        $response['existing_rows'] = $count;
    }
    
} catch (Exception $e) {
    $response['status'] = 'error';
    $response['message'] = 'Database error: ' . $e->getMessage();
}

echo json_encode($response);
