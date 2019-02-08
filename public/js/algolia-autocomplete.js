$(document).ready(function() {
    $('.js-user-autocomplete').each(function() {
        var autocompleteUrl = $(this).data('autocomplete-url');

        $(this).autocomplete({hint: false}, [
            {
                source: function(query, cb) {
                    $.ajax({
                        url: autocompleteUrl+'?query='+query
                    }).then(function(data) {
                        cb(data.users);
                    });
                },
                displayKey: 'email',
                debounce: 500 // only request every 1/2 second
            }
        ])
    });
});
