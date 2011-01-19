$(function(){
	
swfu = new SWFUpload({
	// Backend Settings
	upload_url: "/dashboard/upload",
	
	// File Upload Settings
	file_size_limit : "1 MB",
    file_types : "*.jpg;*.jpeg;*.png;*.gif",
    file_types_description : "Images",
	file_upload_limit : 0,
	
	file_dialog_complete_handler : function(numFilesSelected, numFilesQueued) {
		try {
			if (numFilesQueued > 0) {
				this.startUpload();
			}
		} catch (ex) {
			this.debug(ex);
		}
	},
	
	upload_error_handler: function (file, errorCode, message) {
		$.sirius.statusMessage("图片上传失败", "error");
	},
	
	upload_success_handler: function (file, serverData) {
		console.log(serverData);
		var data = $.parseJSON(serverData);
		if (data == undefined || data.imageUrl == undefined) {
			$.sirius.statusMessage("图片上传失败", "error");
			return;
		}
		$.sirius.addPictureToMessageBox(data.imageUrl, data.imageThumbUrl);
	},
	
	
	button_placeholder_id : "uploadPlaceholder",
	button_width: 30,
	button_height: 30,
	button_text : '',
	button_text_style : '.button { font-family: Helvetica, Arial, sans-serif; font-size: 12pt; } .buttonSmall { font-size: 10pt; }',
	button_text_top_padding: 0,
	button_text_left_padding: 18,
	button_window_mode: SWFUpload.WINDOW_MODE.TRANSPARENT,
	button_cursor: SWFUpload.CURSOR.HAND,
	
	// Flash Settings
	flash_url : "/swf/swfupload.swf",
	
	
	
	// Debug Settings
	debug: false
	});
});
