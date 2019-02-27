$(document).ready(function() {
    $('.js-purchase-btn').on('click', function(e) {
        e.preventDefault();

        Swal.fire({
            type: 'success',
            title: 'Excellent Choice!',
            html: '<div>You just bought this delicious Ice Cream from the Space!</div>' +
                '<div><em>Wait for our intergalactic delivery soon ;)</em></div>',
            confirmButtonText: 'Thank you!'
        })
    });
});
