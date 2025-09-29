<section class="container mx-auto px-8 py-10">
  <div class="grid grid-cols-1 md:grid-cols-2 gap-10">

    <!-- Left: Image Gallery -->
    <div class="flex flex-col md:flex-row gap-6 px-4">
      <!-- Thumbnails (desktop only) -->
      <div class="hidden md:flex flex-col gap-4 w-28">
        <img src="assets/images/placeholder.png" class="cursor-pointer"/>
        <img src="assets/images/placeholder.png" class="cursor-pointer"/>
        <img src="assets/images/placeholder.png" class="cursor-pointer"/>
      </div>

      <!-- Main Image -->
      <div class="flex-1 w-[384px] h-[540px]">
        <img id="mainImage" src="assets/images/placeholder.png" class="w-full h-full object-cover"/>
      </div>
    </div>

    <!-- Right: Product Details -->
    <div class="flex flex-col gap-6">
      <h1 class="text-3xl font-light font-outfit">Linen oversized trenchcoat</h1>
      <p class="text-3xl font-outfit">$320</p>

      <!-- Sizes -->
      <div class="flex justify-start items-center gap-2">
          <h3 class="text-2xl ">Size:</h3>
          <div class="text-xl flex justify-start items-center">
            <button class="px-3 py-1 hover:rounded-full hover:border hover:border-brand hover:text-brand">XS</button>
            <button class="px-3 py-1 hover:rounded-full hover:border hover:border-brand hover:text-brand">S</button>
            <button class="px-3 py-1 hover:rounded-full hover:border hover:border-brand hover:text-brand">M</button>
            <button class="px-3 py-1 hover:rounded-full hover:border hover:border-brand hover:text-brand">L</button>
          </div>
      </div>
      <!-- Colors -->
      <div class="flex justify-start items-center gap-2">
        <h3 class="text-2xl 
        ">Colors:</h3>
        <div class="w-5 h-5 bg-red-500 rounded-full"></div>
        
      </div>

      <!-- Quantity + Add to Cart -->
      <div class="flex gap-4 items-center">
        <div class="flex justify-center items-center gap-4 border text-xl px-4 py-2 text-brand">
          <button class="">-</button>
          <div class="">1</div>
          <button class="">+</button>
        </div>
        <button class="bg-brand font-outfit text-xl text-white px-8 py-2">Add to Cart</button>
      </div>

      <!-- Accordion -->
      <div class="border-b border-t border-brand w-64">
        <div class="flex justify-between items-center p-2 text-xl text-brand">
          <p>Description</p>
          <i class="fi fi-rr-arrow-small-right"></i>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- Similar Products -->
<section class="py-12">
  <h2 class="text-6xl font-normal mb-8 text-center font-cor">Similar Products</h2>
  <div class="swiper similarProductsSwiper px-6">
    <div class="swiper-wrapper">
      <div class="swiper-slide">
        <img src="assets/images/placeholder.png" class="w-full h-64 object-cover rounded">
        <p class="mt-2">Modena cream palazzo pants</p>
        <span class="text-brand">$100</span>
      </div>
      <div class="swiper-slide">
        <img src="assets/images/placeholder.png" class="w-full h-64 object-cover rounded">
        <p class="mt-2">Blazer with two slits</p>
        <span class="text-brand">$85</span>
      </div>
      <!-- Repeat -->
    </div>
    <div class="swiper-button-prev hidden md:block"></div>
    <div class="swiper-button-next hidden md:block"></div>
    <div class="swiper-pagination block md:hidden"></div>
  </div>
</section>