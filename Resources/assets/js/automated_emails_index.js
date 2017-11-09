$('.datatable').DataTable({
	responsive: true,
	order: [[0, 'asc']],
	columns: [
		{data: 'name', name: 'name'},
		{
			data: 'actions',
			name: 'actions',
			orderable: false,
			searchable: false,
			class: 'text-center'
		}
	]
});
