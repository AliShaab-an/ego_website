<?php 
    require_once __DIR__ . '/../../config/path.php';
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="<?= CSS_PATH ?>/style.css">
    <title>Ego Clothing - Admin Login</title>
</head>
<body class="min-h-screen flex items-center justify-center bg-gradient-to-br from-gray-100 via-gray-200 to-gray-300">

    <div class="w-full max-w-md bg-white rounded-2xl shadow-xl p-8">
        <div class="text-center mb-6">
            <h1 class="text-3xl font-bold text-gray-800">Ego Clothing</h1>
            <p class="text-gray-500 text-sm mt-1">Admin Panel</p>
        </div>

        <?php if (!empty($error)): ?>
            <div class="mb-4 p-3 rounded bg-red-100 text-red-700 text-sm">
                <?= htmlspecialchars($error) ?>
            </div>
        <?php endif; ?>

        <form method="POST" action="">
            <div class="mb-5">
                <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                <input type="email" id="email" name="email" required
                    class="w-full rounded border border-gray-300 outline-none focus:border-brand focus:ring-1 focus:ring-brand p-2">
            </div>

            <div class="mb-6">
                <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Password</label>
                <input type="password" id="password" name="password" required
                    class="w-full rounded border border-gray-300 focus:border-brand outline-none focus:ring-1 focus:ring-brand p-2">
            </div>

            <button type="submit"
                class="w-full bg-[rgba(183,146,103,1)] hover:bg-[rgba(160,120,80,1)]
                        text-white font-semibold py-2 rounded-lg shadow transition">
                Sign in
            </button>
        </form>
        <p class="mt-6 text-center text-sm text-gray-500">
            &copy; <?= date('Y') ?> Ego Clothing. All rights reserved.
        </p>
    </div>
</body>
</html>
