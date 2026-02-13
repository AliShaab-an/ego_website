
<!-- Login Overlay -->
<div id="loginOverlay"
    class="fixed inset-0 z-50 hidden bg-black/40 backdrop-blur-sm flex items-center justify-center p-4">
  <div class="bg-white w-full max-w-4xl rounded-xl overflow-hidden grid md:grid-cols-2 shadow-2xl">
    
    <!-- Left: Form -->
    <div class="relative flex flex-col justify-center px-8 py-10 sm:px-12">
      <!-- Close Button -->
      <button id="closeLogin"
              class="absolute top-5 left-5 text-2xl text-gray-500 hover:text-gray-800 transition">
        &times;
      </button>

      <!-- Text -->
      <h2 class="text-2xl sm:text-3xl font-bold mb-1 text-gray-900">Welcome Back</h2>
      <p class="text-gray-500 text-sm mb-8">Please enter your details</p>

      <!-- Form -->
      <form id="customerLogin" class="space-y-5">
        <div class="text-left">
          <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
          <input type="email" name="email" placeholder="Enter your email"
                 class="w-full border border-gray-300 px-3 py-2 text-sm rounded-md outline-none
                        focus:border-[#b38b5e] focus:ring-1 focus:ring-[#b38b5e] transition">
        </div>

        <div class="text-left">
          <label class="block text-sm font-medium text-gray-700 mb-1">Password</label>
          <input type="password" name="password" placeholder="Enter your password"
                 class="w-full border border-gray-300 px-3 py-2 text-sm rounded-md outline-none
                        focus:border-[#b38b5e] focus:ring-1 focus:ring-[#b38b5e] transition">
        </div>

        <div class="flex justify-end">
          <a href="<?= page_url('forgotPassword') ?>" class="text-xs font-medium text-gray-600 hover:text-[#b38b5e]">Forgot Password?</a>
        </div>

        <button type="submit"
                class="w-full bg-[#b38b5e] text-white py-2.5 font-semibold rounded-md
                       hover:bg-[#a27c53] transition">
          Log in
        </button>

        <p class="text-center text-xs text-gray-600">
          Don’t have an account?
          <button id="signInRedirect"
                  class="text-[#b38b5e] font-semibold hover:underline cursor-pointer">
            Sign Up
          </button>
        </p>
      </form>
    </div>

    <!-- Right: Image -->
    <div class="hidden md:block">
      <img src="<?= IMG_PATH ?>login-image.png"
           alt="Login Visual"
           class="w-full h-full object-cover">
    </div>
  </div>
</div>
