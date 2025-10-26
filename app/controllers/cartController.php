<?php 
    require_once __DIR__ . '/../config/path.php';
    require_once MODELS . 'Product.php';
    require_once MODELS . 'Cart.php';

    class CartController{

        public function addToCart() {
            try{
                $productId = $_POST['productId'] ?? null;
                $size = $_POST['size'] ?? null;
                $color = $_POST['color'] ?? null;
                $quantity = (int)($_POST['quantity'] ?? 1);

                if (!$productId) {
                    return ['success' => false, 'message' => 'Missing product ID'];
                }
                
                // If user is logged in, save to database
                if(isset($_SESSION['user_id'])){
                    $userId = $_SESSION['user_id'];
                    $success = Cart::addItemForUser($userId, $productId, $size, $color, $quantity);
                    $cartCount = Cart::getCartCount($userId);
                    if($success){
                        return [
                            'success' => true,
                            'cart_count' => $cartCount,
                            'message' => 'Added to cart successfully!'
                        ];
                    }else{
                        return ['success' => false, 'message' => 'Failed to add item. Variant not found.'];
                    }
                }

                // For guest users, save to session
                if(!isset($_SESSION['cart'])){
                    $_SESSION['cart'] = [];
                }

                $key = "{$productId}_{$size}_{$color}";
                if(isset($_SESSION['cart'][$key])){
                    // Replace quantity instead of adding to it
                    $_SESSION['cart'][$key]['quantity'] = $quantity;
                }else{
                    $_SESSION['cart'][$key] = [
                        'product_id' => $productId,
                        'size' => $size,
                        'color' => $color,
                        'quantity' => $quantity
                    ];
                }

                $cartCount = array_sum(array_column($_SESSION['cart'], 'quantity'));

                return [
                    'success' => true,
                    'cart_count' => $cartCount,
                    'message' => 'Added to session cart successfully!'
                ];

            }catch (Exception $e) {
                return ['success' => false, 'message' => 'Server error: ' . $e->getMessage()];
            }
        }

        public function getCartItems() {
            try {
                // If user is logged in, get from database
                if(isset($_SESSION['user_id'])){
                    $userId = $_SESSION['user_id'];
                    $cartId = Cart::getOrCreateCart($userId);
                    $items = Cart::getCartItemsWithDetails($cartId);
                    $total = Cart::getCartTotal($cartId);
                    
                    // Calculate count from actual displayable items instead of all database items
                    $count = 0;
                    foreach($items as $item) {
                        $count += $item['quantity'];
                    }
                    
                    return [
                        'success' => true,
                        'items' => $items,
                        'total' => $total,
                        'count' => $count
                    ];
                }

                // For guest users, get from session
                if(!isset($_SESSION['cart']) || empty($_SESSION['cart'])){
                    return [
                        'success' => true,
                        'items' => [],
                        'total' => 0,
                        'count' => 0
                    ];
                }

                $items = [];
                $total = 0;
                $count = 0;

                foreach($_SESSION['cart'] as $item) {
                    // Debug: Log the item being processed
                    error_log("Cart Debug - Processing item: " . json_encode($item));
                    
                    $productDetails = Product::getProductWithVariant(
                        $item['product_id'], 
                        $item['size'], 
                        $item['color']
                    );
                    
                    // Debug logging
                    error_log("Cart Debug - Product Details for ID " . $item['product_id'] . ": " . json_encode($productDetails));
                    
                    if($productDetails) {
                        $itemTotal = $productDetails['price'] * $item['quantity'];
                        $total += $itemTotal;
                        $count += $item['quantity'];
                        
                        $items[] = [
                            'product_id' => $item['product_id'],
                            'name' => $productDetails['name'],
                            'size' => $item['size'],
                            'color' => $item['color'],
                            'quantity' => $item['quantity'],
                            'price' => $productDetails['price'],
                            'total' => $itemTotal,
                            'image' => $productDetails['image'] ?? null
                        ];
                        
                        error_log("Cart Debug - Successfully added item to cart items array");
                    } else {
                        error_log("Cart Debug - Product NOT FOUND for ID: " . $item['product_id'] . ", Size: " . $item['size'] . ", Color: " . $item['color']);
                        
                        // Try to get product without variant matching as fallback
                        $basicProduct = Product::findById($item['product_id']);
                        if ($basicProduct) {
                            error_log("Cart Debug - Basic product found: " . json_encode($basicProduct));
                            
                            // Add item with basic product info
                            $itemTotal = $basicProduct['base_price'] * $item['quantity'];
                            $total += $itemTotal;
                            $count += $item['quantity'];
                            
                            $items[] = [
                                'product_id' => $item['product_id'],
                                'name' => $basicProduct['name'],
                                'size' => $item['size'],
                                'color' => $item['color'],
                                'quantity' => $item['quantity'],
                                'price' => $basicProduct['base_price'],
                                'total' => $itemTotal,
                                'image' => null // We'll add this later
                            ];
                        } else {
                            error_log("Cart Debug - Basic product also NOT FOUND for ID: " . $item['product_id']);
                        }
                    }
                }

                return [
                    'success' => true,
                    'items' => $items,
                    'total' => $total,
                    'count' => $count
                ];

            } catch (Exception $e) {
                return ['success' => false, 'message' => 'Server error: ' . $e->getMessage()];
            }
        }

        public function updateCartItem() {
            try {
                $productId = $_POST['productId'] ?? null;
                $size = $_POST['size'] ?? null;
                $color = $_POST['color'] ?? null;
                $quantity = (int)($_POST['quantity'] ?? 1);

                if (!$productId) {
                    return ['success' => false, 'message' => 'Missing product ID'];
                }

                // If user is logged in, update in database
                if(isset($_SESSION['user_id'])){
                    $userId = $_SESSION['user_id'];
                    $success = Cart::updateItemQuantityForUser($userId, $productId, $size, $color, $quantity);
                    
                    if($success) {
                        $cartCount = Cart::getCartCount($userId);
                        return [
                            'success' => true,
                            'cart_count' => $cartCount,
                            'message' => 'Cart updated successfully!'
                        ];
                    } else {
                        return ['success' => false, 'message' => 'Failed to update cart item'];
                    }
                }

                // For guest users, update session
                if(!isset($_SESSION['cart'])){
                    return ['success' => false, 'message' => 'Cart is empty'];
                }

                $key = "{$productId}_{$size}_{$color}";
                if(isset($_SESSION['cart'][$key])){
                    if($quantity <= 0) {
                        unset($_SESSION['cart'][$key]);
                    } else {
                        $_SESSION['cart'][$key]['quantity'] = $quantity;
                    }
                    
                    $cartCount = array_sum(array_column($_SESSION['cart'], 'quantity'));
                    return [
                        'success' => true,
                        'cart_count' => $cartCount,
                        'message' => 'Cart updated successfully!'
                    ];
                } else {
                    return ['success' => false, 'message' => 'Item not found in cart'];
                }

            } catch (Exception $e) {
                return ['success' => false, 'message' => 'Server error: ' . $e->getMessage()];
            }
        }

        public function removeFromCart() {
            try {
                $productId = $_POST['productId'] ?? null;
                $size = $_POST['size'] ?? null;
                $color = $_POST['color'] ?? null;

                if (!$productId) {
                    return ['success' => false, 'message' => 'Missing product ID'];
                }

                // If user is logged in, remove from database
                if(isset($_SESSION['user_id'])){
                    $userId = $_SESSION['user_id'];
                    $success = Cart::removeItemForUser($userId, $productId, $size, $color);
                    
                    if($success) {
                        $cartCount = Cart::getCartCount($userId);
                        return [
                            'success' => true,
                            'cart_count' => $cartCount,
                            'message' => 'Item removed from cart!'
                        ];
                    } else {
                        return ['success' => false, 'message' => 'Failed to remove item'];
                    }
                }

                // For guest users, remove from session
                if(!isset($_SESSION['cart'])){
                    return ['success' => false, 'message' => 'Cart is empty'];
                }

                $key = "{$productId}_{$size}_{$color}";
                if(isset($_SESSION['cart'][$key])){
                    unset($_SESSION['cart'][$key]);
                    $cartCount = array_sum(array_column($_SESSION['cart'], 'quantity'));
                    return [
                        'success' => true,
                        'cart_count' => $cartCount,
                        'message' => 'Item removed from cart!'
                    ];
                } else {
                    return ['success' => false, 'message' => 'Item not found in cart'];
                }

            } catch (Exception $e) {
                return ['success' => false, 'message' => 'Server error: ' . $e->getMessage()];
            }
        }

        public function clearCart() {
            try {
                // If user is logged in, clear database cart
                if(isset($_SESSION['user_id'])){
                    $userId = $_SESSION['user_id'];
                    $cartId = Cart::getOrCreateCart($userId);
                    Cart::clearCart($cartId);
                    
                    return [
                        'success' => true,
                        'cart_count' => 0,
                        'message' => 'Cart cleared successfully!'
                    ];
                }

                // For guest users, clear session cart
                unset($_SESSION['cart']);
                return [
                    'success' => true,
                    'cart_count' => 0,
                    'message' => 'Cart cleared successfully!'
                ];

            } catch (Exception $e) {
                return ['success' => false, 'message' => 'Server error: ' . $e->getMessage()];
            }
        }

        public function getCartCount() {
            try {
                // If user is logged in, get count from displayable items
                if(isset($_SESSION['user_id'])){
                    $userId = $_SESSION['user_id'];
                    $cartId = Cart::getOrCreateCart($userId);
                    $items = Cart::getCartItemsWithDetails($cartId);
                    
                    $count = 0;
                    foreach($items as $item) {
                        $count += $item['quantity'];
                    }
                    
                    return ['success' => true, 'count' => $count];
                }

                // For guest users, get count from displayable session items
                if(!isset($_SESSION['cart']) || empty($_SESSION['cart'])){
                    return ['success' => true, 'count' => 0];
                }

                $count = 0;
                foreach($_SESSION['cart'] as $item) {
                    $productDetails = Product::getProductWithVariant(
                        $item['product_id'], 
                        $item['size'], 
                        $item['color']
                    );
                    
                    // Only count items that can actually be displayed
                    if($productDetails) {
                        $count += $item['quantity'];
                    }
                }
                
                return ['success' => true, 'count' => $count];

            } catch (Exception $e) {
                return ['success' => false, 'message' => 'Server error: ' . $e->getMessage()];
            }
        }

        public function migrateSessionCartToUser($userId) {
            if (!isset($_SESSION['cart']) || empty($_SESSION['cart'])) {
                return true;
            }

            try {
                foreach ($_SESSION['cart'] as $item) {
                    Cart::addItemForUser(
                        $userId,
                        $item['product_id'],
                        $item['size'],
                        $item['color'],
                        $item['quantity']
                    );
                }

                // Clear session cart after migration
                unset($_SESSION['cart']);
                return true;

            } catch (Exception $e) {
                error_log("Cart migration error: " . $e->getMessage());
                return false;
            }
        }
    }