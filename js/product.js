

  //== Get Product detail in pop up mode
    $(document).ready(function () {
    // Open modal + load product
    $(".view-details").click(function () {
      var productId = $(this).data("id");
      var myModal = new bootstrap.Modal(document.getElementById('productModal'));
      myModal.show();
      $("#productDetails").html("<p class='text-center'>Loading...</p>");
      $.post("", { ajax: "getProduct", id: productId }, function (data) {
        $("#productDetails").html(data);
      }).fail(function () {
        $("#productDetails").html("<p class='text-danger'>Failed to load product details.</p>");
      });
    });
    // Add to Cart (AJAX)
    $(document).on("submit", "#modalAddToCart", function (e) {
      e.preventDefault(); // stop page reload

      $.ajax({
        url: $(this).attr("action"),   // use form action (add_to_cart.php)
        method: $(this).attr("method"), // use form method (POST)
        data: $(this).serialize(),
        success: function (res) {
          alert("✅ Added to Cart!");
          // optional: update cart count in header without reload
          // $("#cartCount").text(res.newCount);
        },
        error: function () {
          alert("❌ Failed to add to cart.");
        }
      });
    });
    // Handle Wishlist AJAX
    $(document).on("submit", ".wishlistForm", function (e) {
      e.preventDefault(); // stop normal form submit
      $.ajax({
        url: $(this).attr("action"),    // wishlist.php
        method: $(this).attr("method"), // POST
        data: $(this).serialize(),
        success: function (res) {
          try {
            let response = JSON.parse(res);
            if (response.success) {
              alert("💖 " + response.message);
              // optionally update wishlist count
              // $("#wishlistCount").text(response.count);
            } else {
              alert("⚠️ " + response.message);
            }
          } catch (e) {
            alert("💖 Added to Wishlist!");
          }
        },
        error: function () {
          alert("❌ Failed to add to wishlist.");
        }
      });
    });
  });
  // Add simple JS to highlight selected box
  const radios = document.querySelectorAll('input[name="product_size"]');
  radios.forEach(radio => {
    radio.addEventListener('change', () => {
      document.querySelectorAll('label').forEach(lbl => lbl.style.background = '');
      radio.parentElement.style.background = '#d1f7d6'; // highlight selected
    });
  });
  
