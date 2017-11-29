new Vue({
    el: '#emailApp',

    data: {
        except: [],
        filters: filters,
        values: {},
        receivers: 'all-users'
    },

    created(){
        var self = this;

        jQuery.each(this.filters, function (key, filter) {
            var newValues = [];
            jQuery.each(filter.values, function (subKey, value) {
                newValues.push({
                    id: subKey,
                    text: value
                });
            });

            filter.values = newValues;

            if(filter.type === 'select'){
                self.values[key] = '';
            } else if(filter.type === 'multi-select'){
                self.values[key] = [];
            } else if(filter.type === 'from-to'){
                self.values[key] = {
                    from: '',
                    to: ''
                };
            }
        });
    },

    methods: {
        searchReceivers() {
            var self = this;

            $('.search-table').DataTable().destroy();
            $('.search-table').DataTable({
                'processing': true,
                'serverSide': true,
                'responsive': true,
                'ajax': {
                    url: search_url,
                    type: 'POST',
                    data: {
                        receivers: self.receivers,
                        filters: self.values
                    }
                },
                'columns': [
                    {'data': 'checkbox'},
                    {'data': 'email', 'name': 'email'}
                ],
                'columnDefs': {
                    orderable: false, targets: 0
                }
            });
        },

        changeReceivers() {
            $('.search-table').DataTable().clear().draw();
        }
    }
});

var exceptInput = $('input[name=except]');
var except = [];

$('.search-table').DataTable({
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
    $('.receivers-table').DataTable({
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
