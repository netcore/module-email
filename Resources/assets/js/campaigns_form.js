var users = $('.users');
var userIds = [];

$('.search').DataTable({
    'columnDefs' : {
        orderable: false, targets: 0
    }
});

$('.search-users').on('click', function (e) {
    e.preventDefault();

    var data = $('.form-horizontal').find('.filter-data :input').serializeArray();
    $.ajax({
        type: 'POST',
        url: search_url,
        data: data,
        success: function (data) {
            populateDataTable(data);
        },
        error: function (e) {
            console.log("error: " + JSON.stringify(e.responseText));
        }
    });
});

function populateDataTable(data) {
    userIds.length = 0;

    $('.search').DataTable().destroy();
    $('.search').DataTable({
        'responsive': true,
        'aaData': data,
        'columns': [
            {
                'data': function (row, type, val, meta) {
                    return '<input type=checkbox name=found[] value=' + row.id + ' class=checkbox checked>';
                },
                orderable: false
            },
            {'data': 'full_name'},
            {'data': 'email'}
        ]
    });

    for (var i = 0; i < Object.keys(data).length; i++) {
        userIds.push(data[i].id);
    }

    users.val(JSON.stringify(userIds));
}

function deleteItem(id) {
    var found = 0;
    while ((found = userIds.indexOf(id, found)) !== -1) userIds.splice(found, 1);
}

$(document).on('change', '.checkbox', function () {
    var userId = parseInt($(this).val());

    if ($(this).is(':checked')) {
        userIds.push(userId);
    } else {
        deleteItem(userId);
    }

    users.val(JSON.stringify(userIds));
});

if (users_url) {
    $('.datatable').DataTable({
        processing: true,
        serverSide: true,
        ajax: users_url,
        responsive: true,
        order: [[0, 'asc']],
        columns: [
            {data: 'full_name', name: 'full_name'},
            {data: 'email', name: 'email'},
            {data: 'sent', name: 'pivot.is_sent'},
            {
                data: 'actions',
                name: 'actions',
                orderable: false,
                searchable: false,
                class: 'text-center'
            }
        ]
    });
}
