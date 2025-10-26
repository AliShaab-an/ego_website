import { ajaxRequest } from "../utils/ajax.js";
import { showLoader, hideLoader } from "../utils/loader.js";

const Products = {
  currentPage: 1,
  limit: 18,
  categoryLimit: 12, // Limit for category pages

  init() {
    this.loadFilters();
    this.bindEvents();
    this.loadProducts();
  },

  initCategory() {
    // Initialize for category page (no filters)
    this.bindCategoryEvents();
    this.loadCategoryProducts();
  },

  bindEvents() {
    // Pagination
    $(document).on("click", "#nextPage", () => this.changePage("next"));
    $(document).on("click", "#prevPage", () => this.changePage("prev"));

    // Filter events
    $("#applyFilters").on("click", () => this.applyFilters());
    $("#clearFilters").on("click", () => this.clearFilters());

    // Filter sidebar toggle
    $("#openFilter").on("click", () => this.openFilterSidebar());
    $("#closeFilter").on("click", () => this.closeFilterSidebar());

    // Color filter visual feedback
    $(document).on("change", ".color-filter", function () {
      const checkbox = $(this);
      const colorDiv = checkbox.siblings("div").first();
      const checkIcon = colorDiv.find("div").first();

      if (checkbox.is(":checked")) {
        colorDiv.addClass("border-brand");
        checkIcon.removeClass("hidden");
      } else {
        colorDiv.removeClass("border-brand");
        checkIcon.addClass("hidden");
      }
    });

    // Size filter visual feedback
    $(document).on("change", ".size-filter", function () {
      const checkbox = $(this);
      const sizeDiv = checkbox.siblings("div").first();

      if (checkbox.is(":checked")) {
        sizeDiv.addClass("bg-brand text-white border-brand");
      } else {
        sizeDiv.removeClass("bg-brand text-white border-brand");
      }
    });

    // Price range updates
    $("#minPrice, #maxPrice").on("input", () => this.updatePriceDisplay());
  },

  bindCategoryEvents() {
    // Category-specific pagination events (no filters)
    $(document).on("click", "#nextPage", () => this.changeCategoryPage("next"));
    $(document).on("click", "#prevPage", () => this.changeCategoryPage("prev"));
  },

  loadProducts(page = 1) {
    const filters = this.collectFilters();
    const container = $("#productsContainer");

    // Show loader
    container.html(`
      <div class="col-span-full flex justify-center items-center py-20">
        <div class="flex flex-col items-center gap-4">
          <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-gray-900"></div>
          <p class="text-gray-600 text-lg">Loading products...</p>
        </div>
      </div>
    `);

    ajaxRequest({
      url: `/Ego_website/public/api/list-products.php?page=${page}&limit=${this.limit}`,
      type: "GET",
      data: filters,
      success: (res) => {
        container.empty();
        const pagination = $("#paginationNumbers").empty();

        if (res.status === "success" && res.data?.length) {
          $("#totalCount").text(res.total);
          $("#showingCount").text(res.data.length);
          res.data.forEach((p) => {
            container.append(`
              <a href="product.php?id=${p.id}" class="flex flex-col group">
                <div class="md:w-full md:h-96 overflow-hidden">
                  <img src="/Ego_website/public/${p.image_path}" 
                       alt="${p.name}" 
                       loading="lazy" 
                       class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                </div>
                <p class="mt-2 text-gray-600 text-lg">${p.name}</p>
                <p class="text-brand font-bold">$${Number(p.base_price).toFixed(
                  2
                )}</p>
              </a>
            `);
          });

          const totalPages = Math.ceil(res.total / this.limit);
          for (let i = 1; i <= totalPages; i++) {
            pagination.append(`
              <button 
                class="px-2 ${
                  i === page
                    ? "bg-brand text-white rounded"
                    : "text-brand font-bold"
                }"
                data-page="${i}">
                ${i}
              </button>
            `);
          }

          // Enable click on page numbers
          $("#paginationNumbers button").on("click", (e) => {
            const newPage = $(e.currentTarget).data("page");
            this.currentPage = newPage;
            this.loadProducts(newPage);
          });

          // Handle next/prev visibility
          $("#prevPage").toggle(page > 1);
          $("#nextPage").toggle(page < totalPages);
        } else {
          container.html(
            `
            <div class="col-span-full w-full text-center">
            <p class="text-center text-gray-500 py-10">No products found.</p>
            </div>`
          );
          pagination.empty();
        }
      },
      error: (xhr) => {
        console.error(xhr.responseText);
        container.html(`
          <div class="col-span-full w-full text-center">
            <p class="text-center text-red-500 py-10">Error loading products. Please try again.</p>
          </div>
        `);
      },
    });
  },

  changePage(direction) {
    if (direction === "next") this.currentPage++;
    else if (direction === "prev" && this.currentPage > 1) this.currentPage--;
    this.loadProducts(this.currentPage);
  },

  changeCategoryPage(direction) {
    if (direction === "next") this.currentPage++;
    else if (direction === "prev" && this.currentPage > 1) this.currentPage--;
    this.loadCategoryProducts(this.currentPage);
  },

  loadCategoryProducts(page = 1) {
    const categoryId = $("body").data("category-id");

    if (!categoryId) {
      console.error("No category ID found");
      return;
    }

    const container = $("#productsContainer");

    // Show loader
    container.html(`
      <div class="col-span-full flex justify-center items-center py-20">
        <div class="flex flex-col items-center gap-4">
          <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-gray-900"></div>
          <p class="text-gray-600 text-lg">Loading products...</p>
        </div>
      </div>
    `);

    const apiUrl = `/Ego_website/public/api/list-products.php?page=${page}&limit=${this.categoryLimit}&category=${categoryId}`;

    ajaxRequest({
      url: apiUrl,
      type: "GET",
      success: (res) => {
        container.empty();
        const pagination = $("#paginationNumbers").empty();

        if (res.status === "success" && res.data?.length) {
          $("#totalCount").text(res.total);
          $("#showingCount").text(res.data.length);
          res.data.forEach((p) => {
            container.append(`
              <a href="product.php?id=${p.id}" class="flex flex-col group">
                <div class="md:w-full md:h-96 overflow-hidden">
                  <img src="/Ego_website/public/${p.image_path}" 
                       alt="${p.name}" 
                       loading="lazy" 
                       class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                </div>
                <p class="mt-2 text-gray-600 text-lg">${p.name}</p>
                <p class="text-brand font-bold">$${Number(p.base_price).toFixed(
                  2
                )}</p>
              </a>
            `);
          });

          const totalPages = Math.ceil(res.total / this.categoryLimit);
          for (let i = 1; i <= totalPages; i++) {
            pagination.append(`
              <button 
                class="px-2 ${
                  i === page
                    ? "bg-brand text-white rounded"
                    : "text-brand font-bold"
                }"
                data-page="${i}">
                ${i}
              </button>
            `);
          }

          // Enable click on page numbers for category
          $("#paginationNumbers button").on("click", (e) => {
            const newPage = $(e.currentTarget).data("page");
            this.currentPage = newPage;
            this.loadCategoryProducts(newPage);
          });

          // Handle next/prev visibility
          $("#prevPage").toggle(page > 1);
          $("#nextPage").toggle(page < totalPages);

          // Update pagination handlers for category
          $("#nextPage")
            .off("click")
            .on("click", () => {
              if (page < totalPages) {
                this.currentPage = page + 1;
                this.loadCategoryProducts(this.currentPage);
              }
            });

          $("#prevPage")
            .off("click")
            .on("click", () => {
              if (page > 1) {
                this.currentPage = page - 1;
                this.loadCategoryProducts(this.currentPage);
              }
            });
        } else {
          container.html(
            `
            <div class="col-span-full w-full text-center">
            <p class="text-center text-gray-500 py-10">No products found in this category.</p>
            </div>`
          );
          pagination.empty();
        }
      },
      error: (xhr) => {
        console.error(xhr.responseText);
        container.html(`
          <div class="col-span-full w-full text-center">
            <p class="text-center text-red-500 py-10">Error loading products. Please try again.</p>
          </div>
        `);
      },
    });
  },

  loadFilters() {
    // Load categories
    ajaxRequest({
      url: "/Ego_website/public/admin/api/list-categories.php",
      type: "GET",
      success: (res) => {
        const container = $("#categoryFilters").empty();
        if (res.status === "success" && res.data?.length) {
          res.data.forEach((cat) => {
            container.append(`
            <label class="flex items-center gap-2">
              <input type="checkbox" name="category" value="${cat.id}" class="accent-brand">
              <span>${cat.name}</span>
            </label>
          `);
          });
        } else {
          container.html(
            "<p class='text-gray-400 text-sm'>No categories found.</p>"
          );
        }
      },
    });

    // Load colors
    ajaxRequest({
      url: "/Ego_website/public/admin/api/list-colors.php",
      type: "GET",
      success: (res) => {
        const container = $("#colorFilters").empty();
        if (res.status === "success" && res.data?.length) {
          res.data.forEach((color) => {
            container.append(`
            <label class="flex items-center gap-2">
              <input type="checkbox" name="color" value="${color.id}" class="accent-brand">
              <span class="flex items-center gap-1">
                ${color.name}
              </span>
            </label>
          `);
          });
        } else {
          container.html(
            "<p class='text-gray-400 text-sm'>No colors found.</p>"
          );
        }
      },
    });

    // Load sizes
    ajaxRequest({
      url: "/Ego_website/public/admin/api/list-sizes.php",
      type: "GET",
      success: (res) => {
        const container = $("#sizeFilters").empty();
        if (res.status === "success" && res.data?.length) {
          res.data.forEach((size) => {
            container.append(`
            <label class="flex items-center gap-2">
              <input type="checkbox" name="size" value="${
                size.id
              }" class="accent-brand">
              <span>${size.name} ${size.type ? `(${size.type})` : ""}</span>
            </label>
          `);
          });
        } else {
          container.html(
            "<p class='text-gray-400 text-sm'>No sizes found.</p>"
          );
        }
      },
    });
  },

  applyFilters() {
    const filters = this.collectFilters();

    this.loadProducts(1, filters);

    const sidebar = document.getElementById("filterSidebar");
    sidebar.classList.add("-translate-x-full");
    document.body.classList.remove("overflow-hidden");
  },

  collectFilters() {
    const categories = $("input[name='categories[]']:checked")
      .map((_, el) => $(el).val())
      .get();

    const colors = $("input[name='colors[]']:checked")
      .map((_, el) => $(el).val())
      .get();

    const sizes = $("input[name='sizes[]']:checked")
      .map((_, el) => $(el).val())
      .get();

    const minPrice = $("#minPrice").val() || 0;
    const maxPrice = $("#maxPrice").val() || 10000;

    return {
      categories,
      colors,
      sizes,
      minPrice: parseInt(minPrice),
      maxPrice: parseInt(maxPrice),
    };
  },

  clearFilters() {
    // Clear all filter checkboxes
    $(
      "input[name='categories[]'], input[name='colors[]'], input[name='sizes[]']"
    ).prop("checked", false);

    // Reset price inputs
    $("#minPrice").val(0);
    $("#maxPrice").val(10000);

    // Update visual feedback
    $(".color-filter").trigger("change");
    $(".size-filter").trigger("change");

    // Update price display
    this.updatePriceDisplay();

    // Reload products with no filters
    this.loadProducts(1);
  },

  openFilterSidebar() {
    const sidebar = document.getElementById("filterSidebar");
    sidebar.classList.remove("-translate-x-full");
    document.body.classList.add("overflow-hidden");
  },

  closeFilterSidebar() {
    const sidebar = document.getElementById("filterSidebar");
    sidebar.classList.add("-translate-x-full");
    document.body.classList.remove("overflow-hidden");
  },

  updatePriceDisplay() {
    const minPrice = $("#minPrice").val() || 0;
    const maxPrice = $("#maxPrice").val() || 10000;
    $("#priceRangeDisplay").text(
      `$${parseInt(minPrice).toLocaleString()} - $${parseInt(
        maxPrice
      ).toLocaleString()}`
    );
  },
};

export default Products;
