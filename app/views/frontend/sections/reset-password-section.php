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

      <h2 class="text-2xl sm:text-3xl font-bold mb-1 text-gray-900">Set New Password</h2>
      <p class="text-gray-500 text-sm mb-8">Choose a strong password for your account.</p>

      <!-- Messages -->
      <div id="resetSuccess" class="hidden mb-4 p-4 bg-green-50 border border-green-200 rounded-md">
        <p class="text-sm text-green-800">
          <i class="fi fi-rr-check-circle text-green-600 mr-2"></i>
          Password updated! Redirecting to the home page...
        </p>
      </div>
      <div id="resetError" class="hidden mb-4 p-4 bg-red-50 border border-red-200 rounded-md">
        <p class="text-sm text-red-800" id="resetErrorText"></p>
      </div>

      <?php if (empty($token)): ?>
        <!-- No token: show a helpful message -->
        <div class="p-4 bg-yellow-50 border border-yellow-200 rounded-md">
          <p class="text-sm text-yellow-800">
            This link is invalid or has expired. Please
            <a href="<?= page_url('forgotPassword') ?>" class="underline font-medium hover:text-[#b38b5e]">
              request a new reset link
            </a>.
          </p>
        </div>
      <?php else: ?>
        <!-- Reset Form -->
        <form id="resetPasswordForm" class="space-y-5">
          <input type="hidden" name="token" value="<?= htmlspecialchars($token) ?>">

          <div class="text-left">
            <label class="block text-sm font-medium text-gray-700 mb-1">New Password</label>
            <input type="password"
                   name="password"
                   id="resetNewPassword"
                   placeholder="Enter new password"
                   minlength="6"
                   required
                   class="w-full border border-gray-300 px-3 py-2 text-sm rounded-md outline-none
                          focus:border-[#b38b5e] focus:ring-1 focus:ring-[#b38b5e] transition">
          </div>

          <div class="text-left">
            <label class="block text-sm font-medium text-gray-700 mb-1">Confirm Password</label>
            <input type="password"
                   name="confirm_password"
                   id="resetConfirmPassword"
                   placeholder="Confirm new password"
                   minlength="6"
                   required
                   class="w-full border border-gray-300 px-3 py-2 text-sm rounded-md outline-none
                          focus:border-[#b38b5e] focus:ring-1 focus:ring-[#b38b5e] transition">
          </div>

          <button type="submit"
                  id="resetSubmitBtn"
                  class="w-full bg-[#b38b5e] text-white py-2.5 font-semibold rounded-md
                         hover:bg-[#a27c53] transition disabled:opacity-50 disabled:cursor-not-allowed">
            <span id="resetSubmitText">Update Password</span>
            <span id="resetSubmitLoader" class="hidden">
              <i class="fi fi-rr-spinner animate-spin"></i> Saving...
            </span>
          </button>
        </form>
      <?php endif; ?>

      <div class="mt-5 text-center">
        <a href="<?= page_url('forgotPassword') ?>"
           class="text-sm text-gray-500 hover:text-[#b38b5e] transition">
          Request a new reset link
        </a>
      </div>
    </div>

    <!-- Right: Decorative Panel -->
    <div class="hidden md:flex items-center justify-center bg-gradient-to-br from-[#b38b5e] to-[#8b6f47] p-8">
      <div class="text-center text-white">
        <i class="fi fi-rr-lock text-6xl mb-4 opacity-90"></i>
        <h3 class="text-2xl font-bold mb-2">Secure Reset</h3>
        <p class="text-sm opacity-90">Your new password is stored securely and encrypted.</p>
      </div>
    </div>

  </div>
</div>

<!-- Footer with Policies -->
<footer class="bg-white px-4 py-8 border-t border-gray-100">
  <div class="max-w-screen-xl mx-auto">
    <div class="flex flex-col md:flex-row justify-between items-center gap-4 text-sm text-gray-600">
      <div><p>&copy; <?= date('Y') ?> EGO Clothing. All rights reserved.</p></div>
      <div class="flex flex-wrap justify-center gap-4 md:gap-6">
        <a href="<?= page_url('contact') ?>" class="hover:text-[#b38b5e] transition">Contact Us</a>
      </div>
      <div><p class="text-[#b38b5e]">Designed &amp; Developed by <span class="font-semibold">G++</span></p></div>
    </div>
  </div>
</footer>

<script>
document.addEventListener('DOMContentLoaded', function () {
  const form = document.getElementById('resetPasswordForm');
  if (!form) return;

  const submitBtn  = document.getElementById('resetSubmitBtn');
  const submitText = document.getElementById('resetSubmitText');
  const submitLoader = document.getElementById('resetSubmitLoader');
  const successMsg = document.getElementById('resetSuccess');
  const errorMsg   = document.getElementById('resetError');
  const errorText  = document.getElementById('resetErrorText');

  form.addEventListener('submit', async function (e) {
    e.preventDefault();

    successMsg.classList.add('hidden');
    errorMsg.classList.add('hidden');

    const password = document.getElementById('resetNewPassword').value;
    const confirm  = document.getElementById('resetConfirmPassword').value;

    if (password !== confirm) {
      errorText.textContent = 'Passwords do not match.';
      errorMsg.classList.remove('hidden');
      return;
    }

    submitBtn.disabled = true;
    submitText.classList.add('hidden');
    submitLoader.classList.remove('hidden');

    try {
      const response = await fetch('<?= PUBLIC_URL ?>api/reset-password.php', {
        method: 'POST',
        body: new FormData(form)
      });
      const result = await response.json();

      if (result.status === 'success') {
        successMsg.classList.remove('hidden');
        form.reset();
        setTimeout(() => { window.location.href = '<?= page_url('home') ?>'; }, 2500);
      } else {
        errorText.textContent = result.message || 'Something went wrong. Please try again.';
        errorMsg.classList.remove('hidden');
      }
    } catch (err) {
      errorText.textContent = 'Network error. Please try again.';
      errorMsg.classList.remove('hidden');
    } finally {
      submitBtn.disabled = false;
      submitText.classList.remove('hidden');
      submitLoader.classList.add('hidden');
    }
  });
});
</script>
