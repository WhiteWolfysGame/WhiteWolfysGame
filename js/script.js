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

function YoutubePlaylistFrame(playlistId, width = 560, height = 315){
   return '<iframe width="' + width + '" height="' + height + '" src="https://www.youtube.com/embed/videoseries?list=' + playlistId + '" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" allowfullscreen></iframe>';
}

function YoutubeFrame(videoId, width = 560, height = 315){
   return '<iframe width="' + width + '" height="' + height + '" src="https://www.youtube.com/embed/' + videoId + '" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" allowfullscreen></iframe>';
}