<?php include("config.php"); ?>
<?php include("include/check_user.php"); ?>
<?php include 'include/header.php'; ?>
</head> <!-- chiusura del head presente in header.php lasciato aperto volutamente -->

<!-- nel vecchio codice $pageName era valorizzata con il nome del file, ovvero della pagina che veniva visualizzata  -->
<?php $pageName=$current_page; // per tenere traccia della navigazione - $current_page viene settata nel header.php ?> 


<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>UOT - Under Construction</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="container">
        <h1>Pagina UOT</h1>
		<span><img src="img/PrestoOnLine.png" alt="icon"></span>
        <p>Questa pagina è in costruzione. Torna presto per ulteriori aggiornamenti!</p>
    </div>
	
	
	<?php include 'include/footer.php';?>
</body>
</html>