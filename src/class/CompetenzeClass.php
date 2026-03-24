<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of CompetenzeClass
 *
 * @author xf46260
 */
class CompetenzeClass {
    private $competenzaId = null;
    private $competenza = null;
    
    function getCompetenzaId() {
        return $this->competenzaId;
    }
    
    function getCompetenza() {
        return $this->competenza;
    }
    
    function setCompetenzaId($competenzaId) {
        return $this->competenzaId=$competenzaId;
    }
    
    function setCompetenza($competenza) {
        return $this->competenza=$competenza;
    }
    
    public function getCompetenze($db) {
        $query = "SELECT * FROM competenze ORDER BY competenza ASC";
        $return = $db->query($query) or die($db->error());
        return $return;
    }
    
    public function getCompetenzaById($db,$id){
        $query = "SELECT competenze.competenza FROM competenze ";
        $query.= "WHERE competenze.competenzaId = ".$id ;
        $return = $db->query($query) or die($db->error());
        return $return;
    }
    
    public function insertCompetenza($db, $post){
        $query="INSERT INTO `competenze` (`competenza`) ";
        $query.=" VALUES ('".$db->mysqli_real_escape($post["competenza"])."' )";
        $return=$db->insert($query, true) or die($db->error());
        return $return;
    }
}

?>
