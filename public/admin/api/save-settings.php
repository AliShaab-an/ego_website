<?php

require_once __DIR__ . '/../../app/config/path.php';
require_once CORE . 'Session.php';
require_once CORE . 'Auth.php';
require_once MODELS . 'Settings.php';

Session::configure(900, url('admin/login.php'), false);
Session::startSession();

// Check if user is admin
Auth::checkAdmin();
Auth::checkRoles(['super_admin']);

header('Content-Type: application/json');

try {
    // Get POST data
    $settings = [];
    
    // General settings
    if (isset($_POST['website_name'])) {
        $settings['website_name'] = $_POST['website_name'];
    }
    if (isset($_POST['website_url'])) {
        $settings['website_url'] = $_POST['website_url'];
    }
    if (isset($_POST['contact_email'])) {
        $settings['contact_email'] = $_POST['contact_email'];
    }
    if (isset($_POST['phone_number'])) {
        $settings['phone_number'] = $_POST['phone_number'];
    }
    if (isset($_POST['company_description'])) {
        $settings['company_description'] = $_POST['company_description'];
    }
    
    // Branding settings
    if (isset($_POST['primary_color'])) {
        $settings['primary_color'] = $_POST['primary_color'];
    }
    if (isset($_POST['secondary_color'])) {
        $settings['secondary_color'] = $_POST['secondary_color'];
    }
    if (isset($_POST['accent_color'])) {
        $settings['accent_color'] = $_POST['accent_color'];
    }
    
    // Handle file uploads
    $uploadDir = ROOT_PATH . 'public/admin/uploads/';
    
    if (isset($_FILES['logo_file']) && $_FILES['logo_file']['error'] === UPLOAD_ERR_OK) {
        $filename = 'logo_' . time() . '.' . pathinfo($_FILES['logo_file']['name'], PATHINFO_EXTENSION);
        if (move_uploaded_file($_FILES['logo_file']['tmp_name'], $uploadDir . $filename)) {
            $settings['logo'] = '/admin/uploads/' . $filename;
        }
    }
    
    if (isset($_FILES['homepage_bg']) && $_FILES['homepage_bg']['error'] === UPLOAD_ERR_OK) {
        $filename = 'homepage_bg_' . time() . '.' . pathinfo($_FILES['homepage_bg']['name'], PATHINFO_EXTENSION);
        if (move_uploaded_file($_FILES['homepage_bg']['tmp_name'], $uploadDir . $filename)) {
            $settings['homepage_background'] = '/admin/uploads/' . $filename;
        }
    }
    
    if (isset($_FILES['shop_bg']) && $_FILES['shop_bg']['error'] === UPLOAD_ERR_OK) {
        $filename = 'shop_bg_' . time() . '.' . pathinfo($_FILES['shop_bg']['name'], PATHINFO_EXTENSION);
        if (move_uploaded_file($_FILES['shop_bg']['tmp_name'], $uploadDir . $filename)) {
            $settings['shop_background'] = '/admin/uploads/' . $filename;
        }
    }
    
    // Pages settings
    if (isset($_POST['about_content'])) {
        $settings['about_content'] = $_POST['about_content'];
    }
    
    // SEO settings
    if (isset($_POST['meta_title'])) {
        $settings['meta_title'] = $_POST['meta_title'];
    }
    if (isset($_POST['meta_description'])) {
        $settings['meta_description'] = $_POST['meta_description'];
    }
    if (isset($_POST['meta_keywords'])) {
        $settings['meta_keywords'] = $_POST['meta_keywords'];
    }
    if (isset($_POST['google_analytics_id'])) {
        $settings['google_analytics_id'] = $_POST['google_analytics_id'];
    }
    
    // Social Media settings
    if (isset($_POST['instagram_url'])) {
        $settings['instagram_url'] = $_POST['instagram_url'];
    }
    if (isset($_POST['facebook_url'])) {
        $settings['facebook_url'] = $_POST['facebook_url'];
    }
    if (isset($_POST['twitter_url'])) {
        $settings['twitter_url'] = $_POST['twitter_url'];
    }
    if (isset($_POST['tiktok_url'])) {
        $settings['tiktok_url'] = $_POST['tiktok_url'];
    }
    if (isset($_POST['linkedin_url'])) {
        $settings['linkedin_url'] = $_POST['linkedin_url'];
    }
    if (isset($_POST['youtube_url'])) {
        $settings['youtube_url'] = $_POST['youtube_url'];
    }
    
    // Save all settings
    if (Settings::saveMultiple($settings)) {
        echo json_encode([
            'success' => true,
            'message' => 'Settings saved successfully'
        ]);
    } else {
        echo json_encode([
            'success' => false,
            'message' => 'Failed to save settings'
        ]);
    }
    
} catch (Exception $e) {
    error_log("Error in save-settings.php: " . $e->getMessage());
    echo json_encode([
        'success' => false,
        'message' => 'Error: ' . $e->getMessage()
    ]);
}
