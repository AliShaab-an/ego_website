
<footer class="bg-white px-4 py-8 shadow-[0px_-2px_21.3px_0px_rgba(0,0,0,0.05)] mt-4">
  <div class="max-w-screen-xl mx-auto">
    <!-- Top Section -->
    <div class="flex flex-col md:flex-row md:justify-between md:items-center gap-8">
      
      <!-- Logo + Socials -->
      <div class="flex flex-col items-center  space-y-4 md:w-1/3">
        <img src="assets/images/egologo.png" alt="EGO" class="h-32 w-auto md:h-60">
        <div class="flex gap-4 text-brand">
          <a href="#"><i class="fa-brands fa-square-instagram text-2xl"></i></a>
          <a href="#"><i class="fa-brands fa-facebook-f text-2xl"></i></a>
          <a href="#"><i class="fa-brands fa-tiktok text-2xl"></i></a>
        </div>
      </div>

      <!-- Navigation + Terms -->
      <div class="flex flex-col sm:flex-row justify-between gap-8 md:w-1/3 text-center sm:text-left">
        <ul class="space-y-2">
          <li><a href="index.php" class="hover:text-brand">Home</a></li>
          <li><a href="shop.php" class="hover:text-brand">Shop</a></li>
          <li><a href="#" class="hover:text-brand">Categories</a></li>
          <li><a href="contact.php" class="hover:text-brand">Contact Us</a></li>
        </ul>
        <ul class="space-y-2">
          <li><a href="#" class="hover:text-brand">Privacy Policy</a></li>
          <li><a href="#" class="hover:text-brand">Terms &amp; Conditions</a></li>
        </ul>
      </div>

      <!-- Newsletter -->
      <div class="w-full md:w-1/3 space-y-4 text-start p-6 shadow-[0px_3px_35px_6px_rgba(0,0,0,0.09)] rounded-lg">
        <h4 class="text-2xl text-brand font-semibold">Newsletter</h4>
        <p class="text-sm text-brand">Be the first to know about our new collections and exclusive sales.</p>
        
        <!-- Success/Error Message Display -->
        <div id="newsletterMessage" class="hidden p-3 rounded-md text-sm"></div>
        
        <form id="newsletterForm" class="space-y-2">
          <input 
            type="text" 
            id="newsletterName"
            name="name"
            value="<?= $userInfo ? htmlspecialchars($userInfo['name']) : '' ?>"
            placeholder="Name" 
            class="w-full border border-brand px-3 py-2 text-sm outline-none placeholder:text-brand focus:ring-1 focus:ring-brand"
            required>
          <span id="newsletterNameError" class="text-red-500 text-xs hidden">Please enter your name</span>
          
          <input 
            type="email" 
            id="newsletterEmail"
            name="email"
            value="<?= $userInfo ? htmlspecialchars($userInfo['email']) : '' ?>"
            placeholder="Email" 
            class="w-full border border-brand px-3 py-2 text-sm outline-none placeholder:text-brand focus:ring-1 focus:ring-brand"
            required>
          <span id="newsletterEmailError" class="text-red-500 text-xs hidden">Please enter a valid email</span>
          
          <div class="w-full text-center">
            <button 
              type="submit" 
              id="newsletterSubmitBtn"
              class="px-4 py-2 bg-brand text-white text-sm rounded hover:bg-opacity-90 transition cursor-pointer disabled:opacity-50 disabled:cursor-not-allowed min-w-[80px]">
              <span id="newsletterSubmitText">Sign Up</span>
              <span id="newsletterSubmitLoader" class="hidden">...</span>
            </button>
          </div>
        </form>
      </div>
    </div>

    <!-- Copy -->
    <div class="mt-8 text-center text-xl text-brand">
      Designed &amp; Developed by <span class="font-semibold">G++</span>
    </div>
  </div>
</footer>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const newsletterForm = document.getElementById('newsletterForm');
    const nameInput = document.getElementById('newsletterName');
    const emailInput = document.getElementById('newsletterEmail');
    const submitBtn = document.getElementById('newsletterSubmitBtn');
    const submitText = document.getElementById('newsletterSubmitText');
    const submitLoader = document.getElementById('newsletterSubmitLoader');
    const messageDisplay = document.getElementById('newsletterMessage');

    if (!newsletterForm) return; // Exit if form doesn't exist

    // Validation functions
    function validateNewsletterName() {
        const name = nameInput.value.trim();
        const nameError = document.getElementById('newsletterNameError');
        
        if (name === '') {
            nameError.textContent = 'Please enter your name';
            nameError.classList.remove('hidden');
            nameInput.classList.add('border-red-500');
            return false;
        }
        
        nameError.classList.add('hidden');
        nameInput.classList.remove('border-red-500');
        return true;
    }

    function validateNewsletterEmail() {
        const email = emailInput.value.trim();
        const emailError = document.getElementById('newsletterEmailError');
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        
        if (email === '') {
            emailError.textContent = 'Please enter your email';
            emailError.classList.remove('hidden');
            emailInput.classList.add('border-red-500');
            return false;
        }
        
        if (!emailRegex.test(email)) {
            emailError.textContent = 'Please enter a valid email address';
            emailError.classList.remove('hidden');
            emailInput.classList.add('border-red-500');
            return false;
        }
        
        emailError.classList.add('hidden');
        emailInput.classList.remove('border-red-500');
        return true;
    }

    // Real-time validation
    nameInput.addEventListener('blur', validateNewsletterName);
    emailInput.addEventListener('blur', validateNewsletterEmail);

    // Clear errors on input
    nameInput.addEventListener('input', function() {
        if (nameInput.value.trim() !== '') {
            document.getElementById('newsletterNameError').classList.add('hidden');
            nameInput.classList.remove('border-red-500');
        }
    });

    emailInput.addEventListener('input', function() {
        if (emailInput.value.trim() !== '') {
            document.getElementById('newsletterEmailError').classList.add('hidden');
            emailInput.classList.remove('border-red-500');
        }
    });

    // Form submission
    newsletterForm.addEventListener('submit', function(e) {
        e.preventDefault();
        
        // Validate all fields
        const isNameValid = validateNewsletterName();
        const isEmailValid = validateNewsletterEmail();
        
        if (!isNameValid || !isEmailValid) {
            return;
        }

        // Show loading state
        submitBtn.disabled = true;
        submitText.classList.add('hidden');
        submitLoader.classList.remove('hidden');

        // Prepare form data
        const formData = new FormData();
        formData.append('name', nameInput.value.trim());
        formData.append('email', emailInput.value.trim());

        // Submit form
        fetch('/Ego_website/public/api/subscribe-newsletter.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Show success message
                messageDisplay.className = 'p-3 rounded-md text-sm bg-green-100 border border-green-400 text-green-700';
                messageDisplay.textContent = data.message || 'Thank you for subscribing to our newsletter!';
                messageDisplay.classList.remove('hidden');
                
                // Reset form if not logged in (keep user info if logged in)
                if (!<?= $userInfo ? 'true' : 'false' ?>) {
                    newsletterForm.reset();
                }
            } else {
                // Show error message
                messageDisplay.className = 'p-3 rounded-md text-sm bg-red-100 border border-red-400 text-red-700';
                messageDisplay.textContent = data.message || 'An error occurred. Please try again.';
                messageDisplay.classList.remove('hidden');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            messageDisplay.className = 'p-3 rounded-md text-sm bg-red-100 border border-red-400 text-red-700';
            messageDisplay.textContent = 'An error occurred. Please try again.';
            messageDisplay.classList.remove('hidden');
        })
        .finally(() => {
            // Reset loading state
            submitBtn.disabled = false;
            submitText.classList.remove('hidden');
            submitLoader.classList.add('hidden');
            
            // Hide message after 5 seconds
            setTimeout(() => {
                messageDisplay.classList.add('hidden');
            }, 5000);
        });
    });
});
</script>













