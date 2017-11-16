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
