<?php
require_once 'game_administration_class.php';

$administration = new GameAdministration();
$administration->GetDiagramOfGamesOnPlattform();
$administration->GetDiagramOfPayedByYear();

?>
<br>
<p>kann geschlossen werden.</p>