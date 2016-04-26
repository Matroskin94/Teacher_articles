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

  $("#send_user-data").click(function(event) {
  	check_passwords($(this),event);
  });
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

	$("tr").click(function(){
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

	$("#choose-jour").change(function() {
		//console.log("opt-clicked");
		//Серия D №2 2016-04-13
		var jour_name = $(this).val(),
			batch = jour_name.substring(0,8),
			reg = /№[\d]{1,2}/,
			number = jour_name.match(reg)[0].substring(1),
			jour_data = {
				"jour_batch" : batch,
				"jour_numb" : number
			};
			//data_to_serv = $.toJSON(jour_data);
		console.log("numb: "+number);
		$.ajax({
			url: 'script.php?req_type=ajax',
			type: 'POST',
			data: 'jsonData=' + $.toJSON(jour_data),
			success:function(data) {
				var resp = JSON.parse(data);
				//alert("data sent");
				console.log("size:"+resp.length);
				console.log(resp[0]);
				console.log(resp[1]);
			}
		});
	});

/*$(document).on('click','option', function(){
	alert('нажатие!');
});*/


  document.getElementById("re_pass").addEventListener("input", validatePassword);
	
});
