<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of IspezioneDocumentiClass
 *
 * @author xf46260
 */
class IspezioneDocumentiClass {
    private $ispezioneIdFk=null;
    private $docIdFk=null;
    
    function getIspezioneIdFk() {
        return $this->ispezioneIdFk;
    }
    function getDocIdFk() {
        return $this->docIdFk;
    }
    
    function setIspezioneIdFk($ispezioneIdFk) {
        $this->ispezioneIdFk = $ispezioneIdFk;
    }
    function setDocIdFk($docIdFk) {
        $this->docIdFk = $docIdFk;
    }
    
    public function getIspezioneRC($db,$ispez){
        $query="SELECT ispezione_tipodoc.* ";
        $query.=" FROM ispezione, tipidocumento, ispezione_tipodoc ";
        $query.=" WHERE (ispezione.ispezioneId=".$db->mysqli_real_escape($ispez).")";
        $query.=" AND (ispezione.ispezioneId=ispezione_tipodoc.idispezione)";
        $query.=" AND (ispezione_tipodoc.idtipodoc=1)";
        $return = $db->query($query) or die($db->error());
        return $return;
    }
    public function getIspezioneEO($db,$ispez){
        $query="SELECT ispezione_tipodoc.* ";
        $query.=" FROM ispezione, tipidocumento, ispezione_tipodoc ";
        $query.=" WHERE (ispezione.ispezioneId=".$db->mysqli_real_escape($ispez).")";
        $query.=" AND (ispezione.ispezioneId=ispezione_tipodoc.idispezione)";
        $query.=" AND (ispezione_tipodoc.idtipodoc=2)";
        $return = $db->query($query) or die($db->error());
        return $return;
    }
    public function getIspezioneST($db,$ispez){
        $query="SELECT ispezione_tipodoc.* ";
        $query.=" FROM ispezione, tipidocumento, ispezione_tipodoc ";
        $query.=" WHERE (ispezione.ispezioneId=".$db->mysqli_real_escape($ispez).")";
        $query.=" AND (ispezione.ispezioneId=ispezione_tipodoc.idispezione)";
        $query.=" AND (ispezione_tipodoc.idtipodoc=3)";
        $return = $db->query($query) or die($db->error());
        return $return;
    }
    public function getIspezioneAL($db,$ispez){
        $query="SELECT ispezione_tipodoc.* ";
        $query.=" FROM ispezione, tipidocumento, ispezione_tipodoc ";
        $query.=" WHERE (ispezione.ispezioneId=".$db->mysqli_real_escape($ispez).")";
        $query.=" AND (ispezione.ispezioneId=ispezione_tipodoc.idispezione)";
        $query.=" AND (ispezione_tipodoc.idtipodoc=4)";
        $return = $db->query($query) or die($db->error());
        return $return;
    }
}

?>
