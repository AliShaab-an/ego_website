$("#productForm").on("submit", function (e) {
  e.preventDefault();

  let formData = new FormData(this);

  $.ajax({
    url: "/Ego_website/public/admin/api/add-product.php",
    type: "POST",
    data: formData,
    processData: false,
    contentType: false,
    dataType: "json",
    success: function (res) {
      if (res.status === "success") {
        alert("Product added with variants and images!");
        $("#productForm")[0].reset();
        $("#variantContainer").html(""); // clear variants
      } else {
        alert(res.message || "Error adding product");
      }
    },
    error: function () {
      alert("Server error while adding product");
    },
  });
});
