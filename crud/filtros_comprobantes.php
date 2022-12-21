<div class="offcanvas-header bg-light">
	<h5 class="offcanvas-title" id="offcanvasExampleLabel">Filtros - Comprobantes</h5>
	<button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
</div>
<!--end offcanvas-header-->
<form class="d-flex flex-column justify-content-end h-100" name="vit_comprobantessearch" id="vit_comprobantessearch" action="vit_comprobantessrch.php" method="post">
<!--<form action="" class="d-flex flex-column justify-content-end h-100">-->
	<div class="offcanvas-body">
		<div class="mb-4">
			<label for="Serie" class="form-label text-muted text-uppercase fw-semibold mb-3">Serie</label>
			<?php
			$x_Cfdi_SerieList = "<select class=\"form-control form-control-sm\" id=\"s_Cfdi_Serie\" name=\"s_Cfdi_Serie\">";
			$x_Cfdi_SerieList .= "<option value=''>Series | Favor de elegir</option>";
			/*$sSqlWrk = "
			SELECT DISTINCT 
			vit_comprobantes.Cfdi_Serie, 
			vit_comprobantes.Cfdi_Status, 
			vit_comprobantes.Cfdi_Error 
			FROM 
			vit_comprobantes 
			WHERE vit_comprobantes.Cfdi_Error = 'Success' 
			AND vit_comprobantes.Cfdi_Status <> 'P'  
			";
			if(@$_SESSION["project1_status_Municipio"] != ""){
			$sSqlWrk .= "AND vit_comprobantes.Mun_ID = '".@$_SESSION["project1_status_Municipio"]."' ";
			}
			$sSqlWrk .= "ORDER BY vit_comprobantes.Cfdi_Serie ASC";
			#echo "<br />".$sSqlWrk;
			$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
			if ($rswrk) {
				$rowcntwrk = 0;
				while ($datawrk = phpmkr_fetch_array($rswrk)) {
					$x_Cfdi_SerieList .= "<option value=\"" . htmlspecialchars($datawrk["Cfdi_Serie"]) . "\"";
					if ($datawrk["Cfdi_Serie"] == @$_GET["Cfdi_Serie"]) {
						$x_Cfdi_SerieList .= "' selected";
					}
					$x_Cfdi_SerieList .= ">" . $datawrk["Cfdi_Serie"] . "</option>";
					$rowcntwrk++;
				}
			}
			@phpmkr_free_result($rswrk);*/
			$x_Cfdi_SerieList .= "</select>";
			echo $x_Cfdi_SerieList;
			?>
		</div>
		<div class="mb-4">
			<label for="Folio" class="form-label text-muted text-uppercase fw-semibold mb-3">Folio</label>
			<input class="form-control form-control-sm" type="text" name="s_Cfdi_Folio" id="s_Cfdi_Folio" size="30" placeholder="Folio" value="<?php echo htmlspecialchars(@$_GET["s_Cfdi_Folio"]); ?>">
		</div>
		<div class="mb-4">
			<label for="datepicker-range" class="form-label text-muted text-uppercase fw-semibold mb-3">Fecha</label>
			<input type="date" class="form-control form-control-sm" data-provider="flatpickr" name="s_Cfdi_Fecha" id="s_Cfdi_Fecha" placeholder="Fecha" value="<?php echo FormatDateTime(@$s_Cfdi_Fecha,5); ?>">
		</div>
		<div class="mb-4">
			<label for="country-select" class="form-label text-muted text-uppercase fw-semibold mb-3">Emisor</label>
			<?php
			$x_Emi_RFCList = "<select class=\"form-control form-control-sm\" id=\"s_Emi_RFC\" name=\"s_Emi_RFC\">";
			$x_Emi_RFCList .= "<option value=''>Emisores | Favor de elegir</option>";
			/*$sSqlWrk = "
			SELECT DISTINCT
			Vit_Emisor.Emi_RFC,
			Vit_Emisor.Emi_NomCorto
			FROM Vit_Emisor
			WHERE Vit_Emisor.Emi_RFC <> '' ";
			if(@$_SESSION["project1_status_Municipio"] != ""){
			$sSqlWrk .= "AND Vit_Emisor.Mun_ID = '".@$_SESSION["project1_status_Municipio"]."' ";
			}
			$sSqlWrk .= "
			ORDER BY Vit_Emisor.Emi_RFC ASC";
			#echo "<br />".$sSqlWrk;
			$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error($conn) . ' SQL:' . $sSqlWrk);
			if ($rswrk) {
				$rowcntwrk = 0;
				while ($datawrk = phpmkr_fetch_array($rswrk)) {
					$x_Emi_RFCList .= "<option value=\"" . htmlspecialchars($datawrk["Emi_RFC"]) . "\"";
					if ($datawrk["Emi_RFC"] == @$_GET["s_Emi_RFC"]) {
						$x_Emi_RFCList .= "' selected";
					}
					$x_Emi_RFCList .= ">" . $datawrk["Emi_RFC"] . " " . $datawrk["Emi_NomCorto"] . "</option>";
					$rowcntwrk++;
				}
			}
			@phpmkr_free_result($rswrk);*/
			$x_Emi_RFCList .= "</select>";
			echo $x_Emi_RFCList;
			?>
		</div>
		<div class="mb-4">
			<label for="country-select" class="form-label text-muted text-uppercase fw-semibold mb-3">Receptor</label>
			<?php
			$x_Rec_RFCList = "<select class=\"form-control form-control-sm\" id=\"s_Rec_RFC\" name=\"s_Rec_RFC\">";
			$x_Rec_RFCList .= "<option value=''>Receptores | Favor de elegir</option>";
			/*$sSqlWrk = "SELECT ";
			$sSqlWrk .= "Vit_Receptor.Rec_RFC, ";
			$sSqlWrk .= "Vit_Receptor.Rec_Nombre, ";
			$sSqlWrk .= "Vit_Receptor.Rec_Apellido_Paterno, ";
			$sSqlWrk .= "Vit_Receptor.Rec_Apellido_Materno ";
			$sSqlWrk .= "FROM Vit_Receptor ";												
			$sSqlWrk .= "WHERE Rec_Nombre <> '' ";
			if(@$_GET["s_Emi_RFC"]!=""){
			$sSqlWrk .= "AND Vit_Receptor.Emi_RFC = '".@$_GET["s_Emi_RFC"]."' ";															
			}
			if(@$_SESSION["project1_status_Municipio"] != ""){
			$sSqlWrk .= "AND Vit_Receptor.Mun_ID = '".@$_SESSION["project1_status_Municipio"]."' ";
			}
			$sSqlWrk .= "GROUP BY Rec_RFC ";
			$sSqlWrk .= "ORDER BY Rec_Nombre, Rec_Apellido_Paterno, Rec_Apellido_Materno Asc";
			#echo "<br />".$sSqlWrk;
			$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error($conn) . ' SQL:' . $sSqlWrk);
			if ($rswrk) {
				$rowcntwrk = 0;
				while ($datawrk = phpmkr_fetch_array($rswrk)) {
					$x_Rec_RFCList .= "<option value=\"" . htmlspecialchars($datawrk["Rec_RFC"]) . "\"";
					if ($datawrk["Rec_RFC"] == @$_GET["s_Rec_RFC"]) {
						$x_Rec_RFCList .= "' selected";
					}
					$x_Rec_RFCList .= ">" . $datawrk["Rec_Nombre"] . " " . $datawrk["Rec_Apellido_Paterno"] . " " . $datawrk["Rec_Apellido_Materno"] . "</option>";
					$rowcntwrk++;
				}
			}
			@phpmkr_free_result($rswrk);*/
			$x_Rec_RFCList .= "</select>";
			echo $x_Rec_RFCList;
			?>
		</div>
		<div class="mb-4">
			<label for="UUID" class="form-label text-muted text-uppercase fw-semibold mb-3">UUID</label>
			<input class="form-control form-control-sm" type="text" name="s_Cfdi_UUID" id="s_Cfdi_UUID" size="30" placeholder="UUID" value="<?php echo (@$_GET["s_Cfdi_UUID"]); ?>">
		</div>
		<div class="mb-4">
			<label for="country-select" class="form-label text-muted text-uppercase fw-semibold mb-3">Municipio</label>
			<?php
			$x_Mun_IDList = "<select class=\"form-control\" id=\"s_Mun_ID\" name=\"s_Mun_ID\">";
			$x_Mun_IDList .= "<option value=''>Municipios | Favor de elegir</option>";
			/*$sSqlWrk = "SELECT DISTINCT `Mun_ID`, `Mun_Descrip` FROM `Vit_Municipios` WHERE Mun_ID <> '' ";
			if(@$_SESSION["project1_status_Municipio"] != ""){
			$sSqlWrk .= "AND Mun_ID = '".@$_SESSION["project1_status_Municipio"]."' ";
			}
			$sSqlWrk .= "ORDER BY `Mun_Descrip` ASC ";
			$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error($conn) . ' SQL:' . $sSqlWrk);
			if ($rswrk) {
				$rowcntwrk = 0;
				while ($datawrk = phpmkr_fetch_array($rswrk)) {
					$x_Mun_IDList .= "<option value=\"" . htmlspecialchars($datawrk["Mun_ID"]) . "\"";
					if ($datawrk["Mun_ID"] == @$_GET["s_Mun_ID"]) {
						$x_Mun_IDList .= "' selected";
					}
					$x_Mun_IDList .= ">" . $datawrk["Mun_Descrip"] . "</option>";
					$rowcntwrk++;
				}
			}
			@phpmkr_free_result($rswrk);*/
			$x_Mun_IDList .= "</select>";
			echo $x_Mun_IDList;
			?>
		</div>
		<div class="mb-4">
		<label for="country-select" class="form-label text-muted text-uppercase fw-semibold mb-3">Status Comprobante</label>
			<?php
			$x_statusList = "<select class=\"form-control form-control-sm\" id=\"s_Status\" name=\"s_Status\">";
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
			<label for="country-select" class="form-label text-muted text-uppercase fw-semibold mb-3">Status SAT</label>
			<?php
			$x_statusList = "<select class=\"form-control form-control-sm\" id=\"s_StatusSat\" name=\"s_StatusSat\">";
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
		<button type="button" id="btn_quitar" class="btn btn-info waves-effect waves-light w-100" title="Quitar Filtros" data-bs-dismiss="offcanvas">Cancelar</button>
		&nbsp;
		<button type="button" id="btn_filtrar" class="btn btn-success waves-effect waves-light w-100" title="Aplicar Filtros" data-bs-dismiss="offcanvas">Aplicar</button>
	</div>
	<!--end offcanvas-footer-->
</form>