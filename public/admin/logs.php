<?php
require_once __DIR__ . '/../../app/config/path.php';
require_once CORE . 'Session.php';

Session::configure(1800, url('admin/login.php'), true);
Session::startSession();

// Check if user is logged in and is admin
if (!isset($_SESSION['user_id']) || !isset($_SESSION['role']) || !in_array($_SESSION['role'], ['admin', 'super_admin'])) {
    header('Location: ' . url('admin/login.php'));
    exit;
}

$nav_logo = "../../assets/images/egologo1.png";
$pageTitle = "System Logs";

// Get log files
$logDir = __DIR__ . '/../../logs/';
$appLogDir = __DIR__ . '/../../app/logs/';

function getLogFiles($dir) {
    $files = [];
    if (is_dir($dir)) {
        $items = scandir($dir);
        foreach ($items as $item) {
            if ($item != '.' && $item != '..' && pathinfo($item, PATHINFO_EXTENSION) === 'log') {
                $files[] = [
                    'name' => $item,
                    'path' => $dir . $item,
                    'size' => filesize($dir . $item),
                    'modified' => filemtime($dir . $item)
                ];
            }
        }
    }
    return $files;
}

$rootLogs = getLogFiles($logDir);
$appLogs = getLogFiles($appLogDir);

// Get selected log content
$selectedLog = $_GET['log'] ?? null;
$selectedLocation = $_GET['location'] ?? 'root';
$logContent = '';
$lines = 100; // Show last 100 lines by default

if ($selectedLog) {
    $logPath = ($selectedLocation === 'app') ? $appLogDir . $selectedLog : $logDir . $selectedLog;
    if (file_exists($logPath)) {
        $allLines = file($logPath);
        $logContent = implode('', array_slice($allLines, -$lines));
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $pageTitle ?> - EGO Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://kit.fontawesome.com/7f6ab6587f.js" crossorigin="anonymous"></script>
    <style>
        .log-content {
            font-family: 'Courier New', monospace;
            font-size: 12px;
            white-space: pre-wrap;
            word-wrap: break-word;
        }
        .log-error { color: #ef4444; }
        .log-warning { color: #f59e0b; }
        .log-info { color: #3b82f6; }
        .log-success { color: #10b981; }
    </style>
</head>
<body class="bg-gray-100">
    <div class="flex h-screen overflow-hidden">
        <?php include BACKEND_VIEWS . 'sidebar.php'; ?>

        <div class="flex-1 overflow-auto">
            <!-- Header -->
            <div class="bg-white shadow-sm p-6 mb-6">
                <div class="flex justify-between items-center">
                    <div>
                        <h1 class="text-3xl font-bold text-gray-800"><?= $pageTitle ?></h1>
                        <p class="text-gray-600 mt-1">Monitor system logs and errors</p>
                    </div>
                    <button onclick="location.reload()" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">
                        <i class="fas fa-sync-alt mr-2"></i>Refresh
                    </button>
                </div>
            </div>

            <div class="px-6 pb-6">
                <div class="grid grid-cols-1 lg:grid-cols-4 gap-6">
                    <!-- Log Files List -->
                    <div class="lg:col-span-1">
                        <!-- Root Logs -->
                        <div class="bg-white rounded-lg shadow-sm p-4 mb-4">
                            <h3 class="font-semibold text-gray-700 mb-3 flex items-center">
                                <i class="fas fa-folder mr-2"></i>Root Logs
                            </h3>
                            <?php if (empty($rootLogs)): ?>
                                <p class="text-gray-500 text-sm">No log files</p>
                            <?php else: ?>
                                <?php foreach ($rootLogs as $log): ?>
                                    <a href="?log=<?= urlencode($log['name']) ?>&location=root" 
                                       class="block p-2 mb-2 rounded hover:bg-gray-100 <?= ($selectedLog === $log['name'] && $selectedLocation === 'root') ? 'bg-blue-50 border border-blue-200' : 'border border-gray-200' ?>">
                                        <div class="flex items-center justify-between">
                                            <span class="text-sm font-medium truncate"><?= $log['name'] ?></span>
                                        </div>
                                        <div class="text-xs text-gray-500 mt-1">
                                            <?= number_format($log['size'] / 1024, 2) ?> KB
                                        </div>
                                    </a>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </div>

                        <!-- App Logs -->
                        <div class="bg-white rounded-lg shadow-sm p-4">
                            <h3 class="font-semibold text-gray-700 mb-3 flex items-center">
                                <i class="fas fa-folder mr-2"></i>App Logs
                            </h3>
                            <?php if (empty($appLogs)): ?>
                                <p class="text-gray-500 text-sm">No log files</p>
                            <?php else: ?>
                                <?php foreach ($appLogs as $log): ?>
                                    <a href="?log=<?= urlencode($log['name']) ?>&location=app" 
                                       class="block p-2 mb-2 rounded hover:bg-gray-100 <?= ($selectedLog === $log['name'] && $selectedLocation === 'app') ? 'bg-blue-50 border border-blue-200' : 'border border-gray-200' ?>">
                                        <div class="flex items-center justify-between">
                                            <span class="text-sm font-medium truncate"><?= $log['name'] ?></span>
                                        </div>
                                        <div class="text-xs text-gray-500 mt-1">
                                            <?= number_format($log['size'] / 1024, 2) ?> KB
                                        </div>
                                    </a>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </div>
                    </div>

                    <!-- Log Content -->
                    <div class="lg:col-span-3">
                        <div class="bg-white rounded-lg shadow-sm">
                            <?php if ($selectedLog): ?>
                                <div class="p-4 border-b flex justify-between items-center">
                                    <div>
                                        <h3 class="font-semibold text-gray-700"><?= $selectedLog ?></h3>
                                        <p class="text-xs text-gray-500">Showing last <?= $lines ?> lines</p>
                                    </div>
                                    <div class="flex gap-2">
                                        <a href="api/download-log.php?log=<?= urlencode($selectedLog) ?>&location=<?= $selectedLocation ?>" 
                                           class="bg-green-500 text-white px-3 py-1 rounded text-sm hover:bg-green-600">
                                            <i class="fas fa-download mr-1"></i>Download
                                        </a>
                                        <button onclick="clearLog('<?= $selectedLog ?>', '<?= $selectedLocation ?>')" 
                                                class="bg-red-500 text-white px-3 py-1 rounded text-sm hover:bg-red-600">
                                            <i class="fas fa-trash mr-1"></i>Clear
                                        </button>
                                    </div>
                                </div>
                                <div class="p-4 bg-gray-900 text-gray-100 log-content max-h-[600px] overflow-auto">
                                    <?php if (empty($logContent)): ?>
                                        <p class="text-gray-400">Log file is empty</p>
                                    <?php else: ?>
                                        <?= htmlspecialchars($logContent) ?>
                                    <?php endif; ?>
                                </div>
                            <?php else: ?>
                                <div class="p-8 text-center text-gray-500">
                                    <i class="fas fa-file-alt text-6xl mb-4 text-gray-300"></i>
                                    <p class="text-lg">Select a log file to view its contents</p>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function clearLog(logName, location) {
            if (confirm('Are you sure you want to clear this log file? This action cannot be undone.')) {
                fetch('api/clear-log.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({ log: logName, location: location })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        location.reload();
                    } else {
                        alert('Error clearing log: ' + data.message);
                    }
                })
                .catch(error => {
                    alert('Error clearing log');
                });
            }
        }
    </script>
</body>
</html>
