<?php


class ComuneClass {
    private $comuneId = null;
    private $comuneNome = null;
    private $comuneProvIdFk = null;
    
    function getComuneId(){
        return $this->comuneId;
    }
    function getComuneNome(){
        return $this->comuneNome;
    }
    function getcomuneProvIdFk(){
        return $this->provIdFk;
    }
    function setComuneId($comuneId) {
        $this->comuneId = $comuneId;
    }
    function setComuneNome($comuneNome) {
        $this->comuneNome = $comuneNome;
    }
    function setComuneProvIdFk($comuneProvIdFk) {
        $this->provIdFk = $comuneProvIdFk;
    }
    
    public function getListaComuni($db) {
        $query = "SELECT comune.* FROM comune ";
        $query.=" ORDER BY comune.comuneNome ASC ";
        $return = $db->query($query) or die($db->error());
        return $return;
    }
    
    public function getComuneStabById($db){
        $query = "SELECT comune.* FROM comune WHERE comune.comuneId = ".$this->getComuneId() ;
        $return = $db->query($query) or die($db->error());
        return $return;
    }
    
    public function esisteComuneInProvincia($db, $nome, $idProv){
        $query="SELECT comune.* " ;
        $query.="FROM comune, provincia ";
        $query.="WHERE (UCASE(comuneNome) = UCASE('".$nome."'))";
        $query.="AND (comune.provIdFk = '".$idProv."')";
        $return = $db->query($query) or die($db->error());
        return $return;
    }
    
    public function insertComune($db, $post){
        $query="INSERT INTO `comune` (`comuneNome`, `provIdFk`) ";
        $query.=" VALUES ('".($post["comune"])."' ";
        $query.=", ".$db->mysqli_real_escape($post["province"]);
        $query.=" )";
        $return=$db->insert($query, true) or die($db->error());
        return $return;
    }
    
    public function insertAutomaticoComune($db, $post1,$post2){
        $query="INSERT INTO `comune` (`comuneNome`, `provIdFk`) ";
        $query.=" VALUES ('".$post1."' ";
        $query.=", ".$post2;
        $query.=" )";
        $return=$db->insert($query, true) or die($db->error());
        return $return;
    }
    
    public function getProvByComune($db, $com){
        $query = "SELECT provincia.prov FROM provincia ";
        $query.=" JOIN comune ON comune.comuneId=".$db->mysqli_real_escape($com);
        $query.=" AND provincia.provinciaId=comune.provIdFk ";
       // $query.=" AND provincia.regioneIdFk=regione.regioneId ";
        $return= $db->query($query) or die($db->error());
        return $return;
    }
    
    public function getRegioneByComune($db, $com){
        $query = "SELECT regione.nomeregione FROM regione ";
        $query.=" JOIN comune ON comune.comuneId=".$db->mysqli_real_escape($com);
        $query.=" JOIN provincia ON provincia.provinciaId=comune.provIdFk ";
        $query.=" AND provincia.regioneIdFk=regione.regioneId ";
        $return= $db->query($query) or die($db->error());
        return $return;
    }
    
}

?>
