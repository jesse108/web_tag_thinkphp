//JS 常用函数

function dump(value) {
    console.log(value);
}


function in_array(needle, haystack) {
    if (!haystack) {
        return false;
    }
    for (var i in haystack) {
    	if(haystack[i] == needle){
    		return true;
    	}
    }
    return false;
}


function buildOptionHtml(options,defaultOption,valueName,showName){
	defaultOption = defaultOption ? defaultOption : '';
	valueName = valueName ? valueName : 'value';
	showName = showName ? showName : 'show';
	
	html = "<option value=''>"+defaultOption+"</option>";
	for(var i in options){
		curHtml = "";
		value = options[i][valueName];
		show = options[i][showName];
		curHtml = "<option value='"+value+"'>" + show + "</option>";
		html += curHtml;
	}
	return html;
}

function count(obj){
	var num = 0;
	for(var i in obj){
		num++;
	}
	return num;
}

function current(obj){
	for(var i in obj){
		return obj[i];
	}
}


function redirect(url){
	window.location.href = url;
}

