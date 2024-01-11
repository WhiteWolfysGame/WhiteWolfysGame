<?php
require 'game_administration_class.php';

$administration = new Genre();
?>

<?php
if(isset($_GET['action'])){
   if($_GET['action'] == "new"){
   ?>
      <form method="post" action="?action=add">
         <fieldset>
            <legend>Neues Genre hinzufügen</legend>
            <label for="description">Beschreibung:</label>
            <div class="input-group mb-3">
               <input class="form-control" type="text" name="description" id="description" required>
               <input class="btn btn-primary" type="submit" value="Hinzufügen">
            </div>
         </fieldset>
      </form>
      <?php
   }
   if($_GET['action'] == "add"){
      $administration->AddGenre($_POST['description']);
      ?>
      <div class="callout callout-info">
         Neue Genre hinzugefügt
      </div>
      <?php
   }
}
?>

<?php
$genre = $administration->GetGenreList();
?>

<h2>Liste verfügbarer Genres</h2>
<table class="table table table-hover table-dark table-sm">
   <tr>
      <th>ID</th>
      <th>Beschreibung</th>
   </tr>
   <?php
   for($row = 0; $row < count($genre); $row++){
      ?>
      <tr>
         <td><?=$genre[$row]->id?></td>
         <td><?=$genre[$row]->description?></td>
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