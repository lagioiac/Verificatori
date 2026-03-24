<?php
//$basename = basename($_SERVER['PHP_SELF']);
$class_0 = "";
$class_1 = "";
$class_2 = "";
$class_3 = "";

switch ($pageMenu){
	case "ispettori":
		$class_0 = "active";
		break;
	case "stabilimenti":
		$class_1 = "active"; 
		break;	
	case "ispezioni":
		$class_2 = "active";
		break;
	case "designazione":	
		$class_3 = "active";
		break;
		
}
?>
<div class="row">
	<div class="span12">
        <div class="logo" style="border-bottom: 10px;"><a href="<?=$PATHSITE?>/home.php">new<span>RISPE</span></a></div>    
        <div class="info">
            <a href="<?=$PATHSITE?>criteri.html"><img src="<?=$PATHSITE?>/img/kpi/kpi1.png" width="35px" height="35px" title="Criteri" alt="criteri"></a>
            
        </div>
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