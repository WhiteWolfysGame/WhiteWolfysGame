<?php
require_once 'game_administration_class.php';

$administration = new GameAdministration();
$numbers = $administration->GetNumberOfGames();

?>

<div class="card-group gallery text-white">
  <div class="card px-1 pb-1 above">
    <div class="card-body">
      <h5 class="card-title"></h5>
      <h6 class="card-subtitle mb-2 text-body-secondary"></h6>
      <div class="card-text">Anzahl Games: <?=$numbers['games']?></div>
      <div class="card-text">davon Collections: <?=$numbers['collections']?></div>
      <div class="card-text">Games in Collections: <?=$numbers['games_in_collections']?></div>
      <div class="card-text">Gesamtanzahl Games: <?=$numbers['sum']?></div>
    </div>
  </div>
</div>
