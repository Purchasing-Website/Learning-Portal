$(document).ready(function() {
    $('#productmanagementtable').DataTable({
        //"dom": '<"top"lf>rt<"bottom"ip><"clear">', 
        "pageLength": 5,
        "lengthMenu": [ [5, 10, 15, 20], [5, 10, 15, 20] ],
		//"paging": true,
        //"searching": true,
        //"ordering": false,
        "language": {
            search: "_INPUT_"
			,searchPlaceholder: "Search for products..."}
			,"createdRow": function(row, data, dataIndex) {
            $('td', row).css({
                //'text-align': 'center',
                'vertical-align': 'middle'
            });
        }
    });
});