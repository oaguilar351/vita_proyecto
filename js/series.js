$(function(){
   
	$(".page-item").off("click");
	$(".page-item").on('click', function(event){
		event.preventDefault();
		var Page = $(this).attr('href');
		var Data = $("#vit_comprobantessearch").serialize();
		$("#listado_series").html('<center>Favor de esperar un momento...<br /><br /><div class="spinner-border text-primary" role="status"><span class="sr-only">Loading...</span></div></center>');
		$.get("crud/listado_series.php", {Data:Data, Page:Page}, function(result){
			$('#listado_series').html(result);
			$.getScript('js/series.js');
		});
	});
  
  
	$("#btn_filtrar").off("click");
	$("#btn_filtrar").on('click', function(event){
		event.preventDefault();
		var Data = $("#vit_comprobantessearch").serialize();
		var Page = 1;
		$("#listado_series").html('<center>Favor de esperar un momento...<br /><br /><div class="spinner-border text-primary" role="status"><span class="sr-only">Loading...</span></div></center>');
		$.get("crud/listado_series.php", {Data:Data, Page:Page}, function(result){
			$('#listado_series').html(result);
			$.getScript('js/series.js');
		});
	});
	
	$("#btn_quitar").off("click");
	$("#btn_quitar").on('click', function(event){

		//alert("quitar....");
		$("#s_Cfdi_Serie").val('');
		$("#s_Cfdi_Fecha").val('');
		$("#s_Emi_RFC").prop("selectedIndex", 0).change();
		$("#s_Mun_ID").prop("selectedIndex", 0).change();
		var Data = $("#vit_comprobantessearch").serialize();
		var Page = 1;
		//alert("quitar....");
		$("#listado_series").html('<center>Favor de esperar un momento...<br /><br /><div class="spinner-border text-primary" role="status"><span class="sr-only">Loading...</span></div></center>');
		$.get("crud/listado_series.php", {Data:Data, Page:Page}, function(result){
			$('#listado_series').html(result);
			$.getScript('js/series.js');
		});
		
		
	});
	
});