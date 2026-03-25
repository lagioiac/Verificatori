<?php

class RuoloClass 
{
    
    private $IdRuolo=null;
    private $ruolo=null;
    private $iconaruolo = null;
    
    /*
    private $statoId=null;
    private $stato=null;
    private $iconastato = null;
    
    private $periodoId=null;
    private $periodo=null;
    
    private $tipoispezioneId=null;
    private $tipoispezione=null;*/
    
    function getIdRuolo()
    {
        return $this->IdRuolo;
    }
    
    function getRuolo()
    {
        return $this->ruolo;
    }
    
    function getIconaruolo()
    {
        return $this->iconaruolo;
    }
    
    /*
    function getStatoId()
    {
        return $this->statoId;
    }
    function getStato()
    {
        return $this->stato;
    }
    function getIconastato()
    {
        return $this->iconastato;
    }
    
    function getPeriodoId()
    {
        return $this->periodoId;
    }
    
    function getPeriodo()
    {
        return $this->periodo;
    }
    
    function getTipoispezioneId()
    {
        return $this->tipoispezioneId;
    }
    
    function getTipoispezione()
    {
        return $this->tipoispezione;
    }
    
    function getAbbrevtipoispezione()
    {
        return $this->abbrevtipoispezione;
    }*/
    
    function setIdRuolo($IdRuolo) 
    {
        $this->IdRuolo = $IdRuolo;
    }
    
    function setRuolo($ruolo) 
    {
        $this->ruolo = $ruolo;
    }
    
    function setIconaruolo($iconaruolo) 
    {
        $this->iconaruolo = $iconaruolo;
    }
    
    /*
    function setStatoId($statoId) 
    {
        $this->statoId = $statoId;
    }
    function setStato($stato) 
    {
        $this->stato = $stato;
    }
    function setIconastato($iconastato) 
    {
        $this->iconastato = $iconastato;
    }
    
    function sePeriodoId($periodoId) 
    {
        $this->periodoId = $periodoId;
    }
    
    function setPeriodo($periodo) 
    {
        $this->periodo = $periodo;
    }
    
    function seTipoispezioneId($tipoispezioneId) 
    {
        $this->tipoispezioneId = $tipoispezioneId;
    }
    
    function setTipoispezione($tipoispezione) 
    {
        $this->tipoispezione = $tipoispezione;
    }
    
    function setAbbrevtipoispezione($abbrevtipoispezione) 
    {
        $this->abbrevtipoispezione = $abbrevtipoispezione;
    }*/
    
    public function getRuoli($db) 
    {
        $query = "SELECT * FROM ruolo ORDER BY IdRuolo ASC";
        $return = $db->query($query) or die($db->error());
        return $return;
    }
    
    public function getRuoloById($db,$id)
    {
        $query = "SELECT ruolo.* FROM ruolo ";
        $query.= "WHERE ruolo.IdRuolo = ".$id ;
        $return = $db->query($query) or die($db->error());
        return $return;
    }
    
    public function getIconaByRuolo($db,$ru)
    {   
        $query = "SELECT ruolo.* FROM ruolo ";
        $query.= "WHERE (ruolo.ruolo ='".$ru. "' )" ;
        $return = $db->query($query) or die($db->error());
        return $return;
    }
    
    /*   
    public function getStati($db) 
    {
        $query = "SELECT * FROM statoispezione ORDER BY statoId ASC";
        $return = $db->query($query) or die($db->error());
        return $return;
    }
    
    public function getPeriodi($db) 
    {
        $query = "SELECT * FROM periodoispezione ORDER BY periodoId ASC";
        $return = $db->query($query) or die($db->error());
        return $return;
    }
    
    public function getPeriodoById($db,$id)
    {
        $query = "SELECT periodoispezione.* FROM periodoispezione ";
        $query.= "WHERE periodoispezione.periodoId = ".$id ;
        $return = $db->query($query) or die($db->error());
        return $return;
    }
    
    public function getTipiispezione($db) 
    {
        $query = "SELECT * FROM tipoispezione ORDER BY tipoispezioneId ASC";
        $return = $db->query($query) or die($db->error());
        return $return;
    }
    
    public function getTipoispezioneById($db,$id)
    {
        $query = "SELECT tipoispezione.* FROM tipoispezione ";
        $query.= "WHERE tipoispezione.tipoispezioneId = ".$id ;
        $return = $db->query($query) or die($db->error());
        return $return;
    }*/

}

?>
