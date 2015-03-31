$(document).ready(function() {
	var table = $('#example').DataTable({
		"columnDefs": [
			{ "visible": false, "targets": 2 }
		],
		"ordering": false,
		"stateSave": true,
		"displayLength": 25,
		"drawCallback": function ( settings ) {
			var api = this.api();
			var rows = api.rows( {page:'current'} ).nodes();
			var last=null;

			api.column(2, {page:'current'} ).data().each( function ( group, i ) {
				if ( last !== group ) {
					$(rows).eq( i ).before(
						'<tr class="group"><td colspan="2">'+group+'</td></tr>'
					);
					last = group;
				}
			} );
		}
	} );
} );