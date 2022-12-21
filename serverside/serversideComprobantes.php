<?php require ("serverside.php") ?>
<?php 
$table_data -> get('vista_comprobantes', 'Cfdi', array('Version','Serie','Folio','Fecha','Subtotal','Descuento','Moneda','Total','Emisor','Receptor','UUID','Estatus','Municipio'));
?>