<?php 

    require_once __DIR__ . '/../models/Product.php';
    require_once __DIR__ . '/../models/ProductImages.php';
    require_once __DIR__ . '/../core/Session.php';
    require_once __DIR__ . '/../models/Cart.php';
    require_once CORE . 'Logger.php';


    class cartController{

        private function getIdentifiers(){
            Session::startSession();
            $userId = Session::getKey('user_id');
            $sessionId = Session::getKey('session_id');


            if(!$userId && !$sessionId){
                $sessionId = session_id();
                Session::set('session_id',$sessionId);
            }
            return [$userId, $sessionId];
        }

        public function addToCart() {
            list($userId, $sessionId) = $this->getIdentifiers();

            if (!$userId && !$sessionId) {
                $sessionId = session_id() ?: bin2hex(random_bytes(10));
                session_start();
                $_SESSION['session_id'] = $sessionId;
            }
            file_put_contents(__DIR__ . '/../../logs/error.log', "DEBUG: userId=$userId, sessionId=$sessionId", FILE_APPEND);

            $productId = $_POST['productId'] ?? null;
            $size      = $_POST['size'] ?? null;
            $color     = $_POST['color'] ?? null;
            $quantity  = intval($_POST['quantity'] ?? 1);

            file_put_contents(__DIR__ . '/../../logs/error.log',
            "DEBUG: productId=$productId, sizeId=$size, colorId=$color, qty=$quantity\n",
                FILE_APPEND
            );

            if (!$productId) {
                return ['success' => false, 'message' => 'Product ID missing'];
            }

            try {
                //  Fetch product from DB (secure, trusted data)
                $product = Product::findProductById($productId); 
                if (!$product) {
                    return ['success' => false, 'message' => 'Product not found'];
                }
                $productName = $product['name'];
                $price = $product['base_price'];
                
                
                //  Get or create cart
                $cart = Cart::getCart($userId, $sessionId);
                error_log("DEBUG: getCart result = " . print_r($cart, true));
                if (!$cart) {
                    $cartId = Cart::createCart($userId, $sessionId);
                    file_put_contents(__DIR__ . '/../../logs/error.log', "DEBUG: created new cartId=$cartId\n", FILE_APPEND);
                } else {
                    $cartId = $cart['cart_id'];
                    file_put_contents(__DIR__ . '/../../logs/error.log', "DEBUG: existing cartId=$cartId\n", FILE_APPEND);
                }

            
                Cart::addOrUpdateItem($cartId, $productId, $quantity, $price, $size, $color, $mainImage);
                file_put_contents(__DIR__ . '/../../logs/error.log', "DEBUG: item added/updated\n", FILE_APPEND);

                //  Update cart count
                $cartCount = Cart::getCartCount($cartId);
                file_put_contents(__DIR__ . '/../../logs/error.log', "DEBUG: cartCount=$cartCount\n", FILE_APPEND);
                return [
                    'success' => true,
                    'message' => "$productName added to cart",
                    'cart_count' => $cartCount
                ];

            } catch (Exception $e) {
                Logger::error("cartController::addProduct", $e->getMessage());
                return ['success' => false, 'message' => 'Server error'];
            }
        }

        public function getCartItems() {
            list($userId, $sessionId) = $this->getIdentifiers();
            $cart = Cart::getCart($userId, $sessionId);

            if (!$cart) {
                return ['items' => []];
            }

            $items = Cart::getItems($cart['cart_id']);
            return ['items' => $items];
        }

        public function updateQuantity() {
            $itemId   = $_POST['itemId'] ?? null;
            $quantity = $_POST['quantity'] ?? null;

            if (!$itemId || !$quantity) {
                return ['success' => false, 'message' => 'Missing parameters'];
            }

            try {
                Cart::updateItemQuantity($itemId, $quantity);
                return ['success' => true, 'message' => 'Quantity updated'];
            } catch(Exception $e) {
                error_log("Update quantity error: " . $e->getMessage());
                return ['success' => false, 'message' => 'Server error'];
            }
        }

        public function removeItem() {
            $itemId = $_POST['itemId'] ?? null;

            if (!$itemId) {
                return ['success' => false, 'message' => 'Item ID missing'];
            }

            try {
                Cart::removeItem($itemId);
                return ['success' => true, 'message' => 'Item removed'];
            } catch(Exception $e) {
                error_log("Remove item error: " . $e->getMessage());
                return ['success' => false, 'message' => 'Server error'];
            }
        }

        public function clearCart() {
            list($userId, $sessionId) = $this->getIdentifiers();
            $cart = Cart::getCart($userId, $sessionId);

            if ($cart) {
                Cart::clearCart($cart['cart_id']);
            }

            return ['success' => true, 'message' => 'Cart cleared'];
        }
    }