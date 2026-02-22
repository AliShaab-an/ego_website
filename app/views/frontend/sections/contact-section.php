<section class="max-w-screen-xl mx-auto px-4 sm:px-6 py-12 grid gap-12 md:grid-cols-2">
  
  <!-- Left column (Store info) - moves below on mobile -->
  <div class="order-2 md:order-1 flex flex-col items-start">
    <?php 
        $storeName = getSetting('website_name', 'EGO STORE');
    ?>
    <h2 class="text-4xl font-thin  text-black font-cor mb-1"><?= htmlspecialchars($storeName) ?></h2>
    <p class="font-thin text-brand font-outfit mb-4">Find us easily</p>

    <div class="flex flex-col items-start text-black mb-4">
      
      <p><span class="text-2xl">Address</span></p>
      <?php $address = getSetting('address', 'Beirut - Aresco center ground floor'); ?>
      <p class="mb-4 text-lg text-gray-500"><?= htmlspecialchars($address) ?></p>

      <p><span class="text-2xl">Phone</span></p>
      <?php $phone = getSetting('phone_number', '00961-81971653'); ?>
      <p class="mb-4 text-lg text-gray-500"><?= htmlspecialchars($phone) ?></p>

      <p><span class="text-2xl font-outfit">Email</span></p>
      <?php $email = getSetting('contact_email', 'Eclothingleb@gmail.com'); ?>
      <p class="text-lg text-gray-500 cursor-pointer"><?= htmlspecialchars($email) ?></p>
    </div>

    <div class="space-y-2 w-full md:w-1/2 flex flex-col items-start ">
      <?php 
        $googleMapsLink = getSetting('google_maps_link', '');
      ?>
      <button class="w-full border border-brand hover:bg-brand hover:text-white transition px-4 py-2 text-brand cursor-pointer"><a target="_blank" href="<?= htmlspecialchars($googleMapsLink) ?>">Find Us</a></button>
      <button class="w-full bg-brand text-white px-4 py-2 cursor-pointer">Contact Us</button>
    </div>
  </div>

  <!-- Right column (Form) - shows first on mobile -->
  <div class="order-1 md:order-2 space-y-4">
    <form id="contactForm" class="space-y-4">
      <div class="text-start">
        <label class="block mb-1 text-sm font-medium text-gray-700">Name</label>
        <input type="text" id="contactName" name="name" class="w-full border border-gray-300 px-3 py-2 outline-none focus:border-brand focus:ring-1 focus:ring-brand" placeholder="Name" required>
      </div>
      
      <div class="text-start">
        <label class="block text-sm font-medium text-gray-700">Email</label>
        <input type="email" id="contactEmail" name="email" class="w-full border border-gray-300 px-3 py-2 outline-none focus:border-brand placeholder:text-gray-400" placeholder="Enter your email" required>
      </div>
      <div class="text-start">
        <label class="block text-sm font-medium text-gray-700">How can we help?</label>
        <textarea id="contactMessage" name="message" class="w-full border border-gray-300 px-3 py-2 outline-none focus:border-brand placeholder:text-gray-400" rows="6" placeholder="Your message..." required></textarea>
      </div>
      
      <!-- Success/Error Message Container -->
      <div id="contactMessageContainer" class="hidden"></div>
      
      <div class="flex items-start">
        <button type="submit" id="contactSubmitBtn" class="w-1/3 bg-brand text-white px-4 py-2 hover:bg-opacity-90 transition-all cursor-pointer">Submit</button>
      </div>
      
    </form>
  </div>
</section>
