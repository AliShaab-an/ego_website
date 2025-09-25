<section class="py-12 bg-white">
  <div class="container mx-auto px-4">
    <!-- Section Title -->
    <div class="text-center mb-10">
      <p class="text-sm uppercase tracking-widest font-outfit text-brand">Check Out Our</p>
      <h2 class="text-5xl font-cor">Collections</h2>
    </div>

    <!-- Swiper container -->
    <div class="swiper myCategories">
      <div class="swiper-wrapper">
      
      <!-- Category Slide -->
        <div class="swiper-slide">
          <div class="grid md:grid-cols-2 gap-6 items-center">
          
            <!-- Left: Big category image -->
            <div>
              <img src="assets/images/category-dresses.jpg" alt="Dresses" 
                  class="w-full h-[400px] object-cover rounded">
              <p class="mt-2 text-lg font-semibold">Dresses</p>
            </div>

            <!-- Right: Products grid -->
            <div class="grid grid-cols-2 gap-4">
              <div class="space-y-2">
                <img src="assets/images/product-1.jpg" class="w-full h-40 object-cover rounded">
                <p class="text-sm">Jersey midi dress with cut</p>
                <span class="text-brand font-medium">$300</span>
              </div>
              <div class="space-y-2">
                <img src="assets/images/product-2.jpg" class="w-full h-40 object-cover rounded">
                <p class="text-sm">Blazer with two slits</p>
                <span class="text-brand font-medium">$85</span>
              </div>
              <div class="space-y-2">
                <img src="assets/images/product-3.jpg" class="w-full h-40 object-cover rounded">
                <p class="text-sm">Modern cream palazzo pants</p>
                <span class="text-brand font-medium">$100</span>
              </div>
              <div class="space-y-2">
                <img src="assets/images/product-4.jpg" class="w-full h-40 object-cover rounded">
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
      <div class="swiper-pagination mt-4"></div>
    </div>
</section>