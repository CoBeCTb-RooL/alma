

function notice(str)
{
	$.stickr({note:str, className:'notice', speed:'fast'});
}


function error(str)
{
	$.stickr({note:str, className:'error', speed:'fast'/*, sticked:true*/});
}

function warning(str)
{
	$.stickr({note:str, className:'warning', speed:'fast'/*, sticked:true*/});
}










//	загрузка
function loading(a, id, speed)
{
	if(typeof id=='undefined')
		alert('"'+id+'" <<< No LoaDiNG LaBeL FouND!')
	if(typeof speed=='undefined')
		speed='medium'
		
	if(a>0)
	{
		$('#'+id).slideDown(speed)
		$('.'+id).slideDown(speed)
		//$('#cart-loading-div').css('display', 'block')
		//$('#'+id).fadeIn('fast')
	}
	else
	{
		$('#'+id).slideUp(speed)
		$('.'+id).slideUp(speed)
		//$('#cart-loading-div').css('display', 'none')
		//$('#'+id).fadeOut('fast')
	}
}





function showErrors()
{
	
}





function showNotice(msg, id, speed)
{
	showMsg(msg, '', id, speed)
}


function showError(msg, id, speed)
{
	showMsg(msg, 'err', id, speed)
}



function showMsg(msg, err_no, id, speed)
{
	
	if(typeof id=='undefined')
		id='cart-info-div'
	if(typeof speed=='undefined')
		speed='fast'
	
	
	var bgColor
	var spanId
	var colorFrom
	if(err_no=='err')
	{
		spanId='error-'+id
		colorFrom='#ff4040	'
		$('#'+id).html('<span class="error" id="'+spanId+'" style="font-size: 11px;color: #c54725">'+msg+'</span>')
		bgColor = $('.error').css('background-color')
	}
	else
	{
		colorFrom='#94ff65'
		spanId='notice-'+id
		$('#'+id).html('<span class="notice" id="'+spanId+'" style="font-size: 11px; color: #309641">'+msg+'</span>')
		bgColor = $('.notice').css('background-color')
	}
		
	//$('#'+id).stop(true).css('display', 'none').slideDown(speed).css('opacity', '1')
	$('#'+id).stop(true).slideDown(speed).css('opacity', '1')
	/*$('#'+spanId).css("background-color", colorFrom)
	$('#'+spanId).animate({ backgroundColor: bgColor}, "slow");
	
	
	$('#'+id).fadeOut(6000)*/
	
	
}





//ф-ция подсвечивания
function highlight(id, ok, clean)
{
	//alert('highlight!')
	if(typeof ok == 'undefined')
		ok = false
	if(typeof clean == 'undefined')
		clean = false
		
	if(ok)
		var color="#8dd848"
	else
		var color="#E0AFB2"
			
	var qqq=[]
	if(typeof id !='object' && typeof id != 'array')
		qqq.push(id)
	else qqq=id
	
	for(var i in qqq)
	{
		//alert(qqq[i])
		/*if(i == 0)
			$('#'+qqq[i]).focus()*/
		//alert('#'+qqq[i])
		$('#'+qqq[i]).css("background-color", color)
		$('#'+qqq[i]).animate({ backgroundColor: "#fff" }, "slow");
		
		if(clean)
		{
			$('#'+qqq[i]).val('')
		}
	}
}


//	выделение
function markError(id, clean)
{
	//alert(123)
	if(typeof clean == 'undefined')
		clean = false
			
	var qqq=[]
	if(typeof id !='object' && typeof id != 'array')
		qqq.push(id)
	else qqq=id
	
	for(var i in qqq)
	{
		//alert(qqq[i])
		/*if(i == 0)
			$('#'+qqq[i]).focus()*/
			
		//$('#'+qqq[i]).addClass("error")
		$('#'+qqq[i]).addClass("field-error")
		
		
		if(clean)
		{
			$('#'+qqq[i]).val('')
		}
	}
}




function switchTo(id, className)
{
	$('.'+className).slideUp();
	$('#'+id).slideDown()
}