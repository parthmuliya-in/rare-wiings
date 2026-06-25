document.addEventListener("DOMContentLoaded", function() {
  // Find all images with the "main-img" class
  const images = document.querySelectorAll(".main-img");

  images.forEach(function(img) {
    img.addEventListener("click", function() {
      const id = this.id.replace("mainImage", "");
      const modal = document.getElementById("fullscreenModal" + id);
      const modalImg = document.getElementById("fullscreenImg" + id);
      const closeBtn = modal.querySelector(".close-modal");

      // Set image source and show modal
      modal.style.display = "flex";
      modalImg.src = this.src;

      // Close on clicking ×
      closeBtn.onclick = function() {
        modal.style.display = "none";
      };

      // Close when clicking outside image
      modal.onclick = function(e) {
        if (e.target === modal) {
          modal.style.display = "none";
        }
      };
    });
  });
});