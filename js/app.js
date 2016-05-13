$(document).ready(function() {
	console.log("ready");
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
  var show_art_table = function (data, jour_name) {
  	var table = $('#article-data > tbody'),
  		new_row = "",
  		new_elem = "",
  		authors_str = "";
  		$("#article-data").css("opacity",0);
  		table.children().slice(1).remove();
  	//console.log(data);
  	for(var i = 0; i<data[0]['authors'].length; i++){
  		authors_str += data[0]['authors'][i]['name'];
  		authors_str += "<br>"
  	}
  	for (var i = 0; i < data.length; i++) {
  		new_elem = $("<tr></tr>");
  		table.append(new_elem);
  		new_elem.append("<td>"+authors_str+"</td>");
  		new_elem.append("<td>"+data[i].art_name+"</td>");
  		new_elem.append("<td>"+data[i].art_pages+"</td>");
  		/*if(data[i].blocked == 1){
  			new_elem.append("<td><div class='status blocked-art'></div></td>");
  		}else{
  			new_elem.append("<td><div class='status unblocked-art'></div></td>");
  		}*/


  		new_elem.append("<td>"+jour_name+"</td>");
  		//new_elem.append("<td>"+data[i].pages+"</td>");
  	}
  		$("#article-data").removeClass("hidden");
  		$("#article-data").addClass("table table-hover");
  		$("#article-data").animate({"opacity":1},500);
  }

  /*Вывод доступных журналов определённого класса*/
  var show_select = function(data,select_journal,select_author){
  	for(var i = 0;i<data['journals'].length;i++){
  		new_elem = $("<option>Серия "+data['journals'][i]['class']+" №"+data['journals'][i]['number']+" "+data['journals'][i]['pub_year']+"</option>");
  		$(select_journal).append(new_elem);
  	}
  	for(var i = 0;i<data['authors'].length;i++){
  		new_elem = $("<option>"+data['authors'][i]['name']+"</option>");
  		$(select_author).append(new_elem);
  	}
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

  /*Функция добавления select для авторов*/

  var add_auth_select = function(){
  	var auth_p = document.createElement('p'),
  		text_p = document.createTextNode("Выберите автора публикации:"),
  		new_select = document.createElement('select'),
  		first_option = document.createElement('option'),
  		f_opt_text = document.createTextNode("Автор"),
  		selector_div = $(".author_selectors")[0],
  		last_selector_opt = $(".author_selectors > p > select:last > option")
  		last_choosen_opt = $(".author_selectors > p > select:last > option:selected"),
  		new_option = "",
  		last_select_name = last_choosen_opt.parent().prop("name");
  		new_option_text = "";
  		//console.log(last_choosen_opt);
  		//console.log(last_select_name);
  		//#redact-article-form > div > p:nth-child(1) > select
  		auth_p.appendChild(text_p);
  		first_option.appendChild(f_opt_text);
  		$(first_option).attr("disabled",true);
  		$(first_option).attr("selected",true);

  		console.log(last_selector_opt);
  		//console.log(last_select_name[last_select_name.length - 1]);
  		$(new_select).attr("name", "author" + Number(Number(last_select_name[last_select_name.length - 1]) + 1));
  		//$(new_select).prop("name",last_choosen_opt.parent().prop("name")[last_choosen_opt.parent().prop("name").length - 1])
  		new_select.appendChild(first_option);
  		//console.log(last_choosen_opt.val());
  		if(last_choosen_opt.val() != "Автор"){
  			for(var i = 1; i < last_selector_opt.length; i++){
  				if($(last_selector_opt[i]).val() != last_choosen_opt.val()){
  					new_option_text = document.createTextNode($(last_selector_opt[i]).val());
  					new_option = document.createElement("option");
  					new_option.appendChild(new_option_text);
  					new_select.appendChild(new_option);
  				}
  			}
  			auth_p.appendChild(new_select);
  			$(auth_p).insertBefore($("#add_author").parent());
  			//selector_div.appendChild(auth_p);
  			if(last_selector_opt.length <= 3){
  				$("button#add_author").attr("disabled",true);
  			}
  		}
  }

  /*Функция добавления формы для источника литературы*/

  var add_lit_form = function(prev_input, prev_numb,add_butt){
  	var lit_div = document.createElement('div'),
  		input_name = document.createElement('input'),
  		input_authors = document.createElement('input'),
  		input_pages = document.createElement('input'),
  		p_name = document.createElement('p'),
  		p_authors = document.createElement('p'),
  		p_pages = document.createElement('p'),
  		txt1 = document.createTextNode("Наименование источника: "),
  		txt2 = document.createTextNode("Список авторов: "),
  		txt3 = document.createTextNode("Страницы: "),
		prev_numb = Number(prev_numb) + 1,
		inputs = Array();
	
	$(lit_div).addClass("literature");	

	//parentElem.appendChild(elem)
	//Добавляет elem в конец дочерних элементов parentElem.				
	//p_name.appendChild(txt);
	//p_authors.appendChild(txt);
	//p_pages.appendChild(txt);
	$("p#err_lit").remove();
	$("#last").removeAttr("id");
	$(input_name).prop({"type":"text","id":"last", "required":"true","name":"literature_name" + prev_numb + ""});
	$(input_authors).prop({"type":"text", "required":"true", "name":"literature_authors" + prev_numb + ""});
	$(input_pages).prop({"type":"text", "required":"true", "name":"literature_pages" + prev_numb + ""});
	inputs[0] = input_name;
	inputs[1] = input_authors;
	inputs[2] = input_pages;

	lit_div.appendChild(txt1);
	lit_div.appendChild(input_name);
	lit_div.appendChild(document.createElement("br"));
	lit_div.appendChild(document.createElement("br"));
	lit_div.appendChild(txt2);
	lit_div.appendChild(input_authors);
	lit_div.appendChild(document.createElement("br"));
	lit_div.appendChild(document.createElement("br"));
	lit_div.appendChild(txt3);
	lit_div.appendChild(input_pages);
	lit_div.appendChild(document.createElement("br"));
	lit_div.appendChild(document.createElement("br"));
	$(lit_div).insertBefore(add_butt);
	return inputs;
  }

	/*Проверка заполненности полей источников литературы*/
	var check_lit_fields = function(prev_numb,butt){
		var prev_lit = "literature_name" + prev_numb,
			prev_auth = "literature_authors" + prev_numb,
			prev_pages = "literature_pages" + prev_numb,
			lit_val = $('input[name="'+prev_lit+'"]').val(),
			auth_val = $('input[name="'+prev_auth+'"]').val(),
			pages_val = $('input[name="'+prev_pages+'"]').val();

		/*if((lit_val != "")&&(auth_val != "")&&(pages_val != "")){
			return true;
		}else{
			if( !$("p").is($("#err_lit"))){
				var err_p = document.createElement("p"),
					err_txt = document.createTextNode("Не все поля заполнены!");
				$(err_p).prop({"id":"err_lit"});
				err_p.appendChild(err_txt);
				$(err_p).insertBefore(butt);
				$(err_p).hide();
				$(err_p).show("slow");
				return false;
			}else{
				$("#err_lit").hide();
				$("#err_lit").fadeIn("slow");
				return false;
			}
		}*/
		return true;

	}

  /*Обработка клика на строку таблицы*/
	$(document).on("click", "tr", function(event){
		if(!$(event.target).hasClass("status")&&!($(event.target).parent().hasClass('table-head'))){
			if($(this).hasClass("choosen") ){
				$(this).removeClass("choosen");
				if($(event.target).closest("table").is("#article-data")){
					$("#redact-article").fadeOut();
					$("#redact-article-form").fadeOut();
					//$("#redact-article-form").hide();	
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
			}
		}
	});

	/*Заполнение полей таблицы редактирования материала*/

	/*Добавление полей вводя для дополнительных авторов*/
	$(document).on("click", "#add-litr", function(event){
		var	prev_input = $(event.target).parent().find("#last");
			prev_numb = prev_input.prop("name")[prev_input.prop("name").length - 1];
		if(check_lit_fields(prev_numb,$("#add-litr"))){
			add_lit_form(prev_input,prev_numb,$('#add-litr'));
			if(prev_numb >= 9){
				var error_p = document.createElement("p"),
				text_p = document.createTextNode("Количество авторов не может превышать 10");
				error_p.appendChild(text_p);
				$(error_p).insertBefore($("#add-litr"));
				$($("#add-litr")).prop({"disabled":true});
			}
		}
		return false;
	});

	/*Выбор журнала (Проверить выборку при отсутствии статей в журнале)*/ 
	$(".choose-jour").change(function() {
		//Серия D №2 2016-04-13
		var jour_name = $(this).val();
		show_journal_articles(jour_name);
	});


	/**/
	var show_journal_articles = function(jour_name){
		var	batch = jour_name.substring(6,7),
			number = jour_name.match(/№[\d]{1,2}/)[0].substring(1),
			year = jour_name.match(/[\d]{4}/)[0],
			jour_data = {
				"jour_batch" : batch,
				"jour_numb" : number,
				"jour_year" : year
			};
		//console.log("numb: "+$.toJSON(jour_data));
		$.ajax({
			url: 'script.php?req_type=ajax_ch_jour',
			type: 'POST',
			data: 'jsonData=' + $.toJSON(jour_data),
			success:function(data) {
				var resp = JSON.parse(data);
				//console.log(resp);
				show_art_table(resp,jour_name);
			}
		});
	}
	/*Изменение статуса статьи*/

	/*$(document).on("click", ".status", function () {
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
	});*/

	/*$("#send_author_data").click(function(event) {
		check_passwords($(this),event);
	});*/

	/*Редактрование материала*/
	$(document).on("click", "#redact-article", function(event){
		var choosen_row = $(".choosen"),
			row_cells = choosen_row.children(),
			data_send = {
				"art_name": $(row_cells.get(1)).text(),
			};
			$.ajax({
			url: 'script.php?req_type=ajax_get_art',
			type: 'POST',
			data: 'jsonData=' + $.toJSON(data_send),
			success: function(data) {
				//console.log(data);
				var resp = JSON.parse(data),
					redact_form = $("#redact-article-form"),
					redact_inputs = redact_form.find("input"),
					last_inputs = "",
					redact_teble = redact_form.find("table"),
					authors_select = redact_form.find("select");
				$(authors_select.children()).remove();
				$(authors_select).append("<option disabled>Автор</option>");
				//console.log(resp);
				redact_form.removeClass("hidden");
				$("#redact-article").hide();
				redact_form.hide();
				redact_form.show("slow");
				//console.log(redact_inputs.get(0));
				$(redact_inputs.get(0)).val(resp[0]["art_name"]);
				$(redact_inputs.get(1)).val(resp[0]["art_pages"]);

				for (var i = 0; i < resp[0]["authors"].length; i++) {
					var new_elem = $("<tr></tr>");
						//new_inp = $("<input disabled type='hidden' name='art_author"+i+"' value='"+resp[0]["authors"][i]["name"]+"'></input>");
					redact_teble.append(new_elem);
					new_elem.append("<td>"+resp[0]["authors"][i]["name"]+"</td>");
					//new_elem.append(new_inp);
					new_elem.append("<td><div class='status unblocked-art'></div></td>");
					//console.log($("#save-change-art")[0]);
					//new_inp.insertBefore($("#save-change-art")[0]);

				}
				var count = 0,
					not_add = false;
				for(var i =0; i < resp['auth_class'].length;i++){
					if(count != resp[0]["authors"].length){
						for(var j= 0; j < resp[0]["authors"].length; j++){
							if(resp['auth_class'][i]['name'] === resp[0]["authors"][j]['name']){
								count++;
								not_add = true;
								break;
							}
						}
					}
					if(!not_add){
						authors_select.append("<option>"+resp['auth_class'][i]['name']+"</option>");
					}
					not_add = false;
				}

			} 
			});
	});

	/*Обработка нажатия маркера удаления автора*/
	$(document).on("click",".status", function(){
		var curr_inp = $(this).parent().prev();
		if($(this).hasClass("unblocked-art")){
			$(this).removeClass("unblocked-art");
			$(this).addClass("blocked-art");
			$(curr_inp).prop("disabled",false);
		}else if($(this).hasClass("blocked-art")){
			$(this).removeClass("blocked-art");
			$(this).addClass("unblocked-art");
			$(curr_inp).prop("disabled",true);
		}
	});

	/*Сохранение статьи после редактирования*/
	$(document).on("click", "#save-change-art", function(){
		$("#redact-article-form").hide("slow");
		$(document).find("tr.choosen").removeClass("choosen");
		/*#auth-redact-data > tbody > tr:nth-child(1) > input[type="hidden"]
		$(redact_inputs.get(0)).val(resp["art_data"]["author"]);
		$(redact_inputs.get(1)).val(resp["art_data"]["name"]);
		$(redact_inputs.get(2)).val("test journal");
		$(redact_inputs.get(3)).val(resp["art_data"]["pages"]);
		$(redact_form.find("textarea").get(0)).val(resp["art_data"]["article_text"]);
		*/

		var redact_form = $("#redact-article-form"),
			redact_inputs = redact_form.find("input"),
			lit_inputs = redact_form.find("div"),
			remove_auth = $("#auth-redact-data").find(".blocked-art").parent().prev();
			add_auth = $(".author_selectors").find("select");
			j = 0,
			data_send = {
			"art_name" : $(redact_inputs.get(0)).val(),
			"pages" : $(redact_inputs.get(1)).val(),
			"new_authors" : [],
			"dell_authors" : []
		};

		//console.log(add_auth);
		//console.log($(remove_auth.get(0)).text());
		for(var i = 0;i < remove_auth.length; i++){
			data_send["dell_authors"][i] = $(remove_auth.get(i)).text();
		}
		for(var i = 0;i < add_auth.length;i++){
			if($(add_auth.get(i)).val() != null){
				data_send["new_authors"][i] = $(add_auth.get(i)).val();
			}
		}
		console.log(data_send);
		$.ajax({
			url: 'script.php?req_type=ajax_update_art',
			type: 'POST',
			data: 'jsonData=' + $.toJSON(data_send),
			success: function(data) {
				console.log("response"+data);
				show_journal_articles($('#journals').val());
			}
		});
		//$(".literature").remove();
		$("#auth-redact-data > tbody > tr").remove();
		$(".author_selectors").hide();
		var old_selects = $(".author_selectors").find("p"),
			select_count = old_selects.length;
		for(var i = 1;i<select_count - 1;i++){
			$(old_selects.get(i)).remove();
		}
		$("#add_author").removeAttr("disabled");
		return false;
	});

  	if(document.getElementById("re_pass")){
  		document.getElementById("re_pass").addEventListener("input", validatePassword);
  	}

  	/*Выбор типа журнала для публикации статьи*/
  	$("#choose-journal-class").change(function(event){
  		var data_send = {
  			"journal_class": $(this).val()
  		};

  		$.ajax({
  			url: 'script.php?req_type=ajax_ch_art_class',
  			type: 'POST',
  			data: 'jsonData=' + $.toJSON(data_send),
  			success: function(data){
  				var resp = JSON.parse(data);
				//console.log("authors");	
  				//console.log(resp['authors']);
  				//console.log("journals");					
  				//console.log(resp['journals']);
  				show_select(resp,$("#avail_journals")[0],$(".avail_authors")[0]);
  				if(resp['journals'].length>0){
  					$("#avail_journals").removeAttr("disabled");
  					$(".avail_authors").removeAttr("disabled");
  					//$("#add_author").removeAttr("disabled");
  					$("#add_author").removeClass("hidden");
  					$("#add_author").hide();
  					$("#add_author").fadeIn(200);
  					$("#choose_journ_p").find("span").remove();
  				}else{
  					$("#avail_journals").attr("disabled",true);
  					$(".avail_authors").attr("disabled",true);
  					$("#choose-journal-class").after("<span>&nbsp&nbsp<nbsp></nbsp>Журналы данного класса ещё не опубликованы</span>");
  				}
  				//console.log(resp.length);

  			}
  		});
  	});



  	/*Выбор типа журнала для просмотра*/
  	$("#vew_journ_class").on("change",function(){
  		var data_send = {
  			"journal_class": $(this).val()
  		};
  		$.ajax({
  			url: 'script.php?req_type=ajax_vew_jour_class',
  			type: 'POST',
  			data: 'jsonData=' + $.toJSON(data_send),
  			success: function(data){
  				var resp = JSON.parse(data);
  				$("#journals").removeAttr("disabled");
  				for(var i = 0;i<resp.length;i++){
  					new_elem = $("<option>Серия "+resp[i]['class']+" №"+resp[i]['number']+" "+resp[i]['pub_year']+"</option>");
  					$("#journals").append(new_elem);
  				}

  			}

  		});
  	});

  	/*Обработка кнопки добавления авторов при редактировании статьи*/

  	$("#redact_add_auth").on("click",function(){
  		var new_authors = $(".author_selectors")[0];
  		$(new_authors).removeClass("hidden");
  		$(new_authors).hide();
  		$(new_authors).fadeIn();
  		return false;
  	});

  	/*Обработка кнопки добавления авторов при добавлении статьи(доработать при не хватке количества авторов)*/
  	$("#add_author").on("click", function(){
  		var prev_select = $(event.target).parent().parent().find(".author_selectors > p > select");
  		console.log(prev_select);
  		add_auth_select();
  		return false;
  	});

	
});
