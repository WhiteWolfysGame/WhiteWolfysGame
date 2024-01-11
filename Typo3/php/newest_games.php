<?php
require_once 'game_administration_class.php';

$administration = new GameAdministration();
$games = $administration->GetNewestGames(3);

function toGermanDate($mysqlDate){
  $newDate = $mysqlDate;
  //print_r(strlen($mysqlDate));
  if(strlen($mysqlDate ?? '') == 10){
    $newDate = substr($mysqlDate, 8, 2) . "." . substr($mysqlDate, 5, 2) . "." . substr($mysqlDate, 0, 4);
  }
  return $newDate;
}
?>


<div class="card-group gallery text-white">
<?php
  for($i = 0; $i < count($games); $i++){
    ?>

    <div class="card px-1 pb-1 above">
      <div class="card-body">
        <h5 class="card-title"><?=$games[$i]->title?></h5>
        <h6 class="card-subtitle mb-2 text-body-secondary"><?=$games[$i]->plattform->description?></h6>
        <div class="card-text">gekauft am <?=toGermanDate($games[$i]->purchase_date)?></div>
      </div>
    </div>
    <?php
  }
?>
</div>
