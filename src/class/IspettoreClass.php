<?php

class IspettoreClass{

	private $ispettoreId = null;
	private $ispettoreCognome = null;
	private $ispettoreNome = null;
        private $ispettoreTel=null;
        private $email=null;
        private $uotIspIdFk=null;
        private $compIdFk=null;
        private $nroIspSGSAu=null;
        private $anniEspSGSAu=null;
        private $nroIspUditAu=null;
        private $corsoIdAu=null;
        private $ruoloIdFk=null;
        private $flgDispTrasferta=null;
        private $noteIspettore=null;
        private $attivo=null;   //11/03/2019

        function getIspettoreId(){
		return $this->ispettoreId;
	}
	
	function getIspettoreCognome(){
		return $this->ispettoreCognome;
	}
        
        function getIspettoreNome(){
		return $this->ispettoreNome;
	}
	
	function getIspettoreTel(){
		return $this->ispettoreTel;
	}
        
        function getEmail(){
		return $this->email;
	}
        
        function getUotIspIdFk(){
		return $this->uotIspIdFk;
	}
        
        function getCompIdFk(){
		return $this->compIdFk;
	}
        
        function getNroIspSGSAu(){
		return $this->nroIspSGSAu;
	}
        
        function getAnniEspSGSAu(){
		return $this->anniEspSGSAu;
	}
        
        function getNroIspUditAu(){
		return $this->nroIspUditAu;
	}
        
        function getCorsoIdAu(){
		return $this->corsoIdAu;
	}
        
        function getRuoloIdFk(){
		return $this->ruoloIdFk;
	}
        
        function getFlgDispTrasferta(){
		return $this->flgDispTrasferta;
	}
        
        function getNoteIspettore(){
		return $this->noteIspettore;
	}
        function getAttivo(){
		return $this->attivo;
	}
	
	function setIspettoreId($ispettoreId) {
        $this->ispettoreId = $ispettoreId;
        }
	
	function setIspettoreCognome($ispettoreCognome) {
        $this->ispettoreCognome = $ispettoreCognome;
        }
	
	function setIspettoreNome($ispettoreNome) {
        $this->ispettoreNome = $ispettoreNome;
        }
        
        function setIspettoreTel($ispettoreTel){
		$this->ispettoreTel = $ispettoreTel;
	}
        
        function setEmail($email){
		$this->email = $email;
	}
        
        function setUotIspIdFk($uotIspIdFk){
		$this->uotIspIdFk = $uotIspIdFk;
	}
        
        function setCompIdFk($compIdFk){
		$this->compIdFk = $compIdFk;
	}
        
        function setNroIspSGSAu($nroIspSGSAu) {
        $this->nroIspSGSAu = $nroIspSGSAu;
        }
        
        function setAnniEspSGSAu($anniEspSGSAu) {
        $this->anniEspSGSAu = $anniEspSGSAu;
        }
        
        function setNroIspUditAu($nroIspUditAu) {
        $this->nroIspUditAu = $nroIspUditAu;
        }
        
        function setCorsoIdAu($corsoIdAu) {
        $this->corsoIdAu = $corsoIdAu;
        }
        
        function setRuoloIdFk($ruoloIdFk) {
        $this->ruoloIdFk = $ruoloIdFk;
        }
        
        function setFlgDispTrasferta($flgDispTrasferta) {
        $this->flgDispTrasferta = $flgDispTrasferta;
        }
        
        function setNoteIspettore($noteIspettore) {
        $this->noteIspettore = $noteIspettore;
        }
        
        function setAttivo($attivo) {
        $this->attivo = $attivo;
        }
	
    public function insertIspettore($db, $post){    //11-03-2019 aggiunto campo 'attivo'
		$query="INSERT INTO `ispettore` (`ispettoreCognome`, `ispettoreNome`, `ispettoreTel`, `email`, `uotIspIdFk`, `compIdFk`, ";
                $query.=" `nroIspSGSAu`, `anniEspSGSAu`, `nroIspUditAu`, `corsoIdAu`, `ruoloIdFk`, `flgDispTrasferta`, `noteIspettore`, `attivo`)"; 
                $query.=" VALUES ('".$db->mysqli_real_escape($post["ispettoreCognome"])."', '".$db->mysqli_real_escape($post["ispettoreNome"]);
                $query.="', '".$db->mysqli_real_escape($post["ispettoreTel"])."', '".$db->mysqli_real_escape($post["email"])."'";
                $query.=", ".$db->mysqli_real_escape($post["uottmp"]);
//                if($post["competenza"]!=""){
                    $query.=", ".$db->mysqli_real_escape($post["competenza"]);
//                }else{$query.=", '' ";}
                if($post["nroIspSGSAu"]!=""){
                    $query.=", ".$db->mysqli_real_escape($post["nroIspSGSAu"]);
                }else{$query.=", 0 ";}
                if($post["anni_SGS"]!=""){
                    $query.=", ".$db->mysqli_real_escape($post["anni_SGS"]);
                }else{$query.=", 0 ";}
                if($post["ispez_ud"]!=""){
                    $query.=", ".$db->mysqli_real_escape($post["ispez_ud"]);
                }else{$query.=", 0 ";}
                if($post["corsoformazione"]!=""){
                    $query.=", ".$db->mysqli_real_escape($post["corsoformazione"]);
                }else{$query.=", 1 ";}
               //11-03-2019 Aggiunto forzatura ruolo
                $flgnew=1;
                $k=$this->checkRuoloIspettore($db, $post,$flgnew);
                if($k==$post["flgRuolo"]){
                    $query.=", ".$k;
                }else{
                    $query.=", ".$post["flgRuolo"];
                }
                $query.=", ".$db->mysqli_real_escape($post["flgDispTrasferta"]);    
                $query.=", '".$db->mysqli_real_escape($post["noteIspettore"])."'";
                if($post["flgAttivo"]!=""){
                    $query.=", ".$db->mysqli_real_escape($post["flgAttivo"]);
                }else{
                    $query.=", 0";
                }
                $query.=")";
                echo $query;
                
        $return=$db->insert($query, true) or die($db->error());
        return $return;

    }
    
     public function updateIspettore($db,$post){
        
        $query = "UPDATE ispettore ";
        $query.=" SET ispettoreCognome='" . $db->mysqli_real_escape($post["ispettoreCognome"]) . "', ";
        $query.=" ispettoreNome='" . $db->mysqli_real_escape($post["ispettoreNome"]) . "', ";
        $query.=" ispettoreTel='" . $db->mysqli_real_escape($post["ispettoreTel"]) . "', ";
        $query.=" email='" . $db->mysqli_real_escape($post["email"]) . "', ";
        $query.=" uotIspIdFk=" . $db->mysqli_real_escape($post["uottmp"]). ", ";
        $query.=" compIdFk=" . $db->mysqli_real_escape($post["competenza"]) . ", ";
        $query.=" nroIspSGSAu=" . $db->mysqli_real_escape($post["nroIspSGSAu"]) . ", ";
        $query.=" anniEspSGSAu=" . $db->mysqli_real_escape($post["anni_SGS"]) . ", ";
        $query.=" nroIspUditAu=" . $db->mysqli_real_escape($post["ispez_ud"]) . ", ";
        $query.=" corsoIdAu=" . $db->mysqli_real_escape($post["corsoformazione"]) . ", ";
        //11-03-2019 Aggiunto forzatura ruolo
        $flgnew=0;
        $k=$this->checkRuoloIspettore($db, $post,$flgnew);
        if($k==$post["flgRuolo"]){
            $query.=" ruoloIdFk=".$k.", ";
        }else{
            $query.=" ruoloIdFk=".$post["flgRuolo"]. ", ";
        }
//        $query.=" ruoloIdFk=". $this->checkRuoloIspettore($db, $post) . ", ";
        $query.=" flgDispTrasferta=". $post["flgDispTrasferta"]. ", ";
        $query.=" noteIspettore='". $post["noteIspettore"]."', ";   //11-03-2019 aggiunto attivo
        $query.=" attivo=".$db->mysqli_real_escape($post["flgAttivo"]);
        
        $query.=" WHERE ispettoreId=" . $this->getIspettoreId();
        echo $query;
        $return = $db->query($query) or die($db->error());
        return $return;
    }
    
    public function updateRuoloUditoreInIspettore($db, $isp){
        $query = "UPDATE ispettore ";
        $query.=" SET ruoloIdFk=". $db->mysqli_real_escape($isp);
        $query.=" WHERE ispettoreId=" . $this->getIspettoreId();
        $return = $db->query($query) or die($db->error());
        return $return;
    }
    
    public function checkRuoloIspettore($db, $post,$flgnew){
        $k=3;
        
        //nsgs=numero ispezioni sgs nudiz=numero ispez come uditore da ispezioni registrate con rispe (dal 2016)
//        if($this->getIspettoreId()>0){
        if($flgnew==0){ //L'ispettore non è nuovo
            $k1=$this->contaIspezioniByUditore($db, $this->getIspettoreId(),1);
            $nudiz=0;
            while($row31= $db->fetchassoc2($k1)){ 
                if($db->mysqli_real_escape($row31["cont"])>0){
                    $nudiz=$db->mysqli_real_escape($row31["cont"]);
                }
            }
        }else{
            $nudiz=0;
        }
        
        $n1=$db->mysqli_real_escape($post["nroIspSGSAu"]) + $db->mysqli_real_escape($post["ispez_ud"]) + $nudiz;

        // criterio di esperti: nroIspSGSAu >= 5 oppure corso + nTot ispez >=3 oppure anniSGS + ispez_ud >= 2 oppure corso + ispez_ud >= 3
        if($db->mysqli_real_escape($post["nroIspSGSAu"]) >= 5){ //qui si deve legger il numero di ispezioni indicate dal DIT
            $k=1;
        }elseif(($n1 >= 3) && ($db->mysqli_real_escape($post["corsoformazione"])>1)){   //1 è l'indice di nessun corso
            $k=1;
        }elseif(($db->mysqli_real_escape($post["anni_SGS"]) >= 5) && (($db->mysqli_real_escape($post["ispez_ud"]) + $nudiz) >= 2)){  
            $k=1;
        }elseif((($db->mysqli_real_escape($post["ispez_ud"]) + $nudiz) >= 3) && ($db->mysqli_real_escape($post["corsoformazione"]) > 1)){
            $k=1;
        }elseif (($db->mysqli_real_escape($post["nroIspSGSAu"])==0) && ($db->mysqli_real_escape($post["anni_SGS"])==0) &&
                ($db->mysqli_real_escape($post["ispez_ud"])==0) && (($db->mysqli_real_escape($post["corsoformazione"])==1) )){ 
            $k=3;
        }elseif (($db->mysqli_real_escape($post["nroIspSGSAu"])==0) && ($db->mysqli_real_escape($post["anni_SGS"])==0) &&
                ($db->mysqli_real_escape($post["ispez_ud"])==0) && (($db->mysqli_real_escape($post["corsoformazione"])>1) )){ 
                $k=2;
        }else{
            $k=2;
        }

        return $k;
    }
    
    public function updateCheckRuoloIspettore($db){
        $k=3;
        
        //sgs=numero ispezioni sgs udiz=numero ispez come uditore da ricognizione
        $sgs=$this->getNroIspSGSAu();
        $udiz=$this->getNroIspUditAu();
        //nsgs=numero ispezioni sgs nudiz=numero ispez come uditore da ispezioni registrate con rispe (dal 2016)
        $k1=$this->contaIspezioniByUditore($db, $this->getIspettoreId(),1);
        $nudiz=0;
        while($row31= $db->fetchassoc2($k1)){ 
            if($db->mysqli_real_escape($row31["cont"])>0){
                $nudiz=$db->mysqli_real_escape($row31["cont"]);
            }
        }
        // criterio di esperti:
        //numero di ispezioni SGS >=5
        if(($sgs+$nudiz)>=5){
            $k=1;
        }elseif((($sgs+$nudiz)>=3) && ($this->getCorsoIdAu()!=1)){  
            //corso + nTot ispez >=3
            $k=1;
        }elseif(($sgs+$nudiz+$udiz)>=2){
            //anniSGS + ispez_ud >= 2
            $k=1;
        }elseif(($nudiz+$udiz)>=3 && ($this->getCorsoIdAu()!=1)){
            //corso + ispez_ud >= 3
            $k=1;
        }elseif((($sgs+$nudiz+$udiz)==0) && ($this->getCorsoIdAu()==1)){
            $k=3;
        }else{
            $k=2;
        }
       
        return $k;
    }
	
    public function getLastRecord($db) {
        $query = "SELECT ispettore.* FROM ispettore ";
        $query.="ORDER BY ispettore.ispettoreId DESC ";
        $return = $db->query($query) or die($db->error());
        return $return;
    }
    
    public function getListaIspettori($db,$ordinaPer) {
        $query = "SELECT ispettore.*, uot.uotDenominazione FROM ispettore, uot ";
        $query.= "WHERE (ispettore.uotIspIdFk = uot.uotId) ";
        //11-03-2019 solo quelli attivi
        $query.= " AND (ispettore.attivo=0) ";
        $query.= " ORDER BY ".$ordinaPer." ASC";
//        $query.=" ORDER BY uot.uotDenominazione ASC ";
        $return = $db->query($query) or die($db->error());
        return $return;
    }
    
    public function getListaIspettoriOut($db,$ordinaPer) {
        $query = "SELECT ispettore.*, uot.uotDenominazione FROM ispettore, uot ";
        $query.= "WHERE (ispettore.uotIspIdFk = uot.uotId) ";
        //11-03-2019 solo quelli NON attivi
        $query.= " AND (ispettore.attivo=1) ";
        $query.= " ORDER BY ".$ordinaPer." ASC";
        echo $query;
        $return = $db->query($query) or die($db->error());
        return $return;
    }
	
    public function getDettaglioIspettore($db) {
        $query = "SELECT ispettore.* FROM ispettore WHERE ispettore.ispettoreId=" . $this->getIspettoreId();
        $return = $db->query($query) or die($db->error());
        return $return;
    }
    
    public function getIspettoriByRegione($db,$regione){
        $query = "SELECT ispettore.* FROM ispettore";
        $query.=" JOIN uot ON (ispettore.uotIspIdFk=uot.uotId)";
        $query.=" JOIN provincia ON (uot.provinciaFkId=provincia.provinciaId)";
        $query.=" JOIN regione ON ((provincia.regioneIdFk=regione.regioneId) AND (regione.nomeregioneLIKE '%".$db->mysqli_real_escape($regione). "%'))";
        
        return $return;
    }
    
    public function getSearchIspettori($db, $post,$ordinaPer){  
        //22-01-2019 modificato l'ordine per i risultati
        $query ="SELECT DISTINCT ispettore.*, uot.uotDenominazione ";
        $query.="FROM ispettore, uot ";
        if($post["searchregione"] != ""){
            $query.=" JOIN provincia ON (uot.provinciaFkId=provincia.provinciaId)";
            $query.=" JOIN regione ON ((provincia.regioneIdFk=regione.regioneId) AND (regione.nomeregione LIKE '%".$db->mysqli_real_escape($post["searchregione"]). "%')) ";
        }
        $query.="WHERE (ispettore.uotIspIdFk = uot.uotId) ";
        if($post["searchispettori"] != ""){
            $query.=" AND (ispettore.ispettoreCognome LIKE '%".$db->mysqli_real_escape($post["searchispettori"]). "%' )";
        }
        if($post["searchuot"] != ""){
            $query.=" AND (uot.uotDenominazione LIKE '%".$db->mysqli_real_escape($post["searchuot"]). "%' ) ";
        }
        //11-03-2019 solo quelli attivi
        $query.= " AND (ispettore.attivo=0) ";
//        $query.=" ORDER BY uot.uotDenominazione ASC ";
        $query.= " ORDER BY ".$ordinaPer." ASC";
        $return = $db->query($query) or die($db->error());
        return $return;
    }
    
    public function old_getSearchIspettori($db, $post){
        $query ="SELECT DISTINCT ispettore.*, uot.uotDenominazione ";
        $query.="FROM ispettore, uot ";
        $query.="WHERE (ispettore.uotIspIdFk = uot.uotId) ";
        if($post["searchispettori"] != ""){
            $query.=" AND (ispettore.ispettoreCognome LIKE '%".$db->mysqli_real_escape($post["searchispettori"]). "%' )";
        }
        if($post["searchuot"] != ""){
            $query.=" AND (uot.uotDenominazione LIKE '%".$db->mysqli_real_escape($post["searchuot"]). "%' ) ";
        }
        $query.=" ORDER BY uot.uotDenominazione ASC ";
        $return = $db->query($query) or die($db->error());
        return $return;
    }
    
    public function getIspettoreById($db,$id){
        $query = "SELECT ispettore.* FROM ispettore ";
        $query.= "WHERE ispettore.ispettoreId = ".$id ;
        $return = $db->query($query) or die($db->error());
        return $return;
    }
    
    public function getIspettoriByUot($db,$id,$ruolo){
        $query = "SELECT DISTINCT ispettore.* FROM ispettore ";
        $query.= "WHERE (ispettore.uotIspIdFk = ".$id.")";
        if($ruolo==1){
            $query.= " AND (ispettore.ruoloIdFk=".$ruolo.")";
        }elseif($ruolo>0){  //caso di uditore oppure nuovo
            $query.= " AND ((ispettore.ruoloIdFk=2) || (ispettore.ruoloIdFk=3))";
        }
        //11-03-2019
        $query.= " AND (ispettore.attivo=0) ";
        $query.= " ORDER BY ispettore.ispettoreCognome";
        $return = $db->query($query) or die($db->error());
        return $return;
    }
    
    public function getBigliettoVisita($db, $idIspettore){
        $query = "SELECT ispettore.ispettoreCognome AS Cognome, ispettore.ispettoreNome AS Nome, uot.uotDenominazione AS UOT,";
        $query.= " uot.uotIndirizzo AS Indirizzo, uot.uotCap AS cap, provincia.prov AS Provincia, ";
        $query.= " uot.uotPec AS PEC, ispettore.email AS email, uot.uotTelefono AS Tel, uot.uotFax AS Fax";
        $query.= " FROM ispettore";
        $query.= " JOIN uot ON uot.uotId=ispettore.uotIspIdFk";
        $query.= " JOIN provincia ON provincia.provinciaId=uot.provinciaFkId";
        $query.= " WHERE ispettore.ispettoreId=".$idIspettore;
        $return = $db->query($query) or die($db->error());
        return $return;
    }
    
    public function getElencoTotaleIspettori($db){  //modificato 27/01/2017 aggiunto flgDispTrasferta
        $query = "SELECT  uot.uotDenominazione AS UOT, regione.nomeregione AS Regione, ruolo.ruolo AS Ruolo, competenze.competenza AS Competenza,";
        $query.= " ispettore.ispettoreId AS ispett, ispettore.ispettoreCognome AS Cognome, ispettore.ispettoreNome AS Nome, ispettore.flgDispTrasferta, ";
        $query.= " ispettore.email AS email, ispettore.nroIspSGSAu AS SGSPIR, ispettore.anniEspSGSAu AS SGS, ispettore.nroIspUditAu as UDIT, ispettore.noteIspettore AS Note, ";
        $query.= " corsoformazione.titolo AS corso, corsoformazione.anno AS annocorso FROM ispettore";
        $query.= " JOIN uot ON uot.uotId=ispettore.uotIspIdFk";
        $query.= " JOIN uot_regione ON uot_regione.uotIdFk=uot.uotId";
        $query.= " JOIN regione ON regione.regioneId=uot_regione.regioneIdFk";
        $query.= " JOIN ruolo ON ruolo.ruoloId=ispettore.ruoloIdFk";
        $query.= " JOIN competenze ON competenze.competenzaId=ispettore.compIdFk";
        $query.= " JOIN corsoformazione ON corsoformazione.corsoId=ispettore.corsoIdAu";
        //11-03-2019
        $query.= " WHERE (ispettore.attivo=0) ";
        $query.= " ORDER BY regione.nomeregione ASC, uot.uotDenominazione ASC, ruolo.ruoloId ASC";
        
        $return = $db->query($query) or die($db->error());
        return $return;
    }
    
    public function getElencoTotaleNoteIspettori($db){
        $query = "SELECT  uot.uotDenominazione AS UOT, regione.nomeregione AS Regione, ruolo.ruolo AS Ruolo,";
        $query.= " ispettore.ispettoreId AS ispett, ispettore.ispettoreCognome AS Cognome, ispettore.ispettoreNome AS Nome, ispettore.noteIspettore AS Note FROM ispettore";
        $query.= " JOIN uot ON uot.uotId=ispettore.uotIspIdFk";
        $query.= " JOIN uot_regione ON uot_regione.uotIdFk=uot.uotId";
        $query.= " JOIN regione ON regione.regioneId=uot_regione.regioneIdFk";
        $query.= " JOIN ruolo ON ruolo.ruoloId=ispettore.ruoloIdFk";
        //11-03-2019
        $query.= " WHERE (ispettore.attivo=0) ";
        $query.= " ORDER BY regione.nomeregione ASC, uot.uotDenominazione ASC, ruolo.ruoloId ASC";
        
        $return = $db->query($query) or die($db->error());
        return $return;
    }
    
    public function getElencoEsperienzeIspettore($db, $idIspettore){    //31-gen-2017
        $query = "SELECT DISTINCT attivitaindustriale.attivita AS Esperienza FROM ispezione, stabilimento, attivitaindustriale";
        $query.= " WHERE ((ispezione.ispettIdFk =".$db->mysqli_real_escape($idIspettore).")";
        $query.= " OR (ispezione.uditIdFk=".$db->mysqli_real_escape($idIspettore)."))"; //modificata il 28/04/2017
        $query.= " AND (ispezione.stabIdFk=stabilimento.stabilimentoId)";
        $query.= " AND (stabilimento.attivIndustrialeIdFk=attivitaindustriale.attivitaindustrialeId)";
        $query.= " AND (ispezione.statoIdFk!=4)";   //NON SI CONTANO LE ISPEZIONI SOSPESE
        
        $return = $db->query($query) or die($db->error());
        return $return;
    }
    
    public function getElencoEsperienzeUditore($db, $idUditore){    //31-gen-2017
        $query = "SELECT DISTINCT attivitaindustriale.attivita AS Esperienza FROM ispezione, stabilimento, attivitaindustriale";
        $query.= " WHERE (ispezione.uditIdFk=".$db->mysqli_real_escape($idUditore).")";
        $query.= " AND (ispezione.stabIdFk=stabilimento.stabilimentoId)";
        $query.= " AND (stabilimento.attivIndustrialeIdFk=attivitaindustriale.attivitaindustrialeId)";
        
        $return = $db->query($query) or die($db->error());
        return $return;
    }
    
    public function contaIspezioniByIspettore($db, $idIspettore, $statoIsp){
        $query = "SELECT COUNT(*) AS cont FROM ispezione, ispettore ";
        $query.= " WHERE (ispettore.ispettoreId=".$db->mysqli_real_escape($idIspettore).")";
        $query.= " AND (ispezione.ispettIdFk=ispettore.ispettoreId)";
        if($statoIsp==1){
            $query.= " AND ((ispezione.statoIdFk=".$db->mysqli_real_escape($statoIsp).") OR (ispezione.statoIdFk=5))";
        }else{
            $query.= " AND (ispezione.statoIdFk=".$db->mysqli_real_escape($statoIsp).")";
        }
        $return = $db->query($query) or die($db->error());
        return $return;
    }
    
    public function contaIspezioniByIspettoreAnno($db, $idIspettore, $statoIsp, $anno){    //NUOVA 02-02-2017
        $query = "SELECT COUNT(*) AS cont FROM ispezione, ispettore ";
        $query.= " WHERE (ispettore.ispettoreId=".$db->mysqli_real_escape($idIspettore).")";
        $query.= " AND (ispezione.ispettIdFk=ispettore.ispettoreId)";
        if($statoIsp==1){       //aggiiunto il constrollo se conclusa e /o archiviata
            $query.= " AND ((ispezione.statoIdFk=".$db->mysqli_real_escape($statoIsp).")";
            $query.= " OR (ispezione.statoIdFk=5))";
        }else{
            $query.= " AND (ispezione.statoIdFk=".$db->mysqli_real_escape($statoIsp).")";
        }
        $query.= " AND (ispezione.anno=".$db->mysqli_real_escape($anno).")";
        
        $return = $db->query($query) or die($db->error());
        return $return;
    }
    
    public function contaIspezioniByUditore($db, $idIspettore, $statoIsp){
        $query = "SELECT COUNT(*) AS cont FROM ispezione, ispettore ";
        $query.= " WHERE (ispettore.ispettoreId=".$db->mysqli_real_escape($idIspettore).")";
        $query.= " AND (ispezione.uditIdFk=ispettore.ispettoreId)";
        if ($db->mysqli_real_escape($statoIsp)==1){
            $query.= " AND ((ispezione.statoIdFk=1) OR (ispezione.statoIdFk=5))";
        }else{
            $query.= " AND (ispezione.statoIdFk=".$db->mysqli_real_escape($statoIsp).")";
        }
        
        $return = $db->query($query) or die($db->error());
        return $return;
    }
    
    public function contaIspezioniByUditoreAnno($db, $idIspettore, $statoIsp, $anno){    //NUOVA 02-02-2017
        $query = "SELECT COUNT(*) AS cont FROM ispezione, ispettore ";
        $query.= " WHERE (ispettore.ispettoreId=".$db->mysqli_real_escape($idIspettore).")";
        $query.= " AND (ispezione.uditIdFk=ispettore.ispettoreId)";
        if($statoIsp==1){       //aggiiunto il constrollo se conclusa e /o archiviata
            $query.= " AND ((ispezione.statoIdFk=".$db->mysqli_real_escape($statoIsp).")";
            $query.= " OR (ispezione.statoIdFk=5))";
        }else{
            $query.= " AND (ispezione.statoIdFk=".$db->mysqli_real_escape($statoIsp).")";
        }
        $query.= " AND (ispezione.anno=".$db->mysqli_real_escape($anno).")";
        $return = $db->query($query) or die($db->error());
        return $return;
    }
    
    public function contaIspezioniProposteByIspettore ($db, $idIspettore, $statoIsp){
        $query = "SELECT COUNT(*) AS cont FROM ispettore ";
        $query.= " JOIN ispezione ON (ispezione.statoIdFk=".$db->mysqli_real_escape($statoIsp).")";
        $query.= " JOIN propostaispezione ON ((propostaispezione.propIspettDaUotIdFk=ispettore.ispettoreId)";
	$query.= " OR (propostaispezione.propIspettIdFk=ispettore.ispettoreId))";
        $query.= " AND (propostaispezione.ispezioneIdFk=ispezione.ispezioneId)";
	$query.= " AND (ispettore.ispettoreId=".$db->mysqli_real_escape($idIspettore).")";
        $return = $db->query($query) or die($db->error());
        return $return;
    }
    public function contaIspezioniProposteByIspettoreAnno ($db, $idIspettore, $statoIsp,$anno){ //NUOVA 02-02-2017
        $query = "SELECT COUNT(*) AS cont FROM ispettore ";
        $query.= " JOIN ispezione ON (ispezione.statoIdFk=".$db->mysqli_real_escape($statoIsp).")";
        $query.= " JOIN propostaispezione ON ((propostaispezione.propIspettDaUotIdFk=ispettore.ispettoreId)";
	$query.= " OR (propostaispezione.propIspettIdFk=ispettore.ispettoreId))";
        $query.= " AND (propostaispezione.ispezioneIdFk=ispezione.ispezioneId)";
	$query.= " AND (ispettore.ispettoreId=".$db->mysqli_real_escape($idIspettore).")";
        $query.= " AND (ispezione.anno=".$anno.")";
        
        $return = $db->query($query) or die($db->error());
        return $return;
    }
    
    public function contaIspettori($db,$stato){
        $query = "SELECT DISTINCT COUNT(*) AS cont FROM ispettore ";
        $query.= " WHERE (ispettore.ruoloIdFk=".$db->mysqli_real_escape($stato).")";
        $return = $db->query($query) or die($db->error());
        return $return;
    }
    
    public function contaIspettoriAnno($db,$stato, $annocurr){
        $query = "SELECT DISTINCT COUNT(*) AS cont FROM ispettore ";
        $query.= " WHERE (ispettore.ruoloIdFk=".$db->mysqli_real_escape($stato).")";
        $return = $db->query($query) or die($db->error());
        return $return;
    }
    
    public function rifiutoIspettoreDiIspezione($db,$ispett,$ispez){
        //28/03/2017
        $query="INSERT INTO ispettorerifiutaispezione VALUES (".$ispett.", ".$ispez.")";
        $return = $db->query($query) or die($db->error());
        return $return;
    }
    
    public function deleteRifiutoIspettoreDiIspezione($db,$ispett,$ispez){
        //28/03/2017
        $query="DELETE FROM ispettorerifiutaispezione WHERE (idispettore= ".$ispett.") AND (idispezione= ".$ispez.")";
        $return = $db->query($query) or die($db->error());
        return $return;
    }
   
    
}
?>