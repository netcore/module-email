new Vue({
    el: '#emailApp',

    data: {
        except: [],
        filters: filters,
        receivers: 'all-users'
    },

    methods: {
        searchReceivers() {

            if (!this.filters) {
                return;
            }

            var filters = $('.form-horizontal').find('.filter-data :input').serializeArray();

            $('.search').DataTable().destroy();
            $('.search').DataTable({
                processing: true,
                serverSide: true,
                'responsive': true,
                'ajax': {
                    url: search_url,
                    type: 'POST',
                    data: filters
                },
                'columns': [
                    {'data': 'checkbox'},
                    {'data': 'email', 'name': 'email'}
                ],
                'columnDefs': {
                    orderable: false, targets: 0
                }
            });
        }
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
            {data: 'sent', name: 'is_sent', class: 'text-center'},
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
