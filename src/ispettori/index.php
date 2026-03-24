<?php
ob_start();
$pageMenu="ispettore";
require '../config.php';

require '../db/mysql.php';
require '../class/IspettoreClass.php';
include("../include/check_user.php");

$pageName="ISPETTORI";
include '../include/header.php'; 

$ispettoreCognome="";

$db= new DbConnect();
$ispettoreClass= new IspettoreClass();

$db->open() or die($db->error());

if(count($_POST)>0){
    $ispettoreCognome=$_POST["ispettore"];
}
$ispettore=$ispettoreClass->getLastRecord($db, $ispettoreCognome);

?>

<script type="text/javascript">
    document.getElementById("myButton").onclick = function () {
        location.href = "index.php?clean";
    };
		function cancella(id){
			if(confirm("Sicuro di voler cancellare?")){	
				$(location).attr('href',"deleteFormazione.php?&id="+id+"");
			}
		}		
</script>
</head>

<header>
	<div class="container">
    	<div class="row">
	<div class="span12">
        <div class="logo" style="border-bottom: 10px;"><a href="../home.php">new<span>RISPE</span></a></div>    
    </div>
</div>
<a href="javascript:;" class="mobilemenu">MENU</a>
<nav>
  	<ul>
        <li class="uno <?php echo $class_0;?>"><a href="<?=$PATHSITE?>/ispettori.html"><img src="<?=$PATHSITE?>/img/icon1.png" alt="icon"><span class="none">Ispettori</span></a></li>
        <li class="due <?php echo $class_1;?>"><a href="<?=$PATHSITE?>/stabilimenti.html"><img src="<?=$PATHSITE?>/img/icon2.png" alt="icon"><span class="none">Stabilimenti</span></a></li>
        <li class="tre <?php echo $class_2;?>"><a href="<?=$PATHSITE?>/ispezioni.html"><img src="<?=$PATHSITE?>/img/icon3.png" alt="icon"><span>Ispezioni</span></a></li>
        <li class="quattro <?php echo $class_3;?>"><a href="<?=$PATHSITE?>/pianificazione.html"><img src="<?=$PATHSITE?>/img/icon4.png" alt="icon"><span>Designazione</span></a></li>
    </ul> 
</nav>
 	</div>
</header>

<section id="page" class="ispettore">
	<div class="container ">
		<div class="header">
			<aside>
				<form method="POST" action="index.php">
                        <input name="ispettore" type="text" placeholder="Cerca ispettore" value="<?=$ispettoreCognome?>" style="display: inline;">
                    </form>
				<a href="detail.php" class="button add">Aggiungi Ispettore</a>
				<a href="../uot/index.php" class="button">UOT</a>
			</aside>
		</div>
		<?php if(isset($_GET["succes"])){ ?>
        <div class="row addnew">
	    <div class="span12"><div class="alert alert-success">Modifica avvenuta con successo!</div></div>
		<?php } ?>
		<div class="row">
			<div class="span12">
				<table>
					<thead>
						<tr>
							<th class="span5"> Cognome</th>
							<th class="span5"> Nome</th>
							<th width="50px"></th>
							<th width="50px"></th>
						</tr>
					</thead>
					<tbody>
						<tr>
                        <td>Nigri</td>
						<td>Francesco</td>
                        <td><a href="ispettore-scheda_esperto.html"><img src="img/icon-mod.png" alt="icon"></td>
						<td><a href="#"><img src="img/icon-canc.png" alt="icon"></td>
                    </tr>
					</tbody>
				</table>
			</div>
		</div>
	</div>
</section>

<?php include '../include/footer.php';?>
</body>
</html>