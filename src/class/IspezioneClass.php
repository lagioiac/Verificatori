<?php

/**
 * Description of ispezionePClass
 *
 * @author xf46260
 * periodo modificato in tipoispez 15-02-2017
 * 
 */
class IspezioneClass {
    private $ispezioneId=null;
    private $anno=null;
    private $stabIdFk=null;
    private $ispettIdFk=null;
    private $uditIdFk=null;
    private $statoIdFk=null;
    private $tipoispez=null;
    private $data_assegnaz=null;
    private $data_fineispez=null;
    private $noteIspezione=null;
    private $rcdoc=null;
    private $eodoc=null;
    private $stdoc=null;
    private $aldoc=null;
    private $midoc=null;    //22-01-2019 - aggiunto
    
    function getIspezioneId(){
            return $this->ispezioneId;
    }
    function getAnno(){
            return $this->anno;
    }
    function getStabIdFk(){
            return $this->stabIdFk;
    }
    function getIspettIdFk(){
            return $this->ispettIdFk;
    }
    function getUditIdFk(){
            return $this->uditIdFk;
    }
    function getStatoIdFk(){
            return $this->statoIdFk;
    }
    function getTipoispez(){
            return $this->tipoispez;
    }
    function getData_assegnaz(){
            return $this->data_assegnaz;
    }
    function getData_fineispez(){
            return $this->data_fineispez;
    }
    function getNoteIspezione(){
            return $this->noteIspezione;
    }
    function getRcdoc(){
            return $this->rcdoc;
    }
    function getStdoc(){
            return $this->stdoc;
    }
    function getEodoc(){
            return $this->eodoc;
    }
    function getAldoc(){
            return $this->aldoc;
    }
    function getMidoc(){    //22-01-2019 - aggiunto
            return $this->midoc;
    }
    
    function setIspezioneId($ispezioneId) {
        $this->ispezioneId = $ispezioneId;
    }
    function setAnno($anno) {
        $this->anno = $anno;
    }
    function setStabIdFk($stabIdFk) {
        $this->stabIdFk = $stabIdFk;
    }
    function setIspettIdFk($ispettIdFk) {
        $this->ispettIdFk = $ispettIdFk;
    }
    function setUditIdFk($uditIdFk) {
        $this->uditIdFk = $uditIdFk;
    }
    function setStatoIdFk($statoIdFk) {
        $this->statoIdFk = $statoIdFk;
    }
    function setTipoispez($tipoispez) {
        $this->tipoispez = $tipoispez;
    }
    function setData_assegnaz($data_assegnaz) {
        $this->data_assegnaz = $data_assegnaz;
    }
    function setData_fineispez($data_fineispez) {
        $this->data_fineispez = $data_fineispez;
    }
    function setNoteIspezione($noteIspezione) {
        $this->noteIspezione = $noteIspezione;
    }
    function setRcdoc($rcdoc) {
        $this->rcdoc = $rcdoc;
    }
    function setStdoc($stdoc) {
        $this->stdoc = $stdoc;
    }
    function setEodoc($eodoc) {
        $this->eodoc = $eodoc;
    }
    function setAldoc($aldoc) {
        $this->aldoc = $aldoc;
    }
    function setMidoc($midoc) {     //22-01-2019 - aggiunto
        $this->midoc = $midoc;
    }
    
    
    public function getListaIspezioni($db) { 
        $query = "SELECT ispezione.* FROM ispezione ";
        $query.=" JOIN stabilimento ON ispezione.stabIdFk=stabilimento.stabilimentoId ";
        $query.=" JOIN uot ON uot.uotId=stabilimento.uotAffIdFk ";
        $query.=" JOIN comune ON stabilimento.comuneIdFk=comune.comuneId ";
        $query.=" JOIN provincia ON comune.provIdFk=provincia.provinciaId";
        $query.=" JOIN regione ON (provincia.regioneIdFk=regione.regioneId) ";
        
        //modificato 01-02-2017 visualizza solo quelle assegnate, da pianificare, NON visualizza le archiviate concluse o sospese
//         modifica del 21/04/2017
        $query.=" WHERE ((ispezione.statoIdFk!=1) AND (ispezione.statoIdFk!=4) AND (ispezione.statoIdFk!=5))";
        
        $query.=" ORDER BY ispezione.anno DESC, ispezione.statoIdFk DESC, regione.nomeregione ASC, uot.uotDenominazione ASC, stabilimento.stabilimentoDenominazione ASC ";
        $return = $db->query($query) or die($db->error());
        return $return;
    }
    
    public function getIspezioniPerStabilimento($db,$stabId){
        $query ="SELECT ispezione.* FROM ispezione, stabilimento ";
        $query.=" WHERE (ispezione.stabIdFk=stabilimento.stabilimentoId) ";
        $query.=" AND (stabilimento.stabilimentoId=".$db->mysqli_real_escape($stabId).")";
        $query.=" ORDER BY ispezione.anno DESC";
        $return = $db->query($query) or die($db->error());
        return $return;
    }
    
    public function getDettaglioIspezione($db) {
        $query = "SELECT ispezione.* FROM ispezione WHERE ispezione.ispezioneId=" . $this->getIspezioneId();
        $return = $db->query($query) or die($db->error());
        return $return;
    }
    
    public function getLastRecord($db) {
        $query = "SELECT ispezione.* FROM ispezione ";
        $query.="ORDER BY ispezione.ispezioneId DESC LIMIT 1";
        $return = $db->query($query) or die($db->error());
        return $return;
    }
    
    public function getIspezioniByUot($db,$id) {
        $query = "SELECT ispezione.* FROM ispezione ";
        $query.= " JOIN stabilimento ON stabilimento.stabilimentoId=ispezione.stabIdFk";
        $query.= " JOIN uot ON (uot.uotId=stabilimento.uotAffIdFk)";
        $query.= " AND (uot.uotId=".$db->mysqli_real_escape($id).")";
        $query.=" ORDER BY ispezione.anno DESC ";
        $return = $db->query($query) or die($db->error());
        return $return;
    }
    
    public function getIspezioniByIspettoreUditore($db,$id) {   //MODIFICATA 24/02/2017
        $query = "SELECT DISTINCT ispezione.* FROM ispezione, ispettore ";
        $query.= " WHERE ((ispezione.ispettIdFk=".$db->mysqli_real_escape($id).")";
        $query.= " OR (ispezione.uditIdFk=".$db->mysqli_real_escape($id)."))";
        $query.=" ORDER BY ispezione.anno DESC, ispezione.statoIdFk ASC ";
        $return = $db->query($query) or die($db->error());
        return $return;
    }
    
    public function getIspezioniDaPianificareByUot($db,$id,$anno) { // MODIFICA 02-02-2017 aggiunto parametro anno
        $query = "SELECT ispezione.* FROM ispezione ";
        $query.= " JOIN stabilimento ON stabilimento.stabilimentoId=ispezione.stabIdFk";
        $query.= " JOIN uot ON (uot.uotId=stabilimento.uotAffIdFk)";
        $query.= " AND (uot.uotId=".$db->mysqli_real_escape($id).") AND ispezione.statoIdFk=3";
        
        $query.= " WHERE (ispezione.anno=".$db->mysqli_real_escape($anno).")";    //NUOVA
        
        $query.=" ORDER BY ispezione.anno DESC ";
        $return = $db->query($query) or die($db->error());
        return $return;
    }
    
    
    public function getSearchIspezioni($db, $post){  
                
        $query ="SELECT ispezione.* FROM ispezione ";
        $query.=" JOIN stabilimento ON ispezione.stabIdFk=stabilimento.stabilimentoId ";
        if($post["searchattiv"] != ""){
            $query.=" JOIN attivitaindustriale ON (stabilimento.attivIndustrialeIdFk=attivitaindustriale.attivitaindustrialeId) ";
            $query.=" AND (attivitaindustriale.attivita LIKE '%".$db->mysqli_real_escape($post["searchattiv"]). "%' ) ";
        }
        if($post["searchstato"] != ""){
            $query.=" JOIN statoispezione ON (ispezione.statoIdFk=statoispezione.statoId) ";
            $query.=" AND (statoispezione.stato LIKE '%".$db->mysqli_real_escape($post["searchstato"]). "%' ) ";
        }
        if($post["searchuot"] != ""){
            $query.=" JOIN uot ON (stabilimento.uotAffIdFk=uot.uotId) ";
            $query.=" AND (uot.uotDenominazione LIKE '%".$db->mysqli_real_escape($post["searchuot"]). "%' ) ";
        }
        if($post["searchispet"] != ""){
            $query.=" JOIN ispettore ON ((ispezione.ispettIdFk=ispettore.ispettoreId) OR (ispezione.uditIdFk=ispettore.ispettoreId)) ";
            $query.=" AND (ispettore.ispettoreCognome LIKE '%".$db->mysqli_real_escape($post["searchispet"]). "%' ) ";
        }
        if($post["searchreg"] != ""){
            $query.=" JOIN comune ON stabilimento.comuneIdFk=comune.comuneId ";
            $query.=" JOIN provincia ON comune.provIdFk=provincia.provinciaId";
            $query.=" JOIN regione ON ((provincia.regioneIdFk=regione.regioneId) AND ";
            $query.=" (regione.nomeregione LIKE '%".$db->mysqli_real_escape($post["searchreg"]). "%' )) ";
        }
        $query.=" WHERE ((ispezione.statoIdFk=2) OR (ispezione.statoIdFk=3)) ";
        $query.=" ORDER BY ispezione.anno DESC, ispezione.statoIdFk DESC, stabilimento.stabilimentoDenominazione ASC ";
        
        $return = $db->query($query) or die($db->error());
        return $return;
    }
    
        public function getSearchIspezioniConcluse($db, $post){
                
        $query ="SELECT ispezione.* FROM ispezione ";
        $query.=" JOIN stabilimento ON ispezione.stabIdFk=stabilimento.stabilimentoId ";
        if($post["searchattiv"] != ""){
            $query.=" JOIN attivitaindustriale ON (stabilimento.attivIndustrialeIdFk=attivitaindustriale.attivitaindustrialeId) ";
            $query.=" AND (attivitaindustriale.attivita LIKE '%".$db->mysqli_real_escape($post["searchattiv"]). "%' ) ";
        }
        if($post["searchstato"] != ""){
            $query.=" JOIN statoispezione ON (ispezione.statoIdFk=statoispezione.statoId) ";
            $query.=" AND (statoispezione.stato LIKE '%".$db->mysqli_real_escape($post["searchstato"]). "%' ) ";
        }
        if($post["searchuot"] != ""){
            $query.=" JOIN uot ON (stabilimento.uotAffIdFk=uot.uotId) ";
            $query.=" AND (uot.uotDenominazione LIKE '%".$db->mysqli_real_escape($post["searchuot"]). "%' ) ";
        }
        if($post["searchispet"] != ""){
            $query.=" JOIN ispettore ON ((ispezione.ispettIdFk=ispettore.ispettoreId) OR (ispezione.uditIdFk=ispettore.ispettoreId)) ";
            $query.=" AND (ispettore.ispettoreCognome LIKE '%".$db->mysqli_real_escape($post["searchispet"]). "%' ) ";
        }
        if($post["searchreg"] != ""){
            $query.=" JOIN comune ON stabilimento.comuneIdFk=comune.comuneId ";
            $query.=" JOIN provincia ON comune.provIdFk=provincia.provinciaId";
            $query.=" JOIN regione ON ((provincia.regioneIdFk=regione.regioneId) AND ";
            $query.=" (regione.nomeregione LIKE '%".$db->mysqli_real_escape($post["searchreg"]). "%' )) ";
        }
//        modifica del 21/04/2017
        $query.=" WHERE (ispezione.statoIdFk=1) OR (ispezione.statoIdFk=5)";    
        $query.=" ORDER BY ispezione.anno DESC, ispezione.statoIdFk DESC, stabilimento.stabilimentoDenominazione ASC ";
        
        $return = $db->query($query) or die($db->error());
        return $return;
    }
        
    public function getIspezioniAssegnate($db){ //modificata 16-02-2017: aggiunto tipoispezione
        $query = " SELECT DISTINCT ispezione.ispezioneId, ispezione.anno AS Anno, stabilimento.stabilimentoDenominazione AS Azienda, attivitaindustriale.attivita AS Attivita,";
        $query.= " comune.comuneNome AS Comune, provincia.prov AS Provincia, regione.nomeregione AS Regione,";
        $query.= " ispezione.ispettIdFk AS Ispettore, ispezione.uditIdFk AS Uditore, ispezione.noteIspezione AS Note, tipoispezione.tipoispezione AS TipoIspez FROM ispezione";
        $query.= " JOIN stabilimento ON stabilimento.stabilimentoId = ispezione.stabIdFk";
        $query.= " JOIN attivitaindustriale ON attivitaindustriale.attivitaindustrialeId = stabilimento.attivIndustrialeIdFk";
        $query.= " JOIN comune ON comune.comuneId = stabilimento.comuneIdFk";
        $query.= " JOIN provincia ON provincia.provinciaId = comune.provIdFk";
        $query.= " JOIN regione ON regione.regioneId = provincia.regioneIdFk";
        $query.= " JOIN statoispezione ON ispezione.statoIdFk=2";
        $query.= " JOIN tipoispezione ON ispezione.tipoispez=tipoispezione.tipoispezioneId";
        $query.= " ORDER BY ispezione.anno DESC, regione.nomeregione ASC";
        $return = $db->query($query) or die($db->error());
        return $return;
    }
    
    public function getIspezioniDaPianificare($db){ //modificata 16-02-2017: aggiunto tipoispezione
        $query = " SELECT DISTINCT ispezione.ispezioneId, ispezione.anno AS Anno, stabilimento.stabilimentoDenominazione AS Azienda, attivitaindustriale.attivita AS Attivita,";
        $query.= " comune.comuneNome AS Comune, provincia.prov AS Provincia, regione.nomeregione AS Regione,";
        $query.= " ispezione.ispettIdFk AS Ispettore, ispezione.uditIdFk AS Uditore, ispezione.noteIspezione AS Note, tipoispezione.tipoispezione AS TipoIspez FROM ispezione";
        $query.= " JOIN stabilimento ON stabilimento.stabilimentoId = ispezione.stabIdFk";
        $query.= " JOIN attivitaindustriale ON attivitaindustriale.attivitaindustrialeId = stabilimento.attivIndustrialeIdFk";
        $query.= " JOIN comune ON comune.comuneId = stabilimento.comuneIdFk";
        $query.= " JOIN provincia ON provincia.provinciaId = comune.provIdFk";
        $query.= " JOIN regione ON regione.regioneId = provincia.regioneIdFk";
        $query.= " JOIN statoispezione ON ispezione.statoIdFk=3";
        $query.= " JOIN tipoispezione ON ispezione.tipoispez=tipoispezione.tipoispezioneId";
        $query.= " ORDER BY ispezione.anno DESC, regione.nomeregione ASC";
        $return = $db->query($query) or die($db->error());
        return $return;
    }
    
    public function getIspezioniConcluseAnno($db, $annocurr){   //12-07-2019 AGGIUNTO IL DOC METODO INDICI INVECCHIAMENTO
        $query = "SELECT DISTINCT ispezione.*, stabilimento.stabilimentoId, stabilimento.stabilimentoDenominazione AS Azienda, attivitaindustriale.attivita AS Attivita,";
        $query.= " comune.comuneNome AS Comune, provincia.prov AS Provincia, regione.nomeregione AS Regione,";
        $query.= " ispezione.ispettIdFk AS Ispettore, ispezione.uditIdFk AS Uditore, ispezione.noteIspezione AS Note, ";
        $query.= " ispezione.rcdoc, ispezione.eodoc, ispezione.stdoc, ispezione.aldoc, ispezione.midoc FROM ispezione ";
        $query.= " JOIN stabilimento ON stabilimento.stabilimentoId = ispezione.stabIdFk";
        $query.= " JOIN attivitaindustriale ON attivitaindustriale.attivitaindustrialeId = stabilimento.attivIndustrialeIdFk";
        $query.= " JOIN comune ON comune.comuneId = stabilimento.comuneIdFk";
        $query.= " JOIN provincia ON provincia.provinciaId = comune.provIdFk";
        $query.= " JOIN regione ON regione.regioneId = provincia.regioneIdFk";
        $query.= " JOIN statoispezione ON (ispezione.statoIdFk=1) OR (ispezione.statoIdFk=5)"; //modificato il 21/04/2017
        $query.= " WHERE (ispezione.anno=".$db->mysqli_real_escape($annocurr).")";
        $query.= " ORDER BY regione.nomeregione ASC";
//        $query.= " ORDER BY ispezione.anno DESC, regione.nomeregione ASC";
        $return = $db->query($query) or die($db->error());
        return $return;
    }
    
    public function getIspezioniSospeseAnno($db, $annocurr){
        $query = "SELECT DISTINCT ispezione.*, stabilimento.stabilimentoId, stabilimento.stabilimentoDenominazione AS Azienda, attivitaindustriale.attivita AS Attivita,";
        $query.= " comune.comuneNome AS Comune, provincia.prov AS Provincia, regione.nomeregione AS Regione,";
        $query.= " ispezione.ispettIdFk AS Ispettore, ispezione.uditIdFk AS Uditore, ispezione.noteIspezione AS Note FROM ispezione";
        $query.= " JOIN stabilimento ON stabilimento.stabilimentoId = ispezione.stabIdFk";
        $query.= " JOIN attivitaindustriale ON attivitaindustriale.attivitaindustrialeId = stabilimento.attivIndustrialeIdFk";
        $query.= " JOIN comune ON comune.comuneId = stabilimento.comuneIdFk";
        $query.= " JOIN provincia ON provincia.provinciaId = comune.provIdFk";
        $query.= " JOIN regione ON regione.regioneId = provincia.regioneIdFk";
        $query.= " JOIN statoispezione ON ispezione.statoIdFk=4";
        $query.= " WHERE (ispezione.anno=".$db->mysqli_real_escape($annocurr).")";
        $query.= " ORDER BY regione.nomeregione ASC";
//        $query.= " ORDER BY ispezione.anno DESC, regione.nomeregione ASC";
        $return = $db->query($query) or die($db->error());
        return $return;
    }
    
    public function getIspezioniStessaUOTAnno($db,$anno,$stato){   //NUOVA 27/02/2017
        $query = "SELECT DISTINCT ispezione.* FROM ispezione ";
        $query.= " JOIN stabilimento ON (ispezione.stabIdFk=stabilimento.stabilimentoId) ";
        $query.= " JOIN ispettore ON (ispezione.ispettIdFk=ispettore.ispettoreId) ";
        $query.= " JOIN uot ON (stabilimento.uotAffIdFk=uot.uotId) AND (ispettore.uotIspIdFk=uot.uotId) ";
        
        if($stato==0){
            $query.= " WHERE ((ispezione.statoIdFk=1) OR (ispezione.statoIdFk=2) OR (ispezione.statoIdFk=5))"; //modifica del 21/04/2017
        }elseif($stato==1){  //
            $query.= " WHERE ((ispezione.statoIdFk=1) OR (ispezione.statoIdFk=5))"; //modifica del 21/04/2017
        }elseif($stato==2){  //
            $query.= " WHERE (ispezione.statoIdFk=2) ";
        }
        $query.=" AND (ispezione.anno=".$db->mysqli_real_escape($anno).")";

        $return = $db->query($query) or die($db->error());
        return $return;
    }
    
    public function getIspezioniDiversaUOTAnno($db,$anno,$stato){   //NUOVA 27/02/2017
        $query = "SELECT DISTINCT ispezione.* FROM ispezione ";
        $query.= " JOIN stabilimento ON (ispezione.stabIdFk=stabilimento.stabilimentoId) ";
        $query.= " JOIN ispettore ON (ispezione.ispettIdFk=ispettore.ispettoreId) ";
        $query.= " JOIN uot ON (stabilimento.uotAffIdFk=uot.uotId) AND (ispettore.uotIspIdFk!=uot.uotId) ";
        
        if($stato==0){
            $query.= " WHERE ((ispezione.statoIdFk=1) OR (ispezione.statoIdFk=2) OR (ispezione.statoIdFk=5))"; //modifica del 21/04/2017
        }elseif($stato==1){  //
            $query.= " WHERE ((ispezione.statoIdFk=1) OR (ispezione.statoIdFk=5)) "; //modifica del 21/04/2017
        }elseif($stato==2){  //
            $query.= " WHERE (ispezione.statoIdFk=2) ";
        }
        $query.=" AND (ispezione.anno=".$db->mysqli_real_escape($anno).")";

        $return = $db->query($query) or die($db->error());
        return $return;
    }
    
    public function getElencoTotaleNoteIspezioni($db){
        $query = "SELECT DISTINCT ispezione.ispezioneId, ispezione.anno AS Anno, stabilimento.stabilimentoDenominazione AS Azienda, attivitaindustriale.attivita AS Attivita,";
        $query.= " comune.comuneNome AS Comune,";
        $query.= " ispezione.noteIspezione AS Note FROM ispezione";
        $query.= " JOIN stabilimento ON stabilimento.stabilimentoId = ispezione.stabIdFk";
        $query.= " JOIN attivitaindustriale ON attivitaindustriale.attivitaindustrialeId = stabilimento.attivIndustrialeIdFk";
        $query.= " JOIN comune ON comune.comuneId = stabilimento.comuneIdFk";
        $query.= " ORDER BY ispezione.anno DESC, stabilimento.stabilimentoDenominazione ASC";
        $return = $db->query($query) or die($db->error());
        return $return;
    }
    
    public function getIspezioneRC($db){
        $query = "SELECT ispezione.rcdoc FROM ispezione WHERE ispezione.ispezioneId=" . $this->getIspezioneId();
        $return = $db->query($query) or die($db->error());
        return $return;
    }
    
    public function getAnnoIspezioniAssegnate($db){//31-01-2017
        $query = "SELECT DISTINCT anno FROM ispezione";
        $query.= " WHERE (ispezione.statoIdFk=2)";
        $query.= " OR (ispezione.statoIdFk=3)";
        $query.= " ORDER BY ispezione.anno ASC";
        
        $return = $db->query($query) or die($db->error());
        return $return;
    }
    
    public function getAnnoIspezioniConcluse($db){//31-01-2017
        $query = "SELECT DISTINCT anno FROM ispezione";
        $query.= " WHERE (ispezione.statoIdFk=1) OR (ispezione.statoIdFk=5)";   //modifica del 21/04/2017
        $query.= " ORDER BY ispezione.anno ASC";
        
        $return = $db->query($query) or die($db->error());
        return $return;
    }
    
    public function getAnniTutteIspezioni($db){//17-02-2017
        $query = "SELECT DISTINCT anno FROM ispezione";
        $query.= " ORDER BY ispezione.anno ASC";
        
        $return = $db->query($query) or die($db->error());
        return $return;
    }
    
    public function contaIspezioniByUot($db,$uotcurr,$anno,$statoispez){  //01-02-2017 MODIFICATA
        $query = "SELECT COUNT(*) AS cont FROM ispezione, stabilimento, uot ";       
        $query.= " WHERE (ispezione.anno=".$db->mysqli_real_escape($anno).")";
        $query.= " AND (stabilimento.uotAffIdFk=uot.uotId)";   //
        $query.= " AND (uot.uotId=".$db->mysqli_real_escape($uotcurr).")";
        $query.= " AND (ispezione.statoIdFk=".$db->mysqli_real_escape($statoispez).")";
        $query.= " AND (ispezione.stabIdFk=stabilimento.stabilimentoId)";
        
        $return = $db->query($query) or die($db->error());
        return $return;
    }
    
    public function contaIspezioniAnno($db,$statoispez,$anno){  //17-02-2017 AGGIUNTA
        $query = "SELECT COUNT(*) AS cont FROM ispezione ";       
        $query.= " WHERE (ispezione.anno=".$db->mysqli_real_escape($anno).")";
        if($statoispez>0){
            $query.= " AND (ispezione.statoIdFk=".$db->mysqli_real_escape($statoispez).")";
        }
        $return = $db->query($query) or die($db->error());
        return $return;
    }
    
    public function contaIspezioniStessaUOTAnno($db,$anno,$stato){
        $query = "SELECT DISTINCT COUNT(*) AS cont FROM ispezione ";
        $query.= " JOIN stabilimento ON (ispezione.stabIdFk=stabilimento.stabilimentoId) ";
        $query.= " JOIN ispettore ON (ispezione.ispettIdFk=ispettore.ispettoreId) ";
        $query.= " JOIN uot ON (stabilimento.uotAffIdFk=uot.uotId) AND (ispettore.uotIspIdFk=uot.uotId) ";
        if($stato==0){
            $query.= " WHERE ((ispezione.statoIdFk=1) OR (ispezione.statoIdFk=2))";
        }elseif($stato==1){  //
            $query.= " WHERE (ispezione.statoIdFk=1) OR (ispezione.statoIdFk=5)";   //Modifica del 21/04/2017
        }elseif($stato==2){  //
            $query.= " WHERE (ispezione.statoIdFk=2) ";
        }
        $query.=" AND (ispezione.anno=".$db->mysqli_real_escape($anno).")";
        
        $return = $db->query($query) or die($db->error());
        return $return;
    }
    
    public function contaIspezioniDiversaUOTAnno($db,$anno,$stato){
        $query = "SELECT DISTINCT COUNT(*) AS cont FROM ispezione ";
        $query.= " JOIN stabilimento ON (ispezione.stabIdFk=stabilimento.stabilimentoId) ";
        $query.= " JOIN ispettore ON (ispezione.ispettIdFk=ispettore.ispettoreId) ";
        $query.= " JOIN uot ON (stabilimento.uotAffIdFk=uot.uotId) AND (ispettore.uotIspIdFk!=uot.uotId) ";
        if($stato==0){
            $query.= " WHERE ((ispezione.statoIdFk=1) OR (ispezione.statoIdFk=2))";
        }elseif($stato==1){  //
            $query.= " WHERE (ispezione.statoIdFk=1) OR (ispezione.statoIdFk=5) ";  //modifica del 21/04/2017
        }elseif($stato==2){  //
            $query.= " WHERE (ispezione.statoIdFk=2) ";
        }
        $query.=" AND (ispezione.anno=".$db->mysqli_real_escape($anno).")";
                
        $return = $db->query($query) or die($db->error());
        return $return;
    }
    
    public function getAnniIspezioniByUot($db,$uotcurr,$statoispez){  //01-02-2017    NUOVA; AGGIUNTO DISTINCT 16-02-2017
        $query = "SELECT DISTINCT ispezione.anno AS Anno FROM ispezione, stabilimento, uot ";       
        $query.= " WHERE (ispezione.stabIdFk=stabilimento.stabilimentoId)";
        $query.= " AND (stabilimento.uotAffIdFk=uot.uotId)";
        $query.= " AND (uot.uotId=".$db->mysqli_real_escape($uotcurr).")";
        $query.= " AND (ispezione.statoIdFk=".$db->mysqli_real_escape($statoispez).")";
        $query.= " ORDER BY ispezione.anno ASC";
        
        $return = $db->query($query) or die($db->error());
        return $return;
    }

    //eliminata il 21/04/2017 perchè uguale nella classe ispettore
//    public function contaIspezioniByIspettore($db,$ispettcurr,$statoispez){   
//        $query = "SELECT COUNT(*) AS cont FROM ispezione, ispettore ";
//        $query.= " WHERE (ispettore.ispettoreId=".$db->mysqli_real_escape($ispettcurr).")";
//        $query.= " AND (ispezione.ispettIdFk=ispettore.ispettoreId)";
//        $query.= " AND (ispezione.statoIdFk=".$statoispez.")";
//                
//        $return = $db->query($query) or die($db->error());
//        return $return;
//        
//    }
    public function contaIspezioniByIspettoreAnno($db,$ispettcurr, $anno, $statoispez){   //NUOVO
        $query = "SELECT COUNT(*) AS cont FROM ispezione, ispettore ";
        $query.= " WHERE (ispettore.ispettoreId=".$db->mysqli_real_escape($ispettcurr).")";
        $query.= " AND (ispezione.ispettIdFk=ispettore.ispettoreId)";
        if($statoispez==1){ //modifica del 21/04/2017
            $query.= " AND ((ispezione.statoIdFk=".$statoispez.") OR (ispezione.statoIdFk=5))";
        }else{
            $query.= " AND (ispezione.statoIdFk=".$statoispez.")";
        }
        
        $query.= " AND (ispezione.anno=".$db->mysqli_real_escape($anno).")";
                
        $return = $db->query($query) or die($db->error());
        return $return;
        
    }
    
    public function contaIspezioniByUditore($db,$udcurr,$statoispez){
        $query = "SELECT COUNT(*) AS cont FROM ispezione, ispettore ";
        $query.= " WHERE (ispettore.ispettoreId=".$db->mysqli_real_escape($udcurr).")";
        $query.= " AND (ispezione.uditIdFk=ispettore.ispettoreId)";
        if($statoispez==1){ //modifica del 21/04/2017
            $query.= " AND ((ispezione.statoIdFk=".$statoispez.") OR (ispezione.statoIdFk=5))";
        }else{
            $query.= " AND (ispezione.statoIdFk=".$statoispez.")";
        }
             
        $return = $db->query($query) or die($db->error());
        return $return;
    }
    public function contaIspezioniByUditoreAnno($db,$udcurr,$anno,$statoispez){   //NUOVO
        $query = "SELECT COUNT(*) AS cont FROM ispezione, ispettore ";
        $query.= " WHERE (ispettore.ispettoreId=".$db->mysqli_real_escape($udcurr).")";
        $query.= " AND (ispezione.uditIdFk=ispettore.ispettoreId)";
        if($statoispez==1){ //modifica del 21/04/2017
            $query.= " AND ((ispezione.statoIdFk=".$statoispez.") OR (ispezione.statoIdFk=5))";
        }else{
            $query.= " AND (ispezione.statoIdFk=".$statoispez.")";            
        }
        $query.= " AND (ispezione.anno=".$db->mysqli_real_escape($anno).")";
                
        $return = $db->query($query) or die($db->error());
        return $return;
    }
    
    
    public function insertIspezione($db, $post){
        $query="INSERT INTO `ispezione` (`anno`, `stabIdFk`, `statoIdFk`, `tipoispez`)";
        $query.=" VALUES (".$db->mysqli_real_escape($post["anno"])." ";
        $query.=", ".$db->mysqli_real_escape($post["stabilimenti"])." ";               
        $query.=", 3";     //stato: da pianificare      
        if($post["tipoispezrif"]!=""){
//            $query.=", ".$db->mysqli_real_escape($post["periodo"])." ";   modificato 13-gen-2017
            $query.=", ".$db->mysqli_real_escape($post["tipoispezrif"])." ";
        }else{$query.=", 1 ";}
        $query.=")";
        $return=$db->insert($query, true) or die($db->error());
        return $return;
    }
    
    public function updateIspezione($db,$post){
        //DA RIFARE
        $query = "UPDATE ispezione ";
        $query.=" SET anno=" . $db->mysqli_real_escape($post["anno"]);
        $query.=", stabIdFk=" . $db->mysqli_real_escape($post["stabilimenti"]);
//        $query.=", periodo=" . $db->mysqli_real_escape($post["periodo"]); modificato 13-gen-2017
        $query.=", tipoispez=" . $db->mysqli_real_escape($post["tipoispezrif"]);
        $query.=" WHERE ispezioneId=" . $this->getIspezioneId();
        $return = $db->query($query) or die($db->error());
        return $return;
    }
    
    public function eliminaIspettoreDaIspezione($db){
        //28/03/2017
        $query = "UPDATE ispezione ";
        $query.=" SET ispettIdFk=NULL " ;
        $query.=" WHERE ispezioneId=" . $this->getIspezioneId();
        $return = $db->query($query) or die($db->error());
        return $return;
    }
    
    public function eliminaUditoreDaIspezione($db){
        //28/03/2017
        $query = "UPDATE ispezione ";
        $query.=" SET uditIdFk=NULL " ;
        $query.=" WHERE ispezioneId=" . $this->getIspezioneId();
        $return = $db->query($query) or die($db->error());
        return $return;
    }
    
    public function updateStatoIspezione($db,$stato){
        //
        $query = "UPDATE ispezione ";
        $query.=" SET statoIdFk=" . $db->mysqli_real_escape($stato);
        $query.=" WHERE ispezioneId=" . $this->getIspezioneId();
        $return = $db->query($query) or die($db->error());
        return $return;
    }
    
    public function updateNoteIspezione($db,$note){

        $query = "UPDATE ispezione ";
        $query.=" SET noteIspezione='".$db->mysqli_real_escape($note)."' ";
        $query.=" WHERE ispezioneId=" . $this->getIspezioneId();
        $return = $db->query($query) or die($db->error());
        return $return;
    }
    
     public function updateRCdoc($db,$rc){

        $query = "UPDATE ispezione ";
        $query.=" SET rcdoc=".$db->mysqli_real_escape($rc);
        $query.=" WHERE ispezioneId=" . $this->getIspezioneId();
        $return = $db->query($query) or die($db->error());
        return $return;
    }
    
    public function updateSTdoc($db,$st){

        $query = "UPDATE ispezione ";
        $query.=" SET stdoc=".$db->mysqli_real_escape($st);
        $query.=" WHERE ispezioneId=" . $this->getIspezioneId();
        $return = $db->query($query) or die($db->error());
        return $return;
    }
    
    public function updateEOdoc($db,$eo){

        $query = "UPDATE ispezione ";
        $query.=" SET eodoc=".$db->mysqli_real_escape($eo);
        $query.=" WHERE ispezioneId=" . $this->getIspezioneId();
        $return = $db->query($query) or die($db->error());
        return $return;
    }
    
    public function updateALdoc($db,$al){

        $query = "UPDATE ispezione ";
        $query.=" SET aldoc=".$db->mysqli_real_escape($al);
        $query.=" WHERE ispezioneId=" . $this->getIspezioneId();
        $return = $db->query($query) or die($db->error());
        return $return;
    }
    
    public function updateMIdoc($db,$mi){   //22-01-2019 - aggiunta

        $query = "UPDATE ispezione ";
        $query.=" SET midoc=".$db->mysqli_real_escape($mi);
        $query.=" WHERE ispezioneId=" . $this->getIspezioneId();
        $return = $db->query($query) or die($db->error());
        return $return;
    }
    
    public function assegnaIspezione($db){
        //DA RIFARE
        $query = "UPDATE ispezione ";
        $query.=" SET ispettIdFk=" . $this->getIspettIdFk();
        if($this->getUditIdFk()>0){
            $query.=", uditIdFk=" . $this->getUditIdFk();
        }
        $query.=", data_assegnaz=" . $this->getData_assegnaz();
        $query.=", statoIdFk=2";
        $query.=" WHERE ispezioneId=" . $this->getIspezioneId();
        $return = $db->query($query) or die($db->error());
        return $return;
    }
    
    public function getIspezioniRifiutateByIspettoreUditore($db,$id) {   //MODIFICATA 24/02/2017
        $query = "SELECT DISTINCT ispettorerifiutaispezione.idispezione, ispezione.anno, stabilimento.stabilimentoDenominazione, ";
        $query.= " stabilimento.stabilimentoId,comune.comuneNome, provincia.prov, regione.nomeregione FROM ispettorerifiutaispezione, ispezione, ";
        $query.= " stabilimento, comune, provincia, regione";
        $query.= " WHERE (ispettorerifiutaispezione.idispettore=".$db->mysqli_real_escape($id).")";
        $query.= " AND (ispezione.ispezioneId=ispettorerifiutaispezione.idispezione)";
        $query.= " AND (stabilimento.stabilimentoId=ispezione.stabIdFk)";
        $query.= " AND (stabilimento.comuneIdFk=comune.comuneId)";
        $query.= " AND (comune.provIdFk=provincia.provinciaId)";
        $query.= " AND (provincia.regioneIdFk=regione.regioneId)";
        $return = $db->query($query) or die($db->error());
        return $return;
    }
}

?>
