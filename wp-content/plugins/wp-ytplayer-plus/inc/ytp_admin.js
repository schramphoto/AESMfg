/*******************************************************************************
 *
 * ytp_admin
 * Author: pupunzi
 * Creation date: 08/11/16
 *
 ******************************************************************************/
function show_message(messageBox, message, time, styleClass, callback) {
	messageBox.fadeOut(500, function(){

		styleClass = styleClass || "success";
		time = time || 0;
		messageBox.addClass(styleClass);
		messageBox.html(message);

		messageBox.fadeIn(500);

		if(time)
			messageBox.delay(time ).fadeOut(500, function(){
				if(typeof callback == "function"){
					callback();
				}
			});
	});

}

var msg_box = jQuery("#license-save-bar .message");

/**
 * VALIDATE FIRST TIME LIC
 */
jQuery('#YTP-license-form').submit( function () {

	if (!jQuery("#mbYTPlayer_license_key").val() || jQuery("#mbYTPlayer_license_key").val().length == 0){
		show_message(msg_box, ytpl_lic.str_valid_key_needed, 5000, "error" );
		return false;
	}

	show_message(msg_box, ytpl_lic.str_license_validating, 5000, "warning" );

	jQuery("#license-save-bar input").hide();
	var data = jQuery(this).serializeObject();
	data.mbYTPlayer_license_key = data.mbYTPlayer_license_key.toUpperCase();
	var lic_key = data.mbYTPlayer_license_key;

	/**
	 * Verify key
	 */
	jQuery.ajax({
		method: "POST",
		url: 'https://pupunzi.com/wpPlus/controller.php',
		data: {"CMD" : "VERIFY-LICENSE", "lic_key" : lic_key, "lic_domain" : ytpl_lic.lic_domain, "lic_theme" : ytpl_lic.lic_theme},
		dataType: 'json',
		success: function(resp){
			if(resp.result == "OK")
				saveLic(data, resp.lic);
			else {
				show_message(msg_box, ytpl_lic.str_license_not_valid, 5000, "error" );
				jQuery("#invalid_lic #invalid_lic_domain").html(resp.lic.lic_domain);
				jQuery("#invalid_lic").slideDown();
				jQuery("#license-save-bar input").show();
			}
		},
		error: function(){
			show_message(msg_box, "ERROR ::" + ytpl_lic.str_server_error, 5000, "error" );
			jQuery("#license-save-bar input").show();
		}
	});

	return false;
});

jQuery.fn.serializeObject = function() {
	var o = {};
	var a = this.serializeArray();
	jQuery.each(a, function() {
		if (o[this.name] !== undefined) {
			if (!o[this.name].push) {
				o[this.name] = [o[this.name]];
			}
			o[this.name].push(this.value || '');
		} else {
			o[this.name] = this.value || '';
		}
	});
	return o;
};

function saveLic(data, lic){

	var key = lic.lic_key;
	var kryptLic = lic.lic;

	jQuery.post( 'options.php', data ).error(
			function() {
				show_message(msg_box, ytpl_lic.str_license_not_valid, 5000, "error" );
			}).success( function() {
				var callback = function(key){
					storeLic(kryptLic);
					jQuery("#validLic #lic_key").html(key);
					jQuery("#validLic #registered_to").html(lic.user_mail);
					jQuery("#validLic #lic_domain").html(lic.lic_domain);
					jQuery("#validLic #lic_theme").html(lic.lic_theme);
					jQuery("#validLic").slideDown();
					jQuery("#getLic").slideUp();
					jQuery("#invalid_lic").fadeOut();
					setTimeout(function(){
						self.location.reload();
					},1000)
				};
				show_message(msg_box, ytpl_lic.str_license_valid, 5000, "success", function(){
					callback(key)
				} );
			});
}

function storeLic(kryptLic){
	jQuery.ajax({
		type : "post",
		dataType : "json",
		url : ajaxurl,
		data : {action: "mbytpplus_storeLic", kryptLic : kryptLic},
		success: function() {}
	})
}

function change_domain(lic_key, lic_domain) {
	
	if (!lic_key || lic_key.length == 0)
		lic_key = jQuery("#mbYTPlayer_license_key").val();

	var msg_box = jQuery("#invalid_lic .message");
	jQuery.ajax({
		method: "POST",
		url: 'https://pupunzi.com/wpPlus/controller.php',
		data: {"CMD" : "RECOVER-LICENSE", "lic_key" : lic_key, "lic_domain" : lic_domain },
		dataType: 'json',
		success: function(){

			console.debug( ytpl_lic.str_email_sent);
			show_message(msg_box, ytpl_lic.str_email_sent, 20000, "success" );
		},
		error: function(){
			show_message(msg_box, ytpl_lic.str_license_not_valid, 5000, "error" );
		}
	});


}
