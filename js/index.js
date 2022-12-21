$(function(){
   
	$("#btn_filtrar").off("click");
	$("#btn_filtrar").on('click', function(event){
		event.preventDefault();
		var Data = $("#vit_comprobantessearch").serialize();
		$("#listado_informe").html('<center>Favor de esperar un momento...<br /><br /><div class="spinner-border text-primary" role="status"><span class="sr-only">Loading...</span></div></center>');
		$.get("crud/listado_informe.php", {Data:Data}, function(result){
			$('#listado_informe').html(result);
			$.getScript('js/index.js');
		});
	});
	
	$("#btn_quitar").off("click");
	$("#btn_quitar").on('click', function(event){

		$("#s_Mun_ID").prop("selectedIndex", 0).change();
		$("#s_Emi_RFC").prop("selectedIndex", 0).change();
		$("#s_Ejercicio").prop("selectedIndex", 0).change();
		$("#s_Periodo").prop("selectedIndex", 0).change();
		var Data = $("#vit_comprobantessearch").serialize();
		
		$("#listado_informe").html('<center>Favor de esperar un momento...<br /><br /><div class="spinner-border text-primary" role="status"><span class="sr-only">Loading...</span></div></center>');
		$.get("crud/listado_informe.php", {Data:Data}, function(result){
			$('#listado_informe').html(result);
			$.getScript('js/index.js');
		});		
		
	});
	
});