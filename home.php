<?php include("config.php"); ?>
<?php include("include/check_user.php"); ?>
<?php include 'include/header.php'; ?>
</head> <!-- chiusura del head presente in header.php lasciato aperto volutamente -->

<!-- nel vecchio codice $pageName era valorizzata con il nome del file, ovvero della pagina che veniva visualizzata  -->
<?php $pageName=$current_page; // per tenere traccia della navigazione - $current_page viene settata nel header.php ?> 


<body>


<header>
	<div class="container">
    	<div class="row">
        	<div class="span12">
                <!-- <div class="logo">Verificatori</div> -->
                <div class="info">
                    <!-- <a href="indicatori.php?an=0"><img src="img/kpi2.png" alt="icon"></a> -->
                    <a href="dati.php">Dati</a>
                    <a href="criteri.php">Criteri</a>
                    <!-- <a href="logout.php">Logout</a> -->
                </div>
            </div>
        </div>
 	</div>
</header>


<section id="home">
	<div class="container">		
    	<div class="row">
        	<a href="verificatori.php" class="span6">
            	<span><img src="img/home1-ispettore.png" alt="icon"></span>
                <p>Personale UOT</p>
            </a>
			<a href="uot.php" class="span6">
				<span><img src="img/home2-stabilimenti.png" alt="icon"></span>
				<p>UOT</p>
			</a>
            <a href="stabilimenti.php" class="span6">
            	<span><img src="img/home2-stabilimenti.png" alt="icon"></span>
				<p>Siti/Aziende</p>
            </a>
            <a href="verifiche.php" class="span6">
            	<span><img src="img/home3-audit.png" alt="icon"></span>
                <p>Verifiche</p>
            </a>			
			<a href="pianificazione.php" class="span12">
				<span><img src="img/home4-pianificazione.png" alt="icon"></span>
				<p>Pianificazione</p>
			</a>
		</div>
    </div>
</section>

<?php include 'include/footer.php';?>

</body>
</html>
