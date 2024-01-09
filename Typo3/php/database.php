<?php
class Database{
  private static $instance;
  public $conn;

  function __construct() {
    $servername = "localhost";
    $dbname = "[database]";
    $username = "[username]";
    $password = "[password]";

    // Überprüfen, ob bereits eine Verbindung besteht
    if (!$this->conn || !mysqli_ping($this->conn)) {
      // Wenn keine Verbindung besteht oder die Verbindung nicht aktiv ist, erstelle eine neue Verbindung
      $this->conn = mysqli_connect($servername, $username, $password, $dbname);
    }
  }

  public static function getInstance() {
    if (!isset(self::$instance)) {
      self::$instance = new self();
    }

    return self::$instance;
  }

  public function getConnection() {
    // Verbindung zurückgeben
    return $this->conn;
  }

  function __destruct() {
    // Verbindung schließen, wenn das Objekt zerstört wird
    $this->closeConnection();
  }

  function closeConnection() {
    // Verbindung schließen, wenn sie existiert und nicht bereits geschlossen ist
    if ($this->conn && !mysqli_connect_errno() && mysqli_ping($this->conn)) {
      mysqli_close($this->conn);
    }
  }
}
?>
