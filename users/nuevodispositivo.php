<?php require ("s_index.php");
      require ("nuevodispositivo_logica.php"); ?>
<?php
if (isset($_GET["step"])) $step=$_GET["step"];
else $step="step1";
$a1=$a2=$a3=$a4="disabled";
# FUNCIONES:
#******************
#global $m1;

switch ($step) {
    case "step1":
		$a1='active';
		break;
    case "step2":
		$a2='active';
		break;
    case "step3":
		$a3='active';
		break;
    case "step4":
		$a4='active';
		break;
	}
?>
<div class="container">
	<div class="row">
		<section>
        <div class="wizard">
            <div class="wizard-inner">
                <div class="connecting-line"></div>
                <ul class="nav nav-tabs" role="tablist">
                    <?php echo"<li role='presentation' class='$a1'>"; ?>
                        <a href="#step1" data-toggle="tab" aria-controls="step1" role="tab" title="Step 1">
                            <span class="round-tab">
                                <i class="glyphicon glyphicon-question-sign"></i>
                            </span>
                        </a>
                    </li>
                    <?php echo"<li role='presentation' class='$a2'>"; ?>
                        <a href="#step2" data-toggle="tab" aria-controls="step2" role="tab" title="Step 2">
                            <span class="round-tab">
								<i class="glyphicon glyphicon-search"></i>
                            </span>
                        </a>
                    </li>
                    <?php echo"<li role='presentation' class='$a3'>"; ?>
                        <a href="#step3" data-toggle="tab" aria-controls="step3" role="tab" title="Step 3">
                            <span class="round-tab">
                                <i class="glyphicon glyphicon-pencil"></i>
                            </span>
                        </a>
                    </li>
                    <?php echo"<li role='presentation' class='$a4'>"; ?>
                        <a href="#complete" data-toggle="tab" aria-controls="complete" role="tab" title="Complete">
                            <span class="round-tab">
                                <i class="glyphicon glyphicon-ok"></i>
                            </span>
                        </a>
                    </li>
                </ul>
            </div>
				<div class="container">
<?php
            $basededatos=conectardb();
						switch ($step) {
							case "step1":
									$m1=step1($basededatos);
									echo "<ul class='list-inline pull-right'>";
									echo "<li><a href='nuevodispositivo.php?step=step2' class='btn btn-primary ".$m1."' role='button'>SIGUIENTE</a></li>";
									break;
							case "step2":
									$m2=step2($_SESSION['iden'], $basededatos);
									echo "<ul class='list-inline pull-right'>";
									echo "<input type='hidden' name='step' value='step3'>";
									echo "<li><a href='nuevodispositivo.php?step=step1' class='btn btn-primary active' role='button'>ANTERIOR</a></li>";
									echo "<li><a href='nuevodispositivo.php?step=step3' class='btn btn-primary ".$m2."' role='button'>SIGUIENTE</a></li>";
									break;

							case "step3":
									$m3=step3($_SESSION['iden'], $basededatos);
									echo "<ul class='list-inline pull-right'>";
									echo "<input type='hidden' name='step' value='step3'>";
									echo "<li><a href='nuevodispositivo.php?step=step2&iden=$_SESSION[iden]' class='btn btn-primary active' role='button'>ANTERIOR</a></li>";
									echo "<li><a href='nuevodispositivo.php?step=step4' class='btn btn-primary active' role='button'>".$m3."</a></li>";
									break;

							case "step4":
									step4();
									echo "<ul class='list-inline pull-right'>";
									echo "<input type='hidden' name='step' value='step3'>";
									echo "<li><a href='nuevodispositivo.php?step=step3' class='btn btn-primary active' role='button'>ANTERIOR</a></li>";
									break;
						}
									echo "<li><a href='s_listadispositivos.php' class='btn btn-primary active' role='button'>Volver al listado</a></li>";
									echo "</ul>";
?>
                </div>
        </div>
    </section>
   </div>
</div>
<?php require ("s_footer.php"); ?>
