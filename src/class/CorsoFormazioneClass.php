<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of CorsoFormazioneClass
 *
 * @author xf46260
 */
class CorsoFormazioneClass {
    private $corsoId=null;
    private $titolo=null;
    private $mese=null;
    private $anno=null;
    
    function getCorsoId() {
        return $this->corsoId;
    }
    
    function getTitolo() {
        return $this->titolo;
    }
    
    function getMese() {
        return $this->mese;
    }
    
    function getAnno() {
        return $this->anno;
    }
    
    function setCorsoId($corsoId) {
        return $this->corsoId=$corsoId;
    }
    
    function setTitolo($titolo) {
        return $this->titolo=$titolo;
    }
    
    function setMese($mese) {
        return $this->mese=$mese;
    }
    
    function setAnno($anno) {
        return $this->anno=$anno;
    }
    
    public function getCorsoFormazione($db) {
        $query = "SELECT * FROM corsoformazione ORDER BY anno, nromese ASC";    //modificato il 11-05-2017
        $return = $db->query($query) or die($db->error());
        return $return;
    }
    
    public function insertCorsoFormazione($db, $post){
		$query="INSERT INTO `corsoformazione` (`titolo`, `mese`, `anno`) ";
                $query.=" VALUES ('".$db->mysqli_real_escape($post["corso"])."' ";
                if($db->mysqli_real_escape($post["mese"])!=""){
                    $query.=", '".$db->mysqli_real_escape($post["mese"])."' ";
                }else{$query.=",''";}
                $query.=", ".$db->mysqli_real_escape($post["anno"]);
                $query.=")";
        $return=$db->insert($query, true) or die($db->error());
        return $return;

    }
}

?>
