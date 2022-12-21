$(function(){
   
	$(".page-item").off("click");
	$(".page-item").on('click', function(event){
		event.preventDefault();
		var Page = $(this).attr('href');
		var Data = $("#vit_comprobantessearch").serialize();
		$("#listado_comprobantes").html('<center>Favor de esperar un momento...<br /><br /><div class="spinner-border text-primary" role="status"><span class="sr-only">Loading...</span></div></center>');
		$.get("crud/listado_comprobantes.php", {Data:Data, Page:Page}, function(result){
			$('#listado_comprobantes').html(result);
			$.getScript('js/comprobantes.js');
		});
	});
  
  
	$("#btn_filtrar").off("click");
	$("#btn_filtrar").on('click', function(event){
		event.preventDefault();
		var Data = $("#vit_comprobantessearch").serialize();
		var Page = 1;
		$("#listado_comprobantes").html('<center>Favor de esperar un momento...<br /><br /><div class="spinner-border text-primary" role="status"><span class="sr-only">Loading...</span></div></center>');
		$.get("crud/listado_comprobantes.php", {Data:Data, Page:Page}, function(result){
			$('#listado_comprobantes').html(result);
			$.getScript('js/comprobantes.js');
		});
	});
	
	$("#btn_quitar").off("click");
	$("#btn_quitar").on('click', function(event){

		//alert("quitar....");
		$("#s_Cfdi_Serie").val('');
		$("#s_Cfdi_Folio").val('');
		$("#s_Cfdi_Fecha").val('');
		$("#s_Emi_RFC").prop("selectedIndex", 0).change();
		$("#s_Rec_RFC").prop("selectedIndex", 0).change();
		$("#s_Cfdi_UUID").val('');
		$("#s_Mun_ID").prop("selectedIndex", 0).change();
		$("#s_Status").prop("selectedIndex", 0).change();
		$("#s_StatusSat").prop("selectedIndex", 0).change();
		var Data = $("#vit_comprobantessearch").serialize();
		var Page = 1;
		//alert("quitar....");
		$("#listado_comprobantes").html('<center>Favor de esperar un momento...<br /><br /><div class="spinner-border text-primary" role="status"><span class="sr-only">Loading...</span></div></center>');
		$.get("crud/listado_comprobantes.php", {Data:Data, Page:Page}, function(result){
			$('#listado_comprobantes').html(result);
			$.getScript('js/comprobantes.js');
		});
		
		
	});
	
});