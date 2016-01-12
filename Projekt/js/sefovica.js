//Nastaví validáciu na kotrolu, či je sa nové heslá napísali správne.
jQuery(document).ready(function(){
	//Nastavuje zobrazenie tooltipu.
	function getValidationTooltip(){
		return {
			errorPlacement: function(error, element) {
				var title=element.attr('title')||element.data('uiTooltipTitle');
				var titleStr=title?'<br/>'+title:'';
				if(element.filter(".error").length===0){return;}
				element.tooltip({
					position: {
						my: "left+5 center",
						at: "right center"
					},
					tooltipClass: "ttError",
					items:element,
					content:error.text()+titleStr
				});
				element.tooltip( "open" );
				window.setTimeout(function(){
					if(jQuery(':hover').filter(element).length===0 && element.data('ui-tooltip')){
						element.tooltip( "close" );
					}
				},3000);
			},
			success:function(label,input){
				if(jQuery(input).data('ui-tooltip')){
					jQuery(input).tooltip('destroy');
				}
			},
			ignoreTitle: true
		};
	}
	//Nastavenie validátora.
	jQuery('#passwordInputs').validate(getValidationTooltip());
	//Keď je formulár nevalídny, disabluj submit.
	jQuery('#passwordInputs').on('change keyup', function() {
		jQuery('#changePassword').attr('disabled', !jQuery(this).validate().checkForm())
	});
	
	jQuery.extend(jQuery.validator.messages, {
    required: "Toto pole je povinné.",
	});

	jQuery.validator.addMethod("checkPaswords", function(value, element) {
		return this.optional(element) || value == jQuery('#newPassword').val();
		}, "Heslá sa nezhodujú!");
		
	jQuery('#newPasswordCheck').rules("add", "checkPaswords required");
	jQuery('#oldPassword').rules("add", "required");
	jQuery('#newPassword').rules("add", "required");
	
	jQuery('#submitData').attr('disabled', true);
	//Kým nie je nastavená cesta k súboru, disabluj submit.
	jQuery('#fileForm').on('change', function() {
		jQuery('#submitData').attr('disabled', false);
	});
});