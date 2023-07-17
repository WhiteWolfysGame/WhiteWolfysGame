// When the user scrolls down 20px from the top of the document, show the button
window.onscroll = function () {
   scrollFunction();
};

function scrollFunction() {
   let backToTop = document.getElementById("back_to_top");
   let scrollDistance = 150;

   if (document.body.scrollTop > scrollDistance || document.documentElement.scrollTop > scrollDistance) {
      backToTop.style.display = "block";
   }
   else {
      backToTop.style.display = "none";
   }
}
