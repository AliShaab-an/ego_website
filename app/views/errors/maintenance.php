<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Under Maintenance</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@400;600;700&family=Outfit:wght@300;400;500&display=swap');
        body { font-family: 'Outfit', sans-serif; }
        .font-cor { font-family: 'Cormorant Garamond', serif; }
    </style>
</head>
<body class="min-h-screen bg-white flex items-center justify-center px-4">
    <div class="text-center max-w-lg">
        <!-- Icon -->
        <div class="mb-8">
            <i class="fa-solid fa-screwdriver-wrench text-6xl text-[rgba(183,146,103,1)]"></i>
        </div>

        <!-- Heading -->
        <h1 class="font-cor text-5xl md:text-6xl font-bold text-gray-900 mb-4">
            Under Maintenance
        </h1>

        <div class="w-16 h-0.5 bg-[rgba(183,146,103,1)] mx-auto mb-6"></div>

        <!-- Message from settings -->
        <p class="text-gray-600 text-lg leading-relaxed mb-8">
            <?= nl2br(htmlspecialchars($maintenanceMessage ?? "We're currently performing scheduled maintenance.\nWe'll be back online shortly — thank you for your patience.")) ?>
        </p>

        <!-- Decorative footer -->
        <p class="text-sm text-gray-400 tracking-widest uppercase font-cor">EGO Clothing</p>
    </div>
</body>
</html>
