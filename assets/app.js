// Import Bootstrap and JQuery
import "bootstrap/scss/bootstrap.scss";
window.bootstrap = require("bootstrap");

// Import Custom CSS and JavaScript
import "./scripts/utils";
import Swal from 'sweetalert2';
import './styles/app.css';
import '@fortawesome/fontawesome-free/js/all.min'; // ICONS


$( '#delete-btn' ).on( 'click', function ( e ) {
	e.preventDefault();
	let targetUrl = this.href;
	let targetID = this.dataset.id;
	console.log( { targetUrl, targetID } );

	Swal.fire( {
		icon: 'warning',
		position: 'center',
		showConfirmButton: true,
		showCancelButton: true,
		reverseButtons: true,
		title: alertTitle,
		text: alertText
	} ).then( function ( result ) {
		if ( result.isConfirmed ) {
			fetch( targetUrl, { method: 'POST' } )
				.then(response => response.json().then(data=>data.returnUrl))
				.then( (returnUrl) => {
					return window.location = returnUrl;
				} )
		}
	} )
} );
