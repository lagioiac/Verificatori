<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of AttivitaIndustrialeClass
 *
 * @author xf46260
 */
class AttivitaIndustrialeClass {
    private $attivitaindustrialeId=null;
    private $attivitaindustriale=null;
    
    function getAttivitaindustrialeId(){
        return $this->attivitaindustrialeId;
    }
    function getAttivita(){
        return $this->attivita;
    }
    function setAttivitaindustrialeId($attivitaindustrialeId) {
        $this->attivitaindustrialeId = $attivitaindustrialeId;
    }
    function setAttivita($attivita) {
        $this->attivita = $attivita;
    }
    
    public function getListaAttivitaindustriale($db) {
        $query = "SELECT attivitaindustriale.* FROM attivitaindustriale ";
        $query.=" ORDER BY attivitaindustriale.attivita ASC ";
        $return = $db->query($query) or die($db->error());
        return $return;
    }
    
    public function getAttivitaById($db,$id){
        $query = "SELECT attivitaindustriale.attivita FROM attivitaindustriale ";
        $query.= "WHERE attivitaindustriale.attivitaindustrialeId = ".$id ;
        $return = $db->query($query) or die($db->error());
        return $return;
    }
    
    public function insertAttivitaIndustriale($db, $post){
        $query="INSERT INTO `attivitaindustriale` (`attivita`) ";
        $query.=" VALUES ('".($post["attivita"])."' ";
        $query.=" )";
        $return=$db->insert($query, true) or die($db->error());
        return $return;
    }
    
}

?>
