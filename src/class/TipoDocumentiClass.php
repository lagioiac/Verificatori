<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of TipoDocumentiClass
 *
 * @author xf46260
 */
class TipoDocumentiClass {
    private $tipodocId=null;
    private $tipodoc=null;
    private $sigladoc=null;
    
    function getTipodocId() {
        return $this->tipodocId;
    }
    function getTipodoc() {
        return $this->tipodoc;
    }
    function getSigladoc() {
        return $this->sigladoc;
    }
    
    function setTipodocId($tipodocId) {
        return $this->tipodocId=$tipodocId;
    }
    function setTipodoc($tipodoc) {
        return $this->tipodoc=$tipodoc;
    }
    function setSigladoc($sigladoc) {
        return $this->sigladoc=$sigladoc;
    }
    
    public function getTipiDocumento($db) {
        $query = "SELECT * FROM tipidocumento ORDER BY tipodocId ASC";
        $return = $db->query($query) or die($db->error());
        return $return;
    }
}

?>
