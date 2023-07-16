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

      this.AddPage("Startseite", "index.html", "", "", true);
      this.AddPage("Werdegang", "werdegang.html", "", "", true);
      this.AddPage("Projektliste", "projektliste.html", "", "", true);
      //this.AddPage("Kenntnisse", "kenntnisse.html", "", "", true);

      this.AddPage("Hobbies", "#", "Wolf", "wolf.html", false);
      this.AddPage("Hobbies", "#", "---");
      this.AddPage("Hobbies", "#", "YouTube", "youtube.html", true);
      this.AddPage("Hobbies", "#", "Twitch", "twitch.html", false);
   }

   AddPage(pagename, url, subpagename, subpageurl, enabled){
      // Gleiche Pagenames werden zusammengeführt, sofern diese existieren
      var page = this.page.find(o => o.name === pagename);

      if (page != null)
         page.subpages.push({name:subpagename, url:subpageurl, enabled:enabled});
      else
         this.page.push({name:pagename, url:url, subpages:[{name:subpagename, url:subpageurl, enabled:enabled}], enabled:enabled});
   }

   BuildNav(currentPage, currentSubpage){
      let html = '<ul class="nav nav-tabs justify-content-center">';
      for(let i = 0; i < this.page.length; i++){
         if(this.page[i].subpages.length > 1){
            html += this.#DropdownNavItem(currentPage, this.page[i], currentSubpage);
         }
         else{
            html += this.#SimpleNavItem(currentPage, this.page[i]);
         }
      }

      /*
      html += '   <li class="nav-item">';
      html += '      <a class="nav-link disabled">Disabled</a>';
      html += '   </li>';
      */

      html += '</ul>';

      return html;
   }

   #DropdownNavItem(currentPage, page, currentSubpage){
      let html = '<li class="nav-item dropdown">';
      if(currentPage == page.name){
         html += '   <a class="nav-link dropdown-toggle active" data-bs-toggle="dropdown" href="#" role="button" aria-expanded="false">' + page.name + '</a>';
      }
      else {
         html += '   <a class="nav-link dropdown-toggle" data-bs-toggle="dropdown" href="#" role="button" aria-expanded="false">' + page.name + '</a>';
      }
      html += '   <ul class="dropdown-menu">';
      for(let j = 0; j < page.subpages.length; j++){
         if(page.subpages[j].name == "---"){
            html += '      <li><hr class="dropdown-divider"></li>';
         }
         else
         {
            if(page.subpages[j].enabled){
               if(currentSubpage == page.subpages[j].name){
                  html += '   <li><a class="dropdown-item active" aria-current="page" href="' + page.subpages[j].url + '">' + page.subpages[j].name + '</a></li>';
               }
               else {
                  html += '   <li><a class="dropdown-item" href="' + page.subpages[j].url + '">' + page.subpages[j].name + '</a></li>';
               }
            }
            else {
               html += '   <li><a class="dropdown-item disabled">' + page.subpages[j].name + '</a></li>';
            }
         }
      }
      html += '   </ul>';
      html += '</li>';

      return html;
   }

   #SimpleNavItem(currentPage, page){
      let html = '<li class="nav-item">';
      if(page.enabled){
         if(currentPage == page.name){
            html += '   <a class="nav-link active" aria-current="page" href="' + page.url + '">' + page.name + '</a>';
         }
         else {
            html += '   <a class="nav-link" href="' + page.url + '">' + page.name + '</a>';
         }
      }
      else {
         html += '   <a class="nav-link disabled">' + page.name + '</a>';
      }
      html += '</li>';

      return html;
   }
}