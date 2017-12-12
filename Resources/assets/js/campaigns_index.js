$('.datatable').DataTable({
	responsive: true,
	order: [[0, 'asc']],
	columns: [
		{data: 'name', name: 'name'},
        {data: 'status', name: 'status'},
		{
			data: 'actions',
			name: 'actions',
			orderable: false,
			searchable: false,
			class: 'text-center'
		}
	]
});

$('.preview').on('click', function () {
    var modal = $('#previewModal');
    var url = $(this).data('url');

    modal.find('.modal-body').html('<iframe frameborder="0" height="100%" width="100%" src="' + url + '"></iframe>');
    modal.find('.modal-body').css('height', '500px'); // TODO: Find a way to set the height based on iframe height
});
