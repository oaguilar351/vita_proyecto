<?php session_start(); ?>
<?php ob_start(); ?>
<?php
if (@$_SESSION["project1_status"] <> "login") {
	header("Location:  login.php");
	exit();
}
?>
<?php

// Initialize common variables
$x_Rec_RFC = Null; 
$ox_Rec_RFC = Null;
$x_Rec_Nombre = Null; 
$ox_Rec_Nombre = Null;
$x_Rec_Apellido_Paterno = Null; 
$ox_Rec_Apellido_Paterno = Null;
$x_Rec_Apellido_Materno = Null; 
$ox_Rec_Apellido_Materno = Null;
$x_Rec_DomicilioFiscaleceptor = Null; 
$ox_Rec_DomicilioFiscaleceptor = Null;
$x_Rec_ResidenciaFiscal = Null; 
$ox_Rec_ResidenciaFiscal = Null;
$x_Rec_NumRegIdTrib = Null; 
$ox_Rec_NumRegIdTrib = Null;
$x_Rec_RegimenFiscalReceptor = Null; 
$ox_Rec_RegimenFiscalReceptor = Null;
$x_Rec_Curp = Null; 
$ox_Rec_Curp = Null;
$x_Rec_NumSeguridadSocial = Null; 
$ox_Rec_NumSeguridadSocial = Null;
$x_Rec_FechaInicioRelLaboral = Null; 
$ox_Rec_FechaInicioRelLaboral = Null;
$x_Rec_Antiguedad = Null; 
$ox_Rec_Antiguedad = Null;
$x_Rec_TipoContrato = Null; 
$ox_Rec_TipoContrato = Null;
$x_Rec_Sindicalizado = Null; 
$ox_Rec_Sindicalizado = Null;
$x_Rec_TipoJornada = Null; 
$ox_Rec_TipoJornada = Null;
$x_Rec_TipoRegimen = Null; 
$ox_Rec_TipoRegimen = Null;
$x_Rec_NumEmpleado = Null; 
$ox_Rec_NumEmpleado = Null;
$x_Rec_Departamento = Null; 
$ox_Rec_Departamento = Null;
$x_Rec_Puesto = Null; 
$ox_Rec_Puesto = Null;
$x_Rec_RiesgoPuesto = Null; 
$ox_Rec_RiesgoPuesto = Null;
$x_Rec_PeriodicidadPago = Null; 
$ox_Rec_PeriodicidadPago = Null;
$x_Rec_Banco = Null; 
$ox_Rec_Banco = Null;
$x_Rec_CuentaBancaria = Null; 
$ox_Rec_CuentaBancaria = Null;
$x_Rec_SalarioBaseCotApor = Null; 
$ox_Rec_SalarioBaseCotApor = Null;
$x_Rec_SalarioDiarioIntegrado = Null; 
$ox_Rec_SalarioDiarioIntegrado = Null;
$x_Rec_ClaveEntFed = Null; 
$ox_Rec_ClaveEntFed = Null;
$x_Rec_Status = Null; 
$ox_Rec_Status = Null;
$x_Mun_ID = Null; 
$ox_Mun_ID = Null;
$x_Rec_CreationDate = Null; 
$ox_Rec_CreationDate = Null;
?>
<?php include ("libs/db.php") ?>
<?php include ("libs/phpmkrfn.php") ?>
<?php

// Get action
$sAction = @$_POST["a_search"];
switch ($sAction)
{
	case "S": // Get Search Criteria

	// Build search string for advanced search, remove blank field
	$sSrchStr = "";

	// Field Rec_RFC
	$s_Rec_RFC = @$_POST["s_Rec_RFC"];
	$z_Rec_RFC = ((function_exists("get_magic_quotes_gpc") && (function_exists("get_magic_quotes_gpc") && get_magic_quotes_gpc()))) ? stripslashes(@$_POST["z_Rec_RFC"][0]) : @$_POST["z_Rec_RFC"][0]; 
	if ($s_Rec_RFC <> "") {
		$sSrchFld = $s_Rec_RFC;
		$sSrchWrk = "s_Rec_RFC=" . urlencode($sSrchFld);
		$sSrchWrk .= "&z_Rec_RFC=" . urlencode($z_Rec_RFC);
	} else {
		$sSrchWrk = "";
	}
	if ($sSrchWrk <> "") {
		if ($sSrchStr == "") {
			$sSrchStr = $sSrchWrk;
		} else {
			$sSrchStr .= "&" . $sSrchWrk;
		}
	}

	// Field Rec_Nombre
	$s_Rec_Nombre = @$_POST["s_Rec_Nombre"];
	$z_Rec_Nombre = ((function_exists("get_magic_quotes_gpc") && (function_exists("get_magic_quotes_gpc") && get_magic_quotes_gpc()))) ? stripslashes(@$_POST["z_Rec_Nombre"][0]) : @$_POST["z_Rec_Nombre"][0]; 
	if ($s_Rec_Nombre <> "") {
		$sSrchFld = $s_Rec_Nombre;
		$sSrchWrk = "s_Rec_Nombre=" . urlencode($sSrchFld);
		$sSrchWrk .= "&z_Rec_Nombre=" . urlencode($z_Rec_Nombre);
	} else {
		$sSrchWrk = "";
	}
	if ($sSrchWrk <> "") {
		if ($sSrchStr == "") {
			$sSrchStr = $sSrchWrk;
		} else {
			$sSrchStr .= "&" . $sSrchWrk;
		}
	}

	// Field Rec_Apellido_Paterno
	$s_Rec_Apellido_Paterno = @$_POST["s_Rec_Apellido_Paterno"];
	$z_Rec_Apellido_Paterno = ((function_exists("get_magic_quotes_gpc") && (function_exists("get_magic_quotes_gpc") && get_magic_quotes_gpc()))) ? stripslashes(@$_POST["z_Rec_Apellido_Paterno"][0]) : @$_POST["z_Rec_Apellido_Paterno"][0]; 
	if ($s_Rec_Apellido_Paterno <> "") {
		$sSrchFld = $s_Rec_Apellido_Paterno;
		$sSrchWrk = "s_Rec_Apellido_Paterno=" . urlencode($sSrchFld);
		$sSrchWrk .= "&z_Rec_Apellido_Paterno=" . urlencode($z_Rec_Apellido_Paterno);
	} else {
		$sSrchWrk = "";
	}
	if ($sSrchWrk <> "") {
		if ($sSrchStr == "") {
			$sSrchStr = $sSrchWrk;
		} else {
			$sSrchStr .= "&" . $sSrchWrk;
		}
	}

	// Field Rec_Apellido_Materno
	$s_Rec_Apellido_Materno = @$_POST["s_Rec_Apellido_Materno"];
	$z_Rec_Apellido_Materno = ((function_exists("get_magic_quotes_gpc") && (function_exists("get_magic_quotes_gpc") && get_magic_quotes_gpc()))) ? stripslashes(@$_POST["z_Rec_Apellido_Materno"][0]) : @$_POST["z_Rec_Apellido_Materno"][0]; 
	if ($s_Rec_Apellido_Materno <> "") {
		$sSrchFld = $s_Rec_Apellido_Materno;
		$sSrchWrk = "s_Rec_Apellido_Materno=" . urlencode($sSrchFld);
		$sSrchWrk .= "&z_Rec_Apellido_Materno=" . urlencode($z_Rec_Apellido_Materno);
	} else {
		$sSrchWrk = "";
	}
	if ($sSrchWrk <> "") {
		if ($sSrchStr == "") {
			$sSrchStr = $sSrchWrk;
		} else {
			$sSrchStr .= "&" . $sSrchWrk;
		}
	}

	// Field Rec_DomicilioFiscaleceptor
	$x_Rec_DomicilioFiscaleceptor = @$_POST["x_Rec_DomicilioFiscaleceptor"];
	$z_Rec_DomicilioFiscaleceptor = ((function_exists("get_magic_quotes_gpc") && (function_exists("get_magic_quotes_gpc") && get_magic_quotes_gpc()))) ? stripslashes(@$_POST["z_Rec_DomicilioFiscaleceptor"][0]) : @$_POST["z_Rec_DomicilioFiscaleceptor"][0]; 
	if ($x_Rec_DomicilioFiscaleceptor <> "") {
		$sSrchFld = $x_Rec_DomicilioFiscaleceptor;
		$sSrchWrk = "x_Rec_DomicilioFiscaleceptor=" . urlencode($sSrchFld);
		$sSrchWrk .= "&z_Rec_DomicilioFiscaleceptor=" . urlencode($z_Rec_DomicilioFiscaleceptor);
	} else {
		$sSrchWrk = "";
	}
	if ($sSrchWrk <> "") {
		if ($sSrchStr == "") {
			$sSrchStr = $sSrchWrk;
		} else {
			$sSrchStr .= "&" . $sSrchWrk;
		}
	}

	// Field Rec_ResidenciaFiscal
	$x_Rec_ResidenciaFiscal = @$_POST["x_Rec_ResidenciaFiscal"];
	$z_Rec_ResidenciaFiscal = ((function_exists("get_magic_quotes_gpc") && (function_exists("get_magic_quotes_gpc") && get_magic_quotes_gpc()))) ? stripslashes(@$_POST["z_Rec_ResidenciaFiscal"][0]) : @$_POST["z_Rec_ResidenciaFiscal"][0]; 
	if ($x_Rec_ResidenciaFiscal <> "") {
		$sSrchFld = $x_Rec_ResidenciaFiscal;
		$sSrchWrk = "x_Rec_ResidenciaFiscal=" . urlencode($sSrchFld);
		$sSrchWrk .= "&z_Rec_ResidenciaFiscal=" . urlencode($z_Rec_ResidenciaFiscal);
	} else {
		$sSrchWrk = "";
	}
	if ($sSrchWrk <> "") {
		if ($sSrchStr == "") {
			$sSrchStr = $sSrchWrk;
		} else {
			$sSrchStr .= "&" . $sSrchWrk;
		}
	}

	// Field Rec_NumRegIdTrib
	$x_Rec_NumRegIdTrib = @$_POST["x_Rec_NumRegIdTrib"];
	$z_Rec_NumRegIdTrib = ((function_exists("get_magic_quotes_gpc") && (function_exists("get_magic_quotes_gpc") && get_magic_quotes_gpc()))) ? stripslashes(@$_POST["z_Rec_NumRegIdTrib"][0]) : @$_POST["z_Rec_NumRegIdTrib"][0]; 
	if ($x_Rec_NumRegIdTrib <> "") {
		$sSrchFld = $x_Rec_NumRegIdTrib;
		$sSrchWrk = "x_Rec_NumRegIdTrib=" . urlencode($sSrchFld);
		$sSrchWrk .= "&z_Rec_NumRegIdTrib=" . urlencode($z_Rec_NumRegIdTrib);
	} else {
		$sSrchWrk = "";
	}
	if ($sSrchWrk <> "") {
		if ($sSrchStr == "") {
			$sSrchStr = $sSrchWrk;
		} else {
			$sSrchStr .= "&" . $sSrchWrk;
		}
	}

	// Field Rec_RegimenFiscalReceptor
	$x_Rec_RegimenFiscalReceptor = @$_POST["x_Rec_RegimenFiscalReceptor"];
	$z_Rec_RegimenFiscalReceptor = ((function_exists("get_magic_quotes_gpc") && (function_exists("get_magic_quotes_gpc") && get_magic_quotes_gpc()))) ? stripslashes(@$_POST["z_Rec_RegimenFiscalReceptor"][0]) : @$_POST["z_Rec_RegimenFiscalReceptor"][0]; 
	if ($x_Rec_RegimenFiscalReceptor <> "") {
		$sSrchFld = $x_Rec_RegimenFiscalReceptor;
		$sSrchWrk = "x_Rec_RegimenFiscalReceptor=" . urlencode($sSrchFld);
		$sSrchWrk .= "&z_Rec_RegimenFiscalReceptor=" . urlencode($z_Rec_RegimenFiscalReceptor);
	} else {
		$sSrchWrk = "";
	}
	if ($sSrchWrk <> "") {
		if ($sSrchStr == "") {
			$sSrchStr = $sSrchWrk;
		} else {
			$sSrchStr .= "&" . $sSrchWrk;
		}
	}

	// Field Rec_Curp
	$s_Rec_Curp = @$_POST["s_Rec_Curp"];
	$z_Rec_Curp = ((function_exists("get_magic_quotes_gpc") && (function_exists("get_magic_quotes_gpc") && get_magic_quotes_gpc()))) ? stripslashes(@$_POST["z_Rec_Curp"][0]) : @$_POST["z_Rec_Curp"][0]; 
	if ($s_Rec_Curp <> "") {
		$sSrchFld = $s_Rec_Curp;
		$sSrchWrk = "s_Rec_Curp=" . urlencode($sSrchFld);
		$sSrchWrk .= "&z_Rec_Curp=" . urlencode($z_Rec_Curp);
	} else {
		$sSrchWrk = "";
	}
	if ($sSrchWrk <> "") {
		if ($sSrchStr == "") {
			$sSrchStr = $sSrchWrk;
		} else {
			$sSrchStr .= "&" . $sSrchWrk;
		}
	}

	// Field Rec_NumSeguridadSocial
	$x_Rec_NumSeguridadSocial = @$_POST["x_Rec_NumSeguridadSocial"];
	$z_Rec_NumSeguridadSocial = ((function_exists("get_magic_quotes_gpc") && (function_exists("get_magic_quotes_gpc") && get_magic_quotes_gpc()))) ? stripslashes(@$_POST["z_Rec_NumSeguridadSocial"][0]) : @$_POST["z_Rec_NumSeguridadSocial"][0]; 
	if ($x_Rec_NumSeguridadSocial <> "") {
		$sSrchFld = $x_Rec_NumSeguridadSocial;
		$sSrchWrk = "x_Rec_NumSeguridadSocial=" . urlencode($sSrchFld);
		$sSrchWrk .= "&z_Rec_NumSeguridadSocial=" . urlencode($z_Rec_NumSeguridadSocial);
	} else {
		$sSrchWrk = "";
	}
	if ($sSrchWrk <> "") {
		if ($sSrchStr == "") {
			$sSrchStr = $sSrchWrk;
		} else {
			$sSrchStr .= "&" . $sSrchWrk;
		}
	}

	// Field Rec_FechaInicioRelLaboral
	$x_Rec_FechaInicioRelLaboral = @$_POST["x_Rec_FechaInicioRelLaboral"];
	$z_Rec_FechaInicioRelLaboral = ((function_exists("get_magic_quotes_gpc") && (function_exists("get_magic_quotes_gpc") && get_magic_quotes_gpc()))) ? stripslashes(@$_POST["z_Rec_FechaInicioRelLaboral"][0]) : @$_POST["z_Rec_FechaInicioRelLaboral"][0]; 
	if ($x_Rec_FechaInicioRelLaboral <> "") {
		$sSrchFld = $x_Rec_FechaInicioRelLaboral;
		$sSrchWrk = "x_Rec_FechaInicioRelLaboral=" . urlencode($sSrchFld);
		$sSrchWrk .= "&z_Rec_FechaInicioRelLaboral=" . urlencode($z_Rec_FechaInicioRelLaboral);
	} else {
		$sSrchWrk = "";
	}
	if ($sSrchWrk <> "") {
		if ($sSrchStr == "") {
			$sSrchStr = $sSrchWrk;
		} else {
			$sSrchStr .= "&" . $sSrchWrk;
		}
	}

	// Field Rec_Antiguedad
	$x_Rec_Antiguedad = @$_POST["x_Rec_Antiguedad"];
	$z_Rec_Antiguedad = ((function_exists("get_magic_quotes_gpc") && (function_exists("get_magic_quotes_gpc") && get_magic_quotes_gpc()))) ? stripslashes(@$_POST["z_Rec_Antiguedad"][0]) : @$_POST["z_Rec_Antiguedad"][0]; 
	if ($x_Rec_Antiguedad <> "") {
		$sSrchFld = $x_Rec_Antiguedad;
		$sSrchWrk = "x_Rec_Antiguedad=" . urlencode($sSrchFld);
		$sSrchWrk .= "&z_Rec_Antiguedad=" . urlencode($z_Rec_Antiguedad);
	} else {
		$sSrchWrk = "";
	}
	if ($sSrchWrk <> "") {
		if ($sSrchStr == "") {
			$sSrchStr = $sSrchWrk;
		} else {
			$sSrchStr .= "&" . $sSrchWrk;
		}
	}

	// Field Rec_TipoContrato
	$x_Rec_TipoContrato = @$_POST["x_Rec_TipoContrato"];
	$z_Rec_TipoContrato = ((function_exists("get_magic_quotes_gpc") && (function_exists("get_magic_quotes_gpc") && get_magic_quotes_gpc()))) ? stripslashes(@$_POST["z_Rec_TipoContrato"][0]) : @$_POST["z_Rec_TipoContrato"][0]; 
	if ($x_Rec_TipoContrato <> "") {
		$sSrchFld = $x_Rec_TipoContrato;
		$sSrchWrk = "x_Rec_TipoContrato=" . urlencode($sSrchFld);
		$sSrchWrk .= "&z_Rec_TipoContrato=" . urlencode($z_Rec_TipoContrato);
	} else {
		$sSrchWrk = "";
	}
	if ($sSrchWrk <> "") {
		if ($sSrchStr == "") {
			$sSrchStr = $sSrchWrk;
		} else {
			$sSrchStr .= "&" . $sSrchWrk;
		}
	}

	// Field Rec_Sindicalizado
	$x_Rec_Sindicalizado = @$_POST["x_Rec_Sindicalizado"];
	$z_Rec_Sindicalizado = ((function_exists("get_magic_quotes_gpc") && (function_exists("get_magic_quotes_gpc") && get_magic_quotes_gpc()))) ? stripslashes(@$_POST["z_Rec_Sindicalizado"][0]) : @$_POST["z_Rec_Sindicalizado"][0]; 
	if ($x_Rec_Sindicalizado <> "") {
		$sSrchFld = $x_Rec_Sindicalizado;
		$sSrchWrk = "x_Rec_Sindicalizado=" . urlencode($sSrchFld);
		$sSrchWrk .= "&z_Rec_Sindicalizado=" . urlencode($z_Rec_Sindicalizado);
	} else {
		$sSrchWrk = "";
	}
	if ($sSrchWrk <> "") {
		if ($sSrchStr == "") {
			$sSrchStr = $sSrchWrk;
		} else {
			$sSrchStr .= "&" . $sSrchWrk;
		}
	}

	// Field Rec_TipoJornada
	$x_Rec_TipoJornada = @$_POST["x_Rec_TipoJornada"];
	$z_Rec_TipoJornada = ((function_exists("get_magic_quotes_gpc") && (function_exists("get_magic_quotes_gpc") && get_magic_quotes_gpc()))) ? stripslashes(@$_POST["z_Rec_TipoJornada"][0]) : @$_POST["z_Rec_TipoJornada"][0]; 
	if ($x_Rec_TipoJornada <> "") {
		$sSrchFld = $x_Rec_TipoJornada;
		$sSrchWrk = "x_Rec_TipoJornada=" . urlencode($sSrchFld);
		$sSrchWrk .= "&z_Rec_TipoJornada=" . urlencode($z_Rec_TipoJornada);
	} else {
		$sSrchWrk = "";
	}
	if ($sSrchWrk <> "") {
		if ($sSrchStr == "") {
			$sSrchStr = $sSrchWrk;
		} else {
			$sSrchStr .= "&" . $sSrchWrk;
		}
	}

	// Field Rec_TipoRegimen
	$x_Rec_TipoRegimen = @$_POST["x_Rec_TipoRegimen"];
	$z_Rec_TipoRegimen = ((function_exists("get_magic_quotes_gpc") && (function_exists("get_magic_quotes_gpc") && get_magic_quotes_gpc()))) ? stripslashes(@$_POST["z_Rec_TipoRegimen"][0]) : @$_POST["z_Rec_TipoRegimen"][0]; 
	if ($x_Rec_TipoRegimen <> "") {
		$sSrchFld = $x_Rec_TipoRegimen;
		$sSrchWrk = "x_Rec_TipoRegimen=" . urlencode($sSrchFld);
		$sSrchWrk .= "&z_Rec_TipoRegimen=" . urlencode($z_Rec_TipoRegimen);
	} else {
		$sSrchWrk = "";
	}
	if ($sSrchWrk <> "") {
		if ($sSrchStr == "") {
			$sSrchStr = $sSrchWrk;
		} else {
			$sSrchStr .= "&" . $sSrchWrk;
		}
	}

	// Field Rec_NumEmpleado
	$s_Rec_NumEmpleado = @$_POST["s_Rec_NumEmpleado"];
	$z_Rec_NumEmpleado = ((function_exists("get_magic_quotes_gpc") && (function_exists("get_magic_quotes_gpc") && get_magic_quotes_gpc()))) ? stripslashes(@$_POST["z_Rec_NumEmpleado"][0]) : @$_POST["z_Rec_NumEmpleado"][0]; 
	if ($s_Rec_NumEmpleado <> "") {
		$sSrchFld = $s_Rec_NumEmpleado;
		$sSrchWrk = "s_Rec_NumEmpleado=" . urlencode($sSrchFld);
		$sSrchWrk .= "&z_Rec_NumEmpleado=" . urlencode($z_Rec_NumEmpleado);
	} else {
		$sSrchWrk = "";
	}
	if ($sSrchWrk <> "") {
		if ($sSrchStr == "") {
			$sSrchStr = $sSrchWrk;
		} else {
			$sSrchStr .= "&" . $sSrchWrk;
		}
	}

	// Field Rec_Departamento
	$s_Rec_Departamento = @$_POST["s_Rec_Departamento"];
	$z_Rec_Departamento = ((function_exists("get_magic_quotes_gpc") && (function_exists("get_magic_quotes_gpc") && get_magic_quotes_gpc()))) ? stripslashes(@$_POST["z_Rec_Departamento"][0]) : @$_POST["z_Rec_Departamento"][0]; 
	if ($s_Rec_Departamento <> "") {
		$sSrchFld = $s_Rec_Departamento;
		$sSrchWrk = "s_Rec_Departamento=" . urlencode($sSrchFld);
		$sSrchWrk .= "&z_Rec_Departamento=" . urlencode($z_Rec_Departamento);
	} else {
		$sSrchWrk = "";
	}
	if ($sSrchWrk <> "") {
		if ($sSrchStr == "") {
			$sSrchStr = $sSrchWrk;
		} else {
			$sSrchStr .= "&" . $sSrchWrk;
		}
	}

	// Field Rec_Puesto
	$x_Rec_Puesto = @$_POST["x_Rec_Puesto"];
	$z_Rec_Puesto = ((function_exists("get_magic_quotes_gpc") && (function_exists("get_magic_quotes_gpc") && get_magic_quotes_gpc()))) ? stripslashes(@$_POST["z_Rec_Puesto"][0]) : @$_POST["z_Rec_Puesto"][0]; 
	if ($x_Rec_Puesto <> "") {
		$sSrchFld = $x_Rec_Puesto;
		$sSrchWrk = "x_Rec_Puesto=" . urlencode($sSrchFld);
		$sSrchWrk .= "&z_Rec_Puesto=" . urlencode($z_Rec_Puesto);
	} else {
		$sSrchWrk = "";
	}
	if ($sSrchWrk <> "") {
		if ($sSrchStr == "") {
			$sSrchStr = $sSrchWrk;
		} else {
			$sSrchStr .= "&" . $sSrchWrk;
		}
	}

	// Field Rec_RiesgoPuesto
	$x_Rec_RiesgoPuesto = @$_POST["x_Rec_RiesgoPuesto"];
	$z_Rec_RiesgoPuesto = ((function_exists("get_magic_quotes_gpc") && (function_exists("get_magic_quotes_gpc") && get_magic_quotes_gpc()))) ? stripslashes(@$_POST["z_Rec_RiesgoPuesto"][0]) : @$_POST["z_Rec_RiesgoPuesto"][0]; 
	if ($x_Rec_RiesgoPuesto <> "") {
		$sSrchFld = $x_Rec_RiesgoPuesto;
		$sSrchWrk = "x_Rec_RiesgoPuesto=" . urlencode($sSrchFld);
		$sSrchWrk .= "&z_Rec_RiesgoPuesto=" . urlencode($z_Rec_RiesgoPuesto);
	} else {
		$sSrchWrk = "";
	}
	if ($sSrchWrk <> "") {
		if ($sSrchStr == "") {
			$sSrchStr = $sSrchWrk;
		} else {
			$sSrchStr .= "&" . $sSrchWrk;
		}
	}

	// Field Rec_PeriodicidadPago
	$x_Rec_PeriodicidadPago = @$_POST["x_Rec_PeriodicidadPago"];
	$z_Rec_PeriodicidadPago = ((function_exists("get_magic_quotes_gpc") && (function_exists("get_magic_quotes_gpc") && get_magic_quotes_gpc()))) ? stripslashes(@$_POST["z_Rec_PeriodicidadPago"][0]) : @$_POST["z_Rec_PeriodicidadPago"][0]; 
	if ($x_Rec_PeriodicidadPago <> "") {
		$sSrchFld = $x_Rec_PeriodicidadPago;
		$sSrchWrk = "x_Rec_PeriodicidadPago=" . urlencode($sSrchFld);
		$sSrchWrk .= "&z_Rec_PeriodicidadPago=" . urlencode($z_Rec_PeriodicidadPago);
	} else {
		$sSrchWrk = "";
	}
	if ($sSrchWrk <> "") {
		if ($sSrchStr == "") {
			$sSrchStr = $sSrchWrk;
		} else {
			$sSrchStr .= "&" . $sSrchWrk;
		}
	}

	// Field Rec_Banco
	$x_Rec_Banco = @$_POST["x_Rec_Banco"];
	$z_Rec_Banco = ((function_exists("get_magic_quotes_gpc") && (function_exists("get_magic_quotes_gpc") && get_magic_quotes_gpc()))) ? stripslashes(@$_POST["z_Rec_Banco"][0]) : @$_POST["z_Rec_Banco"][0]; 
	if ($x_Rec_Banco <> "") {
		$sSrchFld = $x_Rec_Banco;
		$sSrchWrk = "x_Rec_Banco=" . urlencode($sSrchFld);
		$sSrchWrk .= "&z_Rec_Banco=" . urlencode($z_Rec_Banco);
	} else {
		$sSrchWrk = "";
	}
	if ($sSrchWrk <> "") {
		if ($sSrchStr == "") {
			$sSrchStr = $sSrchWrk;
		} else {
			$sSrchStr .= "&" . $sSrchWrk;
		}
	}

	// Field Rec_CuentaBancaria
	$x_Rec_CuentaBancaria = @$_POST["x_Rec_CuentaBancaria"];
	$z_Rec_CuentaBancaria = ((function_exists("get_magic_quotes_gpc") && (function_exists("get_magic_quotes_gpc") && get_magic_quotes_gpc()))) ? stripslashes(@$_POST["z_Rec_CuentaBancaria"][0]) : @$_POST["z_Rec_CuentaBancaria"][0]; 
	if ($x_Rec_CuentaBancaria <> "") {
		$sSrchFld = $x_Rec_CuentaBancaria;
		$sSrchWrk = "x_Rec_CuentaBancaria=" . urlencode($sSrchFld);
		$sSrchWrk .= "&z_Rec_CuentaBancaria=" . urlencode($z_Rec_CuentaBancaria);
	} else {
		$sSrchWrk = "";
	}
	if ($sSrchWrk <> "") {
		if ($sSrchStr == "") {
			$sSrchStr = $sSrchWrk;
		} else {
			$sSrchStr .= "&" . $sSrchWrk;
		}
	}

	// Field Rec_SalarioBaseCotApor
	$x_Rec_SalarioBaseCotApor = @$_POST["x_Rec_SalarioBaseCotApor"];
	$z_Rec_SalarioBaseCotApor = ((function_exists("get_magic_quotes_gpc") && (function_exists("get_magic_quotes_gpc") && get_magic_quotes_gpc()))) ? stripslashes(@$_POST["z_Rec_SalarioBaseCotApor"][0]) : @$_POST["z_Rec_SalarioBaseCotApor"][0]; 
	if ($x_Rec_SalarioBaseCotApor <> "") {
		$sSrchFld = $x_Rec_SalarioBaseCotApor;
		$sSrchWrk = "x_Rec_SalarioBaseCotApor=" . urlencode($sSrchFld);
		$sSrchWrk .= "&z_Rec_SalarioBaseCotApor=" . urlencode($z_Rec_SalarioBaseCotApor);
	} else {
		$sSrchWrk = "";
	}
	if ($sSrchWrk <> "") {
		if ($sSrchStr == "") {
			$sSrchStr = $sSrchWrk;
		} else {
			$sSrchStr .= "&" . $sSrchWrk;
		}
	}

	// Field Rec_SalarioDiarioIntegrado
	$x_Rec_SalarioDiarioIntegrado = @$_POST["x_Rec_SalarioDiarioIntegrado"];
	$z_Rec_SalarioDiarioIntegrado = ((function_exists("get_magic_quotes_gpc") && (function_exists("get_magic_quotes_gpc") && get_magic_quotes_gpc()))) ? stripslashes(@$_POST["z_Rec_SalarioDiarioIntegrado"][0]) : @$_POST["z_Rec_SalarioDiarioIntegrado"][0]; 
	if ($x_Rec_SalarioDiarioIntegrado <> "") {
		$sSrchFld = $x_Rec_SalarioDiarioIntegrado;
		$sSrchWrk = "x_Rec_SalarioDiarioIntegrado=" . urlencode($sSrchFld);
		$sSrchWrk .= "&z_Rec_SalarioDiarioIntegrado=" . urlencode($z_Rec_SalarioDiarioIntegrado);
	} else {
		$sSrchWrk = "";
	}
	if ($sSrchWrk <> "") {
		if ($sSrchStr == "") {
			$sSrchStr = $sSrchWrk;
		} else {
			$sSrchStr .= "&" . $sSrchWrk;
		}
	}

	// Field Rec_ClaveEntFed
	$x_Rec_ClaveEntFed = @$_POST["x_Rec_ClaveEntFed"];
	$z_Rec_ClaveEntFed = ((function_exists("get_magic_quotes_gpc") && (function_exists("get_magic_quotes_gpc") && get_magic_quotes_gpc()))) ? stripslashes(@$_POST["z_Rec_ClaveEntFed"][0]) : @$_POST["z_Rec_ClaveEntFed"][0]; 
	if ($x_Rec_ClaveEntFed <> "") {
		$sSrchFld = $x_Rec_ClaveEntFed;
		$sSrchWrk = "x_Rec_ClaveEntFed=" . urlencode($sSrchFld);
		$sSrchWrk .= "&z_Rec_ClaveEntFed=" . urlencode($z_Rec_ClaveEntFed);
	} else {
		$sSrchWrk = "";
	}
	if ($sSrchWrk <> "") {
		if ($sSrchStr == "") {
			$sSrchStr = $sSrchWrk;
		} else {
			$sSrchStr .= "&" . $sSrchWrk;
		}
	}

	// Field Rec_Status
	$x_Rec_Status = @$_POST["x_Rec_Status"];
	$z_Rec_Status = ((function_exists("get_magic_quotes_gpc") && (function_exists("get_magic_quotes_gpc") && get_magic_quotes_gpc()))) ? stripslashes(@$_POST["z_Rec_Status"][0]) : @$_POST["z_Rec_Status"][0]; 
	if ($x_Rec_Status <> "") {
		$sSrchFld = $x_Rec_Status;
		$sSrchWrk = "x_Rec_Status=" . urlencode($sSrchFld);
		$sSrchWrk .= "&z_Rec_Status=" . urlencode($z_Rec_Status);
	} else {
		$sSrchWrk = "";
	}
	if ($sSrchWrk <> "") {
		if ($sSrchStr == "") {
			$sSrchStr = $sSrchWrk;
		} else {
			$sSrchStr .= "&" . $sSrchWrk;
		}
	}

	// Field Mun_ID
	$s_Mun_ID = @$_POST["s_Mun_ID"];
	$z_Mun_ID = ((function_exists("get_magic_quotes_gpc") && (function_exists("get_magic_quotes_gpc") && get_magic_quotes_gpc()))) ? stripslashes(@$_POST["z_Mun_ID"][0]) : @$_POST["z_Mun_ID"][0]; 
	if ($s_Mun_ID <> "") {
		$sSrchFld = $s_Mun_ID;
		$sSrchWrk = "s_Mun_ID=" . urlencode($sSrchFld);
		$sSrchWrk .= "&z_Mun_ID=" . urlencode($z_Mun_ID);
	} else {
		$sSrchWrk = "";
	}
	if ($sSrchWrk <> "") {
		if ($sSrchStr == "") {
			$sSrchStr = $sSrchWrk;
		} else {
			$sSrchStr .= "&" . $sSrchWrk;
		}
	}

	// Field Rec_CreationDate
	$x_Rec_CreationDate = @$_POST["x_Rec_CreationDate"];
	$z_Rec_CreationDate = ((function_exists("get_magic_quotes_gpc") && (function_exists("get_magic_quotes_gpc") && get_magic_quotes_gpc()))) ? stripslashes(@$_POST["z_Rec_CreationDate"][0]) : @$_POST["z_Rec_CreationDate"][0]; 
	if ($x_Rec_CreationDate <> "") {
		$sSrchFld = $x_Rec_CreationDate;
		$sSrchFld = ConvertDateToMysqlFormat($sSrchFld);
		$sSrchWrk = "x_Rec_CreationDate=" . urlencode($sSrchFld);
		$sSrchWrk .= "&z_Rec_CreationDate=" . urlencode($z_Rec_CreationDate);
	} else {
		$sSrchWrk = "";
	}
	if ($sSrchWrk <> "") {
		if ($sSrchStr == "") {
			$sSrchStr = $sSrchWrk;
		} else {
			$sSrchStr .= "&" . $sSrchWrk;
		}
	}
	if ($sSrchStr <> "") {
		ob_end_clean();
		header("Location: receptores_listado.php" . "?" . $sSrchStr);
		exit();
	}else{
		ob_end_clean();
		header("Location: receptores_listado.php" . "?cmd=reset");
		exit();
	}
}

// Open connection to the database
$conn = phpmkr_db_connect(HOST, USER, PASS, DB, PORT);
?>
<?php
phpmkr_db_close($conn);
?>
