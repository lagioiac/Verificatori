<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of UotRegioneClass
 *
 * @author xf46260
 */
class UotRegioneClass {
    private $uotIdFk=null;
    private $regioneIdFk=null;
    
    function getUotIdFk() {
        return $this->uotIdFk;
    }
    function getRegioneIdFk() {
        return $this->regioneIdFk;
    }
    
    function setUotIdFk($uotIdFk) {
        $this->uotIdFk = $uotIdFk;
    }
    function setRegioneIdFk($regioneIdFk) {
        $this->regioneIdFk = $regioneIdFk;
    }
    
    public function insertUotRegione($db, $uot,$regione){
        $query="INSERT INTO uot_regione VALUES (".$uot.", ".$regione.")";
        $return= $db->query($query) or die($db->error());
        return $return;
    }
    
    public function getUotRegione($db,$regione){
        $query="SELECT uot.*
            FROM uot, uot_regione
            WHERE (uot.uotId=uot_regione.uotIdFk) 
            AND (uot_regione.regioneIdFk=".$regione.")";
        $return = $db->query($query) or die($db->error());
        return $return;
    }
    
    public function getUotOutRegione($db,$regione){
        $query="SELECT regione.nomeregione, uot.*
            FROM uot, uot_regione, regione
            WHERE (uot.uotId=uot_regione.uotIdFk) 
            AND (uot_regione.regioneIdFk=regione.regioneId)
            AND (uot_regione.regioneIdFk!=".$regione.")";
        $query.=" ORDER BY regione.nomeregione ASC";
        $return = $db->query($query) or die($db->error());
        return $return;
    }
    
    public function getRegioneIdByUot($db,$idUot){
        $query = "SELECT uot_regione.regioneIdFk FROM uot_regione ";
        $query.= " WHERE uot_regione.uotIdFk=".$idUot;
        $return = $db->query($query) or die($db->error());
        return $return;
    }
}

?>
