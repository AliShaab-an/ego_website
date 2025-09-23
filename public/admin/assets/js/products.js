$("#productForm").on("submit", function (e) {
  e.preventDefault();

  let formData = new FormData(this);

  for (let [key, value] of formData.entries()) {
    console.log(key, value);
  }

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
        $("#mainImagePreview").empty();
        $("#extraImagesContainer").empty();
      } else {
        alert(res.message || "Error adding product");
      }
    },
    error: function (xhr, status, error) {
      console.error("AJAX Error:", status, error);
      console.error("Response Text:", xhr.responseText);
      alert("Server error: " + xhr.responseText);
    },
  });
});
