<?php
require_once __DIR__ . '/../config/path.php';

require_once MODELS . 'Newsletter.php';
require_once CORE . 'Session.php';
require_once CORE . 'Helper.php';

class NewsletterController {
    
    public function subscribe() {
        try {
            if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
                throw new Exception('Invalid request method');
            }

            $input = json_decode(file_get_contents('php://input'), true);
            
            if (!$input) {
                // Handle form data instead of JSON
                $name = $_POST['name'] ?? '';
                $email = $_POST['email'] ?? '';
            } else {
                $name = $input['name'] ?? '';
                $email = $input['email'] ?? '';
            }

            if (empty($name) || empty($email)) {
                throw new Exception('Name and email are required');
            }

            $subscriberId = Newsletter::subscribe($name, $email);
            
            return [
                'success' => true,
                'message' => 'Successfully subscribed to newsletter!',
                'subscriber_id' => $subscriberId
            ];
            
        } catch (Exception $e) {
            return [
                'success' => false,
                'message' => $e->getMessage()
            ];
        }
    }

    /**
     * Get all newsletter subscribers (admin only)
     */
    public function getSubscribers() {
        try {
            $page = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
            $limit = isset($_GET['limit']) ? max(1, min(100, (int)$_GET['limit'])) : 20;
            $status = isset($_GET['status']) ? $_GET['status'] : 'all';
            
            $offset = ($page - 1) * $limit;
            
            $subscribers = Newsletter::getAll($limit, $offset, $status);
            $totalCount = Newsletter::countAll($status);
            $totalPages = ceil($totalCount / $limit);
            
            return [
                'success' => true,
                'data' => $subscribers,
                'pagination' => [
                    'current_page' => $page,
                    'total_pages' => $totalPages,
                    'total_count' => $totalCount,
                    'per_page' => $limit,
                    'has_next' => $page < $totalPages,
                    'has_prev' => $page > 1
                ],
                'summary' => [
                    'total' => Newsletter::countAll(),
                    'active' => Newsletter::countActive(),
                    'inactive' => Newsletter::countInactive()
                ]
            ];
            
        } catch (Exception $e) {
            return [
                'success' => false,
                'message' => $e->getMessage()
            ];
        }
    }

    /**
     * Update subscriber status (admin only)
     */
    public function updateStatus() {
        try {
            if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
                throw new Exception('Invalid request method');
            }

            $input = json_decode(file_get_contents('php://input'), true);
            
            if (!isset($input['id']) || !isset($input['action'])) {
                throw new Exception('Missing required parameters');
            }

            $id = (int)$input['id'];
            $action = $input['action'];

            if (!in_array($action, ['activate', 'deactivate'])) {
                throw new Exception('Invalid action');
            }

            // Check if subscriber exists
            $subscriber = Newsletter::getById($id);
            if (!$subscriber) {
                throw new Exception('Subscriber not found');
            }

            // Perform action
            if ($action === 'activate') {
                Newsletter::activate($id);
                $message = 'Subscriber has been activated successfully';
            } else {
                Newsletter::deactivate($id);
                $message = 'Subscriber has been deactivated successfully';
            }

            return [
                'success' => true,
                'message' => $message
            ];
            
        } catch (Exception $e) {
            return [
                'success' => false,
                'message' => $e->getMessage()
            ];
        }
    }


    public function deleteSubscriber() {
        try {
            if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
                throw new Exception('Invalid request method');
            }

            $input = json_decode(file_get_contents('php://input'), true);
            
            if (!isset($input['id'])) {
                throw new Exception('Subscriber ID is required');
            }

            $id = (int)$input['id'];

            // Check if subscriber exists
            $subscriber = Newsletter::getById($id);
            if (!$subscriber) {
                throw new Exception('Subscriber not found');
            }

            Newsletter::delete($id);

            return [
                'success' => true,
                'message' => 'Subscriber has been deleted successfully'
            ];
            
        } catch (Exception $e) {
            return [
                'success' => false,
                'message' => $e->getMessage()
            ];
        }
    }

    /**
     * Export subscribers to CSV (admin only)
     */
    public function exportCSV() {
        try {
            $status = isset($_GET['status']) ? $_GET['status'] : 'all';
            $data = Newsletter::getAllForExport($status);
            
            $csv = Newsletter::generateCSV($data);
            
            return [
                'success' => true,
                'csv' => $csv,
                'filename' => 'newsletter_subscribers_' . date('Y-m-d_H-i-s') . '.csv'
            ];
            
        } catch (Exception $e) {
            return [
                'success' => false,
                'message' => $e->getMessage()
            ];
        }
    }
}