<?php

class UotClass{
	
	private $uotId = null;
	private $uotDenominazione = null;
	private $uotIndirizzo = null;
	private $uotPec = null;
	private $uotTelefono = null;
        private $uotFax = null;
	private $uotCap = null;
	private $provinciaFkId = null;
	private $direttoreFkId = null;
	
	function getUotId(){
		return $this->uotId;
	}
	
	function getUotDenominazione(){
		return $this->uotDenominazione;
	}
	function getUotIndirizzo(){
		return $this->uotIndirizzo;
	}
	
	function getUotPec(){
		return $this->uotPec;
	}
	
	function getUotTelefono(){
		return $this->uotTelefono;
	}
        
        function getUotFax(){
		return $this->uotFax;
	}
	
	
	function getUotCap(){
		return $this->uotCap;
	}
	
	function getUotProvinciaFkId(){
		return $this->provinciaFkId;
	}
	
	function getUotDirettoreFkId(){
		return $this->direttoreFkId;
	}
	
	function setUotId($uotId) {
            $this->uotId = $uotId;
        }
	
	function setUotDenominazione($uotDenominazione){
		return $this->uotDenominazione = $uotDenominazione;
	}
	
	function setUotIndirizzo($uotIndirizzo){
		return $this->uotIndirizzo = $uotIndirizzo;
	}
	
	function setUotPec($uotPec){
		return $this->uotPec = $uotPec;
	}
	
	function setUotTelefono($uotTelefono){
		return $this->uotTelefono = $uotTelefono;
	}
        
        function setUotFax($uotFax){
		return $this->uotFax = $uotFax;
	}
	
	function setUotCap($uotCap){
		return $this->uotCap = $uotCap;
	}
	
	function setUotProvinciaFkId($provinciaFkId){
		return $this->provinciaFkId = $provinciaFkId;
	}
	
	function setUotDirettoreFkId($direttoreFkId){
		return $this->direttoreFkId = $direttoreFkId;
	}
	
    public function insertUot($db, $post){
        $query="INSERT INTO `uot`(`uotDenominazione`, `uotIndirizzo`,`uotPec`,`uotTelefono`,`uotFax`, `uotCap`,`provinciaFkId`) VALUES('".$db->mysqli_real_escape($post["uotDenominazione"])."', '".$db->mysqli_real_escape($post["uotIndirizzo"])."', '".$db->mysqli_real_escape($post["uotPec"])."', '".$db->mysqli_real_escape($post["uotTel"])."', '".$db->mysqli_real_escape($post["uotFax"])."', ".$db->mysqli_real_escape($post["uotCap"]).", ".$db->mysqli_real_escape($post["provincia"]).")";
        $return=$db->insert($query, true) or die($db->error());
        return $return;
    }
    
    public function getIdUotByName($db, $nome){
        $query="SELECT uot.* FROM uot 
            WHERE (uot.uotDenominazione='".$db->mysqli_real_escape($nome)."')";
        $return=$db->query($query) or die($db->error());
            return $return;
    }
	
    public function getLastRecord($db) {
        $query = "SELECT uot.* FROM uot ";
        $query.="ORDER BY uot.uotId DESC ";
        $return = $db->query($query) or die($db->error());
        return $return;
    }
    
    public function getDettaglioUot($db){
        $query = "SELECT * FROM uot WHERE uotId=" . $this->getUotId();
        $return = $db->query($query) or die($db->error());
        return $return;
    }
    
    Public function getProvinciaUot($db, $provuotId){
        $query = "SELECT provincia.prov FROM provincia, uot";
        $query.=" WHERE (uot.provinciaFkId=provincia.provinciaId)";
        $query.=" AND (uot.provinciaFkId=".$provuotId.")";
        $return = $db->query($query) or die($db->error());
        return $return;
    }
    
    public function getUot($db) {
        $query = "SELECT DISTINCT * FROM uot ORDER BY uotDenominazione ASC";
        $return = $db->query($query) or die($db->error());
        return $return;
    }
    
    public function getUotById($db,$id){
        $query = "SELECT uot.* FROM uot ";
        $query.= "WHERE uot.uotId = ".$id ;
        $return = $db->query($query) or die($db->error());
        return $return;
    }
    
    public function updateUot($db,$post){
        
        $query = "UPDATE uot ";
        $query.=" SET uotDenominazione='" . $db->mysqli_real_escape($post["uotDenominazione"]) . "', ";
        $query.=" uotIndirizzo='" . $db->mysqli_real_escape($post["uotIndirizzo"]) . "', ";
        $query.=" uotPec='" . $db->mysqli_real_escape($post["uotPec"]) . "', ";
        $query.=" uotTelefono='" . $db->mysqli_real_escape($post["uotTel"]) . "', ";
        $query.=" uotFax='" . $db->mysqli_real_escape($post["uotFax"]) . "', ";
        $query.=" uotCap='" . $db->mysqli_real_escape($post["uotCap"]) . "', ";
        $query.=" provinciaFkId=" . $db->mysqli_real_escape($post["provincia"]) . " ";
        $query.=" WHERE uotId=" . $this->getUotId();
        $return = $db->query($query) or die($db->error());
        return $return;
    }
    
    public function contaRuoliByUot($db,$uotcurr,$tiporuolo){
        $query = "SELECT COUNT(*) AS cont FROM ispettore ";
        $query.= " WHERE (ispettore.uotIspIdFk=".$db->mysqli_real_escape($uotcurr).")";
        $query.= " AND (ispettore.ruoloIdFk=".$db->mysqli_real_escape($tiporuolo).")";
        //11-03-2019
        $query.= " AND (ispettore.attivo=0)";
        $return = $db->query($query) or die($db->error());
        return $return;
    }
    
    public function contaIspettoriLiberiByUot($db,$uotcurr,$tiporuolo){
//        $query = "SELECT COUNT(*) AS cont FROM ispettore ";
//        $query.= " WHERE (ispettore.uotIspIdFk=".$db->mysqli_real_escape($uotcurr).")";
//        $query.= " AND (ispettore.ruoloIdFk=".$db->mysqli_real_escape($tiporuolo).")";
//        $return = $db->query($query) or die($db->error());
//        return $return;
    }
    
    public function contaStabilimentiDaIspezByUot($db,$iduot,$anno){
//        conta gli stabilimenti da ispezionare in una data uot:
        //MODIFICA 02-02-2017: Aggiunto anno

        $query = "SELECT COUNT(*) AS cont FROM ispezione, stabilimento, uot  ";
        $query .= " WHERE ((ispezione.statoIdFk=2) OR (ispezione.statoIdFk=3)) ";
        $query .= " AND (ispezione.stabIdFk=stabilimento.stabilimentoId) ";
        $query .= " AND (stabilimento.uotAffIdFk=uot.uotId) ";   
        $query .= " AND (uot.uotId=".$db->mysqli_real_escape($iduot).")";
        $query .= " AND (ispezione.anno=".$db->mysqli_real_escape($anno).")";
        
        $return = $db->query($query) or die($db->error());
        return $return;
    }
    
}
?>