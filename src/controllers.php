<?php

include("config.php");
ob_start();
$op = $_REQUEST['op'];
$now = getdate();

$UT = new Utility();

// unset($_SESSION['login-error']);
$email = $_POST['email'];
$password = $_POST['password'];

// echo "email : " . $email . "\n";
// echo "password : " . $password . "\n";
// echo "Tutto bene? ";
$item = $FS->login($email, $password);

if ($item) {
   foreach ($item as $k => $v) {
       if ($k != "passwd")
           $_SESSION["user"]["$k"] = $v;
   }
//$FS->indicatoreLog($_SESSION['user']);    
   ob_end_clean();
   //Header("location: home.php");
   echo "<script>document.location.href='home.php';</script>";
   exit;
}else {
   $_SESSION['login-error'] = "<div class='alert alert-error alert-login'>Utente non riconosciuto</div>";
   //Header("location: {$_SERVER['HTTP_REFERER']}");
   echo "<script>document.location.href='".$_SERVER['HTTP_REFERER']."';</script>";
   exit;
   exit;
}

switch ($op) {
	    case 6:
        $tipo = $_GET['tipo'];
        $q = $_GET['term'];
        $item = $FS->get_json($q, $tipo);
        if ($item) {
            $vet = array();
            foreach ($item as $k => $val) {
                $vet[] = '{"id":"' . $k . '","label":"' . $val . '", "value":"' . $val . '"}';
            }
            $product = implode(",", $vet);
            echo"[" . $product . "]";
        }
        break;
		
		case 1:	//restituisce il nome della regione dal nome della provincia
			$id = (int) $_POST('id');
			$item = $FS->get_regione($id);
			if ($item) {
				print_r($item['nomeregione']);
			}
			break;
}

?>
