var console = {
	init: function() {
		$('#provider').change(function(){
			console.changeProvider($(this).val());
		});
		this.changeProvider($('#provider').val());
		
		$('#method').change(function(){
			if ($(this).val() == 'none') return;
			var optionSelector = 'option[value="' + $(this).val() + '"]';
			console.changeMethod(optionSelector);
		});
		
		$(':submit').click(function(){
			console.callApi();
			return false;
		});
	},
	
	changeProvider: function(provider) {
		$.getJSON('/console/config/', {provider: provider}, function(config){
			$('#format').empty();
			$(".parameter").remove();
			$.each(config.formats, function(formatName, formatCaption){
				$("<option/>").val(formatName).html(formatCaption).appendTo('#format');
			});
			$('#method option:not([value="none"])').remove();
			$.each(config.methods, function(methodName, paramDefine){
				$("<option/>").val(methodName).html(methodName).data('paramDefine', paramDefine).appendTo('#method');
			});
		});
	},
	
	changeMethod: function(methodOption) {
		$(".parameter").remove();
		var paramDefine = $(methodOption).data('paramDefine');
		$.each(paramDefine, function(index, paramName){
			var paramDiv = $("<div/>").addClass("control parameter").attr('id', 'arg_' + index);
			$('<label />').attr('id', 'label_' + index).html(paramName).appendTo(paramDiv);
			$('<input type="text" class="inputtext" />').attr('name', 'val_' + index).appendTo(paramDiv);
			paramDiv.insertBefore('.after_parameter');
		});
	},
	
	callApi: function() {
		if ($('#method').val() == 'none') {
			alert("请先选择你要测试的方法!");
			$('#method').focus();
			return;
		}
		$.getJSON("/console/call", $('#test_form').serialize(), function(){
			
		});
	}
}

$(function(){
	console.init();
});