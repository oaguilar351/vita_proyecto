$(function(){
   
	$(".page-item").off("click");
	$(".page-item").on('click', function(event){
		event.preventDefault();
		var Page = $(this).attr('href');
		var Data = $("#vit_comprobantessearch").serialize();
		$("#listado_empleados").html('<center>Favor de esperar un momento...<br /><br /><div class="spinner-border text-primary" role="status"><span class="sr-only">Loading...</span></div></center>');
		$.get("crud/listado_empleados.php", {Data:Data, Page:Page}, function(result){
		//$.get("crud/listado_empleados.php", {Page:Page}, function(result){	
			$('#listado_empleados').html(result);
			$.getScript('js/empleados.js');
		});
	});	
	
	
	$("#btn_filtrar").off("click");
	$("#btn_filtrar").on('click', function(event){
		event.preventDefault();
		var Data = $("#vit_comprobantessearch").serialize();
		var Page = 1;
		$("#listado_empleados").html('<center>Favor de esperar un momento...<br /><br /><div class="spinner-border text-primary" role="status"><span class="sr-only">Loading...</span></div></center>');
		$.get("crud/listado_empleados.php", {Data:Data, Page:Page}, function(result){
			$('#listado_empleados').html(result);
			$.getScript('js/empleados.js');
		});
	});
	
	$("#btn_quitar").off("click");
	$("#btn_quitar").on('click', function(event){

		
		$("#s_FechaInicio").val('');
		$("#s_Mun_ID").prop("selectedIndex", 0).change();
		$("#s_RecNom").val('');
		$("#s_RecRFC").val('');		
		$("#s_Status").prop("selectedIndex", 0).change();
		var Data = $("#vit_comprobantessearch").serialize();
		var Page = 1;
		
		/*var Mun_ID = '';
		$("#select_municipios").html('<center><div class="spinner-border text-primary" role="status"><span class="sr-only">Loading...</span></div></center>');
		$.post("crud/select_municipios.php", {Mun_ID:Mun_ID}, function(resultMun_ID){
			$('#select_municipios').html(resultMun_ID);
			$.getScript('js/empleados.js');
		});*/
		
		$("#listado_empleados").html('<center>Favor de esperar un momento...<br /><br /><div class="spinner-border text-primary" role="status"><span class="sr-only">Loading...</span></div></center>');
		$.get("crud/listado_empleados.php", {Data:Data, Page:Page}, function(result){
			$('#listado_empleados').html(result);
			$.getScript('js/empleados.js');
		});		
		
	});
	
});