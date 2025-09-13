

            <div class="flex gap-4">
                <div class="w-[333px] h-[130px] bg-[rgba(183,146,103,1)] p-4 shadow-[0_0_18px_-4px_rgba(175,137,255,0.15)]">
                    <p class="text-2xl font-bold text-white">Shipped Orders</p>
                </div>
                <div class="w-[333px] h-[130px] bg-[rgba(158,126,89,1)] p-4 shadow-[0_0_18px_-4px_rgba(175,137,255,0.15)]">
                    <p class="text-2xl font-bold text-white">Pending Orders</p>
                </div>
                <div class="w-[333px] h-[130px] bg-[rgba(136,102,61,1)] p-4 shadow-[0_0_18px_-4px_rgba(0,0,0,0.25)]">
                    <p class="text-2xl font-bold text-white">New Orders</p>
                </div>
            </div>
            <div class="w-full bg-white flex flex-col items-start pt-8 pl-4 shadow-[0_0_14.36px_-3.16px_rgba(0,0,0,0.25)] mt-8">
                <h2 class="text-lg font-bold mb-4">Report for this week</h2>
                <div class="flex flex-row  justify-between gap-4">
                    <div class="w-40 border-b-2  border-[rgba(0, 0, 0, 0.2)] p-4 hover:border-[rgba(136,102,61,1)] cursor-pointer">
                        <p class="text-2xl font-bold">52k</p>
                        <p class="text-gray-500 text-sm">Customers</p>
                        </div>
                        <div class="w-40 border-b-2 border-[rgba(0, 0, 0, 0.2)] p-4 hover:border-[rgba(136,102,61,1)] cursor-pointer">
                        <p class="text-2xl font-bold">3.5k</p>
                        <p class="text-gray-500 text-sm">Total Products</p>
                        </div>
                        <div class="w-40 border-b-2 border-[rgba(0, 0, 0, 0.2)] p-4 hover:border-[rgba(136,102,61,1)] cursor-pointer">
                        <p class="text-2xl font-bold">2.5k</p>
                        <p class="text-gray-500 text-sm">Stock Products</p>
                        </div>
                        <div class="w-40 border-b-2 border-[rgba(0, 0, 0, 0.2)] p-4 hover:border-[rgba(136,102,61,1)] cursor-pointer">
                        <p class="text-2xl font-bold">0.5k</p>
                        <p class="text-gray-500 text-sm">Out of Stock</p>
                        </div>
                        <div class="w-40 border-b-2 border-[rgba(0, 0, 0, 0.2)] p-4 hover:border-[rgba(136,102,61,1)] cursor-pointer">
                        <p class="text-2xl font-bold">250k</p>
                        <p class="text-gray-500 text-sm">Revenue</p>
                        </div>
                </div>
                    <!-- Chart -->
                <div class="h-64 w-full mt-8">
                    <canvas id="weeklyReportChart" height="100"></canvas>
                </div>
                    
                
            </div>
