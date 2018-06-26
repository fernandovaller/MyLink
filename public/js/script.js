$(document).on('click', '.menu', function(event) {
	event.preventDefault();
	var pagina = $(this).attr('href').replace('#', '');
	$('.nav-item').removeClass('active');
	$(this).parent().addClass('active');
	console.log(pagina);
	$.get('/' + pagina, {controller:'paginas'}, function(data) {
		$('#paginas').html(data);	
	});
});

$(document).on('blur', '.title', function(event) {
	event.preventDefault();
	var url = $(this).val();
	console.log(url);
	$.ajax({
	  url: 'https://cors.io/?'+url,
	  async: true,
	  success: function(data) {
	    var matches = data.match(/<title>(.*?)<\/title>/);
	    if(matches != null){
	    	$('#titulo').val(matches[1]);
	    	$('#ModalLabelLink').text(matches[1]);	
	    }	    
	  }   
	});
});

$(document).ready( function () {
    $('.datatable').DataTable();
} );

$(document).on('click', '.confirme', function(event) {
	event.preventDefault();

	var msg = $(this).data('msg');
	var cmd = $(this).attr('href');

	alertify.confirm(msg, function () {
		location.href = cmd;
	}, function() {

	});	

});

$(document).on('click', '.edit', function(event) {
	event.preventDefault();	
	$('#modal-edit').modal('show');
	  var id = $(this).data('id');	  
	  $.get('/links/edit/?id='+id, function(data) {
	  	console.log(id);
	  	var dados = $.parseJSON(data);
	  	$('#form-edit input[name=id]').val(dados.id);
	  	$('#form-edit input[name=titulo]').val(dados.titulo);
	  	$('#form-edit input[name=link]').val(dados.link);
	  	$('#form-edit select[name=id_categoria]').val(dados.id_categoria);
	  	$('#form-edit input[name=descricao]').val(dados.descricao);
	  });

});

$(document).on('click', '.edit-cat', function(event) {
	event.preventDefault();
	$('#modal-categoria-cat').modal('show');

	  var id = $(this).data('id');
	  console.log($(this));
	  $.get('/categoria/edit/?id='+id, function(data) {
	  	console.log(data);
	  	var dados = $.parseJSON(data);
	  	$('#form-edit-cat input[name=id]').val(dados.id);
	  	$('#form-edit-cat input[name=categoria]').val(dados.categoria);	  	
	  });

});

$('#descricao').blur(function() {
    var text = this.value;  
    if($('#titulo').val() == ''){
    	$('#titulo').val(text);
    	$('#ModalLabelLink').text(text);	
    }        
});


$("#find").on('keyup', function() {
	var f = $(this).val();	
	$("#box-resultados li").each(function() {
		if ($(this).text().search(new RegExp(f, "i")) < 0) {
			$(this).fadeOut(200);
		} else {
			$(this).show(200);
		}
	});
});