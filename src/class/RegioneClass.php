<?php

class RegioneClass {

	private $regioneId = null;
	private $nomeregione = null;
	
	function getRegioneId(){
		return $this->regioneId;
	}
	
	function getnomeregione(){
		return $this->nomeregione;
	}
	
	function setRegioneId($regioneId) {
        $this->regioneId = $regioneId;
    }
	
	function setnomeregione($NomeRegione) {
        $this->nomeregione = $NomeRegione;
    }
	
	public function getRegioni($db) {
        $query = "SELECT regione.* FROM regione ORDER BY regione.nomeregione ASC";
        $return = $db->query($query) or die($db->error());
        return $return;
    }
    
    public function insertRegione($db, $post){
        $query="INSERT INTO `regione` (`nomeregione`) ";
        $query.=" VALUES ('".$db->mysqli_real_escape($post["regione"])."' )";
        $return=$db->insert($query, true) or die($db->error());
        return $return;
    }
	
    public function getDettaglioRegione($db) {
        $query = "SELECT regione.* FROM regione WHERE regione.regioneId=" . $this->getRegioneId();
        $return = $db->query($query) or die($db->error());
        return $return;
    }
}
?>