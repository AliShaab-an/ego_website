<section class="max-w-screen-xl mx-auto px-4 sm:px-6 py-12">
  <h2 class="text-center text-2xl md:text-3xl font-bold mb-8">
    <span class="block text-sm text-gray-500">Check Out Our</span>
    Collections
  </h2>

  <!-- Swiper container -->
  <div class="swiper myCategories">
    <div class="swiper-wrapper">
      
      <!-- Category Slide -->
      <div class="swiper-slide">
        <div class="grid md:grid-cols-2 gap-6 items-center">
          
          <!-- Left: Big category image -->
          <div>
            <img src="assets/images/placeholder.png" alt="Dresses" 
                  class="w-full h-80 object-cover rounded">
            <p class="mt-2 text-lg font-semibold">Dresses</p>
          </div>

          <!-- Right: Products grid -->
          <div class="grid grid-cols-2 gap-4">
            <div class="space-y-2">
              <img src="assets/images/placeholder.png" class="w-xs h-48 object-cover rounded">
              <p class="text-sm">Jersey midi dress with cut</p>
              <span class="text-brand font-medium">$300</span>
            </div>
            <div class="space-y-2">
              <img src="assets/images/placeholder.png" class="w-52 h-48 object-cover rounded">
              <p class="text-sm">Blazer with two slits</p>
              <span class="text-brand font-medium">$85</span>
            </div>
            <div class="space-y-2">
              <img src="assets/images/placeholder.png" class="w-52 h-48 object-cover rounded">
              <p class="text-sm">Modern cream palazzo pants</p>
              <span class="text-brand font-medium">$100</span>
            </div>
            <div class="space-y-2">
              <img src="assets/images/placeholder.png" class="w-52 h-48 object-cover rounded">
              <p class="text-sm">Faux leather shorts</p>
              <span class="text-brand font-medium">$55</span>
            </div>
          </div>

        </div>
      </div>

      <!-- Repeat swiper-slide for other categories -->
      <div class="swiper-slide">
        <!-- Another category + its 4 products -->
      </div>

    </div>

    <!-- Navigation buttons -->
    <div class="flex justify-center mt-6 gap-4">
      <div class="swiper-button-prev !static !text-black"></div>
      <div class="swiper-button-next !static !text-black"></div>
    </div>

    <!-- Pagination dots -->
    <!-- <div class="swiper-pagination mt-4"></div> -->
  </div>
</section>