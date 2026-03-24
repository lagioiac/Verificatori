<?php
/* 

  //Simply include this file on your page
  require_once("DbConnect.class.php");

  //Set up all yor paramaters for connection
  $db = new DbConnect("localhost","user","password","database",$error_reporting=false,$persistent=false);

  //Open the connection to your database
  $db->open() or die($db->error());

  //Query the database now the connection has been made
  $db->query("SELECT * FROM....") or die($db->error());

  //You have several options on ways of fetching the data
  //as an example I shall use
  while($row=$db->fetcharray()) {

  //do some stuff

  }

  //close your connection
  $db->close();

 */

Class DbConnect {

    var $host = '';
    var $user = '';
    var $password = '';
    var $database = '';
    var $persistent = false;
    var $conn = NULL;
    var $result = false;
    var $error_reporting = true;
    private $sqlQuery = null;

    /* constructor function this will run when we call the class */

    function DbConnect($host = DBHOST, $user = DBUSER, $password = DBPASSWD, $database = DBNAME, $error_reporting = true, $persistent = false) {

        $this->host = $host;
        $this->user = $user;
        $this->password = $password;
        $this->database = $database;
        $this->persistent = $persistent;
        $this->error_reporting = $error_reporting;
    }

    function open() {

        /* Connect to the MySQl Server */
        $this->conn = new mysqli($this->host, $this->user, $this->password,$this->database);
        if($this->conn->connect_errno > 0){
            die('Unable to connect to database [' . $db->connect_error . ']');
        }
        return true;
    }

    
    function conn() {
        return $this->conn;
    }
    
    /* close the connection */

    function close() {
        $this->freeresult();
        return (@mysqli_close($this->conn));
    }

    /* report error if error_reporting set to true */

    function error() {
        if ($this->error_reporting) {
            echo "ERRORE: ".mysqli_error($this->conn);
            error_log(mysqli_error($this->conn));
            return (mysqli_error($this->conn));
        }
    }

    function query($sql) {
        
        $this->sqlQuery=$sql;
        $this->result = mysqli_query($this->conn,$sql);
        return $this->result;
    }

    function insert($sql, $lastId = false) {

        $this->sqlQuery=$sql;
        $this->result = @mysqli_query($this->conn,$sql);

        if ($this->result != false and $lastId) {
            $last=mysqli_insert_id($this->conn);
            return $last;
        }

        return($this->result != false);
    }

    function numrows($result=null) {
        if($result!=null)
            return(@mysqli_num_rows($result));
        else
            return(@mysqli_num_rows($this->result));
    }
    
    function numfield() {

        return(@mysqli_num_fields($this->result));
    }

    function fetchobject() {

        return(@mysqli_fetch_object($this->result, MYSQL_ASSOC));
    }

    function fetcharray() {

        return(@mysqli_fetch_array($this->result));
    }

    function fetchassoc() {
        return(@mysqli_fetch_assoc($this->result));
    }
    
    function fetchassoc2($result) {
        return(@mysqli_fetch_assoc($result));
    }
    
    function fetchrow() {

        return(@mysqli_fetch_row($this->result));
    }

    function fetchfield() {

        return(@mysqli_fetch_field($this->result));
    }

    
    
    function freeresult() {
        if(!is_bool($this->result)){
            return(@mysqli_free_result($this->result));
        }
            
    }
    
    function mysqli_real_escape($stringa) {
       return mysqli_real_escape_string($this->conn,$stringa);
    }
    
    function mysqli_data_seek($resultset) {
        return mysqli_data_seek($resultset, 0);
    }
    
}
?>
