jQuery(function(){
	
	/***********************************************************
	 * Variables
	 */
	var self = this;
	self.siteurl = wsr_myplugin_ajax.siteurl;
	self.path = wsr_myplugin_ajax.path;


	/***********************************************************
	 * Ajax
	 */
	function getData(param){
		displayLoading();
		jQuery.ajax({
			url: wsr_myplugin_ajax.ajaxurl,
			type: 'POST',
			dataType: 'json',
			data: {
		    	action: 'wsr_action',
		    	security: wsr_myplugin_ajax.ajax_nonce,
		    	mydata: param
		    }
		})
		.done(function(res, txtStatus, request) {
			//request.getResponseHeader('X-WP-TotalPages'),
			console.log("success");
			return res;
		})
		.fail(function(err, errtext) {
			console.log("error");
			var result = ['success': false, 'err': errtext];
			return result;
		})
		.always(function(err) {
			console.log("complete");
		});
	}


	/***********************************************************
	 * Functions
	 */

});