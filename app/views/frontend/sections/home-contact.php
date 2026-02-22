<section class="w-full px-4 sm:px-6 py-2 md:flex md:items-start md:justify-between">
  <!-- Info (mobile second, desktop first) -->
  <div class="order-2 md:order-1 md:ml-12 max-w-md text-start">
    <?php 
        $storeName = getSetting('website_name', 'EGO STORE');
    ?>
    <h2 class="md:text-6xl text-4xl font-light text-black font-cor mb-2"><?= htmlspecialchars($storeName) ?></h2>
    <p class="text-2xl text-brand mb-4">Find us easily</p>

    <div class="space-y-3 text-black py-4 mb-10">
      
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

    <div class="flex flex-col space-y-4 ">
      <?php 
        $googleMapsLink = getSetting('google_maps_link', '');
      ?>
      <a href="<?= htmlspecialchars($googleMapsLink) ?>"
      target="_blank"
      class="inline-block w-full md:w-auto text-center md:h-14 md:text-xl border border-brand px-6 py-3 bg-white text-brand hover:bg-brand hover:text-white transition cursor-pointer">
      Find Us
      </a>
        <a href="<?= page_url('contact') ?>" 
        class="block w-full md:w-full md:h-14 md:text-xl bg-brand text-white px-6 py-3 text-center transition cursor-pointer">
            Contact Us
        </a>
    </div>
  </div>

  <!-- Image (mobile first, desktop second) -->
  <div class="order-1 md:order-2 flex-shrink-0 w-full md:w-1/2 lg:w-[40%] mt-6 md:mt-0">
    <img src="<?= IMG_PATH ?>contact-image.png" alt="Store Image" class="w-full h-full object-cover">
  </div>
</section>