<?php
/**
 * Customer Account Page
 * 
 * Variables available:
 * @var array $user - Current user data
 */
$user = $user ?? [];
?>

<section class="max-w-4xl mx-auto px-4 py-12">
    <h1 class="text-3xl font-bold mb-8">My Account</h1>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">

        <!-- Profile Information -->
        <div class="bg-white rounded-lg shadow p-6">
            <h2 class="text-xl font-semibold mb-4">Profile Information</h2>
            <form id="profileForm" class="space-y-4">
                <input type="hidden" name="csrf_token" value="<?= CSRF::getToken() ?>">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Name</label>
                    <input type="text" name="name" value="<?= htmlspecialchars($user['name'] ?? '') ?>" 
                           class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-black" required>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                    <input type="email" name="email" value="<?= htmlspecialchars($user['email'] ?? '') ?>" 
                           class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-black" required>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Phone</label>
                    <input type="tel" name="phone" value="<?= htmlspecialchars($user['phone'] ?? '') ?>" 
                           class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-black">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Address</label>
                    <input type="text" name="address" value="<?= htmlspecialchars($user['address'] ?? '') ?>" 
                           class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-black">
                </div>
                <div class="grid grid-cols-3 gap-3">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">City</label>
                        <input type="text" name="city" value="<?= htmlspecialchars($user['city'] ?? '') ?>" 
                               class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-black">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">State</label>
                        <input type="text" name="state" value="<?= htmlspecialchars($user['state'] ?? '') ?>" 
                               class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-black">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Zip</label>
                        <input type="text" name="zip" value="<?= htmlspecialchars($user['zip_code'] ?? '') ?>" 
                               class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-black">
                    </div>
                </div>
                <button type="submit" class="w-full bg-black text-white py-2 rounded-md hover:bg-gray-800 transition">
                    Update Profile
                </button>
                <div id="profileMessage" class="text-sm mt-2 hidden"></div>
            </form>
        </div>

        <!-- Change Password -->
        <div class="bg-white rounded-lg shadow p-6">
            <h2 class="text-xl font-semibold mb-4">Change Password</h2>
            <form id="passwordForm" class="space-y-4">
                <input type="hidden" name="csrf_token" value="<?= CSRF::getToken() ?>">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Current Password</label>
                    <input type="password" name="current_password" 
                           class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-black" required>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">New Password</label>
                    <input type="password" name="new_password" minlength="6"
                           class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-black" required>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Confirm New Password</label>
                    <input type="password" name="confirm_password" minlength="6"
                           class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-black" required>
                </div>
                <button type="submit" class="w-full bg-black text-white py-2 rounded-md hover:bg-gray-800 transition">
                    Change Password
                </button>
                <div id="passwordMessage" class="text-sm mt-2 hidden"></div>
            </form>
        </div>
    </div>

    <!-- Quick Links -->
    <div class="mt-8 flex gap-4">
        <a href="<?= page_url('order-history') ?>" class="inline-block bg-gray-100 text-gray-700 px-6 py-3 rounded-md hover:bg-gray-200 transition font-medium">
            <i class="fas fa-box mr-2"></i> View My Orders
        </a>
    </div>
</section>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Profile form submission
    document.getElementById('profileForm').addEventListener('submit', function(e) {
        e.preventDefault();
        const btn = this.querySelector('button[type="submit"]');
        const msg = document.getElementById('profileMessage');
        btn.disabled = true;
        btn.textContent = 'Updating...';

        fetch('<?= url("api/update-profile.php") ?>', {
            method: 'POST',
            body: new FormData(this)
        })
        .then(r => r.json())
        .then(data => {
            msg.classList.remove('hidden', 'text-green-600', 'text-red-600');
            msg.classList.add(data.status === 'success' ? 'text-green-600' : 'text-red-600');
            msg.textContent = data.message;
        })
        .catch(() => {
            msg.classList.remove('hidden', 'text-green-600');
            msg.classList.add('text-red-600');
            msg.textContent = 'An error occurred. Please try again.';
        })
        .finally(() => {
            btn.disabled = false;
            btn.textContent = 'Update Profile';
        });
    });

    // Password form submission
    document.getElementById('passwordForm').addEventListener('submit', function(e) {
        e.preventDefault();
        const btn = this.querySelector('button[type="submit"]');
        const msg = document.getElementById('passwordMessage');
        btn.disabled = true;
        btn.textContent = 'Changing...';

        fetch('<?= url("api/change-password.php") ?>', {
            method: 'POST',
            body: new FormData(this)
        })
        .then(r => r.json())
        .then(data => {
            msg.classList.remove('hidden', 'text-green-600', 'text-red-600');
            msg.classList.add(data.status === 'success' ? 'text-green-600' : 'text-red-600');
            msg.textContent = data.message;
            if (data.status === 'success') this.reset();
        })
        .catch(() => {
            msg.classList.remove('hidden', 'text-green-600');
            msg.classList.add('text-red-600');
            msg.textContent = 'An error occurred. Please try again.';
        })
        .finally(() => {
            btn.disabled = false;
            btn.textContent = 'Change Password';
        });
    });
});
</script>
