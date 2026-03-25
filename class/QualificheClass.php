<?php

class QualificheClass 
{
    private $IdQualifica = null;
    private $qualifica = null;
    private $flgTipoQualifica = null; /* in RISPE valore non presente */
    
    function getIdQualifica() 
    {
        return $this->IdQualifica;
    }
    
    function getQualifica() 
    {
        return $this->qualifica;
    }
    
    function getflgTipoQualifica() /* in RISPE function non presente */
    {
        return $this->flgTipoQualifica;
    }
    
    function setIdQualifica($IdQualifica) 
    {
        return $this->IdQualifica=$IdQualifica;
    }
    
    function setQualifica($qualifica) /* in RISPE function non presente */
    {
        return $this->qualifica=$qualifica;
    }
    
    function setflgTipoQualifica($flgTipoQualifica) 
    {
        return $this->flgTipoQualifica=$flgTipoQualifica;
    }
    
    
    public function getQualifiche($db) 
    {
        $query = "SELECT * FROM qualifica ORDER BY qualifica ASC";
        $return = $db->query($query) or die($db->error());
        return $return;
    }
    
    public function getQualificaById($db,$id)
    {
        $query = "SELECT qualifica.qualifica FROM qualifica ";
        $query.= "WHERE qualifica.idQualifica = ".$id ;
        $return = $db->query($query) or die($db->error());
        return $return;
    }
    
    public function getflgTipoQualificaById($db,$id) /* in RISPE function non presente */
    {
        $query = "SELECT qualifica.flgTipoQualifica FROM qualifica ";
        $query.= "WHERE qualifica.idQualifica = ".$id ;
        $return = $db->query($query) or die($db->error());
        return $return;
    }
    
    public function insertQualifica($db, $post)
    {
        $query="INSERT INTO `qualifica` (`qualifica`) ";
        $query.=" VALUES ('".$db->mysqli_real_escape($post["qualifica"])."' )";
        $return=$db->insert($query, true) or die($db->error());
        return $return;
    }
}

?>
