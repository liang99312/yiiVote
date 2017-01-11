var doFlag = false;
$(document).ready(function () {
	var tdNods = $("#tblVote td");
	tdNods.click(tdClick);
});
//td的点击事件
function tdClick() {
	//将td的文本内容保存
	var td = $(this);
	if(td.hasClass("noInput")){
		return;
	}
	var tdText = td.text();
	//将td的内容清空
	td.empty();
	//新建一个输入框
	var tflag = td.hasClass("textTd");
	var input = $("<select><option value ='4'>4</option>\n\
    <option value ='5'>5</option>\n\
<option value ='6'>6</option>\n\
<option value ='7'>7</option>\n\
<option value ='8'>8</option>\n\
<option value ='9'>9</option>\n\
<option value ='10'>10</option></select>");
        if(tdText === undefined || tdText === ""){
             tdText = 6;
             if(tflag){
                 tdText = "";
             }
        }
	if(tflag){
		input = $("<textarea rows='5' cols='20'></textarea>");
                input.val(tdText);
	}else{
            input.find("option[value='"+tdText+"']").attr("selected",true);
        }
//	input.val(tdText);
	
	//将保存的文本内容赋值给输入框
	
	input.css("width",td.css("width"));
        input.css("height","30px");
	//将输入框添加到td中
	td.append(input);
	//给输入框注册事件，当失去焦点时就可以将文本保存起来
	input.blur(function () {
		//将输入框的文本保存
		var input = $(this);
		var inputText = "";
		inputText = input.val();
		var td = input.parent("td");
		if("" === inputText || tflag){
			td.html(inputText);
			if(inputText.length > 0){
				td.addClass("xier");
                                doFlag = true;
			}
			//让td重新拥有点击事件
			td.click(tdClick);
		}else if(!isNaN(inputText)&&inputText>3&&inputText<11&&!tflag){
			//将td的内容，即输入框去掉,然后给td赋值
			td.html(inputText);
			//让td重新拥有点击事件
			td.click(tdClick);
			td.addClass("xier");
                        doFlag = true;
		}else{
			td.html("");
			alert("请输入4-10的数字");
			td.click(tdClick);
			td.removeClass("xier");
		}
	});
	//将输入框中的文本高亮选中
	//将jquery对象转化为DOM对象
        input.focus();
	
	//将td的点击事件移除
	td.unbind("click");
        
}