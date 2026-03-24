<?php

class ProvinciaClass {

	private $provinciaId = null;
	private $ProvinciaProv = null;
	private $provinciaRegioneIdFk = null;
	
	function getProvinciaId(){
		return $this->provinciaId;
	}
	
	function getProvinciaProv(){
		return $this->prov;
	}
	
	function getProvinciaRegioneIdFk(){
		return $this->regioneIdFk;
	}
	
	function setProvinciaId($provinciaId) {
        $this->provinciaId = $provinciaId;
    }
	
	function setProvinciaProv($ProvinciaProv) {
        $this->prov = $ProvinciaProv;
    }
	
	function setProvinciaRegioneIdFk($provinciaRegioneIdFk) {
        $this->regioneIdFk = $provinciaRegioneIdFk;
    }
	
public function getProvince($db) {
        $query = "SELECT * FROM provincia ORDER BY prov ASC";
        $return = $db->query($query) or die($db->error());
        return $return;
    }
    
public function getDettaglioProvincia($db, $id){
        $query = "SELECT * FROM provincia WHERE provinciaId=" . $id;
        $return = $db->query($query) or die($db->error());
        return $return;
    }
	
public function getRegioneProvincia($db, $provincia) {
        $query="SELECT regione.* ";
        $query.="FROM regione ";
        $query.="JOIN provincia ON provincia.provinciaId=".$provincia;
        //$query.="AND provincia.prov=".$provincia;
        $query.=" AND provincia.regioneIdFk=regione.regioneId ";
        $return= $db->query($query) or die($db->error());
        return $return;
    }
    
    public function getProvinceStessaRegione($db, $reg) {
        $query="SELECT provincia.* ";
        $query.=" FROM provincia WHERE provincia.regioneIdFk=".$reg;
        
        $return= $db->query($query) or die($db->error());
        return $return;
    }
    
public function getAutocompleteProvince($db, $nome){
        $query="SELECT provincia.prov as nome, provincia.provinciaId as id " ;
        $query.="FROM provincia ";
        $query.="WHERE UCASE(prov) LIKE UCASE('%".$db->mysqli_real_escape($nome)."%')";
        $return = $db->query($query) or die($db->error());
        return $return;
    }
    
    public function insertProvincia($db, $post){
        $query="INSERT INTO `provincia` (`prov`, `regioneIdFk`) ";
        $query.=" VALUES ('".$db->mysqli_real_escape($post["provincia"])."' ";
        $query.=", ".$db->mysqli_real_escape($post["regioni"]);
        $query.=")";
        $return=$db->insert($query, true) or die($db->error());
        return $return;
    }
    
    public function getLastRecord($db) {
        $query = "SELECT provincia.* FROM provincia ";
        $query.="ORDER BY provincia.provinciaId DESC LIMIT 1";
        $return = $db->query($query) or die($db->error());
        return $return;
    }
}
?>