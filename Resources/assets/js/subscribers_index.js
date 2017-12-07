$('.datatable').DataTable({
	processing: true,
	serverSide: true,
	ajax: pagination_url,
	responsive: true,
	order: [[1, 'desc']],
	columns: [
		{data: 'email', name: 'email'},
        {data: 'created_at', name: 'created_at'},
		{
			data: 'actions',
			name: 'actions',
			orderable: false,
			searchable: false,
			class: 'text-center'
		}
	]
});
