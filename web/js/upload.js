$(function(){
swfu = new SWFUpload({
	// Backend Settings
	upload_url: "/test/upload",
	
	post_params: {"PHPSESSID": "<?php echo $SID; ?>"},
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
	
	
	button_placeholder_id : "uploadPlaceholder",
	button_width: 30,
	button_height: 30,
	button_text : 'Image',
	button_text_style : '.button { font-family: Helvetica, Arial, sans-serif; font-size: 12pt; } .buttonSmall { font-size: 10pt; }',
	button_text_top_padding: 0,
	button_text_left_padding: 18,
	button_window_mode: SWFUpload.WINDOW_MODE.TRANSPARENT,
	button_cursor: SWFUpload.CURSOR.HAND,
	
	// Flash Settings
	flash_url : "/swf/swfupload.swf",
	
	
	
	// Debug Settings
	debug: true
	});
});
