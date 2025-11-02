<!-- Loader Overlay -->
<div id="loaderOverlay"
     class="fixed inset-0 z-[60] hidden bg-black/50 flex items-center justify-center">
    <div class="h-14 w-14 border-4 border-t-transparent border-white rounded-full animate-spin"></div>
</div>

<!-- Sign Up Modal -->
<div id="signupOverlay"
     class="fixed inset-0 z-50 hidden bg-black/40 backdrop-blur-sm flex items-center justify-center p-4">
  <div class="bg-white w-full max-w-4xl rounded-xl overflow-hidden grid md:grid-cols-2 shadow-2xl">
    
    <!-- Left: Form -->
    <div class="relative flex flex-col justify-center px-8 py-10 sm:px-12">
      <!-- Close Button -->
      <button id="closeSignup"
              class="absolute top-5 left-5 text-2xl text-gray-500 hover:text-gray-800 transition">
        &times;
      </button>

      <!-- Text -->
      <h2 class="text-2xl sm:text-3xl font-bold mb-1 text-gray-900">Welcome!</h2>
      <p class="text-gray-500 text-sm mb-8">Please enter your details</p>

      <!-- Form -->
      <form id="customerRegister" class="space-y-5">
        <div id="registerMessage"
             class="hidden text-sm p-2 rounded bg-red-50 text-red-600 border border-red-200"></div>

        <div class="text-left">
          <label class="block text-sm font-medium text-gray-700 mb-1">Name</label>
          <input type="text" name="name" placeholder="Enter your name"
                 class="w-full border border-gray-300 px-3 py-2 text-sm rounded-md outline-none
                        focus:border-[#b38b5e] focus:ring-1 focus:ring-[#b38b5e] transition">
        </div>

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

        <button type="submit"
                class="w-full bg-[#b38b5e] text-white py-2.5 font-semibold rounded-md
                       hover:bg-[#a27c53] transition">
          Sign Up
        </button>

        <p class="text-center text-xs text-gray-600">
          Already have an account?
          <button id="loginRedirect"
                  class="text-[#b38b5e] font-semibold hover:underline cursor-pointer">
            Log In
          </button>
        </p>
      </form>
    </div>

    <!-- Right: Image -->
    <div class="hidden md:block relative">
      <img src="assets/images/signup-image.png"
           alt="Sign Up Visual"
           class="w-full h-full object-cover">
      <div class="absolute inset-0 bg-gradient-to-tr from-black/30 to-transparent"></div>
    </div>
  </div>
</div>