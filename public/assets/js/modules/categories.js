import { ajaxRequest } from "../utils/ajax.js";

const Categories = {
  categories: [],

  init() {
    this.loadCategories();
    this.bindEvents();
  },

  bindEvents() {
    // Desktop category dropdown (click)
    $(document).on("click", ".categories-dropdown-toggle", (e) => {
      e.preventDefault();
      this.toggleDropdown(".categories-dropdown");
    });

    // Mobile category dropdown (click)
    $(document).on("click", ".mobile-categories-toggle", (e) => {
      e.preventDefault();
      this.toggleDropdown(".mobile-categories-dropdown");
    });

    // Close dropdown when clicking outside
    $(document).on("click", (e) => {
      if (!$(e.target).closest(".categories-container, #mobileNav").length) {
        this.closeAllDropdowns();
      }
    });
  },

  loadCategories() {
    ajaxRequest({
      url: "/Ego_website/public/admin/api/list-categories.php",
      type: "GET",
      success: (res) => {
        if (res.status === "success" && res.data?.length) {
          this.categories = res.data;
          this.renderDesktopDropdown();
          this.renderMobileDropdown();
        }
      },
      error: (xhr) => {
        console.error("Failed to load categories:", xhr.responseText);
      },
    });
  },

  renderDesktopDropdown() {
    const dropdownHtml = `
      <div class="categories-dropdown absolute top-full left-0 mt-2 w-48 bg-white rounded-lg shadow-lg border hidden z-50">
        <div class="py-2">
          ${this.categories
            .map(
              (cat) => `
            <a href="category.php?id=${cat.id}" class="block px-4 py-2 text-gray-800 hover:bg-gray-100 transition-colors">
              ${cat.name}
            </a>
          `
            )
            .join("")}
        </div>
      </div>
    `;

    // Update desktop navigation
    const desktopNav = $(".hidden.md\\:flex");
    const categoriesLink = desktopNav.find('a:contains("Categories")');
    if (categoriesLink.length) {
      categoriesLink.parent().addClass("categories-container relative");
      categoriesLink.addClass(
        "categories-dropdown-toggle flex items-center gap-1"
      );
      categoriesLink.html(
        'Categories <i class="fas fa-chevron-down text-xs"></i>'
      );
      categoriesLink.parent().append(dropdownHtml);
    }
  },

  renderMobileDropdown() {
    const mobileDropdownHtml = `
      <div class="mobile-categories-dropdown hidden">
        ${this.categories
          .map(
            (cat) => `
          <a href="category.php?id=${cat.id}" class="block py-2 pl-4 text-white hover:text-gray-300">
            ${cat.name}
          </a>
        `
          )
          .join("")}
      </div>
    `;

    // Update mobile navigation
    const mobileNav = $("#mobileNav");
    const mobileCategoriesLink = mobileNav.find('a:contains("Categories")');
    if (mobileCategoriesLink.length) {
      mobileCategoriesLink.addClass(
        "mobile-categories-toggle flex items-center justify-between"
      );
      mobileCategoriesLink.html(
        'Categories <i class="fas fa-chevron-down text-xs"></i>'
      );
      mobileCategoriesLink.after(mobileDropdownHtml);
    }
  },

  showDropdown(selector) {
    const dropdown = $(selector);
    dropdown.removeClass("hidden");
    dropdown.prev().find(".fa-chevron-down").addClass("rotate-180");
  },

  hideDropdown(selector) {
    const dropdown = $(selector);
    dropdown.addClass("hidden");
    dropdown.prev().find(".fa-chevron-down").removeClass("rotate-180");
  },

  toggleDropdown(selector) {
    const dropdown = $(selector);
    const isVisible = !dropdown.hasClass("hidden");

    // Close all dropdowns first
    this.closeAllDropdowns();

    // Toggle the clicked dropdown
    if (!isVisible) {
      dropdown.removeClass("hidden");
      // Rotate chevron
      dropdown.prev().find(".fa-chevron-down").addClass("rotate-180");
    }
  },

  closeAllDropdowns() {
    $(".categories-dropdown, .mobile-categories-dropdown").addClass("hidden");
    $(".fa-chevron-down").removeClass("rotate-180");
  },
};

export default Categories;
