<?php

use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Core\Core\Bootstrap;


// Ausgabe mit print_r
//print_r(GeneralUtility::implodeArrayForUrl('', $params));




$currentPage = (isset($_GET['page']) && $_GET['page'] > 0) ? $_GET['page'] : 0;
$pages; //$administration->GetNumPages();
$maxVisibleLinks = 2;

$modMaxVisibleLinks = ceil($maxVisibleLinks / 2); // für definition der Pages bei aktuellem Link (links und rechts v. aktuellem page)
$lastPageLinkId = $pages - $maxVisibleLinks;
?>


<pre>
   <?php
   //print_r($GLOBALS['TYPO3_CONF_VARS']);
   //phpinfo();
   ?>
</pre>




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
