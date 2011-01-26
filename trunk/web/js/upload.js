$(function(){
	
swfu = new SWFUpload({
	// Backend Settings
	upload_url: "/dashboard/upload",
	prevent_swf_caching: false,
	
	// File Upload Settings
	file_size_limit : "1 MB",
    file_types : "*.jpg;*.jpeg;*.png;*.gif",
    file_types_description : "Images",
	file_upload_limit : 1,
	
	file_dialog_complete_handler : function(numFilesSelected, numFilesQueued) {
		try {
			if (numFilesQueued > 0) {
				$.sirius.statusMessage("正在上传图片", "info");
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
		$.sirius.statusMessage("图片上传成功", "success");
	},
	
	
	button_placeholder_id : "uploadPlaceholder",
	button_width: 30,
	button_height: 30,
	button_image_url: "images/icon_upload_image.png",
	button_text : '',
	button_text_style : '',
	button_action: SWFUpload.BUTTON_ACTION.SELECT_FILE,
	button_text_top_padding: 0,
	button_text_left_padding: 18,
	button_window_mode: SWFUpload.WINDOW_MODE.TRANSPARENT,
	button_cursor: SWFUpload.CURSOR.HAND,
	
	// Flash Settings
	flash_url : "/swf/swfupload.swf",
});
});
