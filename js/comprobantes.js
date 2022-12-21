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
		$("#s_Mun_ID").prop("selectedIndex", 0).change();
		$("#s_Emi_RFC").prop("selectedIndex", 0).change();
		$("#s_Rec_RFC").prop("selectedIndex", 0).change();
		$("#s_Cfdi_UUID").val('');
		$("#s_RecRFC").val('');
		
		$("#s_Status").prop("selectedIndex", 0).change();
		$("#s_StatusSat").prop("selectedIndex", 0).change();
		var Data = $("#vit_comprobantessearch").serialize();
		var Page = 1;
		//alert("quitar....");
		
		var Mun_ID = '';
		/*alert("Mun_ID: "+Mun_ID);*/
		$("#select_municipios").html('<center><div class="spinner-border text-primary" role="status"><span class="sr-only">Loading...</span></div></center>');
		$.post("crud/select_municipios.php", {Mun_ID:Mun_ID}, function(resultMun_ID){
			$('#select_municipios').html(resultMun_ID);
			$.getScript('js/comprobantes.js');
		});
		
		$("#listado_comprobantes").html('<center>Favor de esperar un momento...<br /><br /><div class="spinner-border text-primary" role="status"><span class="sr-only">Loading...</span></div></center>');
		$.get("crud/listado_comprobantes.php", {Data:Data, Page:Page}, function(result){
			$('#listado_comprobantes').html(result);
			$.getScript('js/comprobantes.js');
		});		
		
	});
	
	
	$("#s_Mun_ID").off("change");
	$("#s_Mun_ID").on('change', function(event){
		event.preventDefault();
		var Mun_ID = $("#s_Mun_ID").val();
		/*alert("Mun_ID: "+Mun_ID);*/
		$("#select_emisores").html('<center><div class="spinner-border text-primary" role="status"><span class="sr-only">Loading...</span></div></center>');
		$.post("crud/select_emisores.php", {Mun_ID:Mun_ID}, function(resultMun_ID){
			$('#select_emisores').html(resultMun_ID);
			$.getScript('js/comprobantes.js');
		});
		
		$("#select_series").html('<center><div class="spinner-border text-primary" role="status"><span class="sr-only">Loading...</span></div></center>');
		$.post("crud/select_series.php", {Mun_ID:Mun_ID}, function(resultMun_ID){
			$('#select_series').html(resultMun_ID);
			$.getScript('js/comprobantes.js');
		});
		
		$("#select_receptores").html('<center><div class="spinner-border text-primary" role="status"><span class="sr-only">Loading...</span></div></center>');
		$.post("crud/select_receptores.php", {Mun_ID:Mun_ID}, function(resultMun_ID){
			$('#select_receptores').html(resultMun_ID);
			$.getScript('js/comprobantes.js');
		});
	});
	
});