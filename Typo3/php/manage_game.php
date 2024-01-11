<?php
require 'game_administration_class.php';

$administration = new Game();
$plattform = new Plattform();
$genre = new Genre();
?>

<?php
if(isset($_GET['action'])){
   if($_GET['action'] == "new"){
   ?>
      <form method="post" action="?action=add">
         <fieldset class="row g-3">
            <legend>Neues Game hinzufügen</legend>
            <div class="col-md-8">
               <label class="form-label" for="title">Titel:</label>
               <input class="form-control" type="text" name="title" id="title" required>
            </div>
            <div class="col-md-4">
               <label class="form-label" for="plattform">Plattform:</label>
               <select class="form-select" name="plattform" id="plattform" required>
                  <?php
                  $plattformlist = $plattform->GetPlattformList();
                  for($i = 0; $i < count($plattformlist); $i++){
                     echo '<option value="'.$plattformlist[$i]->id.'">'.$plattformlist[$i]->description.'</option>';
                  }
                  ?>
               </select>
            </div>
            <div class="col-md-4">
               <label class="form-label" for="release_date">Veröffentlichungsdatum:</label>
               <input class="form-control form-control-date" type="date" name="release_date" id="release_date" required>
            </div>
            <div class="col-md-4">
               <label class="form-label" for="purchase_date">Kaufdatum:</label>
               <input class="form-control form-control-date" type="date" name="purchase_date" id="purchase_date">
            </div>
            <div class="col-md-4">
               <label class="form-label" for="purchase_price">Einkaufspreis:</label>
               <input class="form-control form-control-number" type="number" name="purchase_price" id="purchase_price" step="0.01">
            </div>
            <div class="col-md-12">
               <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" rel="stylesheet" />
               <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
               <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>

               <label class="form-label" for="genres">Genres:</label>
               <select class="form-control" name="genres[]" id="genres" multiple="multiple" required>
                  <?php
                  $genrelist = $genre->GetGenreList();
                  for($i = 0; $i < count($genrelist); $i++){
                     echo '<option value="'.$genrelist[$i]->id.'">'.$genrelist[$i]->description.'</option>';
                  }
                  ?>
               </select>

               <script>
                  $(document).ready(function () {
                     $("#genres").select2();
                  });
               </script>
            </div>
            <div class="col-md-12">
               <input class="form-check-input" type="checkbox" name="is_collection" id="is_collection">
               <label class="form-check-label" for="is_collection">Ist eine Game-Collection</label>
            </div>
            <div class="col-md-12">
               <input class="btn btn-primary" type="submit" value="Hinzufügen">
            </div>
         </fieldset>
      </form>
      <?php
   }
   if($_GET['action'] == "add"){
      //echo "<pre>";
      //print_r($_POST);
      //echo "</pre>";
      $col = isset($_POST['is_collection']) ? $_POST['is_collection'] : "";
      $result = $administration->AddGame($_POST['title'], $_POST['plattform'],
                  $_POST['release_date'], $_POST['purchase_date'],
                  $_POST['purchase_price'], $_POST['genres'], $col);
      ?>
      <div class="callout callout-info">
         <?=$result?>
      </div>
      <?php
   }
}
?>

<?php
$game = $administration->GetGamesList();
?>

<h2>Liste verfügbarer Games</h2>
<table class="table table table-hover table-dark table-sm">
   <tr>
      <th>ID</th>
      <th>Titel</th>
      <th>Plattform-ID</th>
      <th>Is Collection?</th>

      <th>Genres</th>

      <th>Veröffentlichung</th>
      <th>Einkaufdatum</th>
      <th>Einkaufpreis</th>
   </tr>
   <?php
   for($row = 0; $row < count($game); $row++){
      ?>
      <tr>
         <td><?=$game[$row]->id?></td>
         <td><?=$game[$row]->title?></td>
         <td><?=$game[$row]->plattform->short_desc?></td>
         <td><?=$game[$row]->is_collection?></td>

         <td>
            <?php
            for($i = 0; $i < count($game[$row]->genres); $i++){
               if($i > 0) echo "<br>";
               echo $game[$row]->genres[$i]->description;
            }
            //print_r($game[$row]->genres);
            ?>
         </td>

         <td><?=$game[$row]->release_date?></td>
         <td><?=$game[$row]->purchase_date?></td>
         <td style="text-align:right;"><?=$game[$row]->price?></td>
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