const images = document.querySelectorAll('.myImg');

images.forEach((img) => {
  const modal = img.nextElementSibling; 
  const modalImg = modal.querySelector('.modal-content');
  const captionText = modal.querySelector('.caption');
  const closeBtn = modal.querySelector('.close');

  img.onclick = function () {
    modal.style.display = "block";
    modalImg.src = this.src;
    captionText.innerHTML = this.alt;
  }

  closeBtn.onclick = function () {
    modal.style.display = "none";
  }

  window.addEventListener('click', function (event) {
    if (event.target == modal) {
      modal.style.display = "none";
    }
  });
});
