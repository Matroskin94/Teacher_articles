$(document).ready(function() {
	console.log("ready");
	$("[data-toggle]").click(function() {
		var toggle_el = $(this).data("toggle");
		$('.container-menu').toggleClass("open-sidebar");
    //$("#sidebar ul li.active-item a").toggleClass("#sidebar ul li.active-item a");
});
  //console.log($('.container-menu').hasClass('open-sidebar'));
  /*if($(window).width()<770) {
  	$('.container-menu').removeClass("open-sidebar");
  	$('#page-id').animate({opacity:0},200);
  	$('.menu-icon').show();

  }*/


  /*$(window).resize(function(){
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
  });*/


  /*Переход по вкладкам*/
  $("#main-menu").on("click","a",function(event){
  	var link = $(event.target).attr("href");
  	switch (link){
  		case "#articles-tab" :
  		$("#page-id").fadeOut(200,function(){
  			$("#page-id").text("Публикации");
  		});	
  		break;
  		case "#authors-tab" :
  		$("#page-id").fadeOut(200,function(){
  			$("#page-id").text("Авторы");
  		});
  		break;
  		case "#journals-tab" :
  		$("#page-id").fadeOut(200,function(){
  			$("#page-id").text("Журналы");
  		});
  		break;
  	}
  	$("#page-id").fadeIn(200);
  	$(".active-item").removeClass("active-item");	
  	$($(event.target).parent()[0]).addClass("active-item")
  	$(".active-tab").fadeOut(400,function(){
  		$(".active-tab").removeClass("active-tab");
  		$(link).addClass("active-tab");
  		$(link).fadeIn(400);
  	});
  	

  });

  /*Заполнение таблицы данными*/
  var show_table = function (table,data, jour_name) {
  	var table_body = $('#article-data > tbody'),
  	new_row = "",
  	new_elem = "",
  	authors_str = "";
  	$(table).css("opacity",0);
  	$(table).removeAttr("hidden");
  	$(table).children().children().slice(1).remove();
  	console.log(data);
  	if(table === "#article-data"){
  		if(data != null){
  			for (var i = 0; i < data.length; i++) {
  				for(var j = 0; j<data[i]['authors'].length; j++){
  					authors_str += data[i]['authors'][j]['name'];
  					authors_str += " <br>";
  				}
  				$("#message_p").text("Результат");
  				$("#message_p").fadeIn();
  				new_elem = $("<tr></tr>");
  				$(table).append(new_elem);
  				new_elem.append("<td>"+authors_str+"</td>");
  				new_elem.append("<td>"+data[i].art_name+"</td>");
  				new_elem.append("<td>"+data[i].art_pages+"</td>");
  				new_elem.append("<td>"+jour_name+"</td>");
  				authors_str = "";
  			}
  		}else{
  			$("#message_p").text("Статьи журнала ещё не опубликованы");
  			$("#message_p").fadeIn();
  		}
  	}else if((table === "#authors-data")||(table === "#authors-search")){
  		for (var i = 0; i < data.length; i++) {
  			new_elem = $("<tr></tr>");
  			$(table).append(new_elem);
  			new_elem.append("<td>"+data[i].name+"</td>");
  			new_elem.append("<td>"+data[i].dc_degree+"</td>");
  			new_elem.append("<td>"+data[i].organisation+"</td>");
  			new_elem.append("<input type='hidden' value ='"+data[i].author_id+"'>");
  		}
  	}else if(table === "#journals-data"){
  		for (var i = 0; i < data.length; i++) {
  			new_elem = $("<tr></tr>");
  			$(table).append(new_elem);
  			new_elem.append("<td>"+data[i].name+"</td>");
  			new_elem.append("<td>"+data[i].class+"</td>");
  			new_elem.append("<td>"+data[i].number+"</td>");
  			new_elem.append("<td>"+data[i].pub_year+"</td>");
  			new_elem.append("<td>"+data[i].pages+"</td>");
  		}
  	}
  	$(table).removeClass("hidden");
  	$(table).addClass("table table-hover");
  	$(table).animate({"opacity":1},500);
  	$(table).fadeIn();
  		//$(table).slideDown();
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

  	/*Очистка таблицы авторов при добавлении или редактировании материала*/

  	var clear_auth_table = function(auth_table_id){
  		var child_rows = $(auth_table_id).find("tr"),
  			i = 0;
  			$(auth_table_id).remove("tr");	
  		for(var i = 0;i< child_rows.length;i++ ){
  			child_rows.get(i).remove();
  		}
  	}

  	/*Функция добавления select для авторов*/

  	var add_auth_select = function(butt_id,name,auth_id){
  		var p_div = document.createElement('div'),
  		clear_div = document.createElement('div'),
  		name_div = document.createElement('div'),
  		name_p = document.createElement('p'),
  		name_text = document.createTextNode(name),
  		div_p = document.createElement('p'),
  		text_p = document.createTextNode("Автор:"),
  		//new_select = document.createElement('select'),
  		//first_option = document.createElement('option'),
  		new_auth_id_inp = document.createElement('input'),
  		//f_opt_text = document.createTextNode("Автор"),
  		last_input = "",
  		last_input_name = "";
  		last_input = $("#adding_auth_name > input:last");
  		//console.log(last_input);
  		if(last_input.length == 0){
  			$(new_auth_id_inp).attr({
  				"type":"hidden",
  				"name":"auth_id0",
  				"value": auth_id
  			});
  		}else{
  			last_input_name = last_input.prop("name");
  			$(new_auth_id_inp).attr({
  				"name": "auth_id" + Number(Number(last_input_name[last_input_name.length - 1]) + 1),
  				"type":"hidden",
  				"value": auth_id

  			});

  		}
  	//console.log(name_p);
  	$(clear_div).addClass("clearfix");
  	$(p_div).addClass("col-sm-5");
  	$(name_div).addClass("col-sm-7");
  	name_p.appendChild(name_text);
  	name_div.appendChild(name_p);
  	div_p.appendChild(text_p);
  	p_div.appendChild(div_p);
  	$(p_div).insertBefore($("#add_author_new_art").parent());
  	$(clear_div).insertBefore($(butt_id).parent());
  	$(p_div).insertBefore($(butt_id).parent());
  	$(name_div).insertBefore($(butt_id).parent());
  	$(new_auth_id_inp).insertBefore($(butt_id).parent());

  }



  /*Разбиение массива авторов на ФИО через запятую*/

  var split_by_coma = function(words){
  	var tmp_name = "",
  	art_authors = "";
  	for(var i = 0;i < words.length - 1; i++){
  		if((i+1) % 3 != 0){
  			tmp_name = tmp_name + words[i] + " ";
				//console.log(tmp_name);
			}
			if((i+1) % 3 == 0){
				if(i != words.length - 2){
					art_authors += tmp_name + words[i] + ", ";
				}else{
					art_authors += tmp_name + words[i] + ". ";	
				}
				tmp_name = "";
			}
		}
		//console.log("func"+art_authors);
		return art_authors;
	}

	/*Вывод статей определённого журнала*/
	var show_journal_articles = function(jour_name){
		var	batch = jour_name.substring(6,7),
		number = jour_name.match(/№[\d]{1,2}/)[0].substring(1),
		year = jour_name.match(/[\d]{4}/)[0],
		jour_data = {
			"jour_batch" : batch,
			"jour_numb" : number,
			"jour_year" : year
		};
		$.ajax({
			url: 'script.php?req_type=ajax_ch_jour',
			type: 'POST',
			data: 'jsonData=' + $.toJSON(jour_data),
			success:function(data) {
				var resp = JSON.parse(data);
				//console.log(resp);
				show_table("#article-data",resp,jour_name);
				$("#add-art-but").show(200);
			}
		});
	}

	/*Скрытие формы*/

	var hide_form = function(butt_id){
	  	switch (butt_id) {
	  		case "cancel-article":
	  			if($("#send-article-data").parent().css("display") == "block"){
					$("#send-article-data").parent().hide();
					$("#message_p").text("Выберите необходимый журнал либо воспользуйтесь поиском");
					$("#message_p").fadeIn();
	  			}
	  			if($("#update-article").parent().css("display") == "block"){
	  				$("#add-art-but").fadeIn();
					$("#journals").prop("disabled",false);
	  				$("#vew_journ_class").prop("disabled",false);
					$(document).find("tr.choosen").removeClass("choosen");
					$("#article-data").addClass("table-hover");
					$("#article-data").removeClass("redacting");
					//clear_auth_table("#auth-redact-data");
					$("#auth-redact-data > tbody > tr").remove();
					$("#auth-redact-data").hide();
					
					$("#message_p").text("Выберите необходимый журнал либо воспользуйтесь поиском");
					$("#update-article").parent().hide();
	  			}
	  			$("#add-art-form").slideUp();
	  			$("#add-art-but").fadeIn();
	  			$("#art_warn").text("");
	  		break;
	  		case "cancel-author":
	  			if($("#new-author").parent().css("display") == "block"){
	  				$("#new-author").parent().hide();
	  			}
	  			if($("#update-author").parent().css("display") == "block"){
					$(".choosen").removeClass("choosen");
					$("#authors-data").removeClass("redacting");
					$("#authors-data").addClass("table-hover");
					$("#auth_search").removeAttr("disabled");
	  			}
	  			$("#auth_search").removeAttr("disabled");
	  			$("#redact-author-form").slideUp();
	  			$("#add-aut-but").fadeIn();
	  			$("#auth_warn").text("");
	  		break;
	  		case "cancel-journal":
	  			if($("#new_journal_but").parent().css("display") == "block"){
	  				$("#vew_journal_by_class").prop("disabled",false);
  					$("#new_journal_but").parent().hide();
  					$("#add-jour-but").fadeIn();
  					$("#add-journal-form").slideUp();
	  			}
	  			if($("#update_jour_but").parent().css("display") == "block"){
	  				$(".choosen").removeClass("choosen");
  					$("#journals-data").removeClass("redacting");
  					$("#journals-data").addClass("table-hover");
  					$("#vew_journal_by_class").removeAttr("disabled");
  					$("#vew_journal_year").removeAttr("disabled");
  					$("#update_jour_but").parent().hide();
	  			}
	  			$("#add-journal-form").slideUp();
	  			$("#add-jour-but").fadeIn();
	  			$("#jour_warn").text("");

	  		break;
	  	}
	  }


	  /*Валидация формы статьи*/
	var validate_form = function(form,p_worn){
		var form_inputs = $(form).find('input'),
			form_selects = $(form).find('select'),
			validation_result = {
				"empty_inputs": false,
				"empty_select": false,
				"empty_author": true,
				"wrong_numb_type":false,
				"wrong_text_type":false,
				"error":false
			},
			warn_text = "";
		$(p_worn).hide();
		$(p_worn).text("");
		for(var i = 0;i < form_inputs.length; i++){
			var curr_inp = form_inputs.get(i);
			if(($(curr_inp).val() == "") && (validation_result['empty_inputs']) == false){
				validation_result['empty_inputs'] = true;
				validation_result['error'] = true;
				warn_text = warn_text + "Имеются незаполненные поля<br>";
			}else 
			if(($(curr_inp).prop("name") == "pub_year")||($(curr_inp).prop("name") == "journal_number")){
				if(isNaN($(curr_inp).val()) && (validation_result['wrong_numb_type'] == false)){
					validation_result['error'] = true;
					validation_result['wrong_numb_type'] == true;
					warn_text = warn_text + "Имеются некорректные числовые поля<br>";
				}
			}else if((/\d/.test($(curr_inp).val())) && 
				(validation_result['wrong_text_type'] == false) && 
				($(curr_inp).prop('name') != "journal_pages") && 
				($(curr_inp).prop('name') != "pages") &&
				($(curr_inp).prop('type') != "hidden")){
				validation_result['wrong_text_type'] == true;
				validation_result['error'] = true;	
				warn_text = warn_text + "Имеются некорректные текстовые поля<br>";
			}

			if($(curr_inp).prop("name").indexOf("auth_id") == 0){
				validation_result['empty_author'] = false;
			}
		}
		if(validation_result['empty_author'] == true){
			validation_result['error'] = true;	
			warn_text = warn_text + "Не выбрано ни одного автора<br>";
		}
		for(var i = 0; i < form_selects.length;i++){
			var curr_sel = form_selects.get(i),
				sel_val = curr_sel.options[curr_sel.selectedIndex].value;
			if((sel_val == "Журнал") && ($("#update-article").parent().css("display") != "block")){
				warn_text = warn_text + "Не выбран журнал<br>";
				validation_result['error'] = true;
			}
			
		}
		if(validation_result['error'] == true){
			$(p_worn).append(warn_text);
			$(p_worn).fadeIn();
		}
		return validation_result;

	}


	/*Обработка клика на строку таблицы*/
	$(document).on("click", "tr", function(event){
		if(!$(event.target).hasClass("status")&&!($(event.target).parent().hasClass('table-head'))&&!($(event.target).parent().parent().parent().hasClass("redacting")) && !$(event.target).hasClass("sortable")){
			if($(this).hasClass("choosen") ){
				$(this).removeClass("choosen");
				if($(event.target).closest("table").is("#article-data")){
					$("#redact-article").fadeOut();
					$("#delete-article").fadeOut();
					$("#redact-article-form").fadeOut();
					$("#show_art_data").fadeOut();
					//$("#redact-article-form").hide();	
				}else if($(event.target).closest("table").is("#authors-data")) {
					$("#redact-authors").fadeOut();
					$("#delete-author").fadeOut();
				}else if($(event.target).closest("table").is("#journals-data")){
					$("#redact-journal").fadeOut();
					$("#delete-journal").fadeOut();
				}else if($(event.target).closest("table").is("#authors-search")){
					$("#add_new_auth").fadeOut();
				}
			}else{
				$("tr").removeClass("choosen");
				$(this).addClass("choosen");
				if($(event.target).closest("table").is("#article-data")){
					$("#redact-article").fadeIn();
					$("#delete-article").fadeIn();
					$("#show_art_data").fadeIn();
				}else if($(event.target).closest("table").is("#authors-data")) {
					$("#redact-authors").fadeIn();
					$("#delete-author").fadeIn();
				}else if($(event.target).closest("table").is("#journals-data")) {
					$("#redact-journal").fadeIn();
					$("#delete-journal").fadeIn();
				}else if($(event.target).closest("table").is("#authors-search")){
					$("#add_new_auth").fadeIn();
				}
			}
		}
	});

	/*Выбор журнала (Проверить выборку при отсутствии статей в журнале)*/ 
	$("#journals").change(function() {
		//Серия D №2 2016-04-13
		var jour_name = $(this).val();
		$("#show_art_data").fadeOut();
		$("#add-art-form").fadeOut();
		$("#send-article-data").hide();
		$("#message_p").fadeOut();
		$("#article-data").attr("hidden",true);
		show_journal_articles(jour_name);
	});

	/*Выбор типа журнала для публикации статьи*/
	$("#choose-journal-class").change(function(event){
		var data_send = {
			"journal_class": $(this).val()
		};
		if($(event.target).parent().parent().prop("id") == "add-art-form"){
			$.ajax({
				url: 'script.php?req_type=ajax_ch_art_class',
				type: 'POST',
				data: 'jsonData=' + $.toJSON(data_send),
				success: function(data){
					var resp = JSON.parse(data);
					console.log(resp);
					show_select(resp,$("#avail_journals")[0],$("#add-art-avail-auth")[0]);
					if(resp['journals'].length>0){
						$("#avail_journals").removeAttr("disabled");
						$("#add-art-avail-auth").removeAttr("disabled");
  					//$("#add_author").removeAttr("disabled");
  					$("#add_author").removeClass("hidden");
  					$("#add_author").hide();
  					$("#add_author").fadeIn(200);
  					$("#choose_journ_p").find("span").remove();
  					$("#art-not-exist").fadeOut(200,function(){
  						$("#art-not-exist").text("");
  					});
  					
  				}else{
  					$("#avail_journals").attr("disabled",true);
  					$("#add-art-avail-auth").attr("disabled",true);
  					//$("#choose-journal-class").after("<span>&nbsp&nbsp<nbsp></nbsp>Журналы данного класса ещё не опубликованы</span>");
  					$("#art-not-exist").text("Журналы не опубликованы");
  					$("#art-not-exist").fadeIn();
  				}

  			}
  		});
		}

	});


	/*Обработка нажатия маркера удаления автора статьи*/
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
	$(document).on("click", "#update-article", function(){
		var redact_form = $("#add-art-form"),
			redact_inputs = redact_form.find("input"),
			old_auth_rows = $("#auth-redact-data").find("tr"),
			auth_present = true,
			warn_text = "",
			remove_auth = $("#auth-redact-data").find(".blocked-art").parent().next(),
			add_auth = $(".author_selectors").find("input");
			j = 0,
				data_send = {
				"art_name" : $(redact_inputs.get(0)).val(),
				"pages" : $(redact_inputs.get(1)).val(),
				"new_authors" : [],
				"dell_authors" : []
			},
			validation_res = "";
		$("#art_warn").hide();
		validation_res = validate_form("#add-art-form","#art_warn");
		for(var i = 0;i < remove_auth.length; i++){
			data_send["dell_authors"][i] = $(remove_auth.get(i)).val();
		}
		for(var i = 0;i < add_auth.length;i++){
			if($(add_auth.get(i)).val() != null){
				data_send["new_authors"][i] = $(add_auth.get(i)).val();
			}
		}
		if((remove_auth.length == old_auth_rows.length) && (data_send["new_authors"].length == 0)){
			auth_present = false;
			warn_text = $("#art_warn").html();
			console.log(warn_text);
			warn_text = warn_text + "Не выбрано ни одного автора<br>";
			console.log(warn_text);
			$("#art_warn").show();
			$("#art_warn").text("");
			$("#art_warn").append(warn_text);
			console.log("out val");
	  	}
		if((validation_res['error'] == false) && (auth_present == true)){
			$.ajax({
				url: 'script.php?req_type=ajax_update_art',
				type: 'POST',
				data: 'jsonData=' + $.toJSON(data_send),
				success: function(data) {
					console.log("response"+data);
					show_journal_articles($('#journals').val());
				}
			});
			$("#add-art-form").slideUp(400, function(){
				$("#auth-redact-data > tbody > tr").remove();
				var old_selects = $(".author_selectors").find("p"),
					select_count = old_selects.length;
				for(var i = 0;i<select_count;i++){
					$(old_selects.get(i)).parent().remove();
				}
				hide_form("cancel-article");
			});
		}

		return false;
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
				//console.log(resp);
				if(resp == null){
					$("#message_p").text("Журналы данной серии ещё не опубликованы");
					$("#message_p").hide();
					$("#message_p").fadeIn(500);
					$($("#journals").find("option").get(0)).prop("selected",true);
					$("#journals").attr("disabled",true);
					$("#article-data").fadeOut();
				}else{
					$("#journals").removeAttr("disabled");
					//console.log($("#journals").children().length);
					$("#journals").children().slice(1).remove();
					for(var i = 0;i<resp.length;i++){
						new_elem = $("<option>Серия "+resp[i]['class']+" №"+resp[i]['number']+" "+resp[i]['pub_year']+"</option>");
						$("#journals").append(new_elem);
					}
				}
			}

		});
	});

	/*Обработка кнопки редактирования автора*/
	$("#redact-authors").on("click", function(){
		$("#redact-author-form").removeClass("hidden");
		$("#redact-author-form").hide();
		$("#redact-author-form").slideDown();
		$("#update-author").parent().show();
		$("#redact-authors").fadeOut();
		$("#delete-author").fadeOut();
		$("#add-aut-but").fadeOut();
		$("#auth_search").prop("disabled",true);
		var choosen_row = $(".choosen"),
		row_cells = choosen_row.children(),
		data_send = {
			"aut_name": $(row_cells.get(0)).text(),
		},
		redact_inputs = $("#redact-author-form").find('input'),
		redact_selector = $("#redact-author-form").find('select')[0],
		select_options = $(redact_selector).find('option');
		//console.log(select_options);
		//choosen_row.parent().addClass("redacting-row");
		choosen_row.parent().parent().removeClass("table-hover");
		choosen_row.parent().parent().addClass("redacting");
		$.ajax({
			url: 'script.php?req_type=ajax_get_aut',
			type: 'POST',
			data: 'jsonData=' + $.toJSON(data_send),
			success : function(data) {
				var resp = JSON.parse(data);
				console.log(resp);
				for(var i = 0; i < select_options.length; i++){
					//console.log($(select_options[i]).text());
					if($(select_options[i]).text() == resp['class']){
						$(select_options[i]).attr("selected",true);
					}
				}
				$(redact_inputs[0]).val(resp['name']);
				$(redact_inputs[1]).val(resp['dc_degree']);
				$(redact_inputs[2]).val(resp['organisation']);
			}
		});

	});

	/*Обработка сохранения изменений автора*/

	$("#update-author").on("click",function(){
		var choosen_row = $(".choosen");
		var update_form = $("#redact-author-form"),
		update_inputs = update_form.find("input"),
		update_auth = $("#auth_s").val(),
		j = 0,
		data_send = {
			"aut_name" : $(update_inputs.get(0)).val(),
			"dc_degree" : $(update_inputs.get(1)).val(),
			"organisation" : $(update_inputs.get(2)).val(),
			"auth_class" : update_auth
		},
		validation_res = validate_form("#redact-author-form","#auth_warn");
		if(validation_res['error'] == false){
			$.ajax({
			url: 'script.php?req_type=ajax_update_aut',
			type: 'POST',
			data: 'jsonData=' + $.toJSON(data_send),
			success : function(data) {
				var resp = JSON.parse(data);
				console.log(resp);
				show_table("#authors-data",resp,null);
			}

			});
			hide_form("cancel-author");
		}
		return false;
	});



	/*Выбор серии журнала для вывода автора*/
	$("#vew_author_by_class").on("change", function(){
		var auth_table = $("#authors-data"),
		data_send = {
			"class": $(this).val()
		};
		$.ajax({
			url: 'script.php?req_type=ajax_ch_aut_class',
			type: 'POST',
			data: 'jsonData=' + $.toJSON(data_send),
			success: function(data){
				var resp = JSON.parse(data);
				show_table("#authors-data",resp,null);
			}
		});
	});

	/*Обработка кнопки удаления статьи*/

	$("#delete-article").on("click",function(){
		var choosen_row = $("#article-data").find(".choosen")[0],
		art_name = $(choosen_row).find('td')[1],
		art_class = $("#vew_journ_class").val(),
		jour_name = $("#journals").val(),
		data_send = {
			"art_name": $(art_name).text(),
			"art_class": art_class
		};
		console.log(jour_name);
		$.ajax({
			url: 'script.php?req_type=ajax_del_art',
			type: 'POST',
			data: 'jsonData=' + $.toJSON(data_send),
			success: function(data){
  					//var resp = JSON.parse(data);
  					//show_table("#authors-data",resp,null);
  					show_journal_articles(jour_name);
  				}
  			});

	});

	/*Обработка кнопки удаления автора */

	$("#delete-author").on("click",function(){
		var choosen_row = $("#authors-data").find(".choosen")[0],
		auth_name = $(choosen_row).find("td")[0],
		auth_class = $("#vew_author_by_class").val();
		data_send = {
			"author_name": $(auth_name).text(),
			"author_class": auth_class
		};
		$("#delete-author").fadeOut();
		$("#redact-authors").fadeOut();
		console.log(data_send);
		$.ajax({
			url: 'script.php?req_type=ajax_del_aut',
			type: 'POST',
			data: 'jsonData=' + $.toJSON(data_send),
			success: function(data){
				var resp = JSON.parse(data);
				console.log(resp);
  					//show_table("#authors-data",resp,null);
  					show_table("#authors-data",resp,null);
  				}
  			});
	});

	/*Обработка кнопки удаления журнала*/

	$("#delete-journal").on("click",function(){
		var choosen_row = $("#journals-data").find(".choosen")[0],
		row_cells = $(choosen_row).children(),
		data_send = {
			"jour_class" : $(row_cells[1]).text(),
			"jour_numb" : $(row_cells[2]).text(),
			"jour_year" : $(row_cells[3]).text()
		};
			//console.log(data_send);
			$.ajax({
				url: 'script.php?req_type=ajax_del_jour',
				type: 'POST',
				data: 'jsonData=' + $.toJSON(data_send),
				success: function(data){
					var resp = JSON.parse(data);
					console.log(resp);
  					//show_table("#authors-data",resp,null);
  					show_table("#journals-data",resp,null);
  					$("#delete-journal, #redact-journal").fadeOut();
  				}
  			});

		});

	/*Обработка кнопки получения данных о статье*/
	$("#show_art_data").on("click",function(){
		var choosen_row = $("table").find(".choosen")[0],
		row_cells = $(choosen_row).children(),
		art_authors = $(row_cells[0]).text(),
		art_name = $(row_cells[1]).text(),
		art_pages = $(row_cells[2]).text(),
		art_journal = $(row_cells[3]).text(),
		art_text = "",
		art_p = document.getElementById('article_str'),
		article_data = "",
		words = [],
		words = art_authors.split(" ");
		console.log(art_authors);
		$(art_p).text("");
		art_authors = "";
		art_authors = split_by_coma(words);
		article_data = "Авторы:"+art_authors+"Cтатья:"+art_name+" Журнал:"+art_journal+" (стр."+art_pages+")";
		$(art_p).hide();
		$(art_p).text(article_data);
		$(art_p).prop("id","article_str");
		$("#article_str").fadeIn();
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
	$("#add_author_new_art, #add_author_red_art").on("click", function(){
  		var butt_id = "#" + $(event.target).prop("id");
  		add_auth_select(butt_id);
  		return false;
  	});

	/*Обработка кнопки открытия формы для добавления автора */
	$("#add-aut-but").on("click",function(){
		$("#authors-data").fadeOut(200, function(){
			$("#redact-author-form").slideDown(200);
		});
		$("#add-aut-but ,#redact-authors,#delete-author").fadeOut();
		$("#new-author").parent().show();
		$("#auth_search").prop("disabled",true);
		$("#update-author").parent().hide();
	});

	



	/*Обработка кнопки добавления автора*/
	$("#new-author").on("click",function(){
		var update_form = $("#redact-author-form"),
			update_inputs = update_form.find("input"),
			update_select = update_form.find("select")[0],
			j = 0,
			data_send = {
				"aut_name" : $(update_inputs.get(0)).val(),
				"dc_degree" : $(update_inputs.get(1)).val(),
				"organisation" : $(update_inputs.get(2)).val()
			},
			validation_res = validate_form("#redact-author-form","#auth_warn");


		//console.log(validate_form("#redact-author-form"));
		if(validation_res['error'] == false){
				$.ajax({
				url: 'script.php?req_type=ajax_add_aut',
				type: 'POST',
				data: 'jsonData=' + $.toJSON(data_send),
				success : function(data) {
					$(s_auth_res).text("Автор добавлен");				
					hide_form("cancel-author");
				}
			});
		}
		/**/
	});


	/*Обработка кнопки открытия формы добавления статьи
	  Обработка кнопки открытия формы редактирования статьи
	  */
	  $("#add-art-but, #redact-article").on("click",function(event){
	  	if($(event.target).prop("id") == "add-art-but"){
	  		$("#add-art-but, #redact-article,#delete-article, #article-data").fadeOut(200, function(){
	  			$("#send-article-data").parent().show();
	  			$("#add-art-form").slideDown(200);
	  			$("#message_p").text("Добавление статьи");
	  		});
	  	}else if($(event.target).prop("id") == "redact-article"){
	  		var choosen_row = $(".choosen"),
	  		row_cells = choosen_row.children(),
	  		data_send = {
	  			"art_name": $(row_cells.get(1)).text(),
	  		};
	  		$(".author_selectors").show();
	  		$("#journals").prop("disabled",true);
	  		$("#vew_journ_class").prop("disabled",true);
	  		choosen_row.parent().parent().removeClass("table-hover");
	  		choosen_row.parent().parent().addClass("redacting");
	  		$("#update-article").parent().show();
	  		console.log("redacting");
	  		$.ajax({
	  			url: 'script.php?req_type=ajax_get_art',
	  			type: 'POST',
	  			data: 'jsonData=' + $.toJSON(data_send),
	  			success: function(data) {
					//console.log(data);
					var resp = JSON.parse(data),
					redact_form = $("#add-art-form"),
					redact_inputs = redact_form.find("input"),
					redact_table = redact_form.find("table");
					$(redact_form.find("select")[0]).prop("disabled",true);
					console.log(resp);
					redact_form.removeClass("hidden");
					redact_table.show();
					$("#redact-article").hide();
					$("#delete-article").hide();
					$("#add-art-but").hide();
					redact_form.hide();
					redact_form.show("slow");
					//console.log(redact_inputs.get(0));
					$(redact_inputs.get(0)).val(resp[0]["art_name"]);
					$(redact_inputs.get(1)).val(resp[0]["art_pages"]);
					for (var i = 0; i < resp[0]["authors"].length; i++) {
						var new_elem = $("<tr></tr>"),
							new_inp = document.createElement('input');
						$(new_inp).attr({
		  					"type":"hidden",
		  					"name":"auth_id"+i,
		  					"value": resp[0]["authors"][i]["author_id"]
		  				});
						redact_table.append(new_elem);
						new_elem.append("<td>"+resp[0]["authors"][i]["name"]+"</td>");
						new_elem.append("<td><div class='status unblocked-art'></div></td>");
						new_elem.append(new_inp);
					}

				} 
			});
	  	}
	  });


  	/*
  	  Обработка кнопки добавления журнала
	  Обработка кнопки редактирования журнала
	  */

	  $("#add-jour-but, #redact-journal").on("click", function(event){
	  	$("#vew_journal_year").prop("disabled",true);
	  	$("#vew_journal_by_class").prop("disabled",true);
	  	if($(event.target).prop("id") == "add-jour-but"){
	  		$("#new_journal_but").parent().show();
	  		$("#redact-journal, #delete-journal, #journals-data, #add-jour-but").fadeOut(200, function(){
	  			$("#add-journal-form").slideDown(200);
	  		});
	  	}else if($(event.target).prop("id") == "redact-journal"){
	  		var redact_inputs = "",
	  		choosen_row = $("#journals-data").find(".choosen")[0];
	  		$("#update_jour_but").parent().show();
	  		$($("#add-journal-form").find("select")[0]).prop("disabled",true);
	  		var choosen_row = $("#journals-data").find(".choosen")[0],
	  		row_cells = $(choosen_row).children(),
	  		data_send = {
	  			"jour_class" : $(row_cells[1]).text(),
	  			"jour_numb" : $(row_cells[2]).text(),
	  			"jour_year" : $(row_cells[3]).text()
	  		};
			//console.log(data_send);
			$.ajax({
				url: 'script.php?req_type=ajax_get_jour',
				type: 'POST',
				data: 'jsonData=' + $.toJSON(data_send),
				success: function(data){
					var resp = JSON.parse(data);
					$(choosen_row).parent().parent().removeClass("table-hover");
					$(choosen_row).parent().parent().addClass("redacting");
					$("#redact-journal, #delete-journal, #add-jour-but").fadeOut(200, function(){
						$("#add-journal-form").slideDown();
					});
					redact_inputs = $("#add-journal-form").find('input');
					//console.log(resp);
					$(redact_inputs[0]).val(resp['name']);
					$(redact_inputs[1]).val(resp['pub_year']);
					$(redact_inputs[2]).val(resp['number']);
					$(redact_inputs[3]).val(resp['pages']);
  					//show_table("#authors-data",resp,null);
  					//show_table("#journals-data",resp,null);
  				}
  			});

		}
		//document.getElementById('vew_journal_by_class').options[0].selected=true;
	});

	  /*Обработка кнопки отправки отредактированного журнала */
	  $("#update_jour_but").on("click",function(){
	  	//$("#add-journal-form").slideUp();
  		var update_form = $("#add-journal-form"),
  			update_inputs = update_form.find("input"),
  			j = 0,
  			data_send = {
  				"jour_name" : $(update_inputs.get(0)).val(),
  				"jour_year" : $(update_inputs.get(1)).val(),
  				"jour_numb" : $(update_inputs.get(2)).val(),
  				"jour_pages": $(update_inputs.get(3)).val()
  			},
  			validation_res = validate_form("#add-journal-form","#jour_warn");
  		//console.log(data_send);
		if(validation_res['error'] == false){
			hide_form("cancel-journal");
  				$.ajax({
  				url: 'script.php?req_type=ajax_update_jour',
  				type: 'POST',
  				data: 'jsonData=' + $.toJSON(data_send),
  				success : function(data) {
  					var resp = JSON.parse(data);
  					//console.log(resp);
  					show_table("#journals-data",resp,null);
  				}

  				});
  			}
		//console.log(data_send);
		return false;
	});

	  /*Обработка кнопки создания журнала*/
	  $("#new_journal_but").on("click",function(){
  		//$("#add-journal-form").slideDown();
  		var form_inputs = $("#add-journal-form").find("input"),
  			form_select = $("#add-journal-form").find("select")[0],
  			data_send = {
  				"jour_name" : $(form_inputs.get(0)).val(),
  				"jour_class" : $(form_select).val(),
  				"jour_year" : $(form_inputs.get(1)).val(),
  				"jour_numb" : $(form_inputs.get(2)).val(),
  				"jour_pages" : $(form_inputs.get(3)).val(),
  			},
  			validation_res = validate_form("#add-journal-form","#jour_warn");
		if(validation_res['error'] == false){
  			$.ajax({
  				url: 'script.php?req_type=ajax_add_jour',
  				type: 'POST',
  				data: 'jsonData=' + $.toJSON(data_send),
  				success : function(data) {
  					var resp = JSON.parse(data);
  					hide_form("cancel-journal");
  					show_table("#journals-data",resp,null);
  				}
  			});
  		}

  	});

	  /*Обработка выблора класса журнала для просмотра*/
	  $("#vew_journal_by_class").on("change", function(event){
	  	var jour_class = $(event.target).val(),	
	  	data_send = {
	  		"jour_class" : jour_class
	  	};
	  	//console.log(data_send);
	  	$.ajax({
	  		url: 'script.php?req_type=ajax_ch_jour_class',
	  		type: 'POST',
	  		data: 'jsonData=' + $.toJSON(data_send),
	  		success : function(data) {
	  			var resp = JSON.parse(data);
	  			//console.log(resp);
	  			if(resp.length != 0){
	  				$("#journals-res").text("Выберите год");
	  				$("#journals-res").hide();
	  				$("#journals-res").fadeIn(200);
	  				$("#vew_journal_year").removeAttr("disabled");
	  				$("#vew_journal_year").children().slice(1).remove();
	  				$("#journals").children().slice(1).remove();
	  				for(var i = 0; i < resp.length; i++){
	  					year = $('<option>'+resp[i]+'</option>');
	  					$("#vew_journal_year").append(year);
	  				}
	  			}else{
	  				$("#journals-res").text("Журналы не опубликованы");
	  				$("#journals-res").hide();
	  				$("#journals-res").fadeIn(200);
	  			}
	  		}
	  	});


	  });

	  /*Просмотр журналов определённого года*/
	  $("#vew_journal_year").on("change",function(event){
	  	var data_send = {
	  		"class" : $("#vew_journal_by_class").val(),
	  		"year" : $(event.target).val()
	  	};

	  	$.ajax({
	  		url: 'script.php?req_type=ajax_ch_jour_year',
	  		type: 'POST',
	  		data: 'jsonData=' + $.toJSON(data_send),
	  		success : function(data) {
	  			var resp = JSON.parse(data);
  				//console.log(resp);
  				show_table("#journals-data",resp,null);
  			}
  		});
	  });


	  /*Обработка кнопки поиска автора*/
	  $("#new_auth_search, #auth_search").on("click",function(event){
	 	//console.log($("#n_auth_s").val());
	 	var search_word = "",
	 		search_but_id = $(event.target).prop("id"),
	 		res_table = "",
	 		h_resp = "",
	 		data_send = {
	 			"name": ""
	 		};
	 	if(search_but_id == "auth_search"){
	 		res_table = "#authors-data";
	 		h_resp = "#s_auth_res";
	 		data_send['name'] =  $("#auth_s").val();
	 	}else if(search_but_id == "new_auth_search"){
	 		res_table = "#authors-search";
	 		data_send['name'] =  $("#n_auth_s").val();
	 		h_resp = "#n_auth_s";
	 	}
	 	//#authors-data
	 	$.ajax({
	 		url: 'script.php?req_type=ajax_find_auth',
	 		type: 'POST',
	 		data: 'jsonData=' + $.toJSON(data_send),
	 		success : function(data) {
	 			var resp = JSON.parse(data);
  				//console.log(resp);
  				if(resp != "not_found"){
  					$(h_resp).text("Результат");
  					show_table(res_table,resp,null);
  				}else if(resp == "not_found"){
  					$(h_resp).text("Поиск не дал результатов");
  					$(res_table).fadeOut();
  				}
  			}
  		});
	 });

	  /*Обработка кнопки добавления автора*/
	  $("#add_new_auth").on("click", function(){
	  	var choosen_row = $("#authors-search").find(".choosen")[0],
	  	choosen_cells = $(choosen_row).children();
	  	name = $(choosen_cells.get(0)).text(),
	  	auth_id = $(choosen_cells.get(3)).val();
	  	add_auth_select("#add_author_new_art",name,auth_id);
	  	$(choosen_row).remove();
	  	$("#add_new_auth").fadeOut();
	  });


	  /*Обработка кнопки добавления статьи*/
	  $("#send-article-data").on("click",function(){
	  	var art_form = $("#add-art-form"),
	  	form_inputs = $(art_form).find('input'),
	  	form_selects = $(art_form).find('select'),
	  	data_send = {
	  		"art_name": $(form_inputs.get(0)).val(),
	  		"art_pages": $(form_inputs.get(1)).val(),
	  		"art_journal": $(form_selects.get(1)).val(),
	  		"art_class": $(form_selects.get(0)).val(),
	  		"art_authors":[]
	  	},
  		validation_res = validate_form("#add-art-form","#art_warn");
	  	for(var i = 2; i < form_inputs.length; i++){
	  		data_send['art_authors'][i-2] = $(form_inputs.get(i)).val();
	  	}
	  	//console.log(data_send);
	  	if(validation_res['error'] == false){
	  		$.ajax({
	  			url: 'script.php?req_type=ajax_add_art',
	  			type: 'POST',
	  			data: 'jsonData=' + $.toJSON(data_send),
	  			success : function(data) {
	  				//$("#add-art-form").slideUp();
	  				$("#message_p").text("Статья добавлена");
	  				$("#send-article-data").parent().attr("hidden",true);
	  				hide_form("cancel-article");
  				}
  			});
	  	}

	  });

	  /*Обработка кнопки отмены*/
	  $("#cancel-article, #cancel-author, #cancel-journal").on("click",function(event){
	  	var butt_id = $(event.target).prop("id");
	  	hide_form(butt_id);
	  });

	  
	  /*Сортировка таблица*/

	  $(".sortable").on("click",function(event){
	  	var head_rows = $(event.target).parent().parent().find(".sortable"),
	  		sort_column = head_rows.index(event.target),
	  		table_rows = $(event.target).parent().parent().parent().find("tr"),
	  		head_row = table_rows[0],
	  		sort_type = 0,
	  		sort_func = function(a,b){
	  			var sort_cell_A = $($(a).find("td")[sort_column]).text(),
	  				sort_cell_B = $($(b).find("td")[sort_column]).text();
	  			if(isNaN(Number(sort_cell_A)) == true){
		  			if((sort_type == 1)){
			  			if (sort_cell_A > sort_cell_B){return 1;} 
		  				if (sort_cell_A < sort_cell_B){return -1;}
		  			}else if(sort_type == 0){
		  				if (sort_cell_A > sort_cell_B){return -1;} 
	  					if (sort_cell_A < sort_cell_B){return 1;}
		  			}
	  			}else{
	  				if((sort_type == 1)){
			  			if (parseInt(sort_cell_A,10) > parseInt(sort_cell_B,10)){return 1;} 
		  				if (parseInt(sort_cell_A,10) < parseInt(sort_cell_B,10)){return -1;}
		  			}else if(sort_type == 0){
		  				if (parseInt(sort_cell_A,10) > parseInt(sort_cell_B,10)){return -1;} 
	  					if (parseInt(sort_cell_A,10) < parseInt(sort_cell_B,10)){return 1;}
		  			}

	  			}

	  		};

	  	table_rows.splice(0,1);	
	  	if($(event.target).hasClass("sort-up") == true){
	  		$(event.target).removeClass("sort-up").addClass("sort-down");
	  		sort_type = 0;
	  	}else if(($(event.target).hasClass("sort-down") == true)||$(event.target).hasClass("not-sorted") == true){
	  		$(event.target).parent().parent().find(".sortable").removeClass("sort-up").removeClass("sort-down").addClass("not-sorted");
	  		$(event.target).removeClass("sort-down").removeClass("not-sorted").addClass("sort-up");
	  		sort_type = 1;
	  	}
	  	var old_rows = table_rows;
	  	table_rows.sort(sort_func);
	  	for(var i = 0;i < table_rows.length;i++){
	  		$(head_row).after(table_rows[i]);
	  	}
	  })



});
