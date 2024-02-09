<?php
require 'game_administration_class.php';

$lp = new YoutubeLetsPlay();
$games = $lp->GetWishlist();

$maxToShow = 10;


function toGermanDate($mysqlDate){
   $newDate = $mysqlDate;
   
   if(strlen($mysqlDate ?? '') == 10){
      $newDate = substr($mysqlDate, 8, 2) . "." . substr($mysqlDate, 5, 2) . "." . substr($mysqlDate, 0, 4);
   }
   return $newDate;
}

function GetPlayedGametitle($g){
   return ($g->game->is_collection) ? $g->collection->title : $g->game->title;
}

for($i = 0; $i < count($games); $i++){
   if($i < $maxToShow){
      ?>
      <div class="row d-none d-sm-flex">
      
         <div class="col-2 col-xl-1 col-md-2">
            <?=$games[$i]->votes?> Votes
         </div>

         <div class="col-10 col-xl-11 col-md-10">
            <?=GetPlayedGametitle($games[$i])?>
            <span class="badge bg-secondary"><?=$games[$i]->game->plattform->short_desc?></span>
            <?php
            if($games[$i]->game->is_digital){
               ?>
               <span class="badge bg-secondary">Digital</span>
               <?php
            }

            if($games[$i]->game->is_collection){
               ?>
               <span class="text-secondary">[<?=$games[$i]->game->title?>]</span>
               <?php
            }
            ?>
         </div>

         <div class="col-12 d-md-none"><br></div>

      </div>

      <?php
   }
   else{
      if($i==$maxToShow){
         ?>
         <br />

         <div class="accordion">
            <div class="accordion-item">
               <h2 id="heading-<?=$i?>" class="accordion-header">
                  <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse<?=$i?>" aria-expanded="true" aria-controls="collapse<?=$i?>">
                     Weitere Titel...
                  </button>               
               </h2>
               <div id="collapse<?=$i?>" class="accordion-collapse collapse" aria-labelledby="heading-<?=$i?>" data-bs-parent="#accordionExample">
                  <div class="accordion-body">


            <br />
         <?php
      }
      ?>
      
      <div class="row d-none d-sm-flex">
      
         <div class="col-2 col-xl-1 col-md-2">
            <?=$games[$i]->votes?> Votes
         </div>

         <div class="col-10 col-xl-11 col-md-10">
            <?=GetPlayedGametitle($games[$i])?>
            <span class="badge bg-secondary"><?=$games[$i]->game->plattform->short_desc?></span>
            <?php
            if($games[$i]->game->is_digital){
               ?>
               <span class="badge bg-secondary">Digital</span>
               <?php
            }

            if($games[$i]->game->is_collection){
               ?>
               <span class="text-secondary">[<?=$games[$i]->game->title?>]</span>
               <?php
            }
            ?>
         </div>

         <div class="col-12 d-md-none"><br></div>

      </div>

      <?php
      if($i==count($games)){
         ?>
                  </div>
               </div>
            </div>
         </div>
         <?php
      }
      ?>

      <?php
   }
   ?>


   <div class="row d-sm-none">
      <!-- Mobile Darstellung -->
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
                  <?=$games[$i]->votes?> Votes
               </div>
               <div class="card-footer">
                  <?php
                  if($games[$i]->game->is_collection){
                     ?>
                     <span class="text-secondary"><?=$games[$i]->game->title?></span>
                     <?php
                  }
                  ?>
               </div>
            </div>
         </div>
      </div>
   </div>

   <?php
}


?>



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