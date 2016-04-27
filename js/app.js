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

  /*Обработка клика на строку таблицы*/
	$(document).on("click", "tr", function(){
		if($(this).hasClass("choosen")){
			$(this).removeClass("choosen");
			$("#redact-authors").fadeOut();
		}else{
			$("tr").removeClass("choosen");
			$(this).addClass("choosen");
			$("#redact-authors").fadeIn();
			$("#redact-authors").css("display","inline");
		}
	});

	/*Выбор журнала*/
	$("#choose-jour").change(function() {
		//Серия D №2 2016-04-13
		var jour_name = $(this).val(),
			batch = jour_name.substring(0,8),
			reg = /№[\d]{1,2}/,
			number = jour_name.match(reg)[0].substring(1),
			jour_data = {
				"jour_batch" : batch,
				"jour_numb" : number
			};
		//console.log("numb: "+number);
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
		var parrent_row = $(this).parent().parent(),
			row_cells = parrent_row.children(),
			test ="",
			data_send = {
				"art_name": $(row_cells.get(1)).text(),
				"art_stat": art_stat
			};
		//console.log("JSON"+$.toJSON(data_send));
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

  document.getElementById("re_pass").addEventListener("input", validatePassword);
	
});
