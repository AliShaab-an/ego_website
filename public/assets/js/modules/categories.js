import { ajaxRequest } from "../utils/ajax.js";
import Config from "../config.js";

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
      this.toggleDropdown("#mobileCategoriesDropdown");
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
      url: Config.getApiUrl("list-categories.php"),
      type: "GET",
      success: (res) => {
        if (res.status === "success" && res.data?.length) {
          this.categories = res.data;
          this.renderDesktopDropdown();
          this.renderMobileDropdown();
        }
      },
      error: (xhr) => {
        console.error("Error loading categories:", xhr);
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
    const dropdown = $("#mobileCategoriesDropdown");

    if (dropdown.length) {
      dropdown.empty();

      this.categories.forEach((cat) => {
        const link = $(
          `<a href="category.php?id=${cat.id}" class="block py-2 pl-4 text-gray-700 hover:text-brand transition-colors border-b">${cat.name}</a>`
        );
        dropdown.append(link);
      });
    }
  },

  showDropdown(selector) {
    const dropdown = $(selector);
    dropdown.removeClass("hidden");
    // Find the toggle button and rotate its chevron
    if (selector === ".mobile-categories-dropdown") {
      $(".mobile-categories-toggle .fa-chevron-down").addClass("rotate-180");
    } else {
      dropdown.prev().find(".fa-chevron-down").addClass("rotate-180");
    }
  },

  hideDropdown(selector) {
    const dropdown = $(selector);
    dropdown.addClass("hidden");
    // Find the toggle button and un-rotate its chevron
    if (selector === ".mobile-categories-dropdown") {
      $(".mobile-categories-toggle .fa-chevron-down").removeClass("rotate-180");
    } else {
      dropdown.prev().find(".fa-chevron-down").removeClass("rotate-180");
    }
  },

  toggleDropdown(selector) {
    const dropdown = $(selector);
    const isVisible = !dropdown.hasClass("hidden");

    this.closeAllDropdowns();

    if (!isVisible) {
      dropdown.removeClass("hidden");

      if (selector === "#mobileCategoriesDropdown") {
        $(".mobile-categories-toggle .transform").addClass("rotate-180");
      } else if (selector === ".mobile-categories-dropdown") {
        $(".mobile-categories-toggle .transform").addClass("rotate-180");
      } else {
        dropdown.prev().find(".fa-chevron-down").addClass("rotate-180");
      }
    }
  },

  closeAllDropdowns() {
    $(
      ".categories-dropdown, .mobile-categories-dropdown, #mobileCategoriesDropdown"
    ).addClass("hidden");
    $(".fa-chevron-down, .transform").removeClass("rotate-180");
  },
};

export default Categories;
