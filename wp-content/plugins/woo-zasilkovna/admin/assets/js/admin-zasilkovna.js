(function ( $ ) {
	"use strict";

	$(function () {
            
//          if ( $('.box-body.licence-aktivni').length ) {
//             $('.woo-box.zasilkovna-obsah').show();
//            
//             // $('.licence .h2-licence').hide();
//               $('.box-header .h3-licence').hide();
//          }
//          else {
//             $('.woo-box.zasilkovna-obsah').hide();
//           
//              
//          }
          

$('.zasilkovna-kurz .kurz-btn').click(function() {
   if($('.zasilkovna-woo-kurz').is(':visible')) {
       $('.zasilkovna-woo-kurz').slideUp();
   }
  else {
       $('.zasilkovna-woo-kurz').slideDown();
  }
});

$('.zasilkovna-mena .mena-btn').click(function() {
   if($('.zasilkovna-woo-mena').is(':visible')) {
       $('.zasilkovna-woo-mena').slideUp();
   }
  else {
       $('.zasilkovna-woo-mena').slideDown();
  }
});

    
    
    
	
	});

}(jQuery));
