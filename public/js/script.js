$(window).ready(function () {
    formValidationControl('#taskControlForm');
    formValidationControl('#adminLoginForm');

    $('.changeSubmit').each(function () {
        $(this).change(() => $('#taskFilterForm').submit())
    });

    $('a.confirmLink').each(function () {
        $(this).click(function (event) {
            event.preventDefault();
            let text = $(this).attr('confirmText') ?? 'Вы уверены ?';
            Swal.fire({
                icon: 'warning',
                title: 'Подтверждение !',
                text,
                confirmButtonText: 'Потвердить',
                confirmButtonColor: '#3b3b3b',
                showCancelButton: true,
                cancelButtonText: 'Отменить',
                cancelButtonColor: '#CC2121',
                confirmButtonShadow: 'none',
                focusCancel: true
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.replace($(this).attr('href'));
                }
            });
        })
    });
});

function formValidationControl(form) {
    $(form).submit(function (event) {
        let submitAccess = [];
        $(this).find('.inputValidation').each(function () {
            taskControlFormValidation(this);
            if (!$(this).val().trim()) {
                submitAccess.push(false);
            }
        });

        if (submitAccess.includes(false)) {
            event.preventDefault();
        }
    });

    $(`${form} .inputValidation`).each(function () {
        $(this).on('input', function () {
            taskControlFormValidation(this);
        });
    });
}

function taskControlFormValidation(tag) {
    if (!$(tag).val().trim()) {
        $(tag).addClass('border-danger');
        $(tag).next().show();
    } else {
        $(tag).removeClass('border-danger');
        $(tag).next().hide();
    }
}