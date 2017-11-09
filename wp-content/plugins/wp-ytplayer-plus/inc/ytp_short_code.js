
var selection = null;

function show_ytplayer_editor(){

	jQuery('#ytplayer-form form')[0].reset();
	jQuery("body").css({overflow:"hidden"});
	jQuery("#ytplayer-form").slideDown(300);


	selection = tinyMCE.activeEditor.selection.getContent();

	if(selection.trim().length){
		selection = wp.shortcode.attrs( selection ).named;
		set_youtube_parameters(selection);
	}
}

function set_youtube_parameters(obj){

	for (var key in obj) {

		var field = jQuery("[name=" + key + "]");

		if(field.is("input[type=checkbox]") && obj[key] != "false")
			field.attr("checked", "checked");

		if(field.is("input[type=text]"))
			field.val( obj[key]);

		if(field.is("textarea"))
			field.val( obj[key]);

		if(field.is("select"))
			jQuery("option[value='" + obj[key] + "']").attr("selected", "selected");

	}

}

function hide_ytplayer_editor(){
	jQuery("#ytplayer-form").slideUp(300);
	jQuery("body").css({overflow:"auto"});

}

jQuery("body").on("click","#ytplayer-form", function(e) {
	var target = e.originalEvent.target;
	if(jQuery(target).parents().is("#ytplayer-form"))
		return;
	hide_ytplayer_editor();
});

function isInline(){
	var inlineBox = jQuery('#inlinePlayer');
	if(!jQuery("[name=isinline]").is(":checked")){
		inlineBox.slideUp();
		jQuery("[name=showcontrols]").removeAttr("checked");
		jQuery("[name=autoplay]").attr("checked", "checked");
		jQuery("[name=elementselector]").val("");

	}else{
		inlineBox.slideDown();
		jQuery("[name=showcontrols]").attr("checked","checked");
		jQuery("[name=autoplay]").removeAttr("checked");
	}
	showControlBox();
}

function isElement(){
	var inlineBox_check = jQuery('#inlinePlayer-checkbox');
	var inlineBox = jQuery('#inlinePlayer');
	if(jQuery("[name=elementselector]").val().length > 0){
		inlineBox_check.slideUp();
		jQuery("[name=isinline]").removeAttr("checked");
		inlineBox.slideUp();
	}else{
		inlineBox_check.slideDown();
	}
}

function showControlBox(){
	var controlBox = jQuery('#controlBox');
	if(!jQuery("[name=showcontrols]").is(":checked")){
		controlBox.slideUp();
	}else{
		controlBox.slideDown();
	}
}

function suggestedHeight(){
	var width = parseFloat(jQuery("[name=playerwidth]").val());
	var margin = (width*10)/100;
	width = width + margin;
	var ratio = jQuery("[name=inLine_ratio]").val();
	var suggestedHeight = "";
	if(width)
		if(ratio == "16/9"){
			suggestedHeight = (width*9)/16;
		}else{
			suggestedHeight = (width*3)/4;
		}
	jQuery("[name=playerheight]").val(Math.floor(suggestedHeight));
}

var ytp_form = jQuery('#ytplayer-form form').get(0),

		isEmpty = function(value) {
			return (/^\s*$/.test(value));
		},

		encodeStr = function(value) {
			return value.replace(/\s/g, "%20")
					.replace(/"/g, "%22")
					.replace(/'/g, "%27")
					.replace(/=/g, "%3D")
					.replace(/\[/g, "%5B")
					.replace(/\]/g, "%5D")
					.replace(/\//g, "%2F");
		};

function insertShortcode(){
	var sc = "[mbYTPlayer",
			inputs = ytp_form.elements,
			input,
			inputName,
			inputValue,
			l = inputs.length, i = 0;

	for ( ; i < l; i++) {
		input = inputs[i];
		inputName = input.name;
		inputValue = input.value;

		// Video URL validation
		if (inputName == "url" && (isEmpty(inputValue) || ((inputValue.toLowerCase().indexOf("youtube")==-1) && inputValue.toLowerCase().indexOf("youtu.be")==-1))){
			alert("a valid Youtube video URL is required");
			return false;
		}
		// inputs of type "checkbox", "radio" and "text"
		if (
				((input.type == "text" || input.type == "textarea") && !isEmpty(inputValue) && inputValue != input.defaultValue)
				|| input.type == "select-one"
				|| input.type =="checkbox"
				|| input.type =="radio"
				) {

			if (input.type =="checkbox") {
				if(!input.checked)
					inputValue = false;
			}

			if (inputName =="realfullscreen" && !input.checked)
				continue;

			if (inputName =="inLine_ratio")
				continue;

			sc += ' ' + inputName + '="' + inputValue + '"';
		}
	}
	sc += " ]";

	var win = window.dialogArguments || opener || parent || top;
	win.send_to_editor( sc );

	hide_ytplayer_editor();

	return false;
};

ytp_form.onsubmit = insertShortcode;

/* global tinymce */
tinymce.PluginManager.add('wpytplayer', function( editor ) {

	function replaceytplayershortcodes( content ) {
		return content.replace( /\[mbYTPlayer([^\]]*)\]/g, function( match ) {
			return html( 'wp-ytplayer', match );
		});
	}

	function html( cls, data ) {

		var dataObj =JSON.stringify(wp.shortcode.attrs( data).named);
		data = window.encodeURIComponent( data );
		return '<img src="/wp-content/plugins/wp-ytplayer-plus/images/ytplayershortcode.png" class="mceItem ' + cls + '" ' +
				'data-wp-ytplayer="' + data + '" data-mce-resize="false" data-mce-placeholder="1" alt="" data-ytp-obj=\''+dataObj+'\' />';
	}

	function restoreytplayershortcodesayer( content ) {
		function getAttr( str, name ) {
			name = new RegExp( name + '=\"([^\"]+)\"' ).exec( str );
			return name ? window.decodeURIComponent( name[1] ) : '';
		}

		return content.replace( /(?:<p(?: [^>]+)?>)*(<img [^>]+>)(?:<\/p>)*/g, function( match, image ) {
			var data = getAttr( image, 'data-wp-ytplayer' );

			if ( data ) {
				return data ;
//				return  "<p>" + data + "</p>";
			}

			return match;
		});
	}

	editor.on( 'click', function( event ) {
		node = event.target;

		if(jQuery(node).is("[data-ytp-obj]")) {
			jQuery(node).select();
			setTimeout(show_ytplayer_editor,400);
			//show_ytplayer_editor();

		}

	});

	editor.on( 'mouseover', function( event ) {
		node = event.target;

		if(jQuery(node).is("[data-ytp-obj]")) {

			jQuery(node).css({cursor:"pointer"})

		}

	});

	editor.on( 'BeforeSetContent', function( event ) {
		event.content = replaceytplayershortcodes( event.content );
		if ( ! editor.plugins.wpview || typeof wp === 'undefined' || ! wp.mce ) {
			event.content = replaceytplayershortcodes( event.content );
		}
	});

	editor.on( 'PostProcess', function( event ) {
		if ( event.get ) {
			event.content = restoreytplayershortcodesayer( event.content );
		}
	});
});


