<div class="offcanvas-header bg-light">
	<h5 class="offcanvas-title" id="offcanvasExampleLabel">Filtros - Comprobantes</h5>
	<button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
</div>
<!--end offcanvas-header-->
<form class="d-flex flex-column justify-content-end h-100" name="vit_comprobantessearch" id="vit_comprobantessearch" action="vit_comprobantessrch.php" method="post">
<!--<form action="" class="d-flex flex-column justify-content-end h-100">-->
	<div class="offcanvas-body">
		<div class="mb-4">
			<input class="form-control" type="text" name="s_Cfdi_Serie" id="s_Cfdi_Serie" size="30" maxlength="120" placeholder="Serie" value="<?php echo htmlspecialchars(@$_GET["s_Cfdi_Serie"]) ?>">
		</div>
		<div class="mb-4">
			<input class="form-control" type="text" name="s_Cfdi_Folio" id="s_Cfdi_Folio" size="30" placeholder="Folio" value="<?php echo htmlspecialchars(@$_GET["s_Cfdi_Folio"]); ?>">
		</div>
		<div class="mb-4">
				<input type="date" class="form-control" data-provider="flatpickr" name="s_Cfdi_Fecha" id="s_Cfdi_Fecha" placeholder="Fecha" value="<?php echo FormatDateTime(@$s_Cfdi_Fecha,5); ?>">
		</div>
		<div class="mb-4">
				<?php
				$x_Emi_RFCList = "<select class=\"form-control\" id=\"s_Emi_RFC\" name=\"s_Emi_RFC\">";
				$x_Emi_RFCList .= "<option value=''>Emisores | Favor de elegir</option>";
				$sSqlWrk = "SELECT vit_emisor.Emi_RFC,
				vit_emisor.Emi_Nombre
				FROM vit_comprobantes INNER JOIN vit_emisor ON vit_comprobantes.Emi_RFC = vit_emisor.Emi_RFC
				WHERE vit_emisor.Emi_RFC <> '' ";
				if(@$_SESSION["project1_status_Municipio"] != ""){
				$sSqlWrk .= "AND vit_emisor.Mun_ID = '".@$_SESSION["project1_status_Municipio"]."' ";
				}
				$sSqlWrk .= "
				GROUP BY vit_emisor.Emi_RFC
				ORDER BY vit_emisor.Emi_RFC ASC";
				#echo "<br />".$sSqlWrk;
				$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error($conn) . ' SQL:' . $sSqlWrk);
				if ($rswrk) {
					$rowcntwrk = 0;
					while ($datawrk = phpmkr_fetch_array($rswrk)) {
						$x_Emi_RFCList .= "<option value=\"" . htmlspecialchars($datawrk[0]) . "\"";
						if ($datawrk["Emi_RFC"] == @$_GET["s_Emi_RFC"]) {
							$x_Emi_RFCList .= "' selected";
						}
						$x_Emi_RFCList .= ">" . $datawrk["Emi_RFC"] . " " . $datawrk["Emi_Nombre"] . "</option>";
						$rowcntwrk++;
					}
				}
				@phpmkr_free_result($rswrk);
				$x_Emi_RFCList .= "</select>";
				echo $x_Emi_RFCList;
				?>
		</div>
		<div class="mb-4">
				<?php
				$x_Rec_RFCList = "<select class=\"form-control\" id=\"s_Rec_RFC\" name=\"s_Rec_RFC\">";
				$x_Rec_RFCList .= "<option value=''>Receptores | Favor de elegir</option>";
				$sSqlWrk = "SELECT ";
				$sSqlWrk .= "vit_comprobantes.Rec_RFC, ";
				$sSqlWrk .= "vit_receptor.Rec_Nombre, ";
				$sSqlWrk .= "vit_receptor.Rec_Apellido_Paterno, ";
				$sSqlWrk .= "vit_receptor.Rec_Apellido_Materno ";
				$sSqlWrk .= "FROM vit_comprobantes ";
				$sSqlWrk .= "INNER JOIN vit_receptor ON vit_comprobantes.Rec_RFC = vit_receptor.Rec_RFC "; 														
				$sSqlWrk .= "WHERE Rec_Nombre <> '' ";
				if(@$_GET["s_Emi_RFC"]!=""){
				$sSqlWrk .= "AND vit_comprobantes.Emi_RFC = '".@$_GET["s_Emi_RFC"]."' ";															
				}
				if(@$_SESSION["project1_status_Municipio"] != ""){
				$sSqlWrk .= "AND vit_receptor.Mun_ID = '".@$_SESSION["project1_status_Municipio"]."' ";
				}
				$sSqlWrk .= "GROUP BY Rec_RFC ";
				$sSqlWrk .= "ORDER BY Rec_Nombre, Rec_Apellido_Paterno, Rec_Apellido_Materno Asc";
				#echo "<br />".$sSqlWrk;
				$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error($conn) . ' SQL:' . $sSqlWrk);
				if ($rswrk) {
					$rowcntwrk = 0;
					while ($datawrk = phpmkr_fetch_array($rswrk)) {
						$x_Rec_RFCList .= "<option value=\"" . htmlspecialchars($datawrk[0]) . "\"";
						if ($datawrk["Rec_RFC"] == @$_GET["s_Rec_RFC"]) {
							$x_Rec_RFCList .= "' selected";
						}
						$x_Rec_RFCList .= ">" . $datawrk["Rec_Nombre"] . " " . $datawrk["Rec_Apellido_Paterno"] . " " . $datawrk["Rec_Apellido_Materno"] . "</option>";
						$rowcntwrk++;
					}
				}
				@phpmkr_free_result($rswrk);
				$x_Rec_RFCList .= "</select>";
				echo $x_Rec_RFCList;
				?>
				
		</div>
		<div class="mb-4">
			<input class="form-control" type="text" name="s_Cfdi_UUID" id="s_Cfdi_UUID" size="30" placeholder="UUID" value="<?php echo (@$_GET["s_Cfdi_UUID"]); ?>">
		</div>
		<div class="mb-4">
			<?php
			$x_Mun_IDList = "<select class=\"form-control\" id=\"s_Mun_ID\" name=\"s_Mun_ID\">";
			$x_Mun_IDList .= "<option value=''>Municipios | Favor de elegir</option>";
			$sSqlWrk = "SELECT DISTINCT `Mun_ID`, `Mun_Descrip` FROM `vit_municipios` WHERE Mun_ID <> '' ";
			if(@$_SESSION["project1_status_Municipio"] != ""){
			$sSqlWrk .= "AND Mun_ID = '".@$_SESSION["project1_status_Municipio"]."' ";
			}
			$sSqlWrk .= "ORDER BY `Mun_Descrip` ASC ";
			$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error($conn) . ' SQL:' . $sSqlWrk);
			if ($rswrk) {
				$rowcntwrk = 0;
				while ($datawrk = phpmkr_fetch_array($rswrk)) {
					$x_Mun_IDList .= "<option value=\"" . htmlspecialchars($datawrk[0]) . "\"";
					if ($datawrk["Mun_ID"] == @$_GET["s_Mun_ID"]) {
						$x_Mun_IDList .= "' selected";
					}
					$x_Mun_IDList .= ">" . $datawrk["Mun_Descrip"] . "</option>";
					$rowcntwrk++;
				}
			}
			@phpmkr_free_result($rswrk);
			$x_Mun_IDList .= "</select>";
			echo $x_Mun_IDList;
			?>
		</div>
		<div class="mb-4">
			<?php
			$x_statusList = "<select class=\"form-control\" id=\"s_Status\" name=\"s_Status\">";
			$x_statusList .= "<option value=''>Status | Favor de elegir</option>";
				$x_statusList .= "<option value=\"A\"";
				$x_statusList .= ">" . "Activo" . "</option>";
				$x_statusList .= "<option value=\"C\"";
				$x_statusList .= ">" . "Cancelado" . "</option>";
				$x_statusList .= "<option value=\"P\"";
				$x_statusList .= ">" . "Pendiente de cancelar" . "</option>";
			$x_statusList .= "</select>";
			echo $x_statusList;
			?>
		</div>
		<div class="mb-4">
			<?php
			$x_statusList = "<select class=\"form-control\" id=\"s_StatusSat\" name=\"s_StatusSat\">";
			$x_statusList .= "<option value=''>Status SAT | Favor de elegir</option>";
				$x_statusList .= "<option value=\"Success\"";
				$x_statusList .= ">" . "Timbrado" . "</option>";
				$x_statusList .= "<option value=\"Invalido\"";
				$x_statusList .= ">" . "Sin Timbrar" . "</option>";
			$x_statusList .= "</select>";
			echo $x_statusList;
			?>
		</div>
	</div>
	<!--end offcanvas-body-->
	<div class="offcanvas-footer border-top p-3 text-center hstack gap-2">
		<button type="button" id="btn_quitar" class="btn btn-soft-info waves-effect waves-light w-100" title="Quitar Filtros" data-bs-dismiss="offcanvas">Quitar</button>
		&nbsp;
		<button type="button" id="btn_filtrar" class="btn btn-soft-success waves-effect waves-light w-100" title="Aplicar Filtros" data-bs-dismiss="offcanvas">Aplicar</button>
	</div>
	<!--end offcanvas-footer-->
</form>