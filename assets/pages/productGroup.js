import '../app';
import '../styles/pages/product-group.css'
import "../scripts/datatables";

if (window.location.pathname !== '/product/group') {
	$( document ).ready( function () {
		$( '#datatable' ).DataTable( {
			ajax: dataURL,
			responsive: true,
			autoWidth: false,
			columnDefs: [
				{ responsivePriority: 1, targets: 0},
				{ responsivePriority: 5, targets: 1},
				{ responsivePriority: 3, targets: 2},
				{ responsivePriority: 4, targets: -1, orderable: false }
			]
		} )
	} );
}

