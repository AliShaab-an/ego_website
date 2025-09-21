<section class="max-w-screen-xl mx-auto px-4 sm:px-6 py-12 grid gap-12 md:grid-cols-2">
  
  <!-- Left column (Store info) - moves below on mobile -->
  <div class="order-2 md:order-1 flex flex-col items-start">
    <h2 class="text-4xl font-thin  text-black font-cor mb-1">EGO STORE</h2>
    <p class="font-thin text-brand font-outfit mb-4">Find us easily</p>

    <div class="flex flex-col items-start text-black mb-4">
      <p><span class="text-2xl">Address</span></p>
      <p class="mb-4">Beirut, Downtown</p>
      <p><span class="text-2xl">Phone</span></p>
      <p class="mb-4">123 456 789</p>
      <p><span class="text-2xl font-outfit">Email</span></p>
      <p><a href="#">Egoclothing.com</a></p>
    </div>

    <div class="space-y-2  w-1/2 flex flex-col items-start ">
      <button class="w-full border border-brand px-4 py-2 text-brand"><a href="#">Find Us</a></button>
      <button class="w-full bg-brand text-white px-4 py-2">Contact Us</button>
    </div>
  </div>

  <!-- Right column (Form) - shows first on mobile -->
  <div class="order-1 md:order-2 space-y-4">
    <form class="space-y-4">
      <div class="text-start">
        <label class="block mb-1 text-sm font-medium text-gray-700">Name</label>
        <input type="text" class="w-full border border-gray-300 px-3 py-2 outline-none focus:border-brand focus:ring-1 focus:ring-brand" placeholder="Name">
      </div>
      
      <div class="text-start">
        <label class="block text-sm font-medium text-gray-700">Email</label>
        <input type="email" class="w-full border border-gray-300 px-3 py-2 outline-none focus:border-brand placeholder:text-gray-400" placeholder="Enter your email">
      </div>
      <div class="text-start">
        <label class="block text-sm font-medium text-gray-700">How can we help?</label>
        <textarea class="w-full border border-gray-300 px-3 py-2 outline-none focus:border-brand placeholder:text-gray-400" rows="5" placeholder="Your message..."></textarea>
      </div>
      <div class="flex items-start">
        <button type="submit" class="w-1/3  bg-brand text-white px-4 py-2">Submit</button>
      </div>
      
    </form>
  </div>
</section>
