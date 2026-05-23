function handleDeleteAjax(buttonSelector, successMessage = 'Deleted successfully!') {
    $(document).on('click', buttonSelector, function (e) {
        e.preventDefault();

        let form = $(this).closest('form');
        let actionUrl = form.attr('action');
        let row = form.closest('tr');

        Swal.fire({
            title: 'Are you sure?',
            text: "This action cannot be undone!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Yes, delete it!',
            cancelButtonText: 'Cancel'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: actionUrl,
                    type: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: {
                        _method: 'DELETE',
                        _token: $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function (response) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Deleted!',
                            text: successMessage,
                            timer: 2000,
                            showConfirmButton: false
                        });

                        if (row.length) {
                            row.fadeOut(500, function () {
                                $(this).remove();
                            });
                        }
                    },
                    error: function (xhr) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error!',
                            text: 'Something went wrong. Please try again.',
                        });
                    }
                });
            }
        });
    });
}

