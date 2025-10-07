$(document).ready(function () {
  $("#sizeForm").on("submit", function (e) {
    e.preventDefault();

    $.ajax({
      url: "/Ego_website/public/admin/api/add-size.php",
      type: "POST",
      data: $(this).serialize(),
      dataType: "json",
      success: function (res) {
        if (res.status === "success") {
          alert("Size added successfully!");
          $("#sizeForm")[0].reset();

          if (typeof loadSizes === "function") {
            loadSizes();
          }
        } else {
          alert(res.message || "Error adding size");
        }
      },
      error: function () {
        alert("Server error while adding size");
      },
    });
  });
});

let sizeOptions = '<option value="">Size</option>';

function loadSizes() {
  $.ajax({
    url: "/Ego_website/public/admin/api/list-sizes.php",
    type: "GET",
    dataType: "json",
    success: function (res) {
      if (res.status === "success") {
        sizeOptions = '<option value="">Size</option>';
        if (Array.isArray(res.data) && res.data.length) {
          res.data.forEach((size) => {
            sizeOptions += `<option value="${size.id}">${size.name} (${size.type})</option>`;
          });
        } else {
          sizeOptions = '<option value="">No Sizes</option>';
        }
      }
    },
  });
}

$(document).ready(function () {
  $("#colorForm").on("submit", function (e) {
    e.preventDefault();

    $.ajax({
      url: "/Ego_website/public/admin/api/add-color.php",
      type: "POST",
      data: $(this).serialize(),
      dataType: "json",
      success: function (res) {
        if (res.status === "success") {
          alert("Color added successfully!");
          $("#colorForm")[0].reset();

          if (typeof loadColors === "function") {
            loadColors();
          }
        } else {
          alert(res.message || "Error adding color");
        }
      },
      error: function () {
        alert("Server error while adding color");
      },
    });
  });
});

let colorOptions = '<option value="">Color</option>';

function loadColors() {
  $.ajax({
    url: "/Ego_website/public/admin/api/list-colors.php",
    type: "GET",
    dataType: "json",
    success: function (res) {
      if (res.status === "success") {
        colorOptions = '<option value="">Color</option>';
        if (Array.isArray(res.data) && res.data.length) {
          res.data.forEach((color) => {
            colorOptions += `<option value="${color.id}" data-hex="${color.hex_code}">
              ${color.name}
            </option>`;
          });
        } else {
          colorOptions = '<option value="">No Colors</option>';
        }
      }
    },
  });
}
