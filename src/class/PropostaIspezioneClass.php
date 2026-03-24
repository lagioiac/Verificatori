<?php


class PropostaIspezioneClass {
    private $propispezioneId=null;
    private $ispezioneIdFk=null;
    private $propIspettDaUotIdFk=null;
    private $propUditDaUotIdFk=null;
    private $propIspettIdFk=null;
    private $propUditIdFk=null;
    
    function getPropispezioneId(){
            return $this->propispezioneId;
    }
    function getIspezioneIdFk(){
            return $this->ispezioneIdFk;
    }
    function getPropIspettDaUotIdFk(){
            return $this->propIspettDaUotIdFk;
    }
    function getPropUditDaUotIdFk(){
            return $this->propUditDaUotIdFk;
    }
    function getPropIspettIdFk(){
            return $this->propIspettIdFk;
    }
    function getPropUditIdFk(){
            return $this->propUditIdFk;
    }
    function getFlgPresenzaUd(){
            return $this->flgPresenzaUd;
    }
    
    function setPropispezioneId($propispezioneId) {
        $this->propispezioneId = $propispezioneId;
    }
    function setIspezioneId($ispezioneIdFk) {
        $this->ispezioneIdFk = $ispezioneIdFk;
    }
    function setPropIspettDaUotIdFk($propIspettDaUotIdFk) {
        $this->propIspettDaUotIdFk = $propIspettDaUotIdFk;
    }
    function setPropUditDaUotIdFk($propUditDaUotIdFk) {
        $this->propUditDaUotIdFk = $propUditDaUotIdFk;
    }
    function setPropIspettIdFk($propIspettIdFk) {
        $this->propIspettIdFk = $propIspettIdFk;
    }
    function setPropUditIdFk($propUditIdFk) {
        $this->propUditIdFk = $propUditIdFk;
    }
    function setFlgPresenzaUd($flgPresenzaUd) {
        $this->flgPresenzaUd = $flgPresenzaUd;
    }
    
    public function insertPropostaIspezione($db, $id,$isp,$ud){
        //crea semplicemente una copia per studiare la pianificazione
        if($isp>0 && $ud>0){    //sia ispettore che uditore
            $query="INSERT INTO `propostaispezione` (`ispezioneIdFk`, `propIspettDaUotIdFk`, `propUditDaUotIdFk`)";
            $query.=" VALUES (".$db->mysqli_real_escape($id).", ".$db->mysqli_real_escape($isp).", ".$db->mysqli_real_escape($ud).")";
        }elseif($isp>0 && $ud==0){  //solo ispettore
            $query="INSERT INTO `propostaispezione` (`ispezioneIdFk`, `propIspettDaUotIdFk`)";
            $query.=" VALUES (".$db->mysqli_real_escape($id).", ".$db->mysqli_real_escape($isp).")";
        }else{  //nè ispettore nè uditore
            $query="INSERT INTO `propostaispezione` (`ispezioneIdFk`)";
            $query.=" VALUES (".$id.")";
        }
        $return=$db->insert($query, true) or die($db->error());
        return $return;

    }
    
    public function OLD_insertPropostaIspezione($db, $id){
        //crea semplicemente una copia per studiare la pianificazione
        $query="INSERT INTO `propostaispezione` (`ispezioneIdFk`)";
        $query.=" VALUES (".$id.")";
        $return=$db->insert($query, true) or die($db->error());
        return $return;
    }
    
    public function updatePropostaIspezione($db,$ispez,$isp,$ud,$ispdit,$uddit){
        
        $query = "UPDATE propostaispezione ";
        $query.=" SET ispezioneIdFk=" . $db->mysqli_real_escape($ispez);
        if($db->mysqli_real_escape($isp)>0){
            $query.=", propIspettDaUotIdFk=" . $db->mysqli_real_escape($isp);
        }else{
            $query.=", propIspettDaUotIdFk=NULL" ;
        }
        if($db->mysqli_real_escape($ud)>0){
            $query.=", propUditDaUotIdFk=" . $db->mysqli_real_escape($ud);
        }else{
            $query.=", propUditDaUotIdFk=NULL";
        }
        if($db->mysqli_real_escape($ispdit)>0){
            $query.=", propIspettIdFk=" . $db->mysqli_real_escape($ispdit);
        }else{
            $query.=", propIspettIdFk=NULL" ;
        }
        //12-04-2017 se l'uditore designato dal dit esiste e <> da quello uot => memorizzalo
        if($db->mysqli_real_escape($uddit)>0) {
            if($db->mysqli_real_escape($uddit)!=$db->mysqli_real_escape($ud)){
                $query.=", propUditIdFk=" . $db->mysqli_real_escape($uddit);
                $query.=", flgPresenzaUd=0" ;
            }else{
                //uguale, basta aggiornare se necessario il flag
                $query.=", flgPresenzaUd=0" ;
            }  
        }else{
            $query.=", propUditIdFk=NULL";
            $query.=", flgPresenzaUd=0" ;
        }
        $query.=" WHERE propispezioneId=" . $this->getPropispezioneId();
        $return = $db->query($query) or die($db->error());
        return $return;
    }
    
    public function updateFlagPresenzaUditore($db,$flgPresenzaUd){
        
        $query = "UPDATE propostaispezione ";
        $query.=" SET flgPresenzaUd=" . $db->mysqli_real_escape($flgPresenzaUd);
        
        $query.=" WHERE propispezioneId=" . $this->getPropispezioneId();
        $return = $db->query($query) or die($db->error());
        return $return;
    }
    
    public function changePropostaIspezione($db){
        
        $query = "UPDATE propostaispezione ";
        $query.=" SET ispezioneIdFk=" . $this->getIspezioneIdFk();
        if ($this->getPropIspettDaUotIdFk()>0){
            $query.=", propIspettDaUotIdFk=" . $this->getPropIspettDaUotIdFk();
        }
        if ($this->getPropIspettIdFk()>0){
            $query.=", propIspettIdFk=" . $this->getPropIspettIdFk();
        }
        if($this->getPropUditDaUotIdFk()>0){
            $query.=", propUditDaUotIdFk=" . $this->getPropUditDaUotIdFk();
        }
        if($this->getPropUditIdFk()>0){
            $query.=", propUditIdFk=" . $this->getPropUditIdFk();
        }
        $query.=" WHERE propispezioneId=" . $this->getPropispezioneId();
        $return = $db->query($query) or die($db->error());
        return $return;
    }
    
    public function deletePropostoIspettore($db){
        //DA RIFARE!!!!!
        $query = "UPDATE propostaispezione ";
        $query.=" SET propIspettIdFk=NULL";
        $query.=" WHERE propispezioneId=" . $this->getPropispezioneId();
        echo $query;
        $return = $db->query($query) or die($db->error());
        return $return;
    }
    public function deletePropostoIspettoreDaUot($db){
        //DA RIFARE!!!!!
        $query = "UPDATE propostaispezione ";
        $query.=" SET propIspettDaUotIdFk=NULL";
        $query.=" WHERE propispezioneId=" . $this->getPropispezioneId();
        echo $query;
        $return = $db->query($query) or die($db->error());
        return $return;
    }
    
    public function deletePropostoUditore($db){
        //DA RIFARE!!!!!
        $query = "UPDATE propostaispezione ";
        $query.=" SET propUditIdFk=NULL";
        $query.=" WHERE propispezioneId=" . $this->getPropispezioneId();
        echo $query;
        $return = $db->query($query) or die($db->error());
        return $return;
    }
    public function deletePropostoUditoreDaUot($db){
        //DA RIFARE!!!!!
        $query = "INSERT propostaispezione(propUditDaUotIdFk) VALUES(NULL) ";
//        $query.=" SET propUditDaUotIdFk=NULL";
        $query.=" WHERE propispezioneId=" . $this->getPropispezioneId();
        echo $query;
        $return = $db->query($query) or die($db->error());
        return $return;
    }
    
    public function getPropostaIspezioneByIspezione($db, $isp) {
        $query = "SELECT propostaispezione.* FROM propostaispezione WHERE propostaispezione.ispezioneIdFk=" .$db->mysqli_real_escape($isp);
        $return = $db->query($query) or die($db->error());
        return $return;
    }
    
    public function getDettaglioPropostaIspezione($db) {
        $query = "SELECT propostaispezione.* FROM propostaispezione WHERE propispezioneId=" . $this->getPropispezioneId();
        $return = $db->query($query) or die($db->error());
        return $return;
    }
    
    public function getPropostaIspezioneByIspettore($db, $ispett) {
        $query = "SELECT propostaispezione.* FROM propostaispezione ";
        $query.= " WHERE (propostaispezione.propIspettDaUotIdFk=".$db->mysqli_real_escape($ispett).")";
        //$query.= " || ((propostaispezione.propUditDaUotIdFk=".$db->mysqli_real_escape($ispett)."))";
        $return = $db->query($query) or die($db->error());
        return $return;
    }
    
    public function getPropostaIspezioneByIspettoreAnno($db, $ispett, $anno) {  //NUOVA 02-02-2017
        $query = "SELECT propostaispezione.* FROM propostaispezione, ispezione ";
        $query.= " WHERE (propostaispezione.propIspettDaUotIdFk=".$db->mysqli_real_escape($ispett).")";
        $query.= " AND (propostaispezione.ispezioneIdFk=ispezione.ispezioneId)";
        $query.= " AND (ispezione.anno=".$anno.")";
        
        //$query.= " || ((propostaispezione.propUditDaUotIdFk=".$db->mysqli_real_escape($ispett)."))";
        $return = $db->query($query) or die($db->error());
        return $return;
    }
    
    public function getPropostaIspezioneByUditore($db, $ispett) {
        $query = "SELECT propostaispezione.* FROM propostaispezione ";
        $query.= " WHERE (propostaispezione.propUditDaUotIdFk=".$db->mysqli_real_escape($ispett).")";
        //$query.= " || ((propostaispezione.propUditDaUotIdFk=".$db->mysqli_real_escape($ispett)."))";
        $return = $db->query($query) or die($db->error());
        return $return;
    }
    
    public function getPropostaIspezioneByUditoreAnno($db, $ispett, $anno) {   //NUOVA 02-02-2017
        $query = "SELECT propostaispezione.* FROM propostaispezione, ispezione ";
        $query.= " WHERE (propostaispezione.propUditDaUotIdFk=".$db->mysqli_real_escape($ispett).")";
         $query.= " AND (propostaispezione.ispezioneIdFk=ispezione.ispezioneId)";
        $query.= " AND (ispezione.anno=".$anno.")";
        
        //$query.= " || ((propostaispezione.propUditDaUotIdFk=".$db->mysqli_real_escape($ispett)."))";
        $return = $db->query($query) or die($db->error());
        return $return;
    }
    
    public function getProposteIspezioniByUot($db,$id,$anno) {  //MODIFICATO 02-02-2017 AGGIUNTO ANNO
        $query = "SELECT propostaispezione.* FROM propostaispezione ";
        $query.= " JOIN ispezione ON (ispezione.ispezioneId=propostaispezione.ispezioneIdFk)";
        $query.= " AND (ispezione.statoIdFk=3)"; //solo quelle da pianificare
        $query.= " JOIN stabilimento ON stabilimento.stabilimentoId=ispezione.stabIdFk";
        $query.= " JOIN uot ON (uot.uotId=stabilimento.uotAffIdFk)";
        $query.= " AND (uot.uotId=".$db->mysqli_real_escape($id).")";
        
        $query.= " WHERE (ispezione.anno=".$anno.")";
        
        $query.=" ORDER BY stabilimento.stabilimentoDenominazione ASC ";
//        $query.=" ORDER BY ispezione.anno DESC ";
        $return = $db->query($query) or die($db->error());
        return $return;
    }

}

?>
