<?php

ob_start();
$pageMenu="dati";
require 'config.php';
require 'db/mysql.php';
include("include/check_user.php");

$pageName="DATI";
include 'include/header.php';

$db= new DbConnect();
$db->open() or die($db->error());

?>
<script>
</script>

</head>

<body>
    <header>
        <div class="container">
    	<div class="row">
	<div class="span12">
        <div class="logo" style="border-bottom: 10px;"><a href="home.php">newRISPE</a></div>  
        <div class="info">
            <a href="dati.php">Dati</a>
            <a href="#">Criteri</a>
            <a href="logout.php">Logout</a>
        </div>
            </div>
        </div>
        <a href="javascript:;" class="mobilemenu">MENU</a>
        <nav>
        <ul>
            <li class="uno <?php echo $class_0;?>"><a href="ispettori.php"><img src="img/icon_1.png" alt="icon"><span class="none">Ispettori</span></a></li>
            <li class="due <?php echo $class_1;?>"><a href="stabilimenti.php"><img src="img/icon_2.png" alt="icon"><span class="none">Stabilimenti</span></a></li>
            <li class="tre <?php echo $class_2;?>"><a href="ispezioni.php"><img src="img/icon_3.png" alt="icon"><span>Ispezioni</span></a></li>
            <li class="quattro <?php echo $class_3;?>"><a href="pianificazione.php"><img src="img/icon_4.png" alt="icon"><span>Pianificazione</span></a></li>
        </ul> 
        </nav>
 	</div>
    </header>
</body>