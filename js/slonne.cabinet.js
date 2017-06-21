var Cabinet={}
Cabinet.ajaxPath='/modules/cabinet/cabinet.php'


Cabinet.settings={}




Cabinet.checkCabinetData=function(isNew)
{
	var formId = 'cabinet-reg-form'
	//	стираем ерроры
	$('#'+formId+' input').each(function(n,element){
		$(element).removeClass('error')
	});
	$('#'+formId+' .info').html('')
	//$('#cabinet_edit_form .loading').css('display', 'none')
	//loading(0, ''+formId+' .loading', 'fast')
	//loading(0, ''+formId+' .loading', 'fast')
	//return true
	
	var problems = [] 
	var err = _CONST['ERROR Не все обязательные поля заполнены корректно']
	
	if($('#surname').val() == '')
		problems.push('surname')
		
	if($('#name').val() == '')
		problems.push('name')
		
	if($('#email').val() == '')
		problems.push('email')
		
	if($('#phone').val() == '')
		problems.push('phone')
		
	if(isNew)
	{
		var pass=$('#pass').val()
		var pass2=$('#pass2').val()
		
		if(pass == '')
			problems.push('pass');
		if(pass2 == '')
			problems.push('pass2');
		
		if(pass != pass2 != '')
		{
			problems.push('pass');
			problems.push('pass2');
			if(problems.length == 2)
				err = 'Введённые пароли не совпадают!'
		}
		
		if($('#captcha').val() == '')
			problems.push('captcha')
		if(problems.length == 1)
			err = 'Пожалуйста, введите код с картинки!	'
	}
	
	if(problems.length > 0)
	{
		var msg=''
		for(var i in problems)
		{
			//msg+='<br>- '+problems[i]
			$('#'+problems[i]).addClass('error')
		}
			
		showError(err+msg, ''+formId+' .info')
		//highlight(problems)
	}
	else
	{
		/*if(!$('#agree').prop('checked'))
		{
			$('#agree').addClass('error')
			showError(_CONST['ERROR галочка Я ПОДТВЕРЖДАЮ'], 'cabinet_edit_form .info')
			return false
		}*/
		
		loading(1, ''+formId+' .loading', 'fast')
		return true
	}
	
	return false
	
}








Cabinet.checkAuthForm = function(formId)
{
	/*if(typeof formId == 'undefined')
	{
		formId = 'cabinet-auth-form'
	}*/
	$('#'+formId+' input').each(function(n,element){
		$(element).removeClass('error')
	});
	
	$('#'+formId+' .info').html('')
	
	var email=$('#'+formId+' input[name="email"]').val()
	var pass=$('#'+formId+' input[name="password"]').val()
	
	
	var errors = []
		
	if(email == '')
		errors.push({name:'email', msg:_CONST['ERROR Укажите Ваш e-mail']})

	if(pass == '')
		errors.push({name:'password', msg:_CONST['ERROR Введите пароль']})
	
	if(errors.length > 0)
	{
		Forms.displayErrors(formId, errors)
	}
	else
	{
		loading(1, ''+formId+' .loading', 'fast')
		$.ajax({
			url: '/cabinet/authSubmit/?email='+email+'&password='+pass,
			dataType: 'json',
			success: function (data, textStatus){
				if(data.errors.length == 0)
				{
					location.reload(); 
				}
				else
					Forms.displayErrors(formId, data.errors)
			},
			error: function (data, textStatus){
				showError('Ошибка сервера.. попробуйте позднее', ''+formId+' .info')
			},
			complete: function(){
				loading(0, ''+formId+' .loading', 'fast')
			}
		});	
	}
		
}








Cabinet.logout=function()
{
	$.get('/cabinet/logout', function( data ) {
		location.href=""; 
	});
}




