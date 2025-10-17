<?php
    require_once __DIR__ . '/../../app/config/path.php';
    require_once __DIR__ . '/../../app/controllers/AdminController.php';
    require_once __DIR__ . '/../../app/core/Session.php';
    require_once __DIR__ . '/../../app/core/Helper.php';
    
    
    Session::configure(900,'/Ego_website/public/admin/login.php');
    Session::startSession();

    $adminController = new AdminController();

    $action = $_GET['action'] ?? 'dashboard';

    if (!in_array($action, ['login', 'logout'])) {
        Auth::checkAdmin();   // will allow admin or super_admin
    }
?>
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="<?= CSS_PATH ?>/style.css">
        <link rel='stylesheet' href='https://cdn-uicons.flaticon.com/3.0.0/uicons-regular-rounded/css/uicons-regular-rounded.css'>
        <script src="<?= ADMIN_JS_PATH ?>chart.umd.min.js"></script>
        <script src="https://kit.fontawesome.com/7f6ab6587f.js" crossorigin="anonymous"></script>
        <title>Admin Panel - Ego Clothing</title>
    </head>
    <body class="flex justify-center bg-white h-screen overflow-hidden" data-page="<?= htmlspecialchars($action) ?>">
    
        <div class="  h-9/10 flex mt-6">
            <?php include( BACKEND_VIEWS .'sidebar.php');?>
            <main class="flex-1 ml-10 w-5xl h-full overflow-y-auto">    
                
                <?php 
                    switch($action){
                        case 'login':
                            $adminController->login();
                            break;
                        case 'dashboard':
                            $adminController->dashboard();
                            break;
                        case 'orderManagement':
                            $adminController->ordersPage();
                            break;
                        case 'addProduct':
                            $adminController->productsPage();
                            break;
                        case 'Categories':
                            $adminController->categoryPage();
                            break;
                        case 'Admins':
                            // only super_admin can access
                            Auth::checkRoles(['super_admin']); 
                            $adminController->adminsPage();
                            break;
                        case 'ColorsAndSizes':
                            $adminController->colorsAndSizesPage();
                            break;
                        case 'ShippingFees':
                            $adminController->shippingPage();
                            break;
                        case 'Coupons':
                            $adminController->couponsPage();
                            break;
                        case 'manageProducts':
                            $adminController->manageProducts();
                            break;
                        case 'logout':
                            $adminController->logout();
                            break;
                        default:
                            echo "<h1 class='text-2xl font-bold'>404 - Page not found</h1>";
                    }
                ?>
            </main>
            
        </div>
        <script src="<?= JS_PATH ?>jquery-3.7.1.min.js"></script>
        <script type="module" src="<?= ADMIN_JS_PATH ?>main.js"></script>
    </body>
    </html>

    


