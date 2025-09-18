$(document).ready(function () {
  $("#colorForm").on("submit", function (e) {
    e.preventDefault();

    $.ajax({
      url: "/Ego_website/public/admin/api/add-size.php",
      type: "POST",
      data: $(this).serialize(),
      dataType: "json",
      success: function (res) {
        if (res.status === "success") {
          alert("Size added successfully!");
          $("#SizerForm")[0].reset();

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
        let dropdown = `<option value="">Size</option>`;
        sizeOptions = '<option value="">Size</option>';

        if (Array.isArray(res.data) && res.data.length) {
          res.data.forEach((size) => {
            const option = `<option value="${size.id}" style="background:${size.type}">
               ${color.name}
             </option>`;
            sizeOptions += option;
            dropdown += option;
          });
        } else {
          dropdown += `<option value="">No Sizes</option>`;
          sizeOptions = '<option value="">No Sizes</option>';
        }
        $("#sizesDropdown").html(dropdown);
        $("#variantContainer select[name*='[size_id]']").html(sizeOptions);
      } else {
        $("#sizesDropdown").html(`<option value="">Error loading</option>`);
        $("#variantContainer select[name*='[size_id]']").html(
          '<option value="">Error loading</option>'
        );
      }
    },
    error: function () {
      $("#sizesDropdown").html(`<option value="">Server error</option>`);
      $("#variantContainer select[name*='[size_id]']").html(
        '<option value="">Server error</option>'
      );
    },
  });
}