<section class="max-w-7xl mx-auto px-4 py-10">
    <h1 class="text-4xl md:text-5xl font-bold mb-8 text-center font-cor">Checkout</h1>

    <div class="flex flex-col gap-8">
        <!-- Payment Method -->
        <div class="space-y-4 py-4 border-b">
            <h2 class="text-2xl md:text-3xl">Payment Method</h2>
            <div class="flex flex-wrap gap-3">
                <button class="flex-1 min-w-[120px] border px-4 py-2 md:px-8 md:py-3 text-lg md:text-xl hover:border-brand hover:text-brand">
                Cash on delivery
                </button>
                <button class="flex-1 min-w-[120px] border px-4 py-2 md:px-8 md:py-3 text-lg md:text-xl hover:border-brand hover:text-brand">
                Credit Card
                </button>
                <button class="flex-1 min-w-[120px] border px-4 py-2 md:px-8 md:py-3 text-lg md:text-xl hover:border-brand hover:text-brand">
                Wish Money
                </button>
            </div>
        </div>

        <!-- Content -->
        <div class="flex flex-col md:flex-row md:justify-between gap-10">

            <!-- Shipping Info -->
            <form action="" class="flex-1 flex flex-col gap-3">
                <h2 class="text-2xl md:text-3xl">Shipping Information</h2>

                <label class="text-lg" for="name">Name</label>
                <input class="border w-full h-12 p-2 outline-none" type="text" name="name" placeholder="Enter your name">

                <label class="text-lg" for="email">Email</label>
                <input class="border w-full h-12 p-2 outline-none" type="email" name="email" placeholder="Enter your email">

                <label class="text-lg" for="phone">Phone Number</label>
                <input class="border w-full h-12 p-2 outline-none"  id="phone" name="phone" placeholder="+961...">

                <label class="text-lg" for="country">Country</label>
                <input class="border w-full h-12 p-2 outline-none" type="text" placeholder="Enter your country">

                <!-- City, State, Zip -->
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                <div class="flex flex-col">
                    <label class="text-lg" for="city">City</label>
                    <input class="border h-10 p-2 outline-none" type="text" name="city" placeholder="Enter City">
                </div>
                <div class="flex flex-col">
                    <label class="text-lg" for="state">State</label>
                    <input class="border h-10 p-2 outline-none" type="text" name="state" placeholder="Enter State">
                </div>
                <div class="flex flex-col">
                    <label class="text-lg" for="zip">Zip Code</label>
                    <input class="border h-10 p-2 outline-none" type="text" name="zip" placeholder="Enter Code">
                </div>
                </div>
            </form>

            <!-- Cart Review -->
            <div class="flex-1 flex flex-col items-start py-4 gap-4 border-t    md:border-t-0">
                <h3 class="text-xl font-semibold">Review Your Cart</h3>

                <!-- Example product -->
                <div class="flex gap-4 items-center">
                <img src="assets/images/placeholder.png" alt="" class="w-16 h-20 object-cover">
                <div class="flex flex-col">
                    <p class="text-lg md:text-xl font-outfit">Linen oversized coat</p>
                    <p class="text-gray-400 text-sm">1x</p>
                    <p>$320</p>
                </div>
                </div>

                <!-- Discount -->
                <form action="" class="relative w-full max-w-md">
                <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-500">
                    <i class="fi fi-rr-ticket"></i>
                </span>
                <input type="text" placeholder="Discount code"
                    class="w-full border pl-10 pr-16 py-2 outline-none">
                <span
                    class="absolute inset-y-0 right-0 flex items-center pr-3 text-gray-500 text-sm md:text-lg cursor-pointer">
                    Apply
                </span>
                </form>

                <!-- Totals -->
                <div class="w-full max-w-md space-y-2">
                <div class="flex justify-between">
                    <p>Subtotal</p>
                    <p>$420</p>
                </div>
                <div class="flex justify-between">
                    <p>Shipping</p>
                    <p>$25</p>
                </div>
                <div class="flex justify-between">
                    <p>Discount</p>
                    <p>$0</p>
                </div>
                <div class="flex justify-between font-semibold">
                    <p>Total</p>
                    <p>$445</p>
                </div>
                </div>

                <!-- Pay button -->
                <button class="w-full max-w-md bg-brand text-white py-3 mt-4">Pay Now</button>
            </div>
        </div>
    </div>
</section>
