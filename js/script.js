let wwg = "White Wolfys Game";
let copy = "&copy;2023 - " + wwg;


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

class Navigation{
   constructor(){
      this.page = new Array();

      this.AddPage("Startseite", "index.html");
      this.AddPage("Werdegang", "werdegang.html");
      this.AddPage("Projektliste", "projektliste.html");
      //this.AddPage("Kenntnisse", "kenntnisse.html");
   }

   AddPage(pagename, url){
      this.page.push({name:pagename, url:url});
   }

   BuildNav(currentPage){
      let html = '<ul class="nav nav-tabs justify-content-center">';
      for(let i = 0; i < this.page.length; i++){
         html += '   <li class="nav-item">';
         if(currentPage == this.page[i].name){
            html += '      <a class="nav-link active" aria-current="page" href="' + this.page[i].url + '">' + this.page[i].name + '</a>';
         }
         else {
            html += '      <a class="nav-link" href="' + this.page[i].url + '">' + this.page[i].name + '</a>';
         }
         html += '   </li>';
      }

      /*
      html += '      <li class="nav-item dropdown">';
      html += '      <a class="nav-link dropdown-toggle" data-bs-toggle="dropdown" href="#" role="button" aria-expanded="false">Links</a>';
      html += '      <ul class="dropdown-menu">'
      html += '         <li><a class="dropdown-item" href="#">Action</a></li>';
      html += '         <li><hr class="dropdown-divider"></li>';
      html += '         <li><a class="dropdown-item" href="#">Separated link</a></li>';
      html += '      </ul>';
      html += '   </li>';
      html += '   <li class="nav-item">';
      html += '      <a class="nav-link disabled">Disabled</a>';
      html += '   </li>';
      */

      html += '</ul>';

      return html;
   }
}