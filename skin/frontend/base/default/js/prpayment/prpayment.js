function prPaymentOpenHelp(id){
	$("payrandom-modalbox-info-" + id).appear({ duration: 0.3 }).centerHorizontally().centerVertically();
}

function prPaymentCloseHelp(id){
	new Effect.DropOut("payrandom-modalbox-info-" + id);
	$("payrandom-video-" + id).hide();
	$("payrandom-maincontent-" + id).show();
}

Element.addMethods({
	centerHorizontally: function(element) {
		if (!(element = $(element))) return;
 
		var vpWidth = $(document).viewport.getWidth();
		var width = element.getWidth();
		 
		element.style.left = (vpWidth / 2) - (width / 2) + 'px';
		return element;
	},
	centerVertically: function(element) {
		if (!(element = $(element))) return;
		 
		var vpHeight = $(document).viewport.getHeight();
		var height = element.getLayout().get('margin-box-height');
		 
		var avTop = (vpHeight / 2) - (height / 2);
		 
		if (avTop <= 10)
		avTop = 10;
	 
		element.style.top = avTop+ 'px';
		return element;
	}
});