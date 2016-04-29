$(document).ready(function() {
  $("[data-toggle]").click(function() {
    var toggle_el = $(this).data("toggle");
    $('.container-menu').toggleClass("open-sidebar");
    //$("#sidebar ul li.active-item a").toggleClass("#sidebar ul li.active-item a");
  });
  //console.log($('.container-menu').hasClass('open-sidebar'));
  if($(window).width()<770) {
  	$('.container-menu').removeClass("open-sidebar");
  	$('#page-id').animate({opacity:0},200);
  	$('.menu-icon').show();

  }

  $(window).resize(function(){
  	if($(window).width()<770 && ($('.container-menu').hasClass("open-sidebar"))){
  		$('.container-menu').toggleClass("open-sidebar");
  		//$("#sidebar ul li.active-item a").css("background","transparent");
  		$('.menu-icon').show();
  		//$('#page-id').css("opacity","0");
  		$('#page-id').animate({opacity:0},200);
  	}else if($(window).width()>770 && (!$('.container-menu').hasClass("open-sidebar"))){
  		$('.container-menu').toggleClass("open-sidebar");
  		//$("#sidebar ul li.active-item a").css("background","#89323F");
  		$('.menu-icon').hide();
  		$('#page-id').show();
  		//$('#page-id').css("opacity","1");
  		$('#page-id').animate({opacity:1},200);
  	}
  });

  /*Проверка паролей при отправке формы*/

  var check_passwords = function(form,event){
  	var pass2=document.getElementById("pass").value;
  	var pass1=document.getElementById("re_pass").value;
  	if(pass1!=pass2){
  		console.log("Password don't match");
  		event.preventDefault();
  		return false;
  	}
  	else{
  		form.submit();
  		return true;
  	}
  }

  /*Заполнение таблицы данными*/
  var show_table = function (data, jour_name) {
  	var table = $('#article-data > tbody'),
  		new_row = "",
  		new_elem = "";
  		$("#article-data").css("opacity",0);
  		table.children().slice(1).remove();
  	for (var i = 0; i < data.length; i++) {
  		new_elem = $("<tr></tr>");
  		table.append(new_elem);
  		new_elem.append("<td>"+data[i].author+"</td>");
  		new_elem.append("<td>"+data[i].name+"</td>");
  		if(data[i].blocked == 1){
  			new_elem.append("<td><div class='art-status blocked-art'></div></td>");
  		}else{
  			new_elem.append("<td><div class='art-status unblocked-art'></div></td>");
  		}


  		new_elem.append("<td>"+jour_name+"</td>");
  		new_elem.append("<td>"+data[i].pages+"</td>");
  	}
  		$("#article-data").removeClass("hidden");
  		$("#article-data").addClass("table table-hover");
  		$("#article-data").animate({"opacity":1},500);
  }

  /*Проверка совпадения паролей*/
  var validatePassword = function(){
  	var pass2=document.getElementById("pass").value;
  	var pass1=document.getElementById("re_pass").value;
  	if(pass1!=pass2){
  		$(".reg-form").find(".wrong-pass").find("p").text('Пароли не совпадают!');
  		$(".wrong-pass").animate({"opacity":1},500);
  	}
  	else if(pass1 !== "" && pass2 == pass1){
  		$(".wrong-pass").animate({"opacity":0},
  			500,
  			function(){
  				$(".reg-form").find(".wrong-pass").find("p").text('Пароли совпадают!');
  			}
  			);
  		$(".wrong-pass").animate({"opacity":1},500);

  		
  	}
  }

  /*Функция добавления формы для источника литературы*/

  var add_lit_form = function(prev_input, prev_numb,add_butt){
  	var input_name = document.createElement('input'),
				input_authors = document.createElement('input'),
				input_pages = document.createElement('input'),
				p_name = document.createElement('p'),
				p_authors = document.createElement('p'),
				p_pages = document.createElement('p'),
				txt1 = document.createTextNode("Наименование источника: "),
				txt2 = document.createTextNode("Список авторов: "),
				txt3 = document.createTextNode("Страницы: "),
				//add_butt = $('#add-litr'),
				error_p = document.createElement("p"),
				text_p = document.createTextNode("Количество авторов не может превышать 10");
				error_p.appendChild(text_p);		
			
			prev_numb = Number(prev_numb) + 1;
			//p_name.appendChild(txt);
			//p_authors.appendChild(txt);
			//p_pages.appendChild(txt);
			
			$(input_name).prop({"type":"text", "name":"literature_name" + prev_numb + ""});
			$(input_authors).prop({"type":"text", "name":"literature_authors" + prev_numb + ""});
			$(input_pages).prop({"type":"text", "name":"literature_pages" + prev_numb + ""});
			$(txt1).insertBefore(add_butt);	
			$(input_name).insertBefore(add_butt);
			$(document.createElement("br")).insertBefore(add_butt);
			$(document.createElement("br")).insertBefore(add_butt);
			$(txt2).insertBefore(add_butt);
			$(input_authors).insertBefore(add_butt);
			$(document.createElement("br")).insertBefore(add_butt);
			$(document.createElement("br")).insertBefore(add_butt);
			$(txt3).insertBefore(add_butt);
			$(input_pages).insertBefore(add_butt);
			$(document.createElement("br")).insertBefore(add_butt);
			$(document.createElement("br")).insertBefore(add_butt);
  }

  /*Обработка клика на строку таблицы*/
	$(document).on("click", "tr", function(event){
		//console.log($(event.target).closest("table").is("#article-data"));
		if(!$(event.target).hasClass("art-status")){
			if($(this).hasClass("choosen") ){
				$(this).removeClass("choosen");
				if($(event.target).closest("table").is("#article-data")){
					$("#redact-article").fadeOut();
				}else {
					$("#redact-authors").fadeOut();
				}
			}else{
				$("tr").removeClass("choosen");
				$(this).addClass("choosen");
				if($(event.target).closest("table").is("#article-data")){
					$("#redact-article").fadeIn();
				}else {
					$("#redact-authors").fadeIn();
				}
				//$("#redact-authors").fadeIn();
				//$("#redact-authors").css("display","inline");
			}
		}
	});

	/*Выбор журнала (Проверить выборку при отсутствии статей в журнале)*/ 
	$("#choose-jour").change(function() {
		//Серия D №2 2016-04-13
		//console.log('WWW');
		var jour_name = $(this).val(),
			batch = jour_name.substring(6,7),
			reg = /№[\d]{1,2}/,
			number = jour_name.match(reg)[0].substring(1),
			jour_data = {
				"jour_batch" : batch,
				"jour_numb" : number
			};
		//console.log("numb: "+$.toJSON(jour_data));
		$.ajax({
			url: 'script.php?req_type=ajax_ch_jour',
			type: 'POST',
			data: 'jsonData=' + $.toJSON(jour_data),
			success:function(data) {
				var resp = JSON.parse(data);
				show_table(resp,jour_name);

			}
		});
	});

	/*Изменение статуса статьи*/

	$(document).on("click", ".art-status", function () {
		var art_stat = '',
			waiting = false,
			elem = $(this);
		if($(this).hasClass("blocked-art")){
			art_stat = 0;
			$(this).removeClass("blocked-art");
			$(this).addClass("waiting-art");
			waiting = true;
		}else if($(this).hasClass("unblocked-art")){
			art_stat = 1;
			$(this).removeClass("unblocked-art");
			$(this).addClass("waiting-art");
			waiting = true;
		}
		var parent_row = $(this).parent().parent(),
			row_cells = parent_row.children(),
			test ="",
			data_send = {
				"art_name": $(row_cells.get(1)).text(),
				"art_stat": art_stat
			};
		if(waiting){
			console.log("sending");
			$.ajax({
			url: 'script.php?req_type=ajax_bl_art',
			type: 'POST',
			data: 'jsonData=' + $.toJSON(data_send),
			success: function(data) {
				if(art_stat == 1){
					elem.removeClass("waiting-art");
					elem.addClass("blocked-art");
				}else if(art_stat == 0){
					elem.removeClass("waiting-art");
					elem.addClass("unblocked-art");
				}
				var resp = JSON.parse(data);
				//console.log("ready:"+resp);
			} 

			});
		}
	});

	$("#send_user-data").click(function(event) {
		check_passwords($(this),event);
	});


	/*Добавление полей вводя для дополнительных авторов*/
	$(document).on("click", "#add-litr", function(event){
		var	prev_input = $(event.target).prev().prev().prev(),
			prev_numb = prev_input.prop("name")[prev_input.prop("name").length - 1];
		add_lit_form(prev_input,prev_numb,$('#add-litr'));
		//console.log(p_name);
		if(prev_numb >= 9){
			var error_p = document.createElement("p"),
				text_p = document.createTextNode("Количество авторов не может превышать 10");
				error_p.appendChild(text_p);
			$(error_p).insertBefore(add_butt);
			$(add_butt).prop({"disabled":true});
		}

		return false;
	});

	/*Редактрование материала*/
	$(document).on("click", "#redact-article", function(){
		//$("#redact-article").addClass("hidden");
		//$("#redact-article-form").show(500);
		var choosen_row = $(".choosen"),
			row_cells = choosen_row.children(),
			data_send = {
				"art_name": $(row_cells.get(1)).text(),
			};

		//console.log($.toJSON(data_send));
			$.ajax({
			url: 'script.php?req_type=ajax_get_art',
			type: 'POST',
			data: 'jsonData=' + $.toJSON(data_send),
			success: function(data) {
				var resp = JSON.parse(data);
				console.log("ready:"+data);
			} 

			});


	});


  	document.getElementById("re_pass").addEventListener("input", validatePassword);
	
});
