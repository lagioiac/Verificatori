<?php
//  AGGIUNTO CAMPO periodo

class StabilimentoClass {
    
    private $stabilimentoId = null;
    private $stabilimentoDenominazione = null;
    private $stabilimentoCodice = null;
    private $stabilimentoLoc = null;
    private $comuneIdFk = null;
    private $soglia105 = null;
    private $attivIndustrialeIdFk = null;
    private $uotAffIdFk=null;
    private $periodo=null;
    
    function getStabilimentoId(){
            return $this->stabilimentoId;
    }
    function getStabilimentoCodice(){
            return $this->stabilimentoCodice;
    }
    function getStabilimentoDenominazione(){
            return $this->stabilimentoDenominazione;
    }
    function getStabilimentoLoc(){
            return $this->stabilimentoLoc;
    }
    function getComuneIdFk(){
            return $this->comuneIdFk;
    }
    function getSoglia105(){
            return $this->soglia105;
    }
    function getAttivIndustrialeIdFk(){
            return $this->attivIndustrialeIdFk;
    }
    function getUotAffIdFk(){
            return $this->uotAffIdFk;
    }
    function getPeriodo(){
            return $this->periodo;
    }
    
    function setStabilimentoId($stabilimentoId) {
        $this->stabilimentoId = $stabilimentoId;
    }
    function setStabilimentoDenominazione($stabilimentoDenominazione) {
        $this->stabilimentoDenominazione = $stabilimentoDenominazione;
    }
    function setStabilimentoCodice($stabilimentoCodice) {
        $this->stabilimentoCodice = $stabilimentoCodice;
    }
    function setStabilimentoLoc($stabilimentoLoc) {
        $this->stabilimentoLoc = $stabilimentoLoc;
    }
    function setComuneIdFk($comuneIdFk) {
        $this->comuneIdFk = $comuneIdFk;
    }
    function setSoglia105($soglia105) {
        $this->soglia105 = $soglia105;
    }
    function setAttivIndustrialeIdFk($attivIndustrialeIdFk) {
        $this->attivIndustrialeIdFk = $attivIndustrialeIdFk;
    }
    function setUotAffIdFk($uotAffIdFk) {
        $this->uotAffIdFk = $uotAffIdFk;
    }
    function setPeriodo($periodo) {
        $this->periodo = $periodo;
    }

    public function getListaStabilimenti($db,$ordinaPer) {
        //24-01-2019    aggiunto il dipo di ordinamento
        $query = "SELECT stabilimento.* FROM stabilimento ";
        $query.=" JOIN comune ON comune.comuneId=stabilimento.comuneIdFk ";
        $query.=" JOIN provincia ON provincia.provinciaId=comune.provIdFk ";
        $query.=" JOIN regione ON regione.regioneId=provincia.regioneIdFk ";
//        $query.=" ORDER BY regione.nomeregione ASC, provincia.prov ASC, stabilimento.stabilimentoDenominazione ASC";
        $query.=" ORDER BY ".$ordinaPer." ASC";
        $return = $db->query($query) or die($db->error());
        return $return;
    }
    
    public function getElencoStabilimentiConDettagli($db) { //Aggiunto periodo di ispezione 16-02-2017
        $query = "SELECT stabilimento.stabilimentoId, stabilimento.stabilimentoDenominazione AS Stabilimento, ";
        $query.= "stabilimento.stabilimentoCodice AS Codice, stabilimento.stabilimentoLoc AS Localita, comune.comuneNome AS Comune, ";
        $query.= "provincia.prov AS Prov, regione.nomeregione AS NomeRegione, attivitaindustriale.attivita AS Attivita, periodoispezione.periodo AS Periodo FROM stabilimento";
        $query.=" JOIN comune ON comune.comuneId=stabilimento.comuneIdFk ";
        $query.=" JOIN provincia ON provincia.provinciaId=comune.provIdFk ";
        $query.=" JOIN regione ON regione.regioneId=provincia.regioneIdFk ";
        $query.=" JOIN attivitaindustriale ON attivitaindustriale.attivitaindustrialeId=stabilimento.attivIndustrialeIdFk ";
        $query.=" JOIN periodoispezione ON periodoispezione.periodoId=stabilimento.periodo ";
        $query.=" ORDER BY regione.nomeregione ASC, provincia.prov ASC, stabilimento.stabilimentoDenominazione ASC";
        $return = $db->query($query) or die($db->error());
        return $return;
    }
    
    public function getElencoStabilimenti($db) {
        $query = "SELECT stabilimento.* FROM stabilimento ";
        $query.=" ORDER BY stabilimento.stabilimentoDenominazione ASC";
        $return = $db->query($query) or die($db->error());
        return $return;
    }
    
    public function insertStabilimento($db, $post){
		$query="INSERT INTO `stabilimento` (`stabilimentoDenominazione`, `stabilimentoCodice`, `soglia105`, `stabilimentoLoc`, `comuneIdFk`, `attivIndustrialeIdFk`, `periodo`";
                $query.=" )"; 
                $query.=" VALUES ('".$db->mysqli_real_escape($post["stabilimentoDenominazione"])."' ";
                if($post["stabilimentoCodice"]!=""){
                    $query.=", '".$db->mysqli_real_escape($post["stabilimentoCodice"])."' ";
                }else{$query.=", '' ";}                
                $query.=", ".$db->mysqli_real_escape($post["sogliaTipo"]);           
                if($post["stabilimentoLoc"]!=""){
                    $query.=", '".$db->mysqli_real_escape($post["stabilimentoLoc"])."' ";
                }else{$query.=", '' ";}
                if($post["comunestab"]!=""){
                    $query.=", ".$db->mysqli_real_escape($post["comunestab"]);
                }else{$query.=", '' ";}
                if($post["attiv"]!=""){
                    $query.=", ".$db->mysqli_real_escape($post["attiv"]);
                }else{$query.=", 0 ";}
               //AGGIUNTO IL SALVATAGGIO NEL CAMPO periodo
                if($post["periodorif"]!=""){
                    $query.=", ".$db->mysqli_real_escape($post["periodorif"])." ";
                }else{$query.=", 1 ";}
        
                $query.=")";
        $return=$db->insert($query, true) or die($db->error());
        return $return;

    }
    
    public function getDettaglioStabilimento($db) {
        $query = "SELECT stabilimento.* FROM stabilimento WHERE stabilimento.stabilimentoId=" . $this->getStabilimentoId();
        $return = $db->query($query) or die($db->error());
        return $return;
    }
    
    public function getSearchStabilimenti($db, $post,$ordinaPer){
        $query = "SELECT stabilimento.* FROM stabilimento ";
        if($post["searchattiv"] != ""){
            $query.=" JOIN attivitaindustriale ON (stabilimento.attivIndustrialeIdFk=attivitaindustriale.attivitaindustrialeId) ";
            $query.=" AND (attivitaindustriale.attivita LIKE '%".$db->mysqli_real_escape($post["searchattiv"]). "%' ) ";
        }
        if(($post["searchregione"] != "") AND $post["searchprovincia"] == ""){
            $query.=" JOIN comune ON comune.comuneId=stabilimento.comuneIdFk";
            $query.=" JOIN provincia ON comune.provIdFk = provincia.provinciaId";
            $query.=" JOIN regione ON ((provincia.regioneIdFk = regione.regioneId)";
            $query.=" AND (regione.nomeregione LIKE '%".$db->mysqli_real_escape($post["searchregione"]). "%'))";
        }elseif(($post["searchregione"] != "") AND $post["searchprovincia"] != ""){
            $query.=" JOIN comune ON comune.comuneId=stabilimento.comuneIdFk";
            $query.=" JOIN provincia ON ((comune.provIdFk = provincia.provinciaId)";
            $query.=" AND (provincia.prov LIKE '%".$db->mysqli_real_escape($post["searchprovincia"]). "%'))";
            $query.=" JOIN regione ON ((provincia.regioneIdFk = regione.regioneId)";
            $query.=" AND (regione.nomeregione LIKE '%".$db->mysqli_real_escape($post["searchregione"]). "%'))";
        }elseif(($post["searchregione"] == "") AND $post["searchprovincia"] != ""){
            $query.=" JOIN comune ON (comune.comuneId=stabilimento.comuneIdFk)";
            $query.=" JOIN provincia ON ((comune.provIdFk = provincia.provinciaId)";
            $query.=" AND (provincia.prov LIKE '%".$db->mysqli_real_escape($post["searchprovincia"]). "%'))";
        }
        //24-01-2019    aggiunto
        $query.=" ORDER BY ".$ordinaPer." ASC";
//        echo $query;
        $return = $db->query($query) or die($db->error());
        return $return;
    }
    
    public function getRegioneStabilimento($db, $id){
        $query = "SELECT stabilimento.*, regione.* FROM stabilimento, regione ";
        $query.=" JOIN comune ON comune.comuneId=".$db->mysqli_real_escape($id);
        $query.=" JOIN provincia ON comune.provIdFk = provincia.provinciaId";
        $query.=" WHERE ((provincia.regioneIdFk = regione.regioneId)";
        $query.=" AND (stabilimento.comuneIdFk =".$db->mysqli_real_escape($id)."))";
                
        $return = $db->query($query) or die($db->error());
        return $return;
    }
    
    public function getComuneByStabilimento($db, $post) {
        $query = "SELECT comune.* FROM comune WHERE comune.comuneId=" . $db->mysqli_real_escape($post["stabilimento"]);
        $return = $db->query($query) or die($db->error());
        return $return;
    }
    
    public function checkEsisteCodice($db,$stabilimentoCodice){
        //20-05-2019 Verifica se il codice stabilimento è già stato inserito
        $query = "SELECT stabilimento.* FROM stabilimento ";
        $query.=" WHERE (stabilimento.stabilimentoCodice = '".$db->mysqli_real_escape($stabilimentoCodice)."') ";
        $return = $db->query($query) or die($db->error());
        return $return;
    }
    
    public function updateStabilimento($db,$post){
        
        $query = "UPDATE stabilimento ";
        $query.=" SET stabilimentoDenominazione='" . $db->mysqli_real_escape($post["stabilimentoDenominazione"]) . "', ";
        $query.=" stabilimentoCodice='" . $db->mysqli_real_escape($post["stabilimentoCodice"]) . "', ";
        $query.=" soglia105=" . $db->mysqli_real_escape($post["sogliaTipo"]) . ", ";
        $query.=" stabilimentoLoc='" . $db->mysqli_real_escape($post["stabilimentoLoc"]) . "', ";
        $query.=" comuneIdFk=" . $db->mysqli_real_escape($post["comunestab"]). ", ";
        $query.=" attivIndustrialeIdFk=" . $db->mysqli_real_escape($post["attiv"]) . " ";
        //AGGIUNTO AGGIORNAMENTO CAMPO periodo
        $query.=", periodo=" . $db->mysqli_real_escape($post["periodorif"]);
        
        $query.=" WHERE stabilimentoId=" . $this->getStabilimentoId();
        $return = $db->query($query) or die($db->error());
        return $return;
    }
    
    public function updateUotAffStabilimento($db,$id){
        $query = "UPDATE stabilimento ";
        $query.=" SET uotAffIdFk=" . $db->mysqli_real_escape($id) ;
        $query.=" WHERE stabilimentoId=" . $this->getStabilimentoId();
        $return = $db->query($query) or die($db->error());
        return $return;
    }
    
        //PER AGGIUNGERE IL VALORE PERIODO NELLA TABELLA STABILIMENTO
//    public function updatePeriodoStabilimento($db){
//        $query = "UPDATE stabilimento ";
//        $query.=" SET periodo=1"  ;
//        $query.=" WHERE stabilimentoId=" . $this->getStabilimentoId();
//        $return = $db->query($query) or die($db->error());
//        return $return;
//    }
}

?>
