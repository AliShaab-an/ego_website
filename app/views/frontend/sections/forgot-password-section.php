<div class="min-h-screen bg-gradient-to-br from-gray-50 via-white to-gray-100 flex items-center justify-center p-4">
  <div class="bg-white w-full max-w-4xl rounded-xl overflow-hidden grid md:grid-cols-2 shadow-2xl">
    
    <!-- Left: Form -->
    <div class="relative flex flex-col justify-center px-8 py-10 sm:px-12">
      <!-- Back Button -->
      <a href="<?= page_url('home') ?>" 
         class="absolute top-5 left-5 text-2xl text-gray-500 hover:text-gray-800 transition"
         aria-label="Back to Home">
        <i class="fi fi-rr-angle-small-left"></i>
      </a>

      <!-- Text -->
      <h2 class="text-2xl sm:text-3xl font-bold mb-1 text-gray-900">Forgot Password?</h2>
      <p class="text-gray-500 text-sm mb-8">No worries, we'll send you reset instructions.</p>

      <!-- Success Message -->
      <div id="forgotPasswordSuccess" class="hidden mb-4 p-4 bg-green-50 border border-green-200 rounded-md">
        <p class="text-sm text-green-800">
          <i class="fi fi-rr-check-circle text-green-600 mr-2"></i>
          Reset link sent! Please check your email.
        </p>
      </div>

      <!-- Error Message -->
      <div id="forgotPasswordError" class="hidden mb-4 p-4 bg-red-50 border border-red-200 rounded-md">
        <p class="text-sm text-red-800" id="forgotPasswordErrorText"></p>
      </div>

      <!-- Form -->
      <form id="forgotPasswordForm" class="space-y-5">
        <div class="text-left">
          <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
          <input type="email" 
                 name="email" 
                 id="forgotPasswordEmail"
                 placeholder="Enter your email address"
                 required
                 class="w-full border border-gray-300 px-3 py-2 text-sm rounded-md outline-none
                        focus:border-[#b38b5e] focus:ring-1 focus:ring-[#b38b5e] transition">
        </div>

        <button type="submit"
                id="forgotPasswordSubmit"
                class="w-full bg-[#b38b5e] text-white py-2.5 font-semibold rounded-md
                       hover:bg-[#a27c53] transition disabled:opacity-50 disabled:cursor-not-allowed">
          <span id="forgotPasswordSubmitText">Send Reset Link</span>
          <span id="forgotPasswordSubmitLoader" class="hidden">
            <i class="fi fi-rr-spinner animate-spin"></i> Sending...
          </span>
        </button>

        <div class="text-center">
          <a href="<?= page_url('home') ?>" 
             class="inline-flex items-center gap-2 text-sm text-gray-600 hover:text-[#b38b5e] transition">
            <i class="fi fi-rr-arrow-small-left"></i>
            Back to Login
          </a>
        </div>
      </form>
    </div>

    <!-- Right: Image -->
    <div class="hidden md:block relative bg-gradient-to-br from-[#b38b5e] to-[#8b6f47]">
      <div class="absolute inset-0 flex items-center justify-center p-8">
        <div class="text-center text-white">
          <i class="fi fi-rr-lock text-6xl mb-4 opacity-90"></i>
          <h3 class="text-2xl font-bold mb-2">Password Recovery</h3>
          <p class="text-sm opacity-90">We'll help you get back into your account securely.</p>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Footer with Policies -->
<footer class="bg-white px-4 py-8 border-t border-gray-100">
  <div class="max-w-screen-xl mx-auto">
    <div class="flex flex-col md:flex-row justify-between items-center gap-4 text-sm text-gray-600">
      <!-- Copyright -->
      <div class="text-center md:text-left">
        <p>&copy; <?= date('Y') ?> EGO Clothing. All rights reserved.</p>
      </div>

      <!-- Policy Links -->
      <div class="flex flex-wrap justify-center gap-4 md:gap-6">
        <?php 
        $privacyPolicy = getSetting('privacy_policy_url', '#');
        $termsOfService = getSetting('terms_of_service_url', '#');
        ?>
        
        <?php if ($privacyPolicy && $privacyPolicy !== '#'): ?>
          <a href="<?= htmlspecialchars($privacyPolicy) ?>" 
             target="_blank"
             class="hover:text-[#b38b5e] transition">
            Privacy Policy
          </a>
        <?php endif; ?>

        <?php if ($termsOfService && $termsOfService !== '#'): ?>
          <a href="<?= htmlspecialchars($termsOfService) ?>" 
             target="_blank"
             class="hover:text-[#b38b5e] transition">
            Terms of Service
          </a>
        <?php endif; ?>
        
        <a href="<?= page_url('contact') ?>" 
           class="hover:text-[#b38b5e] transition">
          Contact Us
        </a>
      </div>

      <!-- Designed by -->
      <div class="text-center md:text-right">
        <p class="text-[#b38b5e]">Designed &amp; Developed by <span class="font-semibold">G++</span></p>
      </div>
    </div>
  </div>
</footer>

<script>
document.addEventListener('DOMContentLoaded', function() {
  const form = document.getElementById('forgotPasswordForm');
  const submitBtn = document.getElementById('forgotPasswordSubmit');
  const submitText = document.getElementById('forgotPasswordSubmitText');
  const submitLoader = document.getElementById('forgotPasswordSubmitLoader');
  const successMsg = document.getElementById('forgotPasswordSuccess');
  const errorMsg = document.getElementById('forgotPasswordError');
  const errorText = document.getElementById('forgotPasswordErrorText');

  form.addEventListener('submit', async function(e) {
    e.preventDefault();

    // Hide messages
    successMsg.classList.add('hidden');
    errorMsg.classList.add('hidden');

    // Show loader
    submitBtn.disabled = true;
    submitText.classList.add('hidden');
    submitLoader.classList.remove('hidden');

    const formData = new FormData(form);
    
    try {
      const response = await fetch('<?= PUBLIC_URL ?>api/forgot-password.php', {
        method: 'POST',
        body: formData
      });

      const result = await response.json();

      if (result.status === 'success') {
        successMsg.classList.remove('hidden');
        form.reset();
        
        // Redirect to home after 3 seconds
        setTimeout(() => {
          window.location.href = '<?= page_url('home') ?>';
        }, 3000);
      } else {
        errorText.textContent = result.message || 'Something went wrong. Please try again.';
        errorMsg.classList.remove('hidden');
      }
    } catch (error) {
      errorText.textContent = 'Network error. Please try again.';
      errorMsg.classList.remove('hidden');
    } finally {
      // Hide loader
      submitBtn.disabled = false;
      submitText.classList.remove('hidden');
      submitLoader.classList.add('hidden');
    }
  });
});
</script>
