
            <div class="flex gap-4 ">
                <div class="w-[256px] h-[130px] bg-white p-4 shadow-[0_0_18px_-4px_rgba(0,0,0,0.25)]">
                    <p class="text-l font-bold text-black">Total Orders</p>
                    <p class="text-3xl font-bold text-black my-2">1,240</p>
                    <p class="text-sm font-thin text-black">Last 7 days</p>
                </div>
                <div class="w-[256px] h-[130px] bg-white p-4 shadow-[0_0_18px_-4px_rgba(0,0,0,0.25)]">
                    <p class="text-l font-bold text-black">New Orders</p>
                    <p class="text-3xl font-bold text-black my-2">240</p>
                    <p class="text-sm font-thin text-black">Last 7 days</p>
                </div>
                <div class="w-[256px] h-[130px] bg-white p-4 shadow-[0_0_18px_-4px_rgba(0,0,0,0.25)]">
                    <p class="text-l font-bold text-black">Completed Orders</p>
                    <p class="text-3xl font-bold text-black my-2">960</p>
                    <p class="text-sm font-thin text-black">Last 7 days</p>
                </div>
                <div class="w-[256px] h-[130px] bg-white p-4 shadow-[0_0_18px_-4px_rgba(0,0,0,0.25)]">
                    <p class="text-l font-bold text-black">Canceled Orders</p>
                    <p class="text-3xl font-bold text-black my-2">87</p>
                    <p class="text-sm font-thin text-black">Last 7 days</p>
                </div>
            </div>
<div class="w-full bg-white flex flex-col items-start p-8 shadow-[0_0_18.2px_-4px_rgba(0,0,0,0.25)] mt-8">
    
    <div class="w-1/2 flex bg-[rgba(240,215,186,0.2)] justify-between pt-2 pb-2 pr-4 pl-4 rounded mb-10">
                    <a href="#" class="font-bold">All orders</a>
                    <a href="#" class="font-bold">Completed</a>
                    <a href="#" class="font-bold">Pending</a>
                    <a href="#" class="font-bold">Canceled</a>
                </div>
                
                    <table class="table-auto w-full  md:table-fixed ">
                    <thead class="bg-[rgba(240,215,186,0.2)] ">
                        <tr>
                            <th class="pt-4 pb-4">No.</th>
                            <th>Order Id</th>
                            <th>Product</th>
                            <th>Date</th>
                            <th>Price</th>
                            <th>Payment</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr class="text-center border-b border-gray-300 ">
                            <td class="pt-4 pb-4">1</td>
                            <td class="pt-4 pb-4">ORD123</td>
                            <td class="pt-4 pb-4">Product 1</td>
                            <td class="pt-4 pb-4">2023-10-01</td>
                            <td class="pt-4 pb-4">$100</td>
                            <td class="text-center flex items-center justify-center gap-2 pt-4 pb-4">
                                <span class="w-3 h-3 rounded-full bg-green-500"></span>
                                Paid
                            </td>
                            <td class="py-4">Shipped</td>
                        </tr>
                    </tbody>
                </table>
                <div class="flex justify-between w-full mt-4 px-4">
                    
                        <a href="#" class="px-3 py-1 bg-white rounded shadow">Previous</a>

                        <a href="#" class="px-3 py-1 bg-white rounded shadow">Next</a>
                    
                </div>
                
</div>




    <!-- <div class="p-6">
    <h1 class="text-2xl font-bold mb-4">Order Management</h1>

    <table class="table-auto w-full border-collapse">
        <thead class="bg-[rgba(240,215,186,0.2)]">
            <tr>
                <th class="p-3">No.</th>
                <th>Order ID</th>
                <th>Product</th>
                <th>Date</th>
                <th>Price</th>
                <th>Payment</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($orders as $index => $order): ?>
                <tr class="text-center border-b border-gray-300">
                    <td class="p-3"><?= $index + 1 ?></td>
                    <td><?= $order['id'] ?></td>
                    <td><?= $order['product'] ?></td>
                    <td><?= $order['date'] ?></td>
                    <td>$<?= $order['price'] ?></td>
                    <td class="flex items-center justify-center gap-2">
                        <span class="w-3 h-3 rounded-full <?= $order['payment'] === 'Paid' ? 'bg-green-500' : 'bg-red-500' ?>"></span>
                        <?= $order['payment'] ?>
                    </td>
                    <td><?= $order['status'] ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div> -->











<!-- <td class="flex items-center justify-center gap-2">
    <span class="w-3 h-3 rounded-full <?= $order['payment'] === 'Paid' ? 'bg-green-500' : 'bg-red-500' ?>"></span>
    <?= htmlspecialchars($order['payment']) ?>
</td> -->