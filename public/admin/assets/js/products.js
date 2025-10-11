$("#productForm").on("submit", function (e) {
  e.preventDefault();

  let formData = new FormData();

  formData.append("name", $("#name").val());
  formData.append("description", $("[name='description']").val());
  formData.append("base_price", $("[name='base_price']").val());
  formData.append("weight", $("[name='weight']").val());
  formData.append("category_id", $("[name='category_id']").val());
  if ($("#is_top").is(":checked")) formData.append("is_top", 1);

  let variants = collectVariants();
  console.log("Collected Variants before sending:", variants);

  formData.append("variants", JSON.stringify(variants));

  $("input[type='file'][name^='variants']").each(function () {
    let match = $(this)
      .attr("name")
      .match(/variants\[(\d+)\]/);
    if (match) {
      let variantIndex = match[1];
      for (let file of this.files) {
        formData.append(`variant_images[${variantIndex}][]`, file);
      }
    }
  });

  console.log("=== FINAL FormData Content ===");
  for (let pair of formData.entries()) {
    console.log(pair[0] + ": ", pair[1]);
  }
  console.log("==============================");

  $.ajax({
    url: "/Ego_website/public/admin/api/add-product.php",
    type: "POST",
    data: formData,
    processData: false,
    contentType: false,
    dataType: "json",
    success: function (res) {
      console.log("✅ Full Add Product response:", res);

      if (res.status === "success") {
        alert("✅ Product added successfully!");
        $("#productForm")[0].reset();
        $("#colorContainer").empty();
      } else {
        alert("❌ Error: " + res.message);
        console.error("Server response:", res);
      }
    },
    error: function (xhr, status, error) {
      console.error("❌ AJAX error:", xhr.responseText);
      alert("Server error during product creation.");
    },
  });
});

function collectVariants() {
  let variants = [];

  $(".color-block").each(function (colorIndex) {
    let color_id = $(this).find(".colorDropdown").val();
    let sizes = [];

    $(this)
      .find(".size-row")
      .each(function () {
        sizes.push({
          size_id: $(this).find(".sizesDropdown").val(),
          quantity: $(this).find("input[name*='quantity']").val(),
          price: $(this).find("input[name*='price']").val(),
          images_alt_text: $(this)
            .find("input[name*='images_alt_text']")
            .map((_, el) => el.value)
            .get(),
          images_display_order: $(this)
            .find("input[name*='images_display_order']")
            .map((_, el) => el.value)
            .get(),
        });
      });

    variants.push({ color_id, sizes });
  });

  return variants;
}
