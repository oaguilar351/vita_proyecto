$(function(){
   
	$(".page-item").off("click");
	$(".page-item").on('click', function(event){
		event.preventDefault();
		var Page = $(this).attr('href');
		//var Data = $("#Form_Setup").serialize();	
		$("#listado_comprobantes").html('<center><i class="mdi mdi-spin mdi-loading" style="color:#117a65; font-size:48px;"></i><br>Espere un momento por favor...<br >Estamos consultando informacion.</center>');
		//$.get("../crud/materiaprima_detalle_page.php", {Page:Page, ComedorID:ComedorID, Zona:Zona, FechaIni:FechaIni, FechaFin:FechaFin, NumProg:NumProg, CategoryID:CategoryID}, function(result){
		$.get("crud/listado_comprobantes.php", {Page:Page}, function(result){
			$('#listado_comprobantes').html(result);
			$.getScript('js/comprobantes.js');
		});
	});
  
});