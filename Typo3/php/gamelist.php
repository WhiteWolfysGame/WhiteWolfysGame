<?php
require 'game_administration_class.php';

$administration = new GameAdministration();
$currentPage = (isset($_GET['page']) && $_GET['page'] > 0) ? $_GET['page'] : 0;
$games = $administration->GetGames($currentPage);

function toGermanDate($mysqlDate){
   $newDate = $mysqlDate;
   //print_r(strlen($mysqlDate));
   if(strlen($mysqlDate ?? '') == 10){
      $newDate = substr($mysqlDate, 8, 2) . "." . substr($mysqlDate, 5, 2) . "." . substr($mysqlDate, 0, 4);
   }
   return $newDate;
}

if(isset($_POST)){
   if(isset($_POST['game_id']) && isset($_POST['gameInCollection_id'])){
      // Spezifisches Game (gameInCollection_id) innerhalb vom Game (game_id)
      $result = $administration->VoteGame($_POST['game_id'], $_POST['gameInCollection_id']);
      ?>
      <div class="bs-callout bs-callout-info">
         <?=$result?>
      </div>
      <?php
      //echo "Hallo Spiel innerhalb einer Collection";
   }
   
   if(isset($_POST['game_id']) && isset($_POST['game_is_collection'])){
      // Gewähltes Game sowie alle Games innerhalb der Collection
      $result = $administration->VoteGame($_POST['game_id']);
      ?>
      <div class="bs-callout bs-callout-info">
         <?=$result?>
      </div>
      <?php
      //echo "Hallo ausgewähltes Spiel";
   }
}
?>

<div class="accordion">
<?php
for($i = 0; $i < count($games); $i++){
   ?>
   <div class="accordion-item">
      <h2 id="heading-<?=$i?>" class="accordion-header">
         <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse<?=$i?>" aria-expanded="true" aria-controls="collapse<?=$i?>">
            <?=$games[$i]->title?>
            &nbsp; <span class="badge bg-secondary"><?=$games[$i]->plattform->short_desc?></span>
            <?php
            if($games[$i]->is_digital){
               ?>
               &nbsp; <span class="badge bg-secondary">Digital</span>
               <?php
            }
            ?>
         </button>
      </h2>
      <div id="collapse<?=$i?>" class="accordion-collapse collapse" aria-labelledby="heading-<?=$i?>" data-bs-parent="#accordionExample">
         <div class="accordion-body">
            <p>Plattform: <?=$games[$i]->plattform->description?></p>
            <p>Release: <?=toGermanDate($games[$i]->release_date)?></p>
            <p>Kaufdatum: <?=toGermanDate($games[$i]->purchase_date)?></p>
            <p>
               <?php
               if($games[$i]->price == "0.00"){ echo "Kaufpreis: geschenkt oder unbekannt"; }
               else { echo "Kaufpreis: " . str_replace(".", ",", $games[$i]->price) . " &euro;"; }
               ?>
            </p>
            <p>Genres: 
               <?php
               for($g = 0; $g < count($games[$i]->genres); $g++){
                  if($g > 0) echo ", ";
                  echo $games[$i]->genres[$g]->description;
               }
               ?>
            </p>
            <p>
               <?php
               if($games[$i]->is_collection > 0 && isset($games[$i]->game_collection)){
                  ?>
                  Games in der Collection: 
                  <div class="card-group gallery text-white">
                  <?php
                  for($c = 0; $c < count($games[$i]->game_collection); $c++){
                     ?>
                     <div class="card px-1 pb-1 above">
                        <div class="card-body">
                           <div class="card-text">
                              <p>Game: <?=$games[$i]->game_collection[$c]->title?></p>
                              <p>Ursprungsplattform: <?=$games[$i]->game_collection[$c]->origin_plattform->description?></p>
                              <p>Original-Release: <?=toGermanDate($games[$i]->game_collection[$c]->origin_release)?></p>
                              <!-- Button für Wunschliste (Game in Collection) -->
                              <p>
                                 <form id="form-game<?=$games[$i]->id?>-gameInCollection<?=$games[$i]->game_collection[$c]->id?>" method="post" action="">
                                    <input type="hidden" name="game_id" value="<?=$games[$i]->id?>" />
                                    <input type="hidden" name="gameInCollection_id" value="<?=$games[$i]->game_collection[$c]->id?>" />
                                    <button class="btn btn-primary" type="submit">Dieses zur Let's Play Wunschliste hinzufügen</button>
                                 </form>
                              </p>
                           </div>
                        </div>
                     </div>
                     <?php
                  }
                  ?>
                  </div>
                  <?php
               }
               ?>
               <!-- Button für Wunschliste (Game) -->
               <p>
                  <form id="form-fullgame<?=$i?>" method="post" action="">
                     <input type="hidden" name="game_id" value="<?=$games[$i]->id?>" />
                     <input type="hidden" name="game_is_collection" value="<?=$games[$i]->is_collection?>" />
                     <?php
                     if($games[$i]->is_collection > 0 && isset($games[$i]->game_collection)){
                        ?>
                        <button class="btn btn-primary" type="submit">Sammlung zur Let's Play Wunschliste hinzufügen</button>
                        <?php
                     }
                     else {
                        ?>
                        <button class="btn btn-primary" type="submit">Zur Let's Play Wunschliste hinzufügen</button>
                        <?php
                     }
                     ?>
                  </form>
               </p>
            </p>
            <!--<p>
               wenn bereits lets played...
                  dann nichts anzeigen
               wenn noch nicht lets played...
                  Add to Youtube Let's Play Wishlist (evtl Count++)
            </p>-->
         </div>
      </div>
   </div>
   <?php
}
?>
</div>
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
         $pages = $administration->GetNumPages();
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