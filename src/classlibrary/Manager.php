<?php

class Rispe{
    public $_cnn;
    public $_cnn_cache;
    
    public function __construct($mysqli) {
       $this->_cnn = $mysqli;
    }
    
    public function login($email, $password){
	//echo "entro in login!!";
    	$query = "SELECT * FROM utenti WHERE email = '$email' AND password = '$password'";
        $result = $this->_cnn->query($query);	
    	if($result->num_rows >0){
            $row = $result->fetch_array(MYSQLI_ASSOC);
            $result = $this->_cnn->query($query);
            return $row;
        }
        return false;
    }
	
public function lista($modulo, $id){
    //elenca tutti gli elementi
            $v = array();
    switch ($modulo){
            case "provincia":
                $query = "SELECT * FROM provincia  ORDER BY prov ASC"; 
                break;

            case "regione":
                $query = "SELECT * FROM regione  ORDER BY nomeregione ASC"; 
                break;
            
            case "uot-regione":
                $query= "SELECT * FROM uot ";
                $query.= "JOIN provincia ON (uot.provinciaFkId = provincia.provinciaId) ";
                $query.= "JOIN regione ON (provincia.regioneIdFk = regione.regioneId) ";
                $query.= "WHERE (regione.regioneId = ". $id .")";
                break;
            }
        $result = $this->_cnn->query($query);
        if($result->num_rows >0){
                while($row = $result->fetch_array(MYSQLI_ASSOC)){ 
                           $v[] = $row;
                }
    }
    return $v;
    }
    
public function get_lista_uot_by_regione($id){

    $query= "SELECT * FROM uot 
            JOIN provincia ON (uot.provinciaFkId = provincia.provinciaId) 
            JOIN regione ON (provincia.regioneIdFk = regione.regioneId) 
            WHERE (regione.regioneId =  $id )";

    $result = $this->_cnn->query($query);
    if($result->num_rows >0){
            while($row = $result->fetch_array(MYSQLI_ASSOC)){ 
                       $v[] = $row;
            }
    }
    return $v;
}

	
    public function get_by_id($modulo, $id){
	$UT = new Utility();
    	switch ($modulo){
			case "provincia":
				if($id == 0){
					$query = "SELECT * FROM provincia  ORDER BY prov ASC"; 
				}
    			break;
		}
		$result = $this->_cnn->query($query);
        if($result->num_rows >0){
		  $row = $result->fetch_array(MYSQLI_ASSOC);
		  return $row;
		}  
		return false;
		
	}
	
	public function get_json($q, $tipo, $id){
    	$v = array();
    	$q_lower = strtolower($q);
    	switch ($tipo){
			case 2:	//get province 
				$query = "SELECT *
						FROM provincia 
						JOIN regione  ON (regione.id = provincia.regioneIdFk)
						WHERE regione.id = $id ";
    			$result = $this->_cnn->query($query);
		        if($result->num_rows >0){
				  while($row = $result->fetch_array(MYSQLI_ASSOC)){   
				  	    $v[$row['id']] = "$row[prov]";
				  }
				}	
    		break;
		}
	}
	
	public function get_regione($id){
		
		$query = "SELECT *
				FROM regione 
				JOIN provincia  ON (regione.regioneId = provincia.regioneIdFk)
				WHERE provincia.provinciaId = $id ";
		
		$result = $this->_cnn->query($query);
		if($result->num_rows >0){
		  $row = $result->fetch_array(MYSQLI_ASSOC);
		  return $row['nomeregione'];
		}
		return false;
	}
	public function get_regione_prov($nome){
		
		$query = "SELECT *
				FROM regione 
				JOIN provincia  ON (regione.regioneId = provincia.regioneIdFk)
				WHERE provincia.prov = $nome ";
		
		$result = $this->_cnn->query($query);
		if($result->num_rows >0){
		  $row = $result->fetch_array(MYSQLI_ASSOC);
		  return $row['nomeregione'];
		}
		return false;
	}
}
?>
