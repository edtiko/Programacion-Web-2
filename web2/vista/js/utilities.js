$(document).ready(function()  {
	//--cargar paginador	
	$("#resultados").tablesorter();
	$("#resultados").tablesorterPager({container: $("#paginador")});

	var flag = true;
	$('.checkall').click ( function(){				
		$("form input:checkbox").attr ( "checked" , flag );
		flag=!flag;
    });

    
  $("#consulta_general form").submit(function(e){
    var faculta     = jQuery("select[name=id_facultad]").val();
    var id_programa = jQuery("select[name=id_programa]").val();
    var jornada     = jQuery("select[name=jornada]").val();
    var genero      = jQuery("select[name=genero]").val();
    var sede_universidad = jQuery("select[name=sede_universidad]").val();
    var checked_todos    = jQuery("input[name=todos]").is(':checked');
    var alguna_variable  = faculta.length === 0 && id_programa.length === 0 && jornada.length === 0 && genero.length === 0 && sede_universidad.length === 0;
    if(!checked_todos && alguna_variable){
      e.preventDefault();
      alert("Debe seleccionar almenos una opcion."); 
    }    
  });
  loadMessages();
  $(".pagination").quickPagination({pagerLocation:"both",pageSize:"1"});
});

function loadMessages(){
	$('div.msg').click(function() {
		$(this).css("display","none");
	});
	
	$('div.msg').each(function(indice,valor) {
		if($(valor).html() != ""){
		   $(valor).css("display","block");
	   }	   
	});
}
function validarCargaSeleccionados(){
	var flag = true;
	$(".rowR > tbody > tr :hidden").each(function(indice,tr) {					
		if($('input[name="'+$(tr).val()+'"]:checked').length == 0){		
			$("div.error").text("Debe seleccionar la clasificaion de todos los estudiantes");
			$("div.error").show();
			flag = false;
		}
	});	
	return flag;
}

