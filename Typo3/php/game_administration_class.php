<?php
require_once 'database.php';

//require_once ('jpgraph-4.4.2/src/jpgraph.php');
//require_once ('jpgraph-4.4.2/src/jpgraph_bar.php');
//require_once ('jpgraph-4.4.2/src/jpgraph_line.php');
//require_once ('jpgraph-4.4.2/src/jpgraph_pie.php');

class Genre{
   public $id;
   public $description;

   private $conn;
   private $db;

   function __construct(){
      $this->db = Database::getInstance()->getConnection();
      //$db = new Database();
      //$this->conn = $db->conn;
   }

   function GetGenreList(){
      // Überprüfen, ob die Verbindung gültig ist
      if (!mysqli_ping($this->db)) {
         // Wenn die Verbindung nicht aktiv ist, Verbindung wiederherstellen
         $this->db = Database::getInstance()->getConnection();
      }

      $sql = "SELECT id, description ";
      $sql .= "FROM Genre ";
      $sql .= "ORDER BY description ASC";
      
      $result = mysqli_query($this->db, $sql);

      $genres = array();
      if (mysqli_num_rows($result) > 0) {
         // output data of each row
         while($row = mysqli_fetch_assoc($result)) {
            $genre = new Genre();
            $genre->id = $row["id"];
            $genre->description = $row["description"];

            $genres[] = $genre; // Füge das Plattform-Objekt dem Array hinzu.
         }
      }
      
      return $genres;
   }

   function AddGenre($description){
      // Überprüfen, ob die Verbindung gültig ist
      if (!mysqli_ping($this->db)) {
         // Wenn die Verbindung nicht aktiv ist, Verbindung wiederherstellen
         $this->db = Database::getInstance()->getConnection();
      }

      $sql = "SELECT * FROM Genre WHERE description = ?";
      $stmt = mysqli_prepare($this->db, $sql);

      if ($stmt) {
         mysqli_stmt_bind_param($stmt, "s", $description);
         mysqli_stmt_execute($stmt);

         $result = mysqli_stmt_get_result($stmt);
         mysqli_stmt_close($stmt);

         if(mysqli_num_rows($result) == 0){
            // Nur Daten adden, wenn die Beschreibung nicht existiert!
            $sql = "INSERT INTO Genre (description) VALUES (?)";
            $stmt = mysqli_prepare($this->db, $sql);
            
            if ($stmt) {
               mysqli_stmt_bind_param($stmt, "s", $description);
               if (mysqli_stmt_execute($stmt)) {
                  return "Neues Genre hinzugefügt";
               } else {
                  return false;
               }
               mysqli_stmt_close($stmt);
            } else {
               return false;
            }
         }
         else{
            return "Genre existiert bereits";
         }
      }
   }
}

class Plattform{
   public $id;
   public $description;
   public $short_desc;

   private $conn;
   private $db;

   function __construct(){
      $this->db = Database::getInstance()->getConnection();
      //$db = new Database();
      //$this->conn = $db->conn;
   }

   function GetPlattformList(){
      // Überprüfen, ob die Verbindung gültig ist
      if (!mysqli_ping($this->db)) {
         // Wenn die Verbindung nicht aktiv ist, Verbindung wiederherstellen
         $this->db = Database::getInstance()->getConnection();
      }

      $sql = "SELECT id, description, short_desc ";
      $sql .= "FROM Plattform ";
      $sql .= "ORDER BY description ASC";
      
      $result = mysqli_query($this->db, $sql);

      $plattforms = array();
      if (mysqli_num_rows($result) > 0) {
         // output data of each row
         while($row = mysqli_fetch_assoc($result)) {
            $plattform = new Plattform();
            $plattform->id = $row["id"];
            $plattform->description = $row["description"];
            $plattform->short_desc = $row["short_desc"];

            $plattforms[] = $plattform; // Füge das Plattform-Objekt dem Array hinzu.
         }
      }
      
      return $plattforms;
   }

   function AddPlattform($description, $short){
      // Überprüfen, ob die Verbindung gültig ist
      if (!mysqli_ping($this->db)) {
         // Wenn die Verbindung nicht aktiv ist, Verbindung wiederherstellen
         $this->db = Database::getInstance()->getConnection();
      }

      $sql = "SELECT * FROM Plattform WHERE description = ? AND short_desc = ?";
      $stmt = mysqli_prepare($this->db, $sql);

      if ($stmt) {
         mysqli_stmt_bind_param($stmt, "ss", $description, $short);
         mysqli_stmt_execute($stmt);

         $result = mysqli_stmt_get_result($stmt);
         mysqli_stmt_close($stmt);

         if(mysqli_num_rows($result) == 0){
            // Nur Daten adden, wenn die Beschreibung-Short-Konstellation nicht existiert!
            $sql = "INSERT INTO Plattform (description, short_desc) VALUES (?, ?)";
            $stmt = mysqli_prepare($this->db, $sql);
            
            if ($stmt) {
               mysqli_stmt_bind_param($stmt, "ss", $description, $short);
               if (mysqli_stmt_execute($stmt)) {
                  return "Neue Plattform hinzugefügt";
               } else {
                  return false;
               }
               mysqli_stmt_close($stmt);
            } else {
               return false;
            }
         }
         else{
            return "Plattform existiert bereits";
         }
      }
   }
}

class Game{
   public $id;
   public $title;
   public $plattform;
   public $release_date;
   public $purchase_date;
   public $price;
   public $game_collection;
   public $genres;
   public $is_collection;
   public $is_digital;
   
   private $conn;
   private $plattformList;
   private $genreList;
   private $collectionList;

   private $db;

   function __construct(){
      $this->db = Database::getInstance()->getConnection();
      //$db = new Database();
      //$this->conn = $db->conn;

      $collection = new GameCollection();
      $this->collectionList = $collection->GetCollectionList();

      $genre = new Genre();
      $this->genreList = $genre->GetGenreList();

      $plattform = new Plattform();
      $this->plattformList = $plattform->GetPlattformList();
   }

   function GetGamesList(){
      // Überprüfen, ob die Verbindung gültig ist
      if (!mysqli_ping($this->db)) {
         // Wenn die Verbindung nicht aktiv ist, Verbindung wiederherstellen
         $this->db = Database::getInstance()->getConnection();
      }

      $sql = "SELECT id, title, platform_id, released, purchased, price, is_collection, is_digital ";
      $sql .= "FROM Game ";
      $sql .= "ORDER BY title ASC";
      
      $result = mysqli_query($this->db, $sql);
      
      $games = array();
      if (mysqli_num_rows($result) > 0) {
         // output data of each row
         while($row = mysqli_fetch_assoc($result)) {
            $game = new Game();
            $game->id = $row["id"];
            $game->title = $row["title"];

            foreach ($this->plattformList as $element) {
               if ($row["platform_id"] == $element->id) {
                  $game->plattform = $element;
                  break;
               }
            }

            $game->release_date = $row["released"];
            $game->purchase_date = ($row["purchased"] == "0000-00-00") ? null : $row["purchased"];
            $game->price = $row["price"];
            $game->is_collection = $row["is_collection"];
            $game->is_digital = $row["is_digital"];

            if($row["is_collection"] != 0){
               $sqlCollection = "SELECT id, title, origin_plattform, origin_release, game_id FROM GameCollection WHERE game_id = " . $row["id"];
               $resultCollection = mysqli_query($this->db, $sqlCollection);

               while($collectionRow = mysqli_fetch_assoc($resultCollection)){
                  foreach($this->collectionList as $element){
                     if($collectionRow["id"] == $element->id){
                        //$this->debug($element);
                        $game->game_collection[] = $element;
                        break;
                     }
                  }
               }
            }

            $sqlGenre = "SELECT genre_id FROM GameGenre WHERE game_id = " . $game->id;
            $resultGenre = mysqli_query($this->db, $sqlGenre);

            while($genreRow = mysqli_fetch_assoc($resultGenre)){
               foreach ($this->genreList as $element) {
                  if ($genreRow["genre_id"] == $element->id) {
                     $game->genres[] = $element;
                     break;
                  }
               }
            }

            $games[] = $game; // Füge das Game-Objekt dem Array hinzu.
         }
      }

      return $games;
   }

   function AddGame($title, $plattform_id, $release, $purchased, $price, $genres, $is_collection, $is_digital){
      // Überprüfen, ob die Verbindung gültig ist
      if (!mysqli_ping($this->db)) {
         // Wenn die Verbindung nicht aktiv ist, Verbindung wiederherstellen
         $this->db = Database::getInstance()->getConnection();
      }

      $sql = "SELECT * FROM Game WHERE title = ? AND platform_id = ?";
      $stmt = mysqli_prepare($this->db, $sql);

      if($stmt) {
         mysqli_stmt_bind_param($stmt, "si", $title, $plattform_id);
         mysqli_stmt_execute($stmt);

         $result = mysqli_stmt_get_result($stmt);
         mysqli_stmt_close($stmt);

         if(mysqli_num_rows($result) == 0){
            $sql = "INSERT INTO Game (title, platform_id, released, purchased, price, is_collection, is_digital) ";
            $sql .= "VALUES (?, ?, ?, ?, ?, ?, ?)";
            $stmt = mysqli_prepare($this->db, $sql);

            if ($stmt) {
               $collection = ($is_collection == "on") ? 1 : 0;
               $digital = ($is_digital == "on") ? 1 : 0;
               mysqli_stmt_bind_param($stmt, "sissdii", 
                  $title, $plattform_id, $release, $purchased, $price, $collection, $digital);

               if (mysqli_stmt_execute($stmt)) {
                  // Die neue ID des eingefügten Datensatzes
                  $newlyInsertedID = mysqli_insert_id($this->db);
                  mysqli_stmt_close($stmt);

                  for($i = 0; $i < count($genres); $i++){
                     $sql = "INSERT INTO GameGenre (game_id, genre_id) VALUES(?,?);";
                     $stmt = mysqli_prepare($this->db, $sql);
                     if ($stmt) {
                        mysqli_stmt_bind_param($stmt, "ii", $newlyInsertedID, $genres[$i]);
                        mysqli_stmt_execute($stmt);
                     }
                  }

                  return "Neues Game hinzugefügt";
               } else {
                  return false;
               }
               mysqli_stmt_close($stmt);
            }
         }
         else{
            return "Game existiert bereits";
         }
      }
   }

   private function debug($data){
      echo "<pre>";
      print_r($data);
      echo "</pre>";
   }
}

class YoutubeLetsPlay{
   private $db;   
   private $letsplayed;
   private $result_limit = 10;

   function __construct(){
      $this->db = Database::getInstance()->getConnection();

      $this->LoadData();
   }

   function LoadData(){
      if (!mysqli_ping($this->db)) {
         // Wenn die Verbindung nicht aktiv ist, Verbindung wiederherstellen
         $this->db = Database::getInstance()->getConnection();
      }

      $game = new Game();
      $gamesList = $game->GetGamesList();

      $collection = new GameCollection();
      $collectionList = $collection->GetCollectionList();

      $sql = "SELECT id, game_id, game_collection_id, playlist_url, lp_started, lp_ended FROM YoutubeLetsPlay ORDER BY lp_started DESC;";
      $result = mysqli_query($this->db, $sql);

      while($letsPlayRow = mysqli_fetch_assoc($result)){
         //$this->debug($letsPlayRow);

         $lpData = new YoutubeLetsPlayData();
         $lpData->id = $letsPlayRow['id'];

         foreach($gamesList as $theGame){
            if($theGame->id === $letsPlayRow['game_id']){
               $lpData->game = $theGame;
               break;
            }
         }

         if($lpData->game->is_collection){
            foreach($collectionList as $theCollection){
               if($theCollection->id === $letsPlayRow['game_collection_id']){
                  //$this->debug($theCollection);
                  $lpData->collection = $theCollection;
                  break;
               }
            }
         }
         $lpData->playlistUrl = $letsPlayRow['playlist_url'];
         $lpData->started = $letsPlayRow['lp_started'];
         $lpData->ended = $letsPlayRow['lp_ended'];

         $this->letsplayed[] = $lpData;
      }

      //$this->debug($this->letsplayed);
   }

   function GetLetsPlayed($page){

      $startingIndex = $page * $this->result_limit;
      $gamesToShow;

      $counter = 0;

      for($i = $startingIndex; $i < count($this->letsplayed); $i++){
         $gamesToShow[] = $this->letsplayed[$i];
         $counter++;
         if($counter >= $this->result_limit) break;
      }

      return $gamesToShow;
   }

   function GetLetsPlayList(){
      return $this->letsplayed;
   }

   function GetActiveLetsPlays(){
      $activeLP;
      for($i = 0; $i < count($this->letsplayed); $i++){
         if(!isset($this->letsplayed[$i]->ended)){
            $activeLP[] = $this->letsplayed[$i];
         }
      }

      return $activeLP;
   }

   function GetNumPages(){
      $numGames = count($this->letsplayed);
      $pages = $numGames / $this->result_limit;
      // Immer aufrunden, wenn Nachkommastelle existiert
      return ceil($pages);
   }

   private function debug($data){
      echo "<pre>";
      print_r($data);
      echo "</pre>";
   }
}

class YoutubeLetsPlayData{
   public $id;
   public $game;
   public $collection;
   public $playlistUrl;
   public $started;
   public $ended;
}

class GameCollection{
   public $id;
   public $title;
   public $origin_plattform;
   public $origin_release;
   public $game_id;

   private $conn;
   private $plattformList;

   private $db;

   function __construct(){
      $this->db = Database::getInstance()->getConnection();

      $plattform = new Plattform();
      $this->plattformList = $plattform->GetPlattformList();
   }

   function GetGamesDefinedAsCollection(){
      // Überprüfen, ob die Verbindung gültig ist
      if (!mysqli_ping($this->db)) {
         // Wenn die Verbindung nicht aktiv ist, Verbindung wiederherstellen
         $this->db = Database::getInstance()->getConnection();
      }
      
      $sql = "SELECT id, title FROM Game WHERE is_collection = 1 ORDER BY title ASC;";
      $result = mysqli_query($this->db, $sql);

      $games_with_collection = array();
      if (mysqli_num_rows($result) > 0) {

         while($row = mysqli_fetch_assoc($result)) {
            $games_with_collection[] = array("id" => $row["id"], "title" => $row["title"]);
         }

         /*
         echo "<pre>";
         print_r($games_with_collection);
         echo "</pre>";
         */
      }
      return $games_with_collection;
   }

   function GetCollectionList(){
      // Überprüfen, ob die Verbindung gültig ist
      if (!mysqli_ping($this->db)) {
         // Wenn die Verbindung nicht aktiv ist, Verbindung wiederherstellen
         $this->db = Database::getInstance()->getConnection();
      }

      $sql = "SELECT id, title, origin_plattform, origin_release, game_id ";
      $sql .= "FROM GameCollection ";
      $sql .= "ORDER BY title ASC";
      
      $result = mysqli_query($this->db, $sql);
      
      $games = array();
      if (mysqli_num_rows($result) > 0) {
         // output data of each row
         while($row = mysqli_fetch_assoc($result)) {
            $gameCollection = new GameCollection();
            $gameCollection->id = $row["id"];
            $gameCollection->title = $row["title"];

            foreach ($this->plattformList as $element) {
               if ($row["origin_plattform"] == $element->id) {
                  $gameCollection->origin_plattform = $element;
                  break;
               }
            }

            $gameCollection->origin_release = $row["origin_release"];
            $gameCollection->game_id = $row["game_id"];

            $games[] = $gameCollection; // Füge das GameCollection-Objekt dem Array hinzu.
         }
      }

      return $games;
   }

   function AddToCollection($title, $origin_plattform_id, $release_date, $game_id){
      // Überprüfen, ob die Verbindung gültig ist
      if (!mysqli_ping($this->db)) {
         // Wenn die Verbindung nicht aktiv ist, Verbindung wiederherstellen
         $this->db = Database::getInstance()->getConnection();
      }

      $sql = "SELECT * FROM GameCollection WHERE title = ? AND origin_plattform = ? AND game_id = ?";
      $stmt = mysqli_prepare($this->db, $sql);

      if($stmt) {
         mysqli_stmt_bind_param($stmt, "sii", $title, $origin_plattform_id, $game_id);
         mysqli_stmt_execute($stmt);

         $result = mysqli_stmt_get_result($stmt);
         mysqli_stmt_close($stmt);

         if(mysqli_num_rows($result) == 0){
            $sql = "INSERT INTO GameCollection (title, origin_plattform, origin_release, game_id) ";
            $sql .= "VALUES (?, ?, ?, ?)";
            $stmt = mysqli_prepare($this->db, $sql);

            if ($stmt) {
               mysqli_stmt_bind_param($stmt, "sisi", 
                  $title, $origin_plattform_id, $release_date, $game_id);

               if (mysqli_stmt_execute($stmt)) {
                  return "Neues Game zur Collection hinzugefügt";
               } else {
                  return false;
               }
               mysqli_stmt_close($stmt);
            }
         }
         else{
            return "Game in existiert bereits in der Collection";
         }
      }
   }
}

class GameAdministration{
   // todo: diverse Auflistungen fürs Frontend, die die Besucher einsehen können
   private $db;
   private $result_limit = 10;
   private $games; // Games-Dataset



   function __construct(){
      $this->db = Database::getInstance()->getConnection();
      $game = new Game();
      $this->games = $game->GetGamesList();
   }

   function GetGames($page){

      $startingIndex = $page * $this->result_limit;
      $gamesToShow;

      $counter = 0;

      for($i = $startingIndex; $i < count($this->games); $i++){
         $gamesToShow[] = $this->games[$i];
         $counter++;
         if($counter >= $this->result_limit) break;
      }

      return $gamesToShow;
   }

   function GetNumPages(){
      $numGames = count($this->games);
      $pages = $numGames / $this->result_limit;
      // Immer aufrunden, wenn Nachkommastelle existiert
      return ceil($pages);
   }

   function GetNewestGames($max){
      $result;
      $gameComparator = new Comparator("purchase_date");
      usort($this->games, array($gameComparator, 'compareDESC'));

      for($i = 0; $i < $max && $i < count($this->games); $i++){
         //$this->debug($this->games[$i]->purchase_date);
         $result[] = $this->games[$i];
      }

      return $result;
   }

   function GetNumberOfGames(){
      $numOfGames;
      $numOfGames['games'] = count($this->games);

      $countCollections = 0;
      $countGamesInCollections = 0;
      $countDigitals = 0;
      for($i = 0; $i < count($this->games); $i++){
         if($this->games[$i]->is_collection == 1){
            $countCollections++;
         }

         if($this->games[$i]->is_collection > 0 && isset($this->games[$i]->game_collection)){
            $countGamesInCollections += count($this->games[$i]->game_collection);
         }

         if($this->games[$i]->is_digital == 1){
            $countDigitals++;
         }
      }

      $numOfGames['collections'] = $countCollections;
      $numOfGames['games_in_collections'] = $countGamesInCollections;
      $numOfGames['digital_games'] = $countDigitals;

      $numOfGames['sum'] = $numOfGames['games'] - $numOfGames['collections'] + $numOfGames['games_in_collections'] + $numOfGames['digital_games'];

      //$this->debug($numOfGames['collections']);

      return $numOfGames;
   }

   function GetDiagramOfGamesOnPlattform(){
      $dataNums;
      $dataLabels;

      foreach($this->games as $game){
         if(isset($dataNums[$game->plattform->description]))
         {
            $dataNums[$game->plattform->description] += 1;
         }
         else
         {
            $dataNums[$game->plattform->description] = 1;
            $dataLabels[] = $game->plattform->description;
         }
      }
      //$this->debug($dataLabels);

      // Diagramm erstellen
      require_once ('jpgraph-4.4.2/src/jpgraph.php');
      require_once ('jpgraph-4.4.2/src/jpgraph_pie.php');

      $daten = array_values($dataNums); // Werte aus dem assoziativen Array extrahieren

      $graph = new PieGraph(500, 250);
      $graph->SetShadow();
      //$graph->SetMargin(40,20,60,20);

      //$graph->title->Set("Spiele auf Plattformen");
      //$graph->title->SetFont(FF_FONT1, FS_BOLD);

      $p1 = new PiePlot($daten);
      $p1->SetLegends($dataLabels);
      $p1->SetSliceColors(['#FF9999', '#66B2FF', '#99FF99', '#FFCC99']); // Farben für die Segmente festlegen
      $p1->SetSize(0.33); // Größe des Diagramms
      $p1->SetCenter(0.3, 0.5); // Position des Diagramms im Bild festlegen


      $graph->Add($p1);

      // Legende hinzufügen
      $graph->legend->Pos(0.75, 0.1);
      $graph->legend->SetFont(FF_FONT1, FS_NORMAL);
      $graph->legend->SetLayout(LEGEND_VERT);

      // Diagramm anzeigen oder speichern
      $graph->Stroke('../images/statistics/DiagramOfGamesOnPlattform.png');
   }

   function GetDiagramOfPayedByYear(){
      $dataValues;
      $dataLabels;

      $gameComparator = new Comparator("purchase_date");
      usort($this->games, array($gameComparator, 'compareASC'));

      foreach($this->games as $game){
         $year = substr($game->purchase_date, 0, 4);

         if(isset($dataValues[$year]))
         {
            if(isset($year) && !empty($year)){
               $dataValues[$year] += $game->price;
            }
         }
         else
         {
            if(isset($year) && !empty($year)){
               $dataValues[$year] = $game->price;
               $dataLabels[] = $year;
            }
         }
      }

      //$this->debug($dataValues);
      //$this->debug($dataLabels);


      // Balken-Diagramm erstellen
      require_once ('jpgraph/jpgraph.php');
      require_once ('jpgraph/jpgraph_bar.php');

      $daten = array_values($dataValues);




      

      // Daten für das Diagramm (Jahr => Umsatz in Euro)
      $umsatzDaten = array(
         '2021' => 50000,
         '2022' => 75000,
         '2023' => 60000,
         // ... Weitere Jahre und Umsätze
      );

      // Diagramm erstellen
      $graph = new Graph(600, 400);
      $graph->SetScale('textlin');

      // Balkendiagramm erstellen
      $barPlot = new BarPlot(array_values($umsatzDaten));
      $barPlot->SetFillColor('lightblue'); // Farbe der Balken
      $barPlot->SetWidth(0.6); // Breite der Balken

      // X-Achse (Jahre)
      $graph->xaxis->SetTickLabels(array_keys($umsatzDaten));

      // Y-Achse (Umsätze)
      $graph->yaxis->SetNumberFormat('%d €');

      // Diagramm hinzufügen
      $graph->Add($barPlot);


      /*
      // Diagramm erstellen
      require_once ('jpgraph-4.4.2/src/jpgraph.php');
      require_once ('jpgraph-4.4.2/src/jpgraph_pie.php');

      $daten = array_values($dataValues); // Werte aus dem assoziativen Array extrahieren

      $graph = new PieGraph(500, 250);
      $graph->SetShadow();
      //$graph->SetMargin(40,20,60,20);

      //$graph->title->Set("Spiele auf Plattformen");
      //$graph->title->SetFont(FF_FONT1, FS_BOLD);

      $p1 = new PiePlot($daten);
      $p1->SetLegends($dataLabels);
      $p1->SetSliceColors(['#FF9999', '#66B2FF', '#99FF99', '#FFCC99']); // Farben für die Segmente festlegen
      $p1->SetSize(0.33); // Größe des Diagramms
      $p1->SetCenter(0.3, 0.5); // Position des Diagramms im Bild festlegen


      $graph->Add($p1);

      // Legende hinzufügen
      $graph->legend->Pos(0.75, 0.1);
      $graph->legend->SetFont(FF_FONT1, FS_NORMAL);
      $graph->legend->SetLayout(LEGEND_VERT);
      */
      
      // Display the graph
      $graph->Stroke('../images/statistics/DiagramOfPayedByYear.png');
   }

   private function debug($data){
      echo "<pre>";
      print_r($data);
      echo "</pre>";
   }
}

class Comparator {
   private $sortField;

   /**
    * @param String sortField Eigenschaft, nach der sortiert wird
    */
   public function __construct($sortField) {
       $this->sortField = $sortField;
   }

   public function compareASC($game1, $game2) {
      $field = $this->sortField;

      if ($game1->$field == $game2->$field) {
         return 0;
      }

      return ($game1->$field < $game2->$field) ? -1 : 1;
   }

   public function compareDESC($game1, $game2) {
      $field = $this->sortField;

      if ($game1->$field == $game2->$field) {
          return 0;
      }

      return ($game1->$field > $game2->$field) ? -1 : 1;
  }
}

?>