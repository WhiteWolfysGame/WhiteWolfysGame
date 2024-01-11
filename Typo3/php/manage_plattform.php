<?php
require 'game_administration_class.php';

$administration = new Plattform();
?>

<?php
if(isset($_GET['action'])){
   if($_GET['action'] == "new"){
   ?>
      <form method="post" action="?action=add">
         <fieldset>
            <legend>Neue Plattform hinzufügen</legend>
            <div class="mb-3">
               <label class="form-label" for="description">Beschreibung:</label>
               <input class="form-control" type="text" name="description" id="description" required>
            </div>
            <div class="mb-3">
               <label class="form-label" for="short_desc">Kürzel:</label>
               <input class="form-control" type="text" name="short_desc" id="short_desc" required>
            </div>
            <div class="mb-3">
               <input class="btn btn-primary" type="submit" value="Hinzufügen">
            </div>
         </fieldset>
      </form>
      <?php
   }
   if($_GET['action'] == "add"){
      $result = $administration->AddPlattform($_POST['description'], $_POST['short_desc']);
      ?>
      <div class="callout callout-info">
         <?=$result?>
      </div>
      <?php
   }
}
?>

<?php
$plattform = $administration->GetPlattformList();
?>

<h2>Liste verfügbarer Plattformen</h2>
<table class="table table table-hover table-dark table-sm">
   <tr>
      <th>ID</th>
      <th>Beschreibung</th>
      <th>Kürzel</th>
   </tr>
   <?php
   for($row = 0; $row < count($plattform); $row++){
      ?>
      <tr>
         <td><?=$plattform[$row]->id?></td>
         <td><?=$plattform[$row]->description?></td>
         <td><?=$plattform[$row]->short_desc?></td>
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