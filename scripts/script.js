    // from http://stackoverflow.com/questions/5129512/jquery-toggle-between-divs-and-subsequently-hide-active-div
   // showContent($('#coal:not(:visible)'));
window.onload = function(){
	$('#JS-login').bind("click",function(){
		//errorChecking
		if($("#JS-user").val().length==0){
			$("#JS-user-error").css("display","inline");
			return false;
		}
		else{
			$("#JS-user-error").css("display","none");
		}
		
		if($("#JS-password").val().length==0){
			$("#JS-password-error").css("display","inline");
			return false;
		}
		else{
			$("#JS-password-error").css("display","none");
		}

		tpht.easyXHR("post","backend/login.php","username="+$("#JS-user").val()+"&password="+$("#JS-password").val(),function(input){
			console.log(input);
			input = input.substring(input.split("}")[0].length+1,input.length);
			var returned = JSON.parse(input);
			console.log(returned);
			if(returned.status == true){
				$("#modalDialogue").css("display","none");
			}
			else{
				$("#JS-credentials-error").css("display","inline");
				return false;
			}			
		})
	});

	$('#signUp #JS-signup').bind("click",function(){
		//errorChecking
		if($("#JS-user").val().length==0){
			$("#JS-user-error").css("display","inline");
			return false;
		}
		else{
			$("#JS-user-error").css("display","none");
		}
		
		if($("#JS-password").val().length==0){
			$("#JS-password-error").css("display","inline");
			return false;
		}
		else{
			$("#JS-password-error").css("display","none");
		}

		if (/[A-Za-z0-9._%+-]+@[A-Za-z0-9.-]+.[A-Za-z]{2,4}/.test($("#JS-email").val()) == false || $("#JS-email").val().length == 0) {
			$("#JS-email-error").css("display","inline");
			return false;
		} else{
			$("#JS-email-error").css("display","none");
		}

		var email_checked = false;

		if($("#JS-email-checkin").is(':checked')) {
			email_checked = true;
		}

		tpht.easyXHR("post","backend/signup.php","username="+$("#JS-user").val()+"&password="+$("#JS-password").val()+"&email="+$("#JS-email").val()+"&email_bool="+email_checked,function(input){
			console.log(input);
			var returned = JSON.parse(input);
			console.log(returned);
			if(returned.status == true){
				window.location = "index.html";
			}
			else{
				alert("Error: "+returned.response);
				return false;
			}			
		})
	});

    $('#coal, #axe, #blackberry, #cake').hide();
    showContent($('#axe:not(:visible)'));
$('.items').click(function() {
    var id = $(this).data("whatisit");
    var $content = $('#' + id + ':not(:visible)');
    
    if ($('.current').length === 0) {
        showContent($content)
    }
    else {
        $('.current').fadeOut(50, function() {
            showContent($content) 
        });
    }
});

function showContent(content) {
    content.fadeIn(50);
    $('.current').removeClass('current');
    content.addClass('current');
}
console.log("logging");


// tpht.renderModal({
// 		"type" : "display"
// 		});

}