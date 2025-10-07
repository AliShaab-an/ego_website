//Ajax calls for admin panel

function formatDate(isoDate) {
  if (!isoDate) return "";
  const d = new Date(isoDate);
  return d.toLocaleDateString("en-GB");
}

$(document).ready(function () {
  loadCategories();
  loadCustomers();
  loadAdmins();
});

$(document).ready(function () {
  $("#openBtn").on("click", function () {
    $("#popup").removeClass("hidden");
  });
});

$(document).ready(function () {
  $("#openAdminBtn").on("click", function () {
    $("#createAdmin").removeClass("hidden");
  });
});

$(document).ready(function () {
  $("#AdminCloseBtn").on("click", function () {
    $("#createAdmin").addClass("hidden");
  });
});

$(document).ready(function () {
  $("#closeBtn").on("click", function () {
    $("#popup").addClass("hidden");
  });
});

function loadCategories() {
  $.ajax({
    url: "/Ego_website/public/admin/api/category_list.php",
    type: "GET",
    dataType: "json",
    success: function (res) {
      if (res.status === "success") {
        let table = `
        <table class="table-auto w-full  md:table-fixed ">
            <thead class="bg-[rgba(240,215,186,0.2)]">
              <tr>
                <th class="py-4">ID</th>
                <th class="py-4">Name</th>
                <th class="py-4">Created At</th>
                <th class="py-4">Actions</th>
              </tr>
            </thead>
            <tbody>`;

        let grid = "";
        let dropdown = `<option value="">Select Your Category</option>`;
        if (res.data.length) {
          res.data.forEach((cat) => {
            table += `
                    <tr class="text-center border-b border-gray-300 ">
                        <td class="py-4">${cat.id}</td>
                        <td class="py-4">${cat.name}</td>
                        <td class="py-4">${formatDate(cat.created_at)}</td>
                        <td class="py-4">
                            <span  class="flex justify-center gap-2">
                                    <i class="fa-solid fa-trash hover:text-red-500 deleteCategory" data-id="${
                                      cat.id
                                    }"></i>
                                </span>
                        </td>
                    </tr>`;

            grid += `
                <div class="h-20 rounded flex items-center justify-start shadow-[0_0_18px_-4px_rgba(0,0,0,0.25)] cursor-pointer px-4 gap-2">
                    <img src="/Ego_website/public/admin/uploads/${
                      cat.image ?? ""
                    }"
                    alt="${cat.name}"
                    class="h-[65px] w-[64px] object-cover rounded">
                    <p class="bg-opacity-50 text-black font-bold text-center text-lg py-1">
                    ${cat.name}
                    </p>
                </div> 
            `;
            dropdown += `<option value="${cat.id}">${cat.name}</option>`;
          });
        } else {
          table += `<tr><td colspan="3" class="text-center p-4">No categories found</td></tr>`;
          grid = `<p class="col-span-3 text-center text-gray-500">No categories</p>`;
        }
        table += `</tbody></table>`;

        $("#categoryList").html(table);
        $("#categoryGrid").html(grid);
        $("#categoryDropdown").html(dropdown);
      } else {
        $("#categoryList").html(
          "<p class='text-red-500'>Error loading categories</p>"
        );
        $("#categoryGrid").html("");
        $("#categoryDropdown").html(`<option value="">Error loading</option>`);
      }
    },
    error: function () {
      $("#categoryList").html(
        "<p class='text-red-500'>Server error while loading categories.</p>"
      );
      $("#categoryGrid").html("");
      $("#categoryDropdown").html(`<option value="">Server error</option>`);
    },
  });
}

$("#categoryImage").on("change", function () {
  const file = this.files[0];
  if (file) {
    const render = new FileReader();
    render.onload = function (e) {
      $("#imageBox").css({
        "background-image": `url(${e.target.result})`,
        "background-size": "cover",
        "background-position": "center",
      });
      $("#imageBox i").hide();
    };
    render.readAsDataURL(file);
  }
});

$("#addCategoryForm").on("submit", function (e) {
  e.preventDefault();
  let formData = new FormData(this);

  $.ajax({
    url: "/Ego_website/public/admin/api/category_add.php",
    type: "POST",
    data: formData,
    processData: false,
    contentType: false,
    success: function (res) {
      if (res.status === "success") {
        alert(res.message);
        $("#popup").addClass("hidden");
        $("#categoryName").val("");
        $("#categoryImage").val("");
        loadCategories();
      } else {
        alert(res.message);
      }
    },
    error: function () {
      alert("Something went wrong!");
    },
  });
});

// Delete category

$(document).on("click", ".deleteCategory", function () {
  const id = $(this).data("id");
  if (!confirm("Are you sure you want to delete this category?")) return;

  $.ajax({
    url: "/Ego_website/public/admin/api/category_delete.php",
    type: "POST",
    data: { id },
    dataType: "json",
    success: function (res) {
      if (res.status === "success") {
        loadCategories();
      } else {
        alert(res.message);
      }
    },
    error: function () {
      alert("Server error while deleting category.");
    },
  });
});

// Load customers
function loadCustomers() {
  $.ajax({
    url: "/Ego_website/public/admin/api/customers_list.php",
    type: "GET",
    dataType: "json",
    success: function (res) {
      if (res.status === "success") {
        let table = `
          <table class="table-auto w-full md:table-fixed">
            <thead class="bg-[rgba(240,215,186,0.2)]">
              <tr>
                <th class="pt-4 pb-4">No.</th>
                <th>Name</th>
                <th>Email</th>
                <th>Action</th>
              </tr>
            </thead>
            <tbody>`;

        let totalCustomers = `<p class='text-3xl font-bold text-black my-2'>0</p>`;
        let customersLast7Days = `<p class='text-3xl font-bold text-black my-2'>0</p>`;

        if (res.data.length) {
          res.data.forEach((customer) => {
            table += `
              <tr class="text-center border-b border-gray-300">
                <td class="py-4">${customer.id}</td>
                <td class="py-4">${customer.name}</td>
                <td class="py-4">${customer.email}</td>
                <td class="py-4">
                  <span class="flex justify-center gap-2">
                    <i class="fa-solid fa-trash hover:text-red-500 deleteCustomer"
                       data-id="${customer.id}"></i>
                  </span>
                </td>
              </tr>`;
          });

          totalCustomers = `<p class='text-3xl font-bold text-black my-2'>${res.data.length}</p>`;
          customersLast7Days = `<p class='text-3xl font-bold text-black my-2'>${res.last7Days}</p>`;
        } else {
          table += `<tr><td colspan="4" class="text-center p-4">No customers found</td></tr>`;
        }
        table += `</tbody></table>`;
        $("#customersList").html(table);
        $("#totalCustomers").html(totalCustomers);
        $("#customersLast7Days").html(customersLast7Days);
      } else {
        $("#customersList").html(
          "<p class='text-red-500'>Error loading customers</p>"
        );
        $("#totalCustomers").html(totalCustomers);
        $("#customersLast7Days").html(customersLast7Days);
      }
    },
    error: function () {
      $("#customersList").html(
        "<p class='text-red-500'>Server error while loading customers.</p>"
      );
      $("#totalCustomers").html(
        `<p class='text-3xl font-bold text-black my-2'>0</p>`
      );
      $("#customersLast7Days").html(
        `<p class='text-3xl font-bold text-black my-2'>0</p>`
      );
    },
  });
}

function loadAdmins() {
  $.ajax({
    url: "/Ego_website/public/admin/api/admins_list.php",
    type: "GET",
    dataType: "json",
    success: function (res) {
      if (res.status === "success") {
        let table = `
                <table class="table-auto w-full  md:table-fixed ">
                    <thead class="bg-[rgba(240,215,186,0.2)] ">
                        <tr>
                            <th class="py-4">No.</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Role</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                `;

        if (res.data.length) {
          res.data.forEach((admin) => {
            table += `
                            <tr class="text-center border-b border-gray-300 ">
                                <td class="py-4">${admin.id}</td>
                                <td class="py-4">${admin.name}</td>
                                <td class="py-4">${admin.email}</td>
                                <td class="py-4">${admin.role}</td>
                                <td class="py-4">
                                    <span class="flex justify-center gap-2">
                                        <i class="fa-solid fa-trash hover:text-red-500 deleteAdmin"
                                        data-id="${admin.id}"></i>
                                    </span>
                                </td>
                            </tr>
                        `;
          });
        } else {
          table += `<tr><td colspan="4" class="text-center p-4">No admins found</td></tr>`;
        }
        table += `</tbody></table>`;
        $("#adminsList").html(table);
      } else {
        $("#adminsList").html(
          "<p class='text-red-500'>Error loading admins</p>"
        );
      }
    },
    error: function () {
      $("#adminsList").html(
        "<p class='text-red-500'>Server error while loading admins.</p>"
      );
    },
  });
}
