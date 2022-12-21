<?php session_start(); ?>
<?php ob_start(); ?>
<?php

// Initialize common variables
$x_Cfdi_ID = Null; 
$ox_Cfdi_ID = Null;
$x_Cfdi_Version = Null; 
$ox_Cfdi_Version = Null;
$x_Cfdi_Serie = Null; 
$ox_Cfdi_Serie = Null;
$x_Cfdi_Folio = Null; 
$ox_Cfdi_Folio = Null;
$x_Cfdi_Fecha = Null; 
$ox_Cfdi_Fecha = Null;
$x_Cfdi_Sello = Null; 
$ox_Cfdi_Sello = Null;
$x_c_FormaPago = Null; 
$ox_c_FormaPago = Null;
$x_Cfdi_Certificado = Null; 
$ox_Cfdi_Certificado = Null;
$x_Cfdi_NoCertificado = Null; 
$ox_Cfdi_NoCertificado = Null;
$x_Cfdi_CondicionesDePago = Null; 
$ox_Cfdi_CondicionesDePago = Null;
$x_Cfdi_Subtotal = Null; 
$ox_Cfdi_Subtotal = Null;
$x_Cfdi_Descuento = Null; 
$ox_Cfdi_Descuento = Null;
$x_c_Moneda = Null; 
$ox_c_Moneda = Null;
$x_Cfdi_TipoCambio = Null; 
$ox_Cfdi_TipoCambio = Null;
$x_Cfdi_Total = Null; 
$ox_Cfdi_Total = Null;
$x_c_TipoDeComprobante = Null; 
$ox_c_TipoDeComprobante = Null;
$x_c_Exportacion = Null; 
$ox_c_Exportacion = Null;
$x_c_MetodoPago = Null; 
$ox_c_MetodoPago = Null;
$x_c_CodigoPostal = Null; 
$ox_c_CodigoPostal = Null;
$x_Cfdi_Confirmacion = Null; 
$ox_Cfdi_Confirmacion = Null;
$x_Emi_RFC = Null; 
$ox_Emi_RFC = Null;
$x_Rec_RFC = Null; 
$ox_Rec_RFC = Null;
$x_Cfdi_Error = Null; 
$ox_Cfdi_Error = Null;
$x_Cfdi_UsoCFDI = Null; 
$ox_Cfdi_UsoCFDI = Null;
$x_Cfdi_UUID = Null; 
$ox_Cfdi_UUID = Null;
$x_Cfdi_Retcode = Null; 
$ox_Cfdi_Retcode = Null;
$x_Cfdi_Timestamp = Null; 
$ox_Cfdi_Timestamp = Null;
$x_Cfdi_Acuse = Null; 
$ox_Cfdi_Acuse = Null;
$x_Cfdi_Status = Null; 
$ox_Cfdi_Status = Null;
$x_Mun_ID = Null; 
$ox_Mun_ID = Null;
$x_Cfdi_CreationDate = Null; 
$ox_Cfdi_CreationDate = Null;
$x_Cfdi_Cotejado = Null; 
$ox_Cfdi_Cotejado = Null;
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

	// Field Cfdi_ID
	$x_Cfdi_ID = @$_POST["x_Cfdi_ID"];
	$z_Cfdi_ID = ((function_exists("get_magic_quotes_gpc") && (function_exists("get_magic_quotes_gpc") && get_magic_quotes_gpc()))) ? stripslashes(@$_POST["z_Cfdi_ID"][0]) : @$_POST["z_Cfdi_ID"][0]; 
	if ($x_Cfdi_ID <> "") {
		$sSrchFld = $x_Cfdi_ID;
		$sSrchWrk = "x_Cfdi_ID=" . urlencode($sSrchFld);
		$sSrchWrk .= "&z_Cfdi_ID=" . urlencode($z_Cfdi_ID);
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

	// Field Cfdi_Version
	$x_Cfdi_Version = @$_POST["x_Cfdi_Version"];
	$z_Cfdi_Version = ((function_exists("get_magic_quotes_gpc") && (function_exists("get_magic_quotes_gpc") && get_magic_quotes_gpc()))) ? stripslashes(@$_POST["z_Cfdi_Version"][0]) : @$_POST["z_Cfdi_Version"][0]; 
	if ($x_Cfdi_Version <> "") {
		$sSrchFld = $x_Cfdi_Version;
		$sSrchWrk = "x_Cfdi_Version=" . urlencode($sSrchFld);
		$sSrchWrk .= "&z_Cfdi_Version=" . urlencode($z_Cfdi_Version);
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

	// Field Cfdi_Serie
	$s_Cfdi_Serie = @$_POST["s_Cfdi_Serie"];
	$z_Cfdi_Serie = ((function_exists("get_magic_quotes_gpc") && (function_exists("get_magic_quotes_gpc") && get_magic_quotes_gpc()))) ? stripslashes(@$_POST["z_Cfdi_Serie"][0]) : @$_POST["z_Cfdi_Serie"][0]; 
	if ($s_Cfdi_Serie <> "") {
		$sSrchFld = $s_Cfdi_Serie;
		$sSrchWrk = "s_Cfdi_Serie=" . urlencode($sSrchFld);
		$sSrchWrk .= "&z_Cfdi_Serie=" . urlencode($z_Cfdi_Serie);
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

	// Field Cfdi_Folio
	$s_Cfdi_Folio = @$_POST["s_Cfdi_Folio"];
	$z_Cfdi_Folio = ((function_exists("get_magic_quotes_gpc") && (function_exists("get_magic_quotes_gpc") && get_magic_quotes_gpc()))) ? stripslashes(@$_POST["z_Cfdi_Folio"][0]) : @$_POST["z_Cfdi_Folio"][0]; 
	if ($s_Cfdi_Folio <> "") {
		$sSrchFld = $s_Cfdi_Folio;
		$sSrchWrk = "s_Cfdi_Folio=" . urlencode($sSrchFld);
		$sSrchWrk .= "&z_Cfdi_Folio=" . urlencode($z_Cfdi_Folio);
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

	// Field Cfdi_Fecha
	$s_Cfdi_Fecha = @$_POST["s_Cfdi_Fecha"];
	$z_Cfdi_Fecha = ((function_exists("get_magic_quotes_gpc") && (function_exists("get_magic_quotes_gpc") && get_magic_quotes_gpc()))) ? stripslashes(@$_POST["z_Cfdi_Fecha"][0]) : @$_POST["z_Cfdi_Fecha"][0]; 
	if ($s_Cfdi_Fecha <> "") {
		$sSrchFld = $s_Cfdi_Fecha;
		$sSrchFld = ConvertDateToMysqlFormat($sSrchFld);
		$sSrchWrk = "s_Cfdi_Fecha=" . urlencode($sSrchFld);
		$sSrchWrk .= "&z_Cfdi_Fecha=" . urlencode($z_Cfdi_Fecha);
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

	// Field Cfdi_Sello
	$x_Cfdi_Sello = @$_POST["x_Cfdi_Sello"];
	$z_Cfdi_Sello = ((function_exists("get_magic_quotes_gpc") && (function_exists("get_magic_quotes_gpc") && get_magic_quotes_gpc()))) ? stripslashes(@$_POST["z_Cfdi_Sello"][0]) : @$_POST["z_Cfdi_Sello"][0]; 
	if ($x_Cfdi_Sello <> "") {
		$sSrchFld = $x_Cfdi_Sello;
		$sSrchWrk = "x_Cfdi_Sello=" . urlencode($sSrchFld);
		$sSrchWrk .= "&z_Cfdi_Sello=" . urlencode($z_Cfdi_Sello);
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

	// Field c_FormaPago
	$x_c_FormaPago = @$_POST["x_c_FormaPago"];
	$z_c_FormaPago = ((function_exists("get_magic_quotes_gpc") && (function_exists("get_magic_quotes_gpc") && get_magic_quotes_gpc()))) ? stripslashes(@$_POST["z_c_FormaPago"][0]) : @$_POST["z_c_FormaPago"][0]; 
	if ($x_c_FormaPago <> "") {
		$sSrchFld = $x_c_FormaPago;
		$sSrchWrk = "x_c_FormaPago=" . urlencode($sSrchFld);
		$sSrchWrk .= "&z_c_FormaPago=" . urlencode($z_c_FormaPago);
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

	// Field Cfdi_Certificado
	$x_Cfdi_Certificado = @$_POST["x_Cfdi_Certificado"];
	$z_Cfdi_Certificado = ((function_exists("get_magic_quotes_gpc") && (function_exists("get_magic_quotes_gpc") && get_magic_quotes_gpc()))) ? stripslashes(@$_POST["z_Cfdi_Certificado"][0]) : @$_POST["z_Cfdi_Certificado"][0]; 
	if ($x_Cfdi_Certificado <> "") {
		$sSrchFld = $x_Cfdi_Certificado;
		$sSrchWrk = "x_Cfdi_Certificado=" . urlencode($sSrchFld);
		$sSrchWrk .= "&z_Cfdi_Certificado=" . urlencode($z_Cfdi_Certificado);
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

	// Field Cfdi_NoCertificado
	$x_Cfdi_NoCertificado = @$_POST["x_Cfdi_NoCertificado"];
	$z_Cfdi_NoCertificado = ((function_exists("get_magic_quotes_gpc") && (function_exists("get_magic_quotes_gpc") && get_magic_quotes_gpc()))) ? stripslashes(@$_POST["z_Cfdi_NoCertificado"][0]) : @$_POST["z_Cfdi_NoCertificado"][0]; 
	if ($x_Cfdi_NoCertificado <> "") {
		$sSrchFld = $x_Cfdi_NoCertificado;
		$sSrchWrk = "x_Cfdi_NoCertificado=" . urlencode($sSrchFld);
		$sSrchWrk .= "&z_Cfdi_NoCertificado=" . urlencode($z_Cfdi_NoCertificado);
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

	// Field Cfdi_CondicionesDePago
	$x_Cfdi_CondicionesDePago = @$_POST["x_Cfdi_CondicionesDePago"];
	$z_Cfdi_CondicionesDePago = ((function_exists("get_magic_quotes_gpc") && (function_exists("get_magic_quotes_gpc") && get_magic_quotes_gpc()))) ? stripslashes(@$_POST["z_Cfdi_CondicionesDePago"][0]) : @$_POST["z_Cfdi_CondicionesDePago"][0]; 
	if ($x_Cfdi_CondicionesDePago <> "") {
		$sSrchFld = $x_Cfdi_CondicionesDePago;
		$sSrchWrk = "x_Cfdi_CondicionesDePago=" . urlencode($sSrchFld);
		$sSrchWrk .= "&z_Cfdi_CondicionesDePago=" . urlencode($z_Cfdi_CondicionesDePago);
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

	// Field Cfdi_Subtotal
	$x_Cfdi_Subtotal = @$_POST["x_Cfdi_Subtotal"];
	$z_Cfdi_Subtotal = ((function_exists("get_magic_quotes_gpc") && (function_exists("get_magic_quotes_gpc") && get_magic_quotes_gpc()))) ? stripslashes(@$_POST["z_Cfdi_Subtotal"][0]) : @$_POST["z_Cfdi_Subtotal"][0]; 
	if ($x_Cfdi_Subtotal <> "") {
		$sSrchFld = $x_Cfdi_Subtotal;
		$sSrchWrk = "x_Cfdi_Subtotal=" . urlencode($sSrchFld);
		$sSrchWrk .= "&z_Cfdi_Subtotal=" . urlencode($z_Cfdi_Subtotal);
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

	// Field Cfdi_Descuento
	$x_Cfdi_Descuento = @$_POST["x_Cfdi_Descuento"];
	$z_Cfdi_Descuento = ((function_exists("get_magic_quotes_gpc") && (function_exists("get_magic_quotes_gpc") && get_magic_quotes_gpc()))) ? stripslashes(@$_POST["z_Cfdi_Descuento"][0]) : @$_POST["z_Cfdi_Descuento"][0]; 
	if ($x_Cfdi_Descuento <> "") {
		$sSrchFld = $x_Cfdi_Descuento;
		$sSrchWrk = "x_Cfdi_Descuento=" . urlencode($sSrchFld);
		$sSrchWrk .= "&z_Cfdi_Descuento=" . urlencode($z_Cfdi_Descuento);
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

	// Field c_Moneda
	$x_c_Moneda = @$_POST["x_c_Moneda"];
	$z_c_Moneda = ((function_exists("get_magic_quotes_gpc") && (function_exists("get_magic_quotes_gpc") && get_magic_quotes_gpc()))) ? stripslashes(@$_POST["z_c_Moneda"][0]) : @$_POST["z_c_Moneda"][0]; 
	if ($x_c_Moneda <> "") {
		$sSrchFld = $x_c_Moneda;
		$sSrchWrk = "x_c_Moneda=" . urlencode($sSrchFld);
		$sSrchWrk .= "&z_c_Moneda=" . urlencode($z_c_Moneda);
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

	// Field Cfdi_TipoCambio
	$x_Cfdi_TipoCambio = @$_POST["x_Cfdi_TipoCambio"];
	$z_Cfdi_TipoCambio = ((function_exists("get_magic_quotes_gpc") && (function_exists("get_magic_quotes_gpc") && get_magic_quotes_gpc()))) ? stripslashes(@$_POST["z_Cfdi_TipoCambio"][0]) : @$_POST["z_Cfdi_TipoCambio"][0]; 
	if ($x_Cfdi_TipoCambio <> "") {
		$sSrchFld = $x_Cfdi_TipoCambio;
		$sSrchWrk = "x_Cfdi_TipoCambio=" . urlencode($sSrchFld);
		$sSrchWrk .= "&z_Cfdi_TipoCambio=" . urlencode($z_Cfdi_TipoCambio);
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

	// Field Cfdi_Total
	$x_Cfdi_Total = @$_POST["x_Cfdi_Total"];
	$z_Cfdi_Total = ((function_exists("get_magic_quotes_gpc") && (function_exists("get_magic_quotes_gpc") && get_magic_quotes_gpc()))) ? stripslashes(@$_POST["z_Cfdi_Total"][0]) : @$_POST["z_Cfdi_Total"][0]; 
	if ($x_Cfdi_Total <> "") {
		$sSrchFld = $x_Cfdi_Total;
		$sSrchWrk = "x_Cfdi_Total=" . urlencode($sSrchFld);
		$sSrchWrk .= "&z_Cfdi_Total=" . urlencode($z_Cfdi_Total);
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

	// Field c_TipoDeComprobante
	$x_c_TipoDeComprobante = @$_POST["x_c_TipoDeComprobante"];
	$z_c_TipoDeComprobante = ((function_exists("get_magic_quotes_gpc") && (function_exists("get_magic_quotes_gpc") && get_magic_quotes_gpc()))) ? stripslashes(@$_POST["z_c_TipoDeComprobante"][0]) : @$_POST["z_c_TipoDeComprobante"][0]; 
	if ($x_c_TipoDeComprobante <> "") {
		$sSrchFld = $x_c_TipoDeComprobante;
		$sSrchWrk = "x_c_TipoDeComprobante=" . urlencode($sSrchFld);
		$sSrchWrk .= "&z_c_TipoDeComprobante=" . urlencode($z_c_TipoDeComprobante);
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

	// Field c_Exportacion
	$x_c_Exportacion = @$_POST["x_c_Exportacion"];
	$z_c_Exportacion = ((function_exists("get_magic_quotes_gpc") && (function_exists("get_magic_quotes_gpc") && get_magic_quotes_gpc()))) ? stripslashes(@$_POST["z_c_Exportacion"][0]) : @$_POST["z_c_Exportacion"][0]; 
	if ($x_c_Exportacion <> "") {
		$sSrchFld = $x_c_Exportacion;
		$sSrchWrk = "x_c_Exportacion=" . urlencode($sSrchFld);
		$sSrchWrk .= "&z_c_Exportacion=" . urlencode($z_c_Exportacion);
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

	// Field c_MetodoPago
	$x_c_MetodoPago = @$_POST["x_c_MetodoPago"];
	$z_c_MetodoPago = ((function_exists("get_magic_quotes_gpc") && (function_exists("get_magic_quotes_gpc") && get_magic_quotes_gpc()))) ? stripslashes(@$_POST["z_c_MetodoPago"][0]) : @$_POST["z_c_MetodoPago"][0]; 
	if ($x_c_MetodoPago <> "") {
		$sSrchFld = $x_c_MetodoPago;
		$sSrchWrk = "x_c_MetodoPago=" . urlencode($sSrchFld);
		$sSrchWrk .= "&z_c_MetodoPago=" . urlencode($z_c_MetodoPago);
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

	// Field c_CodigoPostal
	$x_c_CodigoPostal = @$_POST["x_c_CodigoPostal"];
	$z_c_CodigoPostal = ((function_exists("get_magic_quotes_gpc") && (function_exists("get_magic_quotes_gpc") && get_magic_quotes_gpc()))) ? stripslashes(@$_POST["z_c_CodigoPostal"][0]) : @$_POST["z_c_CodigoPostal"][0]; 
	if ($x_c_CodigoPostal <> "") {
		$sSrchFld = $x_c_CodigoPostal;
		$sSrchWrk = "x_c_CodigoPostal=" . urlencode($sSrchFld);
		$sSrchWrk .= "&z_c_CodigoPostal=" . urlencode($z_c_CodigoPostal);
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

	// Field Cfdi_Confirmacion
	$x_Cfdi_Confirmacion = @$_POST["x_Cfdi_Confirmacion"];
	$z_Cfdi_Confirmacion = ((function_exists("get_magic_quotes_gpc") && (function_exists("get_magic_quotes_gpc") && get_magic_quotes_gpc()))) ? stripslashes(@$_POST["z_Cfdi_Confirmacion"][0]) : @$_POST["z_Cfdi_Confirmacion"][0]; 
	if ($x_Cfdi_Confirmacion <> "") {
		$sSrchFld = $x_Cfdi_Confirmacion;
		$sSrchWrk = "x_Cfdi_Confirmacion=" . urlencode($sSrchFld);
		$sSrchWrk .= "&z_Cfdi_Confirmacion=" . urlencode($z_Cfdi_Confirmacion);
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

	// Field Emi_RFC
	$s_Emi_RFC = @$_POST["s_Emi_RFC"];
	$z_Emi_RFC = ((function_exists("get_magic_quotes_gpc") && (function_exists("get_magic_quotes_gpc") && get_magic_quotes_gpc()))) ? stripslashes(@$_POST["z_Emi_RFC"][0]) : @$_POST["z_Emi_RFC"][0]; 
	if ($s_Emi_RFC <> "") {
		$sSrchFld = $s_Emi_RFC;
		$sSrchWrk = "s_Emi_RFC=" . urlencode($sSrchFld);
		$sSrchWrk .= "&z_Emi_RFC=" . urlencode($z_Emi_RFC);
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

	// Field Cfdi_Error
	$x_Cfdi_Error = @$_POST["x_Cfdi_Error"];
	$z_Cfdi_Error = ((function_exists("get_magic_quotes_gpc") && (function_exists("get_magic_quotes_gpc") && get_magic_quotes_gpc()))) ? stripslashes(@$_POST["z_Cfdi_Error"][0]) : @$_POST["z_Cfdi_Error"][0]; 
	if ($x_Cfdi_Error <> "") {
		$sSrchFld = $x_Cfdi_Error;
		$sSrchWrk = "x_Cfdi_Error=" . urlencode($sSrchFld);
		$sSrchWrk .= "&z_Cfdi_Error=" . urlencode($z_Cfdi_Error);
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

	// Field Cfdi_UsoCFDI
	$x_Cfdi_UsoCFDI = @$_POST["x_Cfdi_UsoCFDI"];
	$z_Cfdi_UsoCFDI = ((function_exists("get_magic_quotes_gpc") && (function_exists("get_magic_quotes_gpc") && get_magic_quotes_gpc()))) ? stripslashes(@$_POST["z_Cfdi_UsoCFDI"][0]) : @$_POST["z_Cfdi_UsoCFDI"][0]; 
	if ($x_Cfdi_UsoCFDI <> "") {
		$sSrchFld = $x_Cfdi_UsoCFDI;
		$sSrchWrk = "x_Cfdi_UsoCFDI=" . urlencode($sSrchFld);
		$sSrchWrk .= "&z_Cfdi_UsoCFDI=" . urlencode($z_Cfdi_UsoCFDI);
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

	// Field Cfdi_UUID
	$s_Cfdi_UUID = @$_POST["s_Cfdi_UUID"];
	$z_Cfdi_UUID = ((function_exists("get_magic_quotes_gpc") && (function_exists("get_magic_quotes_gpc") && get_magic_quotes_gpc()))) ? stripslashes(@$_POST["z_Cfdi_UUID"][0]) : @$_POST["z_Cfdi_UUID"][0]; 
	if ($s_Cfdi_UUID <> "") {
		$sSrchFld = $s_Cfdi_UUID;
		$sSrchWrk = "s_Cfdi_UUID=" . urlencode($sSrchFld);
		$sSrchWrk .= "&z_Cfdi_UUID=" . urlencode($z_Cfdi_UUID);
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

	// Field Cfdi_Retcode
	$x_Cfdi_Retcode = @$_POST["x_Cfdi_Retcode"];
	$z_Cfdi_Retcode = ((function_exists("get_magic_quotes_gpc") && (function_exists("get_magic_quotes_gpc") && get_magic_quotes_gpc()))) ? stripslashes(@$_POST["z_Cfdi_Retcode"][0]) : @$_POST["z_Cfdi_Retcode"][0]; 
	if ($x_Cfdi_Retcode <> "") {
		$sSrchFld = $x_Cfdi_Retcode;
		$sSrchWrk = "x_Cfdi_Retcode=" . urlencode($sSrchFld);
		$sSrchWrk .= "&z_Cfdi_Retcode=" . urlencode($z_Cfdi_Retcode);
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

	// Field Cfdi_Timestamp
	$x_Cfdi_Timestamp = @$_POST["x_Cfdi_Timestamp"];
	$z_Cfdi_Timestamp = ((function_exists("get_magic_quotes_gpc") && (function_exists("get_magic_quotes_gpc") && get_magic_quotes_gpc()))) ? stripslashes(@$_POST["z_Cfdi_Timestamp"][0]) : @$_POST["z_Cfdi_Timestamp"][0]; 
	if ($x_Cfdi_Timestamp <> "") {
		$sSrchFld = $x_Cfdi_Timestamp;
		$sSrchFld = ConvertDateToMysqlFormat($sSrchFld);
		$sSrchWrk = "x_Cfdi_Timestamp=" . urlencode($sSrchFld);
		$sSrchWrk .= "&z_Cfdi_Timestamp=" . urlencode($z_Cfdi_Timestamp);
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

	// Field Cfdi_Acuse
	$x_Cfdi_Acuse = @$_POST["x_Cfdi_Acuse"];
	$z_Cfdi_Acuse = ((function_exists("get_magic_quotes_gpc") && (function_exists("get_magic_quotes_gpc") && get_magic_quotes_gpc()))) ? stripslashes(@$_POST["z_Cfdi_Acuse"][0]) : @$_POST["z_Cfdi_Acuse"][0]; 
	if ($x_Cfdi_Acuse <> "") {
		$sSrchFld = $x_Cfdi_Acuse;
		$sSrchWrk = "x_Cfdi_Acuse=" . urlencode($sSrchFld);
		$sSrchWrk .= "&z_Cfdi_Acuse=" . urlencode($z_Cfdi_Acuse);
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

	// Field Cfdi_Status
	$x_Cfdi_Status = @$_POST["x_Cfdi_Status"];
	$z_Cfdi_Status = ((function_exists("get_magic_quotes_gpc") && (function_exists("get_magic_quotes_gpc") && get_magic_quotes_gpc()))) ? stripslashes(@$_POST["z_Cfdi_Status"][0]) : @$_POST["z_Cfdi_Status"][0]; 
	if ($x_Cfdi_Status <> "") {
		$sSrchFld = $x_Cfdi_Status;
		$sSrchWrk = "x_Cfdi_Status=" . urlencode($sSrchFld);
		$sSrchWrk .= "&z_Cfdi_Status=" . urlencode($z_Cfdi_Status);
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

	// Field Cfdi_CreationDate
	$x_Cfdi_CreationDate = @$_POST["x_Cfdi_CreationDate"];
	$z_Cfdi_CreationDate = ((function_exists("get_magic_quotes_gpc") && (function_exists("get_magic_quotes_gpc") && get_magic_quotes_gpc()))) ? stripslashes(@$_POST["z_Cfdi_CreationDate"][0]) : @$_POST["z_Cfdi_CreationDate"][0]; 
	if ($x_Cfdi_CreationDate <> "") {
		$sSrchFld = $x_Cfdi_CreationDate;
		$sSrchFld = ConvertDateToMysqlFormat($sSrchFld);
		$sSrchWrk = "x_Cfdi_CreationDate=" . urlencode($sSrchFld);
		$sSrchWrk .= "&z_Cfdi_CreationDate=" . urlencode($z_Cfdi_CreationDate);
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

	// Field Cfdi_Cotejado
	$x_Cfdi_Cotejado = @$_POST["x_Cfdi_Cotejado"];
	$z_Cfdi_Cotejado = ((function_exists("get_magic_quotes_gpc") && (function_exists("get_magic_quotes_gpc") && get_magic_quotes_gpc()))) ? stripslashes(@$_POST["z_Cfdi_Cotejado"][0]) : @$_POST["z_Cfdi_Cotejado"][0]; 
	if ($x_Cfdi_Cotejado <> "") {
		$sSrchFld = $x_Cfdi_Cotejado;
		$sSrchWrk = "x_Cfdi_Cotejado=" . urlencode($sSrchFld);
		$sSrchWrk .= "&z_Cfdi_Cotejado=" . urlencode($z_Cfdi_Cotejado);
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
		header("Location: comprobantes_listado.php" . "?" . $sSrchStr);
		exit();
	}else{
		ob_end_clean();
		header("Location: comprobantes_listado.php" . "?cmd=reset");
		exit();
	}
}

// Open connection to the database
$conn = phpmkr_db_connect(HOST, USER, PASS, DB, PORT);
?>
<?php
phpmkr_db_close($conn);
?>
