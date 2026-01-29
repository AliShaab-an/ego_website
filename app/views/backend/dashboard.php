<div class="">
    <!-- Stats Cards -->
    <div class="flex gap-4 mb-8 flex-wrap">
        <div class="flex-1 min-w-80 bg-gradient-to-br from-[rgba(183,146,103,1)] to-[rgba(160,120,80,1)] rounded-lg p-6 shadow-md text-white">
            <p class="text-3xl font-bold shipped-orders">0</p>
            <p class="text-sm mt-1 opacity-90 text-white">Shipped Orders</p>
        </div>
        <div class="flex-1 min-w-80 bg-gradient-to-br from-[rgba(158,126,89,1)] to-[rgba(136,102,61,1)] rounded-lg p-6 shadow-md text-white">
            <p class="text-3xl font-bold pending-orders">0</p>
            <p class="text-sm mt-1 opacity-90">Pending Orders</p>
        </div>
        <div class="flex-1 min-w-80 bg-gradient-to-br from-[rgba(136,102,61,1)] to-[rgba(110,75,45,1)] rounded-lg p-6 shadow-md text-white">
            <p class="text-3xl font-bold new-orders">0</p>
            <p class="text-sm mt-1 opacity-90">New Orders</p>
        </div>
    </div>

    <!-- Weekly Report -->
    <div class="bg-white rounded-lg shadow-md p-6 mb-8">
        <h2 class="text-xl font-bold text-gray-800 mb-6">Report for this week</h2>
        <div class="flex flex-row gap-8 overflow-x-auto pb-4">
            <div class="flex-shrink-0 w-40 border-b-4 border-[rgba(136,102,61,1)] pb-4 hover:border-[rgba(183,146,103,1)] cursor-pointer transition">
                <p class="text-2xl font-bold text-gray-800 customers-count">0</p>
                <p class="text-gray-500 text-sm mt-1">Customers</p>
            </div>
            <div class="flex-shrink-0 w-40 border-b-4 border-[rgba(136,102,61,1)] pb-4 hover:border-[rgba(183,146,103,1)] cursor-pointer transition">
                <p class="text-2xl font-bold text-gray-800 total-products-count">0</p>
                <p class="text-gray-500 text-sm mt-1">Total Products</p>
            </div>
            <div class="flex-shrink-0 w-40 border-b-4 border-[rgba(136,102,61,1)] pb-4 hover:border-[rgba(183,146,103,1)] cursor-pointer transition">
                <p class="text-2xl font-bold text-gray-800 stock-products-count">0</p>
                <p class="text-gray-500 text-sm mt-1">Stock Products</p>
            </div>
            <div class="flex-shrink-0 w-40 border-b-4 border-[rgba(136,102,61,1)] pb-4 hover:border-[rgba(183,146,103,1)] cursor-pointer transition">
                <p class="text-2xl font-bold text-gray-800 out-of-stock-count">0</p>
                <p class="text-gray-500 text-sm mt-1">Out of Stock</p>
            </div>
            <div class="flex-shrink-0 w-40 border-b-4 border-[rgba(136,102,61,1)] pb-4 hover:border-[rgba(183,146,103,1)] cursor-pointer transition">
                <p class="text-2xl font-bold text-gray-800 revenue-count">$0</p>
                <p class="text-gray-500 text-sm mt-1">Revenue</p>
            </div>
        </div>
        <!-- Chart -->
        <div class="h-64 w-full mt-6">
            <canvas id="weeklyReportChart" height="100"></canvas>
        </div>
    </div>
</div>
