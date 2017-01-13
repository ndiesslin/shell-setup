// Set blog link in breadcrumb
$(document).ready(function(){
	breadCrumbWrite( 'blog' );
});

function breadCrumbWrite( linkText ) {
	$( '#breadcrumbs a' ).each( function() {
		if ( this.text.toLowerCase() == linkText ) {
			this.setAttribute( 'href', '/' + linkText );
			this.setAttribute( 'style', 'text-decoration: underline !important;' );
		}
	});
};
