/**
 * ...
 * @author paul
 */

function initCBX(object, id, options) {
	var design = "assets";
	if(object == null){
		jQuery.noConflict();
		var cboxClass;
		cboxClass = jQuery(id).attr("class");
		if(jQuery.browser.msie && parseInt(jQuery.browser.version)<8 ){
			jQuery(id).colorbox();
		}
		else{
			if(cboxClass.indexOf("cboxElement") == -1){
				if(options.classes.image.id){
					jQuery('.'+options.classes.image.id).colorbox({transition:options.classes.image.transition, slideshow:options.classes.image.slideshow, slideshowSpeed:options.classes.image.slideshowSpeed});
				}
				if(options.classes.video){
					if(options.classes.video.id){
						jQuery('.'+options.classes.video.id).colorbox({iframe:true, innerWidth:options.classes.video.innerWidth, innerHeight:options.classes.video.innerHeight, transition:options.classes.image.transition, slideshow:options.classes.image.slideshow, slideshowSpeed:options.classes.image.slideshowSpeed});
					}
				}
				if(options.classes.swf){
					if(options.classes.swf.id){
						var cbxSWFSrc = jQuery('.'+options.classes.swf.id).attr("href");
						var objEmbd = '<OBJECT classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=6,0,0,0" WIDTH="'+options.classes.video.innerWidth+'" HEIGHT="'+options.classes.video.innerHeight+'" id="cbxSWF" ALIGN="">'+
										'<PARAM NAME=movie VALUE="'+cbxSWFSrc+'">' +
										'<PARAM NAME=quality VALUE=high>' +
										'<PARAM NAME=wmode VALUE=transparent>'+
										'<PARAM NAME=bgcolor VALUE=#333399>'+
										'<EMBED src="'+cbxSWFSrc+'" quality=high wmode=transparent WIDTH="'+options.classes.video.innerWidth+'" HEIGHT="'+options.classes.video.innerHeight+'" NAME="Yourfilename" ALIGN="" TYPE="application/x-shockwave-flash" PLUGINSPAGE="http://www.macromedia.com/go/getflashplayer"></EMBED>'+
										'</OBJECT>';
						jQuery('.'+options.classes.swf.id).colorbox({html:objEmbd, transition:options.classes.image.transition, slideshow:options.classes.image.slideshow, slideshowSpeed:options.classes.image.slideshowSpeed});
					}
				}
			}
		}
		
		jQuery(id).trigger('click');
		return;
	}

	loadjQuery = function(filename) {
		loadjQuery.getScript(object.path+"/"+filename);
		loadjQuery.retry(0); 
	}
	loadColorbox = function(filename) {
		loadColorbox.getScript(object.path+"/"+filename);
		loadColorbox.retry(0); 
	}
	
	loadjQuery.getScript = function(filename) {
		if(typeof jQuery == "undefined"){
			var script = document.createElement('script');
			script.setAttribute("type","text/javascript");
			script.setAttribute("src", filename);
			document.getElementsByTagName("head")[0].appendChild(script);
		}
	}
	
	loadColorbox.getScript = function(filename) {
		if(typeof jQuery.colorbox == "undefined"){
			var link = document.createElement('link'); 
			link.setAttribute('media', 'screen');  
			link.setAttribute('href', object.path+'/'+design+'/colorbox.css'); 
			link.setAttribute('rel', 'stylesheet'); 
			document.getElementsByTagName("head")[0].appendChild(link);
			
			var script = document.createElement('script');
			script.setAttribute("type","text/javascript");
			script.setAttribute("src", filename);
			document.getElementsByTagName("head")[0].appendChild(script);
		}
	}
	
	loadjQuery.retry = function(time_elapsed) {
		if (typeof jQuery == "undefined") { 
			if (time_elapsed <= 5000) { 
				setTimeout("loadjQuery.retry(" + (time_elapsed + 200) + ")", 200); 
			}
		} 
		else {
			if(typeof jQuery.colorbox == "undefined"){
				loadColorbox("jquery.colorbox-min.js");
			}
		}
	}
	
	loadColorbox.retry = function(time_elapsed) {
		if (typeof jQuery.colorbox == "undefined") { 
			if (time_elapsed <= 5000) { 
				setTimeout("loadColorbox.retry(" + (time_elapsed + 200) + ")", 200); 
			}
		} 
	}
	
	if(typeof jQuery == "undefined"){
		loadjQuery("jquery-1.7.2.min.js");
	}
	else if(typeof jQuery.colorbox == "undefined"){
		loadColorbox("jquery.colorbox-min.js");
	}
}
