<?php

    class ContactMessageController {
        
        /**
         * Submit a contact message
         */
        public function submit() {
            if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
                throw new Exception('Invalid request method');
            }

            $input = json_decode(file_get_contents('php://input'), true);
            
            if (!$input) {
                // Handle form data instead of JSON
                $name = $_POST['name'] ?? '';
                $email = $_POST['email'] ?? '';
                $message = $_POST['message'] ?? '';
            } else {
                $name = $input['name'] ?? '';
                $email = $input['email'] ?? '';
                $message = $input['message'] ?? '';
            }

            if (empty($name) || empty($email) || empty($message)) {
                throw new Exception('All fields are required');
            }

            // Check if user is logged in
            $userId = null;
            if (isset($_SESSION['user_id'])) {
                $userId = $_SESSION['user_id'];
            }

            $messageId = ContactMessage::create($name, $email, $message, $userId);
            
            return [
                'success' => true,
                'message' => 'Your message has been sent successfully! We will get back to you soon.',
                'message_id' => $messageId
            ];
        }

        /**
         * Get all contact messages (admin only)
         */
        public function getMessages() {
            $page = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
            $limit = isset($_GET['limit']) ? max(1, min(100, (int)$_GET['limit'])) : 20;
            $status = isset($_GET['status']) ? $_GET['status'] : 'all';
            
            $offset = ($page - 1) * $limit;
            
            $messages = ContactMessage::getAll($limit, $offset, $status);
            $totalCount = ContactMessage::countAll($status);
            $totalPages = ceil($totalCount / $limit);
            
            return [
                'success' => true,
                'data' => $messages,
                'pagination' => [
                    'current_page' => $page,
                    'total_pages' => $totalPages,
                    'total_count' => $totalCount,
                    'per_page' => $limit,
                    'has_next' => $page < $totalPages,
                    'has_prev' => $page > 1
                ],
                'summary' => [
                    'total' => ContactMessage::countAll(),
                    'read' => ContactMessage::countRead(),
                    'unread' => ContactMessage::countUnread()
                ]
            ];
        }

        /**
         * Update message status (admin only)
         */
        public function updateStatus() {
            if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
                throw new Exception('Invalid request method');
            }

            $input = json_decode(file_get_contents('php://input'), true);
            
            if (!isset($input['id']) || !isset($input['action'])) {
                throw new Exception('Missing required parameters');
            }

            $id = (int)$input['id'];
            $action = $input['action'];

            if (!in_array($action, ['read', 'unread'])) {
                throw new Exception('Invalid action');
            }

            // Check if message exists
            $message = ContactMessage::getById($id);
            if (!$message) {
                throw new Exception('Message not found');
            }

            // Perform action
            if ($action === 'read') {
                ContactMessage::markAsRead($id);
                $responseMessage = 'Message marked as read successfully';
            } else {
                ContactMessage::markAsUnread($id);
                $responseMessage = 'Message marked as unread successfully';
            }

            return [
                'success' => true,
                'message' => $responseMessage
            ];
        }

        /**
         * Delete a message (admin only)
         */
        public function deleteMessage() {
            if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
                throw new Exception('Invalid request method');
            }

            $input = json_decode(file_get_contents('php://input'), true);
            
            if (!isset($input['id'])) {
                throw new Exception('Message ID is required');
            }

            $id = (int)$input['id'];

            // Check if message exists
            $message = ContactMessage::getById($id);
            if (!$message) {
                throw new Exception('Message not found');
            }

            ContactMessage::delete($id);

            return [
                'success' => true,
                'message' => 'Message has been deleted successfully'
            ];
        }

        /**
         * Export messages to CSV (admin only)
         */
        public function exportCSV() {
            $status = isset($_GET['status']) ? $_GET['status'] : 'all';
            $data = ContactMessage::getAllForExport($status);
            
            $csv = ContactMessage::generateCSV($data);
            
            return [
                'success' => true,
                'csv' => $csv,
                'filename' => 'contact_messages_' . date('Y-m-d_H-i-s') . '.csv'
            ];
        }

        /**
         * Get a single message by ID (admin only)
         */
        public function getMessage() {
            if (!isset($_GET['id'])) {
                throw new Exception('Message ID is required');
            }

            $id = (int)$_GET['id'];
            $message = ContactMessage::getById($id);

            if (!$message) {
                throw new Exception('Message not found');
            }

            return [
                'success' => true,
                'data' => $message
            ];
        }
    }