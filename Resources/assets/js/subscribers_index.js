$('.datatable').DataTable({
	processing: true,
	serverSide: true,
	ajax: pagination_url,
	responsive: true,
	order: [[0, 'desc']],
	columns: [
        {data: 'created_at', name: 'created_at'},
		{data: 'email', name: 'email'},
		{
			data: 'actions',
			name: 'actions',
			orderable: false,
			searchable: false,
			class: 'text-center'
		}
	]
});
