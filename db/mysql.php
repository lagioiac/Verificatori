<?php

    /* La funzione __construct in una classe PHP è il costruttore, che viene 
     * chiamato automaticamente ogni volta che viene creata un'istanza della 
     * classe. Questo metodo è utilizzato per inizializzare gli oggetti. 
     * Nel caso del vecchio file mysql.php, sostituisce il metodo costruttore 
     * precedente che ha lo stesso nome della classe, il quale è stato 
     * deprecato nelle versioni più recenti di PHP. 
     * Quando si crea un'istanza della classe DbConnect, ad esempio con 
     * $db = new DbConnect();, il costruttore __construct viene automaticamente 
     * chiamato per inizializzare l'oggetto con i parametri di connessione 
     * al database. */



    class DbConnect {
    var $host = '';
    var $user = '';
    var $password = '';
    var $database = '';
    var $persistent = false;
    var $conn = NULL;
    var $result = false;
    var $error_reporting = true;
    private $sqlQuery = null;

    // Metodo costruttore
    function __construct($host = DBHOST, $user = DBUSER, $password = DBPASSWD, $database = DBNAME, $error_reporting = true, $persistent = false) {
        $this->host = $host;
        $this->user = $user;
        $this->password = $password;
        $this->database = $database;
        $this->persistent = $persistent;
        $this->error_reporting = $error_reporting;
    }

    // Altri metodi della classe
    function open() {
        $this->conn = new mysqli($this->host, $this->user, $this->password, $this->database);
        if($this->conn->connect_errno > 0)
            die('Unable to connect to database [' . $this->conn->connect_error . ']');
        return true;
    }
    
   
    function conn() { 
        return $this->conn;
    }

    function close() {
        $this->freeresult();
        return (@mysqli_close($this->conn));
    }

    function error() {
        if ($this->error_reporting) {
            echo "ERRORE: ".mysqli_error($this->conn);
            error_log(mysqli_error($this->conn));
            return (mysqli_error($this->conn));
        }
    }

    function query($sql) {
        $this->sqlQuery = $sql;
        $this->result = mysqli_query($this->conn, $sql);
        return $this->result;
    }

    function insert($sql, $lastId = false) {
        $this->sqlQuery = $sql;
        $this->result = @mysqli_query($this->conn, $sql);
        if ($this->result != false and $lastId) {
            $last = mysqli_insert_id($this->conn);
            return $last;
        }
        return ($this->result != false);
    }

    function numrows($result = null) {
        if($result != null)
            return (@mysqli_num_rows($result));
        else
            return (@mysqli_num_rows($this->result));
    }

    function numfield() {
        return (@mysqli_num_fields($this->result));
    }

    function fetchobject() {
        return (@mysqli_fetch_object($this->result, MYSQL_ASSOC));
    }

    function fetcharray() {
        return (@mysqli_fetch_array($this->result));
    }

    function fetchassoc() {
        return (@mysqli_fetch_assoc($this->result));
    }

    function fetchassoc2($result) {
        return (@mysqli_fetch_assoc($result));
    }

    function fetchrow() {
        return (@mysqli_fetch_row($this->result));
    }

    function fetchfield() {
        return (@mysqli_fetch_field($this->result));
    }

    function freeresult() {
        if(!is_bool($this->result))
            return (@mysqli_free_result($this->result));
    }

    function mysqli_real_escape($stringa) {
       return mysqli_real_escape_string($this->conn, $stringa);
    }

    function mysqli_data_seek($resultset) {
        return mysqli_data_seek($resultset, 0);
    }
}
?>
