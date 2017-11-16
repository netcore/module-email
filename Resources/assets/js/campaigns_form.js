var exceptInput = $('input[name=except]');
var except = [];

$('.search').DataTable({
    'columnDefs': {
        orderable: false, targets: 0
    }
});

$('.search-receivers').on('click', function (e) {
    e.preventDefault();

    var data = $('.form-horizontal').find('.filter-data :input').serializeArray();
    $.ajax({
        type: 'POST',
        url: search_url,
        data: data,
        success: function (response) {
            populateDataTable(response.data);
        },
        error: function (e) {
            console.log("error: " + JSON.stringify(e.responseText));
        }
    });
});

$(document).on('change', '.except', function () {
    var email = $(this).val();

    if ($(this).is(':checked')) {
        restoreReceiver(email);
    } else {
        removeReceiver(email);
    }
});

if (receivers_url) {
    $('.datatable').DataTable({
        processing: true,
        serverSide: true,
        ajax: receivers_url,
        responsive: true,
        order: [[0, 'asc']],
        columns: [
            {data: 'user', name: 'user'},
            {data: 'email', name: 'email'},
            {data: 'sent', name: 'is_sent'},
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

function populateDataTable(data) {
    $('.search').DataTable().destroy();
    $('.search').DataTable({
        'responsive': true,
        'aaData': data,
        'columns': [
            {
                'data': function (row, type, val, meta) {
                    return '<input type=checkbox name=found[] value=' + row.email + ' class=except checked>';
                },
            },
            {'data': 'full_name'},
            {'data': 'email'}
        ],
        'columnDefs': {
            orderable: false, targets: 0
        }
    });
}

function restoreReceiver(email) {
    var found = 0;
    while ((found = except.indexOf(email, found)) !== -1) {
        except.splice(found, 1);
    }

    exceptInput.val(JSON.stringify(except));
}

function removeReceiver(email) {
    except.push(email);

    exceptInput.val(JSON.stringify(except));
}
