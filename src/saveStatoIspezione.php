<?php

ob_start();
require 'config.php';

require 'db/mysql.php';
require 'class/IspezioneClass.php';
require 'class/IspettoreClass.php';

$db = new DbConnect();
$ispezioneClass = new IspezioneClass();
$uditoreClass = new IspettoreClass();
$ispettoreClass = new IspettoreClass();

$db->open() or die($db->error());

if ($_POST["ispezioneId"] != "") {
    //controlla se è stato modificato lo stato dell'ispezione
    $ispezioneClass->setIspezioneId($_POST["ispezioneId"]);
    $ispezioneClass->getDettaglioIspezione($db);
    $row = $db->fetchassoc();
    
    $ispezioneClass->setAnno($row["anno"]);
    $ispezioneClass->setStabIdFk($row["stabIdFk"]);
    $ispezioneClass->setStatoIdFk($row["statoIdFk"]);
    $ispezioneClass->setIspettIdFk($row["ispettIdFk"]);
    $ispezioneClass->setUditIdFk($row["uditIdFk"]);
    $ispezioneClass->setTipoispez($row["tipoispez"]); //modificato il 15-02-2017
    $ispezioneClass->setRcdoc($row["rcdoc"]);
    
    $k1=$ispezioneClass->getStatoIdFk();
    $k2=$db->mysqli_real_escape($_POST["flgStatoIspez"]);
    //Aggiunto 28/03/2017
    if(isset($_POST['noispez'])){  $k3=1;   //checked ispettore rinuncia ispezione
        }else {   $k3=0;}
        
    if(isset($_POST['nouditore'])){  $k4=1;   //checked uditore non svolge ispezione
        }else {   $k4=0;}
        
    if($k2!= $k1){
        //nel caso in cui si sta dichiarando l'ispezione archiviata si deve verificare
        //che sia stato inserito almeno il rapporto conclusivo, altrimenti è solo conclusa
        if($k2==1){
            if($ispezioneClass->getRcdoc()==0){
                //non c'è il rapporto conclusivo, non si può aggiornare lo stato a archiviata
//                header("Location: aggiungi_ispezione.php?msg3");
//                exit();   //Modifica del 21/04/2017
                $k2=5;  //ispezione è solo conclusa
            }
            if($k4==0){
                //Aggiornare l'eventuale uditore, controllando quante ispezioni in cui ha ricevuto l'incarico sono concluse
                if($ispezioneClass->getUditIdFk()>0){
                    //conta le ispezioni già concluse dall'uditore:
                    $ud=$ispezioneClass->getUditIdFk();
                    $uditoreClass->setIspettoreId($ispezioneClass->getUditIdFk());
                    $uditoreClass->getDettaglioIspettore($db);
                    $row2 = $db->fetchassoc();
                    $uditoreClass->setCorsoIdAu($row2["corsoIdAu"]);
                    $uditoreClass->setNroIspSGSAu($row2["nroIspSGSAu"]);
                    $uditoreClass->setNroIspUditAu($row2["nroIspUditAu"]);

                    $k=$uditoreClass->updateCheckRuoloIspettore($db);

                    $uditoreClass->updateRuoloUditoreInIspettore($db,$k);

                }
            }else{  //stato: concluso, uditore ha rinunciato
                if($ispezioneClass->getUditIdFk()>0){
                    $uditoreClass->setIspettoreId($ispezioneClass->getUditIdFk());
                    //ELIMINA EVENTUALI record già inseriti
                    //è uguale per ispettore e uditore
                    $uditoreClass->deleteRifiutoIspettoreDiIspezione($db, $ispezioneClass->getUditIdFk(), $_POST["ispezioneId"]);
                    //crea il record di rifiuto di ispezione da parte dell'ispettore
                    $uditoreClass->rifiutoIspettoreDiIspezione($db, $ispezioneClass->getUditIdFk(), $_POST["ispezioneId"]);
                    //elimina l'informazione nel record ispezione
                    $ispezioneClass->eliminaUditoreDaIspezione($db);
                }
            }
        }
        if(($k2==3) && ($k3==1)){   //stato: da pianificare, ispettore ha rinunciato
            //Ispettore designato ha rinunciato
            $ispet=$ispezioneClass->getIspettIdFk();
            $ispettoreClass->setIspettoreId($ispet);
            //ELIMINA EVENTUALI record già inseriti
            $ispettoreClass->deleteRifiutoIspettoreDiIspezione($db, $ispet, $_POST["ispezioneId"]);
            //crea il record di rifiuto di ispezione da parte dell'ispettore
            $ispettoreClass->rifiutoIspettoreDiIspezione($db, $ispet, $_POST["ispezioneId"]);
            //elimina l'informazione nel record ispezione
            $ispezioneClass->eliminaIspettoreDaIspezione($db);  
            $ispezioneClass->eliminaUditoreDaIspezione($db);    //12-04-2017
        }
        //12-04-2017
        if(($k2==3) && ($k3==0)){   //stato: da pianificare, annulla l'assegnazione precendente
            // si devono eliminare l'ispettore e l'uditore dall'ispezione
            $ispezioneClass->eliminaIspettoreDaIspezione($db);
            $ispezioneClass->eliminaUditoreDaIspezione($db);
        }
        //cambia lo stato
        $ispezioneClass->updateStatoIspezione($db, $k2);
        //ob_end_clean();
        if($k2==5){
            header("Location: aggiungi_ispezione.php?msg3");
        }else{
            header("Location: aggiungi_ispezione.php?msg2");
        }
        exit();
    }else{
    ob_end_clean();
        header("Location: aggiungi_ispezione.php?msg");
        exit();
    }
    
}

?>
