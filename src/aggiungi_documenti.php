<?php

ob_start();
$pageMenu="documenti";
require 'config.php';

require 'db/mysql.php';
require 'class/IspezioneClass.php';
require 'class/TipoDocumentiClass.php';

include 'include/header.php'; 
$db= new DbConnect();
$db->open() or die($db->error());

$ispezioneClass=new IspezioneClass();
$tipodocumentiClass=new TipoDocumentiClass();

if (isset($_GET["id"])) {
    $ispezione = $_GET["id"];
    $ispezioneClass->setIspezioneId($_GET["id"]);
    $ispezioneClass->getDettaglioIspezione($db);
}

$elencotipi=$tipodocumentiClass->getTipiDocumento($db);

?>
<link rel="stylesheet" href="/js/jquery-tagsinput/jquery.tagsinput.css">      
<link rel="stylesheet" href="/js/jquery-ui/jquery-ui.css" />
<link rel="stylesheet" href="/js/bootstrap-fileupload/bootstrap-fileupload.min.css" />                 
<script type="text/javascript" src="js/jquery-ui/jquery-ui.js"></script>
<script type="text/javascript" src="js/bootstrap-fileupload/bootstrap-fileupload.js"></script>

<!-- Add jQuery library -->
<script type="text/javascript" src="lib/jquery-1.10.1.min.js"></script>

<!-- Add mousewheel plugin (this is optional) -->
<script type="text/javascript" src="lib/jquery.mousewheel-3.0.6.pack.js"></script>

<!-- Add fancyBox main JS and CSS files -->
<script type="text/javascript" src="source/jquery.fancybox.js?v=2.1.5"></script>
<link rel="stylesheet" type="text/css" href="source/jquery.fancybox.css?v=2.1.5" media="screen" />

<!-- Add Button helper (this is optional) -->
<link rel="stylesheet" type="text/css" href="source/helpers/jquery.fancybox-buttons.css?v=1.0.5" />
<script type="text/javascript" src="source/helpers/jquery.fancybox-buttons.js?v=1.0.5"></script>

<!-- Add Thumbnail helper (this is optional) -->
<link rel="stylesheet" type="text/css" href="source/helpers/jquery.fancybox-thumbs.css?v=1.0.7" />
<script type="text/javascript" src="source/helpers/jquery.fancybox-thumbs.js?v=1.0.7"></script>

<!-- Add Media helper (this is optional) -->
<script type="text/javascript" src="source/helpers/jquery.fancybox-media.js?v=1.0.6"></script>

<script type="text/javascript">
    $(document).ready(function () {
        $('.fancybox').fancybox();
    });
</script>

<script>
<?php if (isset($_GET['id'])) { ?>
        function cancella_foto(id) {
            if (confirm("Sicuro di voler cancellare?")) {
                $.ajax({
                    type: "POST",
                    url: "controllers.php",
                    data: "op=20&id=" + id + "&id_incidente=<?php echo $id; ?>",
                    success: function (response)
                    {
                        $("#foto_" + id + "").remove();
                    }
                });
            }
        }
<?php } ?>

    $(function () {

        $("select#attrezzature").change(function () {
            var valore = $('select#attrezzature option:selected').val();
            $.ajax({
                type: "POST",
                url: "controllers.php",
                data: "op=13&id=" + valore + "",
                success: function (response)
                {
                    $("#reparto").html(response);
                }
            });
        });

        $("#aggiungi_doc").click(function () {
            $("#altri_doc").append('<div class="fileupload fileupload-new" data-provides="fileupload"><span class="button btn-file btn-large"><span class="fileupload-new">Seleziona file</span><span class="fileupload-exists">Cambia</span><input type="file" name="file[]" id="file"></span><span class="fileupload-preview"></span><a href="#" class="close fileupload-exists" data-dismiss="fileupload" style="float: none">×</a></div>');
            return false;
        });

    });
</script>
<script src="js/selectize/js/selectize-standalone.js"></script>

</head>
<body>
    <header>
        <div class="container">
    	<div class="row">
	<div class="span12">
        <div class="logo" style="border-bottom: 10px;"><a href="home.php">newRISPE</a></div>  
        <div class="info">
            <a href="dati.php">Dati</a>
            <a href="criteri.php">Criteri</a>
            <a href="logout.php">Logout</a>
        </div>
            </div>
        </div>
        <a href="javascript:;" class="mobilemenu">MENU</a>
        <nav>
                <ul>
                <li class="uno <?php echo $class_0;?>"><a href="ispettori.php"><img src="img/icon_1.png" alt="icon"><span class="none">Ispettori</span></a></li>
                <li class="due <?php echo $class_1;?>"><a href="stabilimenti.php"><img src="img/icon_2.png" alt="icon"><span class="none">Stabilimenti</span></a></li>
                <li class="tre active<?php echo $class_2;?>"><a href="ispezioni.php"><img src="img/icon_3.png" alt="icon"><span>Ispezioni</span></a></li>
                <li class="quattro <?php echo $class_3;?>"><a href="pianificazione.php"><img src="img/icon_4.png" alt="icon"><span>Pianificazione</span></a></li>
            </ul> 
        </nav>
 	</div>
    </header>
    <input type="hidden" name="ignora" id="ispezione" value="<?= $ispezione ?>">
    
    <section id="page" >
        <div class="container addnew">
            <div class="header">
                <h1>Ispezione-Aggiungi documenti</h1>
        <aside>
            <a href="ispezioni.php" class="back">Indietro</a>
        </aside>
        <?php if (isset($_GET["msg"])) { ?>
        <div class="row">
            <div class="span12"><div class="alert alert-error">Compila tutti i campi obbligatori!</div></div>
        </div>
        <?php } ?>
        <?php if (isset($_GET["succes"])) { ?>
                <div class="row">
                        <div class="span12"><div class="alert alert-success">Salvataggio avvenuto!</div></div>
                </div>
        <?php } ?>
        <div class="row">
                <div class="span12"><div class="title">Aggiungi documento</div></div>
        </div>
        <input type="hidden" name="indice" value="0" id="indice">
        <div class="container scheda">
            <form method="POST" action="saveDocumento.php" id="form_doc">
                <input type="hidden" name="ispezioneId" value="<?= $ispezioneClass->getIspezioneId() ?>">
                <div class="row">
                    <div class="span8">  
                        <label>Tipo di documento*</label>   
                        <select style="width: 270px;" name="regioni" >
                            <option value="">Seleziona</option>
                            <?php while ($row = $db->fetchassoc2($elencotipi)) { ?>
                                    <option value="<?= $row["tipodocId"] ?>" 
                                    <?php 
                                    if ($tipodocumentiClass->getTipodocId() == $row["tipodocId"]) {
                                            echo 'selected'; 
                                    } ?> ><?= $row["tipodoc"] ?></option>
                            <?php } ?>
                        </select>
                    </div>
                </div>
                
                 <div class="row">
                        <div class="span12">
                            <label>Inserisci documento</label>
                            <div class="fileupload fileupload-new" data-provides="fileupload">
                                <span class="button btn-file btn-large"><span class="fileupload-new">Seleziona file</span>
                                    <span class="fileupload-exists">Cambia</span><input type="file" name="file[]" id="file"></span>
                                <span class="fileupload-preview"></span><a href="#" class="close fileupload-exists" data-dismiss="fileupload" style="float: none">×</a>
                            </div>		

                            <div id="altri_doc"></div>
                            <a class="button small" id="aggiungi_doc">+ Aggiungi documento</a>
                        </div>
                    </div>

                    <div class="row">
                        <div class="span12"><button type="submit">Salva</button> </div>
                    </div>

            </form>
        </div>
    </section>
</body>