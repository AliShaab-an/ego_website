<div id="signupOverlay" class="fixed inset-0 z-50 hidden bg-black/50 flex items-center justify-center">
    <!-- Modal container -->
    <div class="bg-white w-full h-full md:h-auto md:w-3/4 lg:w-2/3 grid md:grid-cols-2">
    
        <!-- Left side: Form -->
        <div class="relative p-8 flex flex-col justify-center">
            <!-- Close button -->
            <button id="closeSignup" class="absolute top-4 left-4 text-2xl cursor-pointer">&times;</button>
      
            <h2 class="text-2xl md:text-3xl font-bold mb-2">Welcome!</h2>
            <p class="text-gray-500 mb-6">Please enter your details</p>
      
            <form id="customerRegister" class="space-y-4">
                <div id="registerMessage" class="hidden text-sm p-2 rounded"></div>
                <div class="text-start space-y-2">
                    <label class="block text-sm font-medium">Name</label>
                    <input type="text" name="name" placeholder="Enter your name" 
                    class="w-full border border-gray-300 px-3 py-2 outline-none 
                        focus:border-brand focus:ring-1 focus:ring-brand">
                </div>
                <div class="text-start space-y-2">
                    <label class="block text-sm font-medium">Email</label>
                    <input type="email" name="email" placeholder="Enter your email" 
                    class="w-full border border-gray-300 px-3 py-2 outline-none 
                        focus:border-brand focus:ring-1 focus:ring-brand">
                </div>
        
                <div class="text-start space-y-2">
                    <label class="block text-sm font-medium">Password</label>
                    <input type="password" name="password" placeholder="Enter your password" 
                    class="w-full border border-gray-300 px-3 py-2 outline-none 
                                    focus:border-brand focus:ring-1 focus:ring-brand">
                </div>
                <button type="submit" 
                class="w-full bg-brand text-white py-3 font-medium cursor-pointer">
                Sign in
                </button>
        
                <p class="text-center text-sm">
                    Don’t have an account?
                    <button id="loginRedirect" class="font-semibold text-brand cursor-pointer">Log In</button>
                </p>
            </form>
        </div>
    
        <!-- Right side: Image (hidden on mobile) -->
        <div class="hidden md:block">
            <img src="assets/images/signup-image.png" alt="Login image" class="w-full h-full object-cover">
        </div>
    </div>
</div>