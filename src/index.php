<!DOCTYPE html>
<html lang="it">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>newRISPE</title>
        <link rel="stylesheet" href="css/bootstrap.css">
        <link rel="stylesheet" href="css/bootstrap-responsive.css">
        <link rel="stylesheet" href="css/style.css">
        <script type="text/javascript" src="js/jquery.min.js"></script>
        <script type="text/javascript" src="js/modernizr.js"></script>
        <script type="text/javascript" src="js/bootstrap.js"></script>
        <script type="text/javascript" src="js/jquery-ui.js"></script>
        <script type='text/javascript' src='js/jquery.cookie.js'></script>
        <script type='text/javascript' src='js/jquery.hoverIntent.minified.js'></script>
        <script type='text/javascript' src='js/jquery.dcjqaccordion.2.7.min.js'></script>
        <script type='text/javascript' src='js/jquery.validate.min.js'></script>
        <script type="text/javascript" src="js/function.js"></script>
    </head>
    <body>
        <header>
            <div class="container">
                <div class="row">
                    <div class="span12">
                        <div class="logo login"><i>RISPE 2.0</i></div>
                    </div>
                </div>
            </div>
        </header>
        
        <section id="login">
            <div class="container">
            <?php 
            if(isset($_SESSION['login-error']))
                print_r($_SESSION['login-error']);
            ?>
            
            <div class="box">
                <div class="title">GESTIONE ISPEZIONI SEVESO</div>
                <div class="content">
                    <form method="POST" action="controllers.php" id="login-form" class="form-validate">
                        <input type="hidden" name="op" value="4" />
				<div class="control-group">
					<label for="email" class="control-label">Email</label>
					<div class="controls">
						<input data-rule-required="true" data-rule-email="false" name="email" type="text" value="" onfocus="if(this.value == this.defaultValue) { this.value = ''; }" onblur="if (this.value == '') { this.value = this.defaultValue;}">
					</div>
				</div>	
				<div class="control-group">
					<label for="password" class="control-label">Password</label>
					<div class="controls">
						<input data-rule-required="true" name="password" type="password" value="" onfocus="if(this.value == this.defaultValue) { this.value = ''; }" onblur="if (this.value == '') { this.value = this.defaultValue;}">
					</div>
				</div>	
                        <button type="submit">ACCEDI</button>
                </form>
				<a href="#" class="setpass">Dimenticato la password?</a>
                </div>
            </div>
        </section>
        <?php
        // put your code here
        ?>
<script type="text/javascript">
	$(document).ready(function(){
			
			$('.setpass').click(function() {
				$('.modale.setpass').fadeIn('fast')
			});
		  	
		  	$(".closepopuppass").click(function() {
				$(this).parent().parent().fadeOut();
				return false;
		  	});
	});
</script>
    </body>
</html>
