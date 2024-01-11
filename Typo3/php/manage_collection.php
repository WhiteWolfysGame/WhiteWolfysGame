<?php
require 'game_administration_class.php';

$administration = new GameCollection();
$collectionList = $administration->GetGamesDefinedAsCollection();
$plattform = new Plattform();

?>

<?php
if(isset($_GET['action'])){
   if($_GET['action'] == "new"){
      if(count($collectionList) > 0) {
      ?>
         <form method="post" action="?action=add">
            <fieldset class="row g-3">
               <legend>Neues Game in Collection hinzufügen</legend>
               <div class="col-md-6">
                  <label class="form-label" for="collection">Collection:</label>
                  <select class="form-select" name="collection" id="collection" required>
                     <?php
                     //print_r($collectionList[0]["id"]);
                     for($i = 0; $i < count($collectionList); $i++){
                        //game_id
                        echo '<option value="'.$collectionList[$i]["id"].'">'.$collectionList[$i]["title"].'</option>';
                     }
                     ?>
                  </select>
               </div>
               <div class="col-md-6">
                  <label class="form-label" for="title">Titel:</label>
                  <input class="form-control" type="text" name="title" id="title" required>
               </div>
               <div class="col-md-6">
                  <label class="form-label" for="origin_plattform">Herkunfts-Plattform:</label>
                  <select class="form-select" name="origin_plattform" id="origin_plattform" required>
                     <?php
                     $plattformlist = $plattform->GetPlattformList();
                     for($i = 0; $i < count($plattformlist); $i++){
                        echo '<option value="'.$plattformlist[$i]->id.'">'.$plattformlist[$i]->description.'</option>';
                     }
                     ?>
                  </select>
               </div>
               <div class="col-md-6">
                  <label class="form-label" for="release_date">Original-Veröffentlichungsdatum:</label>
                  <input class="form-control form-control-date" type="date" name="release_date" id="release_date"><br>
               </div>
               <div class="col-md-12">
                  <input class="btn btn-primary" type="submit" value="Hinzufügen">
               </div>
            </fieldset>
         </form>
         <?php
      }
      else {
         echo "<p>keine Collection in den Games definiert!</p>";
      }
   }
   if($_GET['action'] == "add"){
      $result = $administration->AddToCollection($_POST['title'], $_POST['origin_plattform'], $_POST['release_date'], $_POST['collection']);
      ?>
      <div class="callout callout-info">
         <?=$result?>
      </div>
      <?php
   }
}
?>

<?php
$gamecollection = $administration->GetCollectionList();
?>

<h2>Liste verfügbarer Collections</h2>
<table class="table table table-hover table-dark table-sm">
   <tr>
      <th>ID</th>
      <th>Title</th>
      <th>Origin-Plattform</th>
      <th>Origin-Release</th>
      <th>Collection</th>
   </tr>
   <?php

   /*
   echo "<pre>";
   print_r($gamecollection);
   echo "</pre>";
   */

   for($row = 0; $row < count($gamecollection); $row++){
      ?>
      <tr>
         <td><?=$gamecollection[$row]->id?></td>
         <td><?=$gamecollection[$row]->title?></td>
         <td><?=$gamecollection[$row]->origin_plattform->short_desc?></td>
         <td><?=$gamecollection[$row]->origin_release?></td>
         <td><?=$gamecollection[$row]->game_id?></td>
      </tr>
      <?php
   }
   
   ?>
</table>
<a href="?action=new">Neu</a>

<?php
/*
echo "<br><br>POST<br>";
print_r($_POST);
echo "<br><br>GET<br>";
print_r($_GET);
*/
?>