<?php
    require_once __DIR__ . '/../config/path.php';
    require_once MODELS . 'Dashboard.php';
    require_once MODELS . 'User.php';
    require_once CORE . 'Session.php';
    require_once CORE . 'Auth.php';
    require_once CORE . 'Helper.php';

    class AdminController {

        public function logout() {
            Session::destroySession();
            Helper::redirect('login.php?action=logout');
            exit;
        }

        public function dashboard(){
            include BACKEND_VIEWS . 'dashboard.php';
        }

        public function categoryPage(){
            include BACKEND_VIEWS . 'category.php';
        }

        public function ordersPage() {
            include BACKEND_VIEWS . 'orders_new.php';
        }

        public function adminsPage(){
            include BACKEND_VIEWS . 'admins.php';
        }

        public function productsPage(){
            include BACKEND_VIEWS . 'addProducts.php';
        }
        
        public function colorsAndSizesPage(){
            include BACKEND_VIEWS . 'colorsAndSizes.php';
        }

        public function shippingPage(){
            include BACKEND_VIEWS . 'shippingFee.php';
        }

        public function couponsPage(){
            include BACKEND_VIEWS . 'coupons.php';
        }

        public function manageProducts(){
            include BACKEND_VIEWS . 'manageProducts.php';
        }

        public function newsletterPage(){
            include BACKEND_VIEWS . 'newsletter.php';
        }

        public function contactMessagesPage(){
            include BACKEND_VIEWS . 'contact-messages.php';
        }

        public function settingsPage(){
            include BACKEND_VIEWS . 'settings.php';
        }

        /**
         * Get dashboard statistics
         */
        public function getDashboardStats() {
            header('Content-Type: application/json');
            
            try {
                error_log("getDashboardStats called");
                
                $stats = Dashboard::getStatistics();
                
                error_log("Stats result: " . ($stats === null ? "NULL" : "SUCCESS"));
                
                if ($stats === null) {
                    error_log("Dashboard::getStatistics returned null");
                    throw new Exception('Failed to retrieve dashboard statistics - check error logs');
                }
                
                echo json_encode([
                    'success' => true,
                    'stats' => $stats
                ]);
                
            } catch (Exception $e) {
                error_log("getDashboardStats exception: " . $e->getMessage());
                error_log("Exception trace: " . $e->getTraceAsString());
                
                http_response_code(500);
                echo json_encode([
                    'success' => false,
                    'message' => 'Error fetching dashboard statistics',
                    'error' => $e->getMessage()
                ]);
            }
        }
    }