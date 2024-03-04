<?php
require 'game_administration_class.php';

//$administration = new GameAdministration();
$currentPage = (isset($_GET['page']) && is_numeric($_GET['page']) && $_GET['page'] > 0) ? $_GET['page'] : 0;
//$games = $administration->GetGames($currentPage);

$lp = new YoutubeLetsPlay();
$games = $lp->GetLetsPlayed($currentPage);


function toGermanDate($mysqlDate){
   $newDate = $mysqlDate;
   //print_r(strlen($mysqlDate));
   if(strlen($mysqlDate ?? '') == 10){
      $newDate = substr($mysqlDate, 8, 2) . "." . substr($mysqlDate, 5, 2) . "." . substr($mysqlDate, 0, 4);
   }
   return $newDate;
}

function GetPlayedGametitle($g){
   return ($g->game->is_collection) ? $g->collection->title : $g->game->title;
}

for($i = 0; $i < count($games); $i++){
   ?>

   <div class="row d-none d-sm-flex">
   
      <div class="col-4">
         <?=GetPlayedGametitle($games[$i])?>
         <span class="badge bg-secondary"><?=$games[$i]->game->plattform->short_desc?></span>
         <?php
         if($games[$i]->game->is_digital){
            ?>
            <span class="badge bg-secondary">Digital</span>
            <?php
         }
         ?>
      </div>

      <div class="col-2">
         <a href="<?=$games[$i]->playlistUrl?>" class="external-link" target="_blank">Link zur Playlist</a>
      </div>

      <div class="col-3">
         Gestartet am <?=toGermanDate($games[$i]->started)?>
      </div>

      <div class="col-3">
         <?php
         if(isset($games[$i]->ended)){
            ?>
            Beendet am <?=toGermanDate($games[$i]->ended)?>
            <?php
         }
         else {
            ?>
            Let's Play aktiv
            <?php
         }
         ?>
      </div>

      <div class="col-12 d-lg-none"><br></div>

   </div>

   <div class="row d-sm-none">
      <div class="col-12">
         <div class="card-group gallery text-white">
            <div class="card px-1 pb-1 above">
               <div class="card-header">
                  <?=GetPlayedGametitle($games[$i])?>
                  <span class="badge bg-secondary"><?=$games[$i]->game->plattform->short_desc?></span>
                  <?php
                  if($games[$i]->game->is_digital){
                     ?>
                     <span class="badge bg-secondary">Digital</span>
                     <?php
                  }
                  ?>
               </div>
               <div class="card-body">
                  Gestartet am <?=toGermanDate($games[$i]->started)?><br>
                  <?php
                  if(isset($games[$i]->ended)){
                     ?>
                     Beendet am <?=toGermanDate($games[$i]->ended)?>
                     <?php
                  }
                  else {
                     ?>
                     Let's Play aktiv
                     <?php
                  }
                  ?>
               </div>
               <div class="card-footer">
                  <a href="<?=$games[$i]->playlistUrl?>" class="external-link" target="_blank">Link zur Playlist</a>
               </div>
            </div>
         </div>
      </div>
   </div>

   <?php
}


?>

<br />
<div class="btn-toolbar justify-content-center" role="toolbar" aria-label="Pagination Buttongroup">
   <div class="btn-group me-2 btn-group-sm" role="group" aria-label="To First and/or Preview">
      <?php
      if($currentPage == 0){
         ?>
         <a href="?page=<?=$currentPage?>" class="btn btn-primary">Start</a>
         <?php
      }
      else {
         ?>
         <a href="?page=0" class="btn btn-outline-primary">Start</a>
         <a href="?page=<?=$currentPage-1?>" class="btn btn-outline-primary">&lt;&lt;</a>
         <?php
      }
      ?>
   </div>

   <div class="btn-group me-2 btn-group-sm" role="group" aria-label="The Pages">
      <?php
         $pages = $lp->GetNumPages();
         $maxVisibleLinks = 1;
         $modMaxVisibleLinks = ceil($maxVisibleLinks / 2); // für definition der Pages bei aktuellem Link (links und rechts v. aktuellem page)
         $lastPageLinkId = $pages - $maxVisibleLinks;

         $skippedPrevFromCurrent = 0;
         $skippedNextFromCurrent = 0;
         $done = false;
         
         for($i = 0; $i < $pages; $i++){

            if($i < $currentPage){
               // erste Pages anzeigen
               if($i < $maxVisibleLinks){
                  ?>
                  <a href="?page=<?=$i?>" class="btn btn-outline-primary"><?=($i+1)?></a>
                  <?php
               }
               else{
                  $skippedPrevFromCurrent++;
               }

               continue;
            }

            // aktuelle seite erreicht... jetzt die geskippten als Dropdown integrieren
            if($i == $currentPage){
               // geskippte Pages als Dropdown hinzufügen
               if($skippedPrevFromCurrent > 1){
                  ?>
                  <div class="btn-group">
                     <button type="button" class="btn btn-outline-primary dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">...</button>
                     <ul class="dropdown-menu">
                        <?php
                        // geskippte als Dropdown
                        for($iSkippedBefore = $skippedPrevFromCurrent-$modMaxVisibleLinks; $iSkippedBefore > 0; $iSkippedBefore--){
                           ?>
                           <li><a class="dropdown-item" href="?page=<?=$i-$iSkippedBefore-1?>"><?=($i-$iSkippedBefore)?></a></li>
                           <?php
                        }
                        ?>
                     </ul>
                  </div>
                  <?php
               }

               // jetzt noch die AKTUELLE Page anzeigen
               if($i > $maxVisibleLinks){
                  ?>
                  <a href="?page=<?=$i-1?>" class="btn btn-outline-primary"><?=($i+1-1)?></a>
                  <?php
               }
               ?>
               <a href="?page=<?=$i?>" class="btn btn-primary"><?=($i+1)?></a>
               <?php
               if($i < $lastPageLinkId-1){
                  ?>
                  <a href="?page=<?=$i+1?>" class="btn btn-outline-primary"><?=($i+1+1)?></a>
                  <?php
               }
            }

            if($i > $currentPage){

               if($i >= $lastPageLinkId){
                  // vor dem Abschluss noch die geskippten Pages als Dropdown integrieren
                  if($skippedNextFromCurrent > 1 && !$done){
                     ?>
                     <div class="btn-group">
                        <button type="button" class="btn btn-outline-primary dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">...</button>
                        <ul class="dropdown-menu">
                           <?php
                           // geskippte als Dropdown
                           for($iSkippedNext = $skippedNextFromCurrent-$modMaxVisibleLinks-1; $iSkippedNext >= 0; $iSkippedNext--){
                              ?>
                              <li><a class="dropdown-item" href="?page=<?=$i-$iSkippedNext-1?>"><?=($i-$iSkippedNext)?></a></li>
                              <?php
                           }
                           $done = true;
                           ?>
                        </ul>
                     </div>
                     <?php
                  }

                  // letzte mögliche Pages anzeigen
                  ?>
                  <a href="?page=<?=$i?>" class="btn btn-outline-primary"><?=($i+1)?></a>
                  <?php
               }
               else{
                  $skippedNextFromCurrent++;
               }

               continue;
            }
         }
      ?>
   </div>

   <div class="btn-group me-2 btn-group-sm" role="group" aria-label="To Last and/or Next">
      <?php
      if($currentPage == ($pages-1)){
         ?>
         <a href="?page=<?=($pages-1)?>" class="btn btn-primary">Letzte</a>
         <?php
      }
      else{
         ?>
         <a href="?page=<?=($currentPage+1)?>" class="btn btn-outline-primary">&gt;&gt;</a>
         <a href="?page=<?=($pages-1)?>" class="btn btn-outline-primary">Letzte</a>
         <?php
      }
      ?>
   </div>
</div>



<br/>
<?php
/*
echo "<br><br>administration-object:<br>";
echo "<pre>";
print_r($games);
echo "</pre>";
echo "<br><br>POST<br>";
print_r($_POST);
echo "<br><br>GET<br>";
print_r($_GET);
*/
?>