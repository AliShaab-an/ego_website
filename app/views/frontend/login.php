
<div id="loginOverlay" class="fixed inset-0 z-50 hidden bg-black/50 flex items-center justify-center">
    <!-- Modal container -->
    <div class="bg-white w-full h-full md:h-auto md:w-3/4 lg:w-2/3 grid md:grid-cols-2">
    
        <!-- Left side: Form -->
        <div class="relative p-8 flex flex-col justify-center">
            <!-- Close button -->
            <button id="closeLogin" class="absolute top-4 left-4 text-2xl cursor-pointer">&times;</button>
      
            <h2 class="text-2xl md:text-3xl font-bold mb-2">Welcome Back</h2>
            <p class="text-gray-500 mb-6">Please enter your details</p>
      
            <form class="space-y-4">
                <div class="text-start space-y-2">
                    <label class="block text-sm font-medium">Email</label>
                    <input type="email" placeholder="Enter your email" 
                    class="w-full border border-gray-300 px-3 py-2 outline-none 
                        focus:border-brand focus:ring-1 focus:ring-brand">
                </div>
        
                <div class="text-start space-y-2">
                    <label class="block text-sm font-medium">Password</label>
                    <input type="password" placeholder="Enter your password" 
                            class="w-full border border-gray-300 px-3 py-2 outline-none 
                                    focus:border-brand focus:ring-1 focus:ring-brand">
                </div>
        
                <div class="flex justify-end">
                    <a href="#" class="text-sm font-medium">Forgot Password?</a>
                </div>
        
                <button type="submit" 
                class="w-full bg-brand text-white py-3 font-medium cursor-pointer">
                Log in
                </button>
        
                <p class="text-center text-sm">
                    Don’t have an account?
                    <button id="signInRedirect"  class="font-semibold text-brand cursor-pointer">Sign Up</button>
                </p>
            </form>
        </div>
    
        <!-- Right side: Image (hidden on mobile) -->
        <div class="hidden md:block">
            <img src="assets/images/login-image.png" alt="Login image" class="w-full h-full object-cover">
        </div>
    </div>
</div>