new Vue({
    el: '#emailApp',

    data: {
        except: [],
        filters: filters,
        receivers: 'users'
    },

    methods: {
        searchReceivers() {
            var data = $('.form-horizontal').find('.filter-data :input').serializeArray();

            $.ajax({
                type: 'POST',
                url: search_url,
                data: data,
                success: function (response) {
                    this.populateDataTable(response.data);
                }.bind(this),
                error: function (e) {
                    console.log("error: " + JSON.stringify(e.responseText));
                }
            });
        },

        populateDataTable(data) {
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
                    {'data': 'email'}
                ],
                'columnDefs': {
                    orderable: false, targets: 0
                }
            });
        },
    }
});

var exceptInput = $('input[name=except]');
var except = [];

$('.search').DataTable({
    'columnDefs': {
        orderable: false, targets: 0
    }
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

$('.summernote').summernote({
    height: 300,
    focus: true,
    toolbar: [
        ['style', ['bold', 'italic', 'underline', 'clear']],
        ['fontsize', ['fontsize']],
        ['color', ['color']],
        ['style', ['style']],
        ['para', ['ul', 'ol', 'paragraph']],
        ['height', ['height']],
        ['insert', ['picture', 'link']]
    ],
    fontSizes: ['10', '11', '12', '13', '14', '15', '16', '17', '18', '19', '20', '21', '22', '23', '24']
});
