<?php
    /* Classe PersonaleUOTClass: definisce le proprietà corrispondenti alle 
       colonne della tabella personaleuot nel database e fornisce metodi 
       getter e setter per ciascuna proprietà. */
    class PersonaleUOTClass
    {
        // Dichiarazione della struttura Tabella: personaleuot
        private $IDXF = null; 
        private $Cognome = null; 
        private $Nome = null; 
        private $iduot=null;
        private $idqualifica=null; 
        private $cell=null;
        private $telfisso=null;
        private $email=null;
        private $flgP=null;
        private $flgS=null;
        private $flgR=null;
        private $flgT=null;
        private $flgAltreUot=null;
        private $disponibile=null;
        private $note=null;
        private $flgAdmin=null;
        private $idruolo=null;

        /* *** FUNZIONI GET *** */
        function getIDXF() /* in RISPE getIspettoreId($ispettoreId) */
        {
            return $this->IDXF;
        }

        function getCognome()
        {
            return $this->Cognome;
        }

        function getNome()
        {
            return $this->Nome;
        }

        function getiduot()
        {
            return $this->iduot;
        }

        function getidqualifica()
        {
            return $this->idqualifica;
        }

        function getcell()
        {
            return $this->cell;
        }

        function gettelfisso()
        {
            return $this->telfisso;
        }

        function getemail()
        {
            return $this->email;
        }

        function getflgP()
        {
            return $this->flgP;
        }

        function getflgS()
        {
            return $this->flgS;
        }

        function getflgR()
        {
            return $this->flgR;
        }

        function getflgT()
        {
            return $this->flgT;
        }

        function getflgAltreUot()
        {
            return $this->flgAltreUot;
        }

        function getdisponibile()
        {
            return $this->disponibile;
        }
        function getnote()
        {
            return $this->note;
        }

        function getflgAdmin()
        {
            return $this->flgAdmin;
        }

        function getidruolo()
        {
            return $this->idruolo;
        }


        /* *** FUNZIONI SET *** */
        function setIDXF($IDXF) /* in RISPE setIspettoreId($ispettoreId) */
        {
            $this->IDXF = $IDXF;
        }

        function setCognome($Cognome) 
        {
            $this->Cognome = $Cognome;
        }

        function setNome($Nome) 
        {
            $this->Nome = $Nome;
        }

        function setiduot($iduot)
        {
            $this->iduot = $iduot;
        }

        function setidqualifica($idqualifica)
        {
            $this->idqualifica = $idqualifica;
        }

        function setcell($cell)
        {
            $this->cell = $cell;
        }

        function settelfisso($telfisso)
        {
            $this->telfisso = $telfisso;
        }

        function setemail($email) 
        {
            $this->email = $email;
        }

        function setflgP($flgP)   /* competenza nella Presisone */ 
        {
            $this->flgP = $flgP;
        }

        function setflgS($flgS)  /* competenza nel Sollevamento */ 
        {
            $this->flgS = $flgS;
        }

        function setflgR($flgR) /* competenza nel Riscaldamento */ 
        {
            $this->flgR = $flgR;
        }

        function setflgT($flgT) /* competenza nella messa a Terra */ 
        {
            $this->flgT = $flgT;
        }

        function setflgAltreUot($flgAltreUot)  /* disponibilità nelle attività per altre UOT */ 
        {
            $this->flgAltreUot = $flgAltreUot;
        }
        
        function setdisponibile($disponibile) /* Disponibilità al supporto da Remoto (R), in Presenza (P) o per entrambi (X)  */ 
        {
            $this->disponibile = $disponibile;
        }

        function setnote($note) 
        {
            $this->note = $note;
        }

        function setflgAdmin($flgAdmin) /* indica se l'utente del Peronale è un Admin */
        {
            $this->flgAdmin = $flgAdmin;
        }

        function setidruolo($idruolo) /* Colore e logo per indicare se il personale della UOT è un Verificatore o altro */ 
        {
            $this->idruolo = $idruolo;
        }


        /* Funzione insertPersonaleUOT: inserisce un nuovo record nella tabella
           personaleuot utilizzando i dati passati tramite l'array $post. */
        public function insertPersonaleUOT($db, $post) 
        {
            /* Preparazione della Query: La query SQL di inserimento è costruita
               concatenando i valori dei campi opportunamente escapati per 
               evitare SQL injection. 
               N.B. La query SQL di inserimento è stata suddivisa in più righe 
                    per migliorare la leggibilità, con ogni campo e valore su 
                    una nuova riga*/
        
            $query = "INSERT INTO personaleuot
            (
                Cognome, Nome, iduot, idqualifica, cell, telfisso, 
                email, flgP, flgS, flgR, flgT, flgAltreUot, disponibile, 
                note, flgAdmin, idruolo
            ) 
            VALUES 
            (
                '".$db->mysqli_real_escape_string($post['Cognome'])."',
                '".$db->mysqli_real_escape_string($post['Nome'])."',
                '".$db->mysqli_real_escape_string($post['iduot'])."',
                '".$db->mysqli_real_escape_string($post['idqualifica'])."',
                '".$db->mysqli_real_escape_string($post['cell'])."',
                '".$db->mysqli_real_escape_string($post['telfisso'])."',
                '".$db->mysqli_real_escape_string($post['email'])."',
                '".$db->mysqli_real_escape_string($post['flgP'])."',
                '".$db->mysqli_real_escape_string($post['flgS'])."',
                '".$db->mysqli_real_escape_string($post['flgR'])."',
                '".$db->mysqli_real_escape_string($post['flgT'])."',
                '".$db->mysqli_real_escape_string($post['flgAltreUot'])."',
                '".$db->mysqli_real_escape_string($post['disponibile'])."',
                '".$db->mysqli_real_escape_string($post['note'])."',
                '".$db->mysqli_real_escape_string($post['flgAdmin'])."',
                '".$db->mysqli_real_escape_string($post['idruolo'])."'
            )";

            // Stampa la query per debug, da commentare se non serve
            echo $query;

            /* Esecuzione della Query: La query viene eseguita utilizzando il 
               metodo insert dell'oggetto $db, con gestione degli errori. */
            $result = $db->insert($query, true) or die($db->error());
            return $result;
            
            /* *** NOTA BENE *** 
              Verificare la connessione al database se è configurata 
              correttamente nel progetto e che la funzione 
              "mysqli_real_escape_string" sia definita nell'oggetto $db. */
            
        }

        /* *** FUNZIONE DI AGGIORNAMENTO ELEMENTO NEL DB *** */
        /* ***      Funzione updatePersonaleUOT          *** */ 

        public function updatePersonaleUOT($db, $post) 
        {
            // Prepara la query di aggiornamento
            /* - Prepara una query SQL UPDATE per aggiornare un record nella 
                 tabella personaleuot
               - Usa la funzione "mysqli_real_escape_string" per sanitizzare i dati 
                 ed evitare attacchi SQL injection. */
            $query = "UPDATE personaleuot 
            SET 
                Cognome='" . $db->mysqli_real_escape_string($post['Cognome']) . "', 
                Nome='" . $db->mysqli_real_escape_string($post['Nome']) . "', 
                iduot=" . $db->mysqli_real_escape_string($post['iduot']) . ", 
                idqualifica=" . $db->mysqli_real_escape_string($post['idqualifica']) . ", 
                cell='" . $db->mysqli_real_escape_string($post['cell']) . "', 
                telfisso='" . $db->mysqli_real_escape_string($post['telfisso']) . "', 
                email='" . $db->mysqli_real_escape_string($post['email']) . "', 
                flgP=" . $db->mysqli_real_escape_string($post['flgP']) . ", 
                flgS=" . $db->mysqli_real_escape_string($post['flgS']) . ", 
                flgR=" . $db->mysqli_real_escape_string($post['flgR']) . ", 
                flgT=" . $db->mysqli_real_escape_string($post['flgT']) . ", 
                flgAltreUot=" . $db->mysqli_real_escape_string($post['flgAltreUot']) . ", 
                disponibile=" . $db->mysqli_real_escape_string($post['disponibile']) . ", 
                note='" . $db->mysqli_real_escape_string($post['note']) . "', 
                flgAdmin=" . $db->mysqli_real_escape_string($post['flgAdmin']) . ", 
                idruolo=" . $db->mysqli_real_escape_string($post['idruolo']) . " 
            WHERE 
                IDXF=" . $this->getIDXF();

            // Stampa la query per scopi di debug, da commentare se non serve
            echo $query;

            // Esegue la query e restituisce il risultato
            $result = $db->query($query) or die($db->error());
            return $result;
        }


        /* continuare da qui */

        public function getListaPersonaleUOT($db,$ordinaPer) 
        {
            $query = "SELECT personaleuot.*, uot.denominazione FROM personaleuot, uot ";
            $query.= "WHERE (personaleuot.iduot = uot.IdUot) ";
            /* da usare per visualizzare solo i verificatori e non altro personale, quelli attivi
            $query.= " AND (personaleuot.attivo=0) "; */
            $query.= " ORDER BY ".$ordinaPer." ASC";
    //        $query.=" ORDER BY uot.uotDenominazione ASC ";
            $return = $db->query($query) or die($db->error());
            return $return;
        }    

        public function getSearchPersonaleUOT_OLD($db, $post, $ordinaPer)
        {  
            // modificato l'ordine per i risultati
            $query ="SELECT DISTINCT personaleuot.*, uot.denominazione ";
            $query.="FROM personaleuot, uot ";
            /*if($post["searchregione"] != "")
            {
                $query.=" JOIN provincia ON (uot.provinciaFkId=provincia.provinciaId)";
                $query.=" JOIN regione ON ((provincia.idregione=regione.regioneId) AND (regione.nomeregione LIKE '%".$db->mysqli_real_escape($post["searchregione"]). "%')) ";
            }*/
            $query.="WHERE (personaleuot.iduot = uot.IdUot) ";
            if($post["searchpersonaleuot"] != "")
                $query.=" AND (personaleuot.cognome LIKE '%".$db->mysqli_real_escape($post["searchpersonaleuot"]). "%' )";

            if($post["searchuot"] != "")
                $query.=" AND (uot.denominazione LIKE '%".$db->mysqli_real_escape($post["searchuot"]). "%' ) ";

            // solo quelli attivi
            /* $query.= " AND (ispettore.attivo=0) ";    **da verificare come mettere solo i verificatori */
            //        $query.=" ORDER BY uot.uotDenominazione ASC ";
            $query.= " ORDER BY ".$ordinaPer." ASC";
            $return = $db->query($query) or die($db->error());
            return $return;
        }   

        public function getSearchPersonaleUOT($db, $post, $ordinaPer)
        {
            $query = "SELECT DISTINCT personaleuot.*, uot.denominazione, regione.regione, ruolo.ruolo 
                      FROM personaleuot
                      JOIN uot ON personaleuot.iduot = uot.IdUot
                      JOIN comune ON uot.idcomune = comune.idComune
                      JOIN provincia ON comune.idprovincia = provincia.idProvincia
                      JOIN regione ON provincia.idregione = regione.idRegione
                      JOIN ruolo ON personaleuot.idruolo = ruolo.IdRuolo
                      WHERE 1=1";

            if (!empty($post["searchPersonaleUOT"])) {
                $query .= " AND LOWER(personaleuot.cognome) LIKE LOWER('%".$db->mysqli_real_escape($post["searchPersonaleUOT"])."%')";
            }

            if (!empty($post["searchuot"])) {
                $query .= " AND uot.IdUot = ".$db->mysqli_real_escape($post["searchuot"]);
            }

            if (!empty($post["searchregione"])) {
                $query .= " AND regione.idRegione = ".$db->mysqli_real_escape($post["searchregione"]);
            }

            if (!empty($post["searchruolo"])) {
                $query .= " AND ruolo.IdRuolo = ".$db->mysqli_real_escape($post["searchruolo"]);
            }

            $query .= " ORDER BY ".$ordinaPer." ASC";
            $return = $db->query($query) or die($db->error());
            return $return;
        }

        /* *** Funzioni per il file aggiungi_verifictore.php per assicurarsi 
         * che i dati vengano recuperati correttamente dal database *** */ 
        public function getVerificatoreByCognome($db, $cognome)
        {
            /* vecchia query senza controllo sul keysensitive 
            $query = "SELECT * FROM personaleuot WHERE cognome = '" . $db->mysqli_real_escape($cognome) . "'";
            $result = $db->query($query) or die($db->error());
            return $db->fetchassoc($result); */
            
            $cognome_input = $db->mysqli_real_escape($cognome);
            $query = "SELECT * FROM personaleuot WHERE LOWER(cognome) = LOWER('$cognome_input')";
            $result = $db->query($query) or die($db->error());
            return $db->fetchassoc($result);
        }
        public function getVerificatoreById($db, $id)
        {
            $query = "SELECT * FROM personaleuot WHERE IDXF = " . $db->mysqli_real_escape($id);
            $result = $db->query($query) or die($db->error());
            return $db->fetchassoc($result);
        }
        

        public function getDettaglioPersonaleUOT($db)  /* in RISPE getDettaglioIspettore($db) */
        {
            $query = "SELECT personaleuot.* FROM personaleuot WHERE personaleuot.IDXF=" . $this->getIDXF();
            $return = $db->query($query) or die($db->error());
            return $return;
        }

        public function updateRuoloUditoreInPersonaleUOT($db, $isp)  // in RISPE PersonaleUOT = Ispettori/e
        {
            $query = "UPDATE personaleuot ";
            $query.=" SET idruolo=". $db->mysqli_real_escape($isp);
            $query.=" WHERE IDXF =" . $this->getIDXF();
            $return = $db->query($query) or die($db->error());
            return $return;
        }    

        public function getPersonaleUOTByUot($db,$id,$ruolo) // in RISPE PersonaleUOT = Ispettori/e
        {
            $query = "SELECT DISTINCT personaleuot.* FROM personaleuot ";
            $query.= "WHERE (personaleuot.iduot = ".$id.")";
            if($ruolo==1)
                $query.= " AND (personaleuot.idruolo=".$ruolo.")";
            elseif($ruolo>0)
                $query.= " AND ((personaleuot.idruolo=2) || (personaleuot.idruolo=3))";

            /* $query.= " AND (ispettore.attivo=0) "; */
            $query.= " ORDER BY personaleuot.cognome";
            $return = $db->query($query) or die($db->error());
            return $return;
        }

        public function getElencoTotalePersonaleUOT($db)  // in RISPE PersonaleUOT = Ispettori/e
        {
            $query = "SELECT  uot.denominazione AS UOT, regione.regione AS Regione, ruolo.ruolo AS Ruolo, qualifica.qualifica AS Qualifica, qualifica.flgTipoQualifica AS TipoQualifica,";
            $query.= " personaleuot.IDXF AS PersonaleUOT, personaleuot.Cognome AS Cognome, personaleuot.Nome AS Nome, personaleuot.cell AS Cellulare, personaleuot.telfisso AS TelFisso,";
            $query.= " personaleuot.email AS eMail, personaleuot.flgP AS Pressione, personaleuot.flgS AS Sollevamento, personaleuot.flgR AS Riscaldamento, personaleuot.flgT AS Terra,";
            $query.= " personaleuot.flgAltreUot AS DispAltreUOT, personaleuot.disponibile AS PresRemoto, personaleuot.note AS Note, personaleuot.flgAdmin AS Admin, FROM personaleuot";
            $query.= " JOIN uot ON uot.IdUot=personaleuot.iduot";
            /* $query.= " JOIN uot_regione ON uot_regione.uotIdFk=uot.uotId"; 
            $query.= " JOIN regione ON regione.IdRegione=uot_regione.regioneIdFk"; */
            $query.= " JOIN ruolo ON ruolo.IdRuolo=personaleuot.idruolo";
            $query.= " JOIN qualifica ON qualifica.idQualifica=personaleuot.idqualifica";
            /* $query.= " JOIN corsoformazione ON corsoformazione.corsoId=ispettore.corsoIdAu";*/

            $query.= " WHERE (personaleuot.attivo=0) "; // controllare il WHERE per una funzionalità più specifica per i verificatori "WHERE (personaleuot.attivo=0)" è ereditato da RISPE e il campo "attivo" non esiste in "personaleuot"
            $query.= " ORDER BY regione.regione ASC, uot.denominazione ASC, ruolo.IdRuolo ASC";

            $return = $db->query($query) or die($db->error());
            return $return;
        }

        public function getElencoTotaleNotePersonaleUOT($db) // in RISPE PersonaleUOT = Ispettori/e
        {
            $query = "SELECT  uot.denominazione AS UOT, regione.regione AS Regione, ruolo.ruolo AS Ruolo,";
            $query.= " ispettore.IDXF AS PersonaleUOT, personaleuot.Cognome AS Cognome, personaleuot.Nome AS Nome, personaleuot.note AS Note FROM personaleuot";
            $query.= " JOIN uot ON uot.IdUot=personaleuot.iduot";
            /*$query.= " JOIN uot_regione ON uot_regione.uotIdFk=uot.uotId";
            $query.= " JOIN regione ON regione.regioneId=uot_regione.regioneIdFk";*/
            $query.= " JOIN ruolo ON ruolo.IdRuolo=personaleuot.idruolo";

            $query.= " WHERE (personaleuot.attivo=0) "; // controllare il WHERE per una funzionalità più specifica per i verificatori "WHERE (personaleuot.attivo=0)" è ereditato da RISPE e il campo "attivo" non esiste in "personaleuot"
            $query.= " ORDER BY regione.regione ASC, uot.denominazione ASC, ruolo.idruolo ASC";

            $return = $db->query($query) or die($db->error());
            return $return;
        }

        public function contaVerificatori($db,$stato) //da verificare come applicarla sulla conta dei verificatori - in RISPE si chiamava "contaIspettori"
        {
            $query = "SELECT DISTINCT COUNT(*) AS cont FROM personaleuot ";
            $query.= " WHERE (personaleuot.idruolo=".$db->mysqli_real_escape($stato).")";
            $return = $db->query($query) or die($db->error());
            return $return;
        }

        public function contaIspettoriAnno($db,$stato,$annocurr) // forse non ha senso per i verificatori - in RISPE si chiamava "contaIspettoriAnno"
        {
            $query = "SELECT DISTINCT COUNT(*) AS cont FROM personaleuot ";
            $query.= " WHERE (personaleuot.idruolo=".$db->mysqli_real_escape($stato).")";
            $return = $db->query($query) or die($db->error());
            return $return;
        }


        /* INZIO Funzioni di RISPE da valutare cosa può servire o da cancellare */
        /*



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



         * 
         * 
         * 
         *   CONTINUARE DA QUI
         * 
         *

        public function getLastRecord($db) {
            $query = "SELECT ispettore.* FROM ispettore ";
            $query.="ORDER BY ispettore.ispettoreId DESC ";
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


        public function getIspettoriByRegione($db,$regione){
            $query = "SELECT ispettore.* FROM ispettore";
            $query.=" JOIN uot ON (ispettore.uotIspIdFk=uot.uotId)";
            $query.=" JOIN provincia ON (uot.provinciaFkId=provincia.provinciaId)";
            $query.=" JOIN regione ON ((provincia.regioneIdFk=regione.regioneId) AND (regione.nomeregioneLIKE '%".$db->mysqli_real_escape($regione). "%'))";

            return $return;
        }





        public function getIspettoreById($db,$id){
            $query = "SELECT ispettore.* FROM ispettore ";
            $query.= "WHERE ispettore.ispettoreId = ".$id ;
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

       */
        /* FINE Funzioni di RISPE da valutare cosa può servire o da cancellare */ 


    
    }
?>