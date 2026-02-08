<?php
    class AdminController {

        private function renderAdmin(string $view, array $data = []): void {
            $data['action'] ??= ($_GET['action'] ?? $view);
            View::render('admin/' . $view, $data, 'layouts/admin');
        }

        public function logout() {
            Session::destroySession();
            redirect('login.php?action=logout');
            exit;
        }

        private $ego = 'Ego luxury-';

        public function dashboard(): void{
            $this->renderAdmin('dashboard', ['pageTitle' => $this->ego.' Dashboard']);
        }

        public function categoryPage(){
            $this->renderAdmin('category', ['pageTitle' => $this->ego.' Categories']);
        }

        public function ordersPage() {
            $this->renderAdmin('orders_new', ['pageTitle' => $this->ego.' Orders']);
        }

        public function adminsPage(){
            $this->renderAdmin('admins', ['pageTitle' => $this->ego.' Admins']);
        }

        public function productsPage(){
            $this->renderAdmin('addProducts', ['pageTitle' => $this->ego.' Add Products']);
        }
        
        public function colorsAndSizesPage(){
            $this->renderAdmin('colorsAndSizes', ['pageTitle' => $this->ego.' Colors and Sizes']);
        }

        public function shippingPage(){
            $this->renderAdmin('shippingFee', ['pageTitle' => $this->ego.' Shipping Fee']);
        }

        public function couponsPage(){
            $this->renderAdmin('coupons', ['pageTitle' => $this->ego.' Coupons']);
        }

        public function manageProducts(){
            $this->renderAdmin('manageProducts', ['pageTitle' => $this->ego.' Manage Products']);
        }

        public function newsletterPage(){
            $this->renderAdmin('newsletter', ['pageTitle' => $this->ego.' Newsletter']);
        }

        public function contactMessagesPage(){
            $this->renderAdmin('contact-messages', ['pageTitle' => $this->ego.' Contact Messages']);
        }

        public function settingsPage(){
            $this->renderAdmin('settings', ['pageTitle' => $this->ego.' Settings']);
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