<?php require ("serverside.php") ?>
<?php 
$table_data -> get('vista_comprobantes', 'Cfdi_ID', array('Cfdi_Version','Cfdi_Serie','Cfdi_Folio','Cfdi_Fecha','Cfdi_Subtotal','Cfdi_Descuento','c_Moneda','Cfdi_Total','Emi_Nombre','Rec_Nombre','Cfdi_UUID','Cfdi_Status','Mun_Descrip'));
?>