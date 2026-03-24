<?php
if(!$_SESSION['user']){
	 Header("location: index.php");
     exit; 
}
?>