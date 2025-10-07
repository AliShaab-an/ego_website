$("#productForm").on("submit", function (e) {
  e.preventDefault();

  let basicData = new FormData();

  basicData.append("name", $("#name").val());
  basicData.append("description", $("#description").val());
  basicData.append("base_price", $("#base_price").val());
  basicData.append("weight", $("#weight").val());
  basicData.append("category_id", $("#category_id").val());
  if ($("#is_top").is(":checked")) basicData.append("is_top", 1);

  $.ajax({
    url: "/Ego_website/public/admin/api/add-product.php",
    type: "POST",
    data: basicData,
    processData: false,
    contentType: false,
    dataType: "json",
    success: function (res) {
      if (res.status === "success") {
        console.log("Product created: ", res);
        let productId = res.id;
        addProductVariants(productId);
      } else {
        alert("Error: ", res.message);
      }
    },
    error: function () {
      console.error("Product creation failed", xhr.responseText);
      alert("Server Error during product creation.");
    },
  });
});

function addProductVariants(productId) {
  let variants = collectVariants(); // function that gathers variant data (colors, sizes, etc.)

  $.ajax({
    url: "/Ego_website/public/admin/api/add-product-variants.php",
    type: "POST",
    data: JSON.stringify({ product_id: productId, variants: variants }),
    contentType: "application/json",
    dataType: "json",
    success: function (res) {
      console.log("Variants response:", res);
      if (res.status === "success" || res.status === "partial_success") {
        addProductImages(productId);
      } else {
        alert("Variants failed: " + res.message);
      }
    },
    error: function (xhr, status, error) {
      console.error("Variants AJAX error:", xhr.responseText);
      alert("Error adding variants.");
    },
  });
}

function addProductImages(productId) {
  let imageData = new FormData();
  imageData.append("product_id", productId);

  // append each variant image set
  $("input[type='file'][name^='variants']").each(function (i, input) {
    for (let file of input.files) {
      imageData.append("images[]", file);
    }
  });

  $.ajax({
    url: "/Ego_website/public/admin/api/add-product-images.php",
    type: "POST",
    data: imageData,
    processData: false,
    contentType: false,
    dataType: "json",
    success: function (res) {
      if (res.status === "success") {
        alert("✅ Product added successfully with all variants and images!");
        $("#productForm")[0].reset();
        $("#extraImagesContainer").empty();
      } else {
        alert("Image upload failed: " + res.message);
      }
    },
    error: function (xhr, status, error) {
      console.error("Image upload error:", xhr.responseText);
      alert("Error uploading images.");
    },
  });
}

function collectVariants() {
  let variants = [];

  $(".variant-block").each(function () {
    let v = {
      color_id: $(this).find(".color-select").val(),
      size_id: $(this).find(".size-select").val(),
      quantity: $(this).find(".quantity-input").val(),
      price: $(this).find(".price-input").val(),
      images_alt_text: [$(this).find(".alt-text-input").val()],
      images_display_order: [$(this).find(".display-order-input").val()],
    };
    variants.push(v);
  });

  return variants;
}
