<?php include("config.php"); ?>
<?php $pageName="HOME"; ?>
<?php include("include/check_user.php"); ?>
<?php include 'include/header.php'; ?>
 
</head>

<body>

<header>
	<div class="container">
    	<div class="row">
        	<div class="span12">
                <div class="logo">newRISPE</div>
                <div class="info">
                    <a href="indicatori.php?an=0"><img src="img/kpi2.png" alt="icon"></a>
                    <a href="dati.php">Dati</a>
                    <a href="criteri.php">Criteri</a>
                    <a href="logout.php">Logout</a>
                </div>
            </div>
        </div>
 	</div>
</header>

<section id="home">
	<div class="container">
    	<div class="row">
        	<a href="ispettori.php" class="span6">
            	<span><img src="img/home1-ispettore.png" alt="icon"></span>
                <p>Ispettori</p>
            </a>
            <a href="stabilimenti.php" class="span6">
            	<span><img src="img/home2-stabilimenti.png" alt="icon"></span>
				<p>Stabilimenti</p>
            </a>
            <a href="ispezioni.php" class="span6">
            	<span><img src="img/home3-audit.png" alt="icon"></span>
                <p>Ispezioni</p>
            </a>
            <a href="pianificazione.php" class="span6">
            	<span><img src="img/home4-pianificazione.png" alt="icon"></span>
                <p>Pianificazione</p>
            </a>
    	</div>
    </div>
</section>

<?php include 'include/footer.php';?>

</body>
</html>
