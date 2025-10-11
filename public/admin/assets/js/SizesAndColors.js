$(document).ready(function () {
  $("#addColorBtn").on("click", () =>
    $("#addColorModal").removeClass("hidden")
  );
  $("#closeColorModal").on("click", () =>
    $("#addColorModal").addClass("hidden")
  );

  $("#addSizeBtn").on("click", () => $("#addSizeModal").removeClass("hidden"));
  $("#closeSizeModal").on("click", () => $("#addSizeModal").addClass("hidden"));

  $("#addColorForm").on("submit", function (e) {
    e.preventDefault();
    $(".color-message").remove();

    const name = $.trim($(this).find('input[name="name"]').val());
    const hex = $.trim($(this).find('input[name="hex_code"]').val());

    if (name === "" || hex === "") {
      $(this).prepend(`
        <div class="color-message bg-red-100 text-red-600 text-sm p-2 rounded mb-2">
          Please fill in both color name and hex code.
        </div>
      `);
      fadeOutMessages();
      return;
    }

    // ---- AJAX Request ----
    $.ajax({
      url: "/Ego_website/public/admin/api/add-color.php",
      type: "POST",
      data: $(this).serialize(),
      dataType: "json",
      success: function (res) {
        $(".color-message").remove();

        if (res.status === "success") {
          $("#addColorForm").prepend(`
            <div class="color-message bg-green-100 text-green-700 text-sm p-2 rounded mb-2">
              Color added successfully!
            </div>
          `);
          $("#addColorForm")[0].reset();
          fadeOutMessages();
          setTimeout(() => $("#addColorModal").addClass("hidden"), 1000);
          loadColors();
        } else {
          $("#addColorForm").prepend(`
            <div class="color-message bg-red-100 text-red-600 text-sm p-2 rounded mb-2">
              ${res.message || "Error adding color."}
            </div>
          `);
          fadeOutMessages();
        }
      },
      error: function (xhr, status, error) {
        console.log("AJAX error:", error);
        console.log("Response text:", xhr.responseText);
        alert("Server error: " + xhr.status + " " + xhr.statusText);
        $(".color-message").remove();
        $("#addColorForm").prepend(`
          <div class="color-message bg-red-100 text-red-600 text-sm p-2 rounded mb-2">
            Server error while adding color.
          </div>
        `);
        fadeOutMessages();
      },
    });
  });

  $("#addSizeForm").on("submit", function (e) {
    e.preventDefault();
    $(".size-message").remove(); // remove previous messages

    const name = $.trim($(this).find('input[name="name"]').val());
    const type = $.trim($(this).find('input[name="type"]').val());

    // ---- Front-end validation ----
    if (name === "" || type === "") {
      $(this).prepend(`
        <div class="size-message bg-red-100 text-red-600 text-sm p-2 rounded mb-2">
          Please fill in both size name and type.
        </div>
      `);
      fadeOutMessages();
      return;
    }

    // ---- AJAX Request ----
    $.ajax({
      url: "/Ego_website/public/admin/api/add-size.php",
      type: "POST",
      data: $(this).serialize(),
      dataType: "json",
      success: function (res) {
        $(".size-message").remove();

        if (res.status === "success") {
          $("#addSizeForm").prepend(`
            <div class="size-message bg-green-100 text-green-700 text-sm p-2 rounded mb-2">
              Size added successfully!
            </div>
          `);
          $("#addSizeForm")[0].reset();
          fadeOutMessages();
          setTimeout(() => $("#addSizeModal").addClass("hidden"), 1000);
          loadSizes();
        } else {
          $("#addSizeForm").prepend(`
            <div class="size-message bg-red-100 text-red-600 text-sm p-2 rounded mb-2">
              ${res.message || "Error adding size."}
            </div>
          `);
          fadeOutMessages();
        }
      },
      error: function () {
        $(".size-message").remove();
        $("#addSizeForm").prepend(`
          <div class="size-message bg-red-100 text-red-600 text-sm p-2 rounded mb-2">
            Server error while adding size.
          </div>
        `);
        fadeOutMessages();
      },
    });
  });

  // ===================== LOAD COLORS =====================
  window.loadColors = function () {
    $.ajax({
      url: "/Ego_website/public/admin/api/list-colors.php",
      type: "GET",
      dataType: "json",
      success: function (res) {
        const tbody = $("#colorTableBody");
        tbody.empty();

        if (
          res.status === "success" &&
          Array.isArray(res.data) &&
          res.data.length
        ) {
          res.data.forEach((color, i) => {
            tbody.append(`
              <tr class="text-center border-b border-gray-300">
                <td class="py-4">${i + 1}</td>
                <td>${color.name}</td>
                <td>${color.hex_code}</td>
                <td><span class="inline-block w-6 h-6 rounded border border-brand" style="background-color:${
                  color.hex_code
                }"></span></td>
                <td class="flex justify-center gap-2 py-4">
                  <button class="text-blue-500 hover:underline editColorBtn" data-id="${
                    color.id
                  }">Edit</button>
                  <button class="text-red-500 hover:underline deleteColorBtn" data-id="${
                    color.id
                  }">Delete</button>
                </td>
              </tr>
            `);
          });
        } else {
          tbody.append(`
            <tr><td colspan="6" class="text-center py-4 text-gray-500">No colors found.</td></tr>
          `);
        }

        $("#totalColors").text(res.data?.length || 0);
      },
    });
  };

  // ===================== LOAD SIZES =====================
  window.loadSizes = function () {
    $.ajax({
      url: "/Ego_website/public/admin/api/list-sizes.php",
      type: "GET",
      dataType: "json",
      success: function (res) {
        const tbody = $("#sizeTableBody");
        tbody.empty();

        if (
          res.status === "success" &&
          Array.isArray(res.data) &&
          res.data.length
        ) {
          res.data.forEach((size, i) => {
            tbody.append(`
              <tr class="text-center border-b border-gray-300">
                <td class="py-4">${i + 1}</td>
                <td>${size.name}</td>
                <td>${size.type}</td>
                <td class="flex justify-center gap-2 py-4">
                  <button class="text-blue-500 hover:underline editSizeBtn" data-id="${
                    size.id
                  }">Edit</button>
                  <button class="text-red-500 hover:underline deleteSizeBtn" data-id="${
                    size.id
                  }">Delete</button>
                </td>
              </tr>
            `);
          });
        } else {
          tbody.append(`
            <tr><td colspan="5" class="text-center py-4 text-gray-500">No sizes found.</td></tr>
          `);
        }

        $("#totalSizes").text(res.data?.length || 0);
      },
    });
  };

  // ===================== UTILITY =====================
  function fadeOutMessages() {
    setTimeout(() => {
      $(".color-message, .size-message").fadeOut(400, function () {
        $(this).remove();
      });
    }, 2500);
  }

  // Initial load
  loadColors();
  loadSizes();
});

$(document).ready(function () {
  // ===================== OPEN & CLOSE EDIT MODAL =====================
  $(document).on("click", ".editColorBtn", function () {
    const id = $(this).data("id");
    const row = $(this).closest("tr");
    const name = row.find("td:nth-child(2)").text().trim();
    const hex = row.find("td:nth-child(3)").text().trim();

    $("#editColorId").val(id);
    $("#editColorName").val(name);
    $("#editColorHex").val(hex);

    $("#editColorModal").removeClass("hidden");
  });

  $("#closeEditColorModal").on("click", function () {
    $("#editColorModal").addClass("hidden");
  });

  // ===================== UPDATE COLOR =====================
  $("#editColorForm").on("submit", function (e) {
    e.preventDefault();
    $(".edit-message").remove();

    const id = $("#editColorId").val().trim();
    const name = $("#editColorName").val().trim();
    const hex = $("#editColorHex").val().trim();

    // Front-end validation
    if (name === "" || hex === "") {
      $("#editColorForm").prepend(`
        <div class="edit-message bg-red-100 text-red-600 text-sm p-2 rounded mb-2">
          Please fill in both color name and hex code.
        </div>
      `);
      fadeOutMessages();
      return;
    }

    $.ajax({
      url: "/Ego_website/public/admin/api/update-color.php",
      type: "POST",
      data: $(this).serialize(),
      dataType: "json",
      success: function (res) {
        $(".edit-message").remove();

        if (res.status === "success") {
          $("#editColorForm").prepend(`
            <div class="edit-message bg-green-100 text-green-700 text-sm p-2 rounded mb-2">
              ${res.message}
            </div>
          `);
          fadeOutMessages();
          setTimeout(() => {
            $("#editColorModal").addClass("hidden");
            loadColors();
          }, 1000);
        } else {
          $("#editColorForm").prepend(`
            <div class="edit-message bg-red-100 text-red-600 text-sm p-2 rounded mb-2">
              ${res.message || "Error updating color."}
            </div>
          `);
          fadeOutMessages();
        }
      },
      error: function () {
        $(".edit-message").remove();
        $("#editColorForm").prepend(`
          <div class="edit-message bg-red-100 text-red-600 text-sm p-2 rounded mb-2">
            Server error while updating color.
          </div>
        `);
        fadeOutMessages();
      },
    });
  });

  // ===================== UTILITIES =====================
  function fadeOutMessages() {
    setTimeout(() => {
      $(".edit-message").fadeOut(400, function () {
        $(this).remove();
      });
    }, 2500);
  }

  function showToast(message, type = "success") {
    const bg =
      type === "success"
        ? "bg-green-100 text-green-700"
        : "bg-red-100 text-red-600";
    const toast = $(`
      <div class="fixed top-4 right-4 ${bg} p-3 rounded shadow text-sm z-[9999]">${message}</div>
    `);
    $("body").append(toast);
    setTimeout(() => toast.fadeOut(500, () => toast.remove()), 2500);
  }
});

$(document).ready(function () {
  // ===================== OPEN EDIT SIZE MODAL =====================
  $(document).on("click", ".editSizeBtn", function () {
    const id = $(this).data("id");
    const row = $(this).closest("tr");
    const name = row.find("td:nth-child(2)").text().trim();
    const type = row.find("td:nth-child(3)").text().trim();

    $("#editSizeId").val(id);
    $("#editSizeName").val(name);
    $("#editSizeType").val(type);

    $("#editSizeModal").removeClass("hidden");
  });

  $("#closeEditSizeModal").on("click", function () {
    $("#editSizeModal").addClass("hidden");
  });

  // ===================== UPDATE SIZE =====================
  $("#editSizeForm").on("submit", function (e) {
    e.preventDefault();
    $(".edit-size-message").remove();

    const id = $("#editSizeId").val().trim();
    const name = $("#editSizeName").val().trim();
    const type = $("#editSizeType").val().trim();

    if (name === "" || type === "") {
      $("#editSizeForm").prepend(`
        <div class="edit-size-message bg-red-100 text-red-600 text-sm p-2 rounded mb-2">
          Please fill in both size name and type.
        </div>
      `);
      fadeOutMessages();
      return;
    }

    $.ajax({
      url: "/Ego_website/public/admin/api/update-size.php",
      type: "POST",
      data: $(this).serialize(),
      dataType: "json",
      success: function (res) {
        $(".edit-size-message").remove();

        if (res.status === "success") {
          $("#editSizeForm").prepend(`
            <div class="edit-size-message bg-green-100 text-green-700 text-sm p-2 rounded mb-2">
              ${res.message}
            </div>
          `);
          fadeOutMessages();
          setTimeout(() => {
            $("#editSizeModal").addClass("hidden");
            loadSizes();
          }, 1000);
        } else {
          $("#editSizeForm").prepend(`
            <div class="edit-size-message bg-red-100 text-red-600 text-sm p-2 rounded mb-2">
              ${res.message || "Error updating size."}
            </div>
          `);
          fadeOutMessages();
        }
      },
      error: function () {
        $(".edit-size-message").remove();
        $("#editSizeForm").prepend(`
          <div class="edit-size-message bg-red-100 text-red-600 text-sm p-2 rounded mb-2">
            Server error while updating size.
          </div>
        `);
        fadeOutMessages();
      },
    });
  });

  // ===================== UTILITIES =====================
  function fadeOutMessages() {
    setTimeout(() => {
      $(".edit-size-message").fadeOut(400, function () {
        $(this).remove();
      });
    }, 2500);
  }
});

$(document).ready(function () {
  let deleteType = ""; // "color" or "size"
  let deleteId = null;
  let deleteName = "";

  // ---------- OPEN CONFIRM DELETE MODAL ----------
  $(document).on("click", ".deleteColorBtn, .deleteSizeBtn", function () {
    deleteId = $(this).data("id");
    deleteType = $(this).hasClass("deleteColorBtn") ? "color" : "size";
    deleteName = $(this).closest("tr").find("td:nth-child(2)").text().trim();

    const itemLabel = deleteType === "color" ? "color" : "size";
    $("#confirmDeleteText").html(
      `Are you sure you want to delete <span class="font-semibold">${deleteName}</span> ${itemLabel}?`
    );
    $("#confirmDeleteModal").removeClass("hidden");
  });

  // ---------- CANCEL ----------
  $("#cancelDeleteBtn").on("click", function () {
    $("#confirmDeleteModal").addClass("hidden");
    resetDeleteVars();
  });

  // ---------- CONFIRM DELETE ----------
  $("#confirmDeleteBtn").on("click", function () {
    if (!deleteId || !deleteType) return;

    const endpoint =
      deleteType === "color"
        ? "/Ego_website/public/admin/api/delete-color.php"
        : "/Ego_website/public/admin/api/delete-size.php";

    $.ajax({
      url: endpoint,
      type: "POST",
      data: { id: deleteId },
      dataType: "json",
      success: function (res) {
        $("#confirmDeleteModal").addClass("hidden");
        if (res.status === "success") {
          showToast(
            `${capitalize(deleteType)} deleted successfully.`,
            "success"
          );
          if (deleteType === "color") loadColors();
          else loadSizes();
        } else {
          showToast(res.message || `Error deleting ${deleteType}.`, "error");
        }
      },
      error: function () {
        $("#confirmDeleteModal").addClass("hidden");
        showToast(`Server error while deleting ${deleteType}.`, "error");
      },
      complete: function () {
        resetDeleteVars();
      },
    });
  });

  // ---------- UTILITIES ----------
  function resetDeleteVars() {
    deleteType = "";
    deleteId = null;
    deleteName = "";
  }

  function showToast(message, type = "success") {
    const bg =
      type === "success"
        ? "bg-green-100 text-green-700"
        : "bg-red-100 text-red-600";
    const toast = $(`
      <div class="fixed top-4 right-4 ${bg} p-3 rounded shadow text-sm z-[9999]">${message}</div>
    `);
    $("body").append(toast);
    setTimeout(() => toast.fadeOut(500, () => toast.remove()), 2500);
  }

  function capitalize(str) {
    return str.charAt(0).toUpperCase() + str.slice(1);
  }
});
