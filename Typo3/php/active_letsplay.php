<?php
require_once 'game_administration_class.php';

$ytLetsplay = new YoutubeLetsPlay();
$active = $ytLetsplay->GetActiveLetsPlays();

function GetPlayedGametitle($g){
  return ($g->game->is_collection) ? $g->collection->title : $g->game->title;
}

/*
echo "<pre>";
print_r($active);
echo "</pre>";
*/
?>

<div class="card-group gallery text-white">
  <?php
  foreach($active as $game)
  {
    ?>
    <div class="card px-1 pb-1 above">
      <div class="card-body">
        <h5 class="card-title">
          <?=GetPlayedGametitle($game)?>
        </h5>
        <h6 class="card-subtitle mb-2 text-body-secondary">
          <span class="badge bg-secondary"><?=$game->game->plattform->short_desc?></span>
          <?php
          if($game->game->is_digital){
            ?>
            <span class="badge bg-secondary">Digital</span>
            <?php
          }
          ?>
        </h6>
        <div class="card-text">
          <a href="<?=$game->playlistUrl?>" class="external-link" target="_blank">Zur Playlist</a>
        </div>
      </div>
    </div>

    <?php
  }
  ?>
</div>
