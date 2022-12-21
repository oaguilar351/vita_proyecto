$(function(){
   
	$("#submit").click(function(){
		let userid = $("#userid").val();
		let passwd = $("#passwd").val();
		if(userid=='' || passwd==''){
			if(userid==''){
				$("#userid").focus();
			}
			if(passwd==''){
				$("#passwd").focus();
			}
			$("#msg_login").html('Favor de ingresar Usuario y Contraseña');			
			$('#msg_login').css('color', '#aa0000');
		}else{
			let submit = $("#submit").val();	
			$.post("libs/valida_acceso.php", {Userid: userid, Passwd:passwd, Submit:submit }, function(result){
				if(result==1){
					//alert("result: "+result);
					$("#msg_login").html('Usuario, Contraseña correctos');
					$('#msg_login').css('color', '#566573');
					$('#msg_login').css('font-weight', 'bold');
					let delay = 3000;
					/*let url = 'index.php';*/
					let url = 'emisores_listado.php';
					setTimeout(function(){
						document.location.href = url;
					},2000);
				}else{
					$("#msg_login").html('Usuario, Contraseña incorrecto');
					$('#msg_login').css('color', '#aa0000');					
				}
			});
		}
	});   
	
	
	$(document).keypress(function(e) {
		if(e.which == 13) {
			
			/*let userid = $("#userid").val();
			let passwd = $("#passwd").val();
			if(userid=='' || passwd==''){
				if(userid==''){
					$("#userid").focus();
				}
				if(passwd==''){
					$("#passwd").focus();
				}
				$("#msg_login").html('Favor de ingresar Usuario y Contraseña');			
				$('#msg_login').css('color', '#aa0000');
			}else{
				let submit = $("#submit").val();	
				$.post("libs/valida_acceso.php", {Userid: userid, Passwd:passwd, Submit:submit }, function(result){
					if(result==1){
						$("#msg_login").html('Usuario, Contraseña correctos');
						$('#msg_login').css('color', '#689f38');
						let delay = 5000;
						let url = 'index.php';
						setTimeout(function(){
							document.location.href = url;
						},2000);
					}else{
						$("#msg_login").html('Usuario, Contraseña incorrecto');
						$('#msg_login').css('color', '#aa0000');					
					}
				});
			}*/
			$("#submit").trigger("click");
		}
	});
  
});