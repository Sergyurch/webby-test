$(document).ready(function() {
    let checkedFilms = [];

    $('#films').on('click', (e) => {
        if ($(e.target).is($('#checkAll'))) {
            let checked = $(e.target).prop('checked');
            checkedFilms = [];
            
            $(e.target).closest('table').find('tbody tr input').each(function() {
                $(this).prop('checked', checked);

                if (checked) {
                    checkedFilms.push($(this).attr('data-id'));
                }
            });
        }

        if ($(e.target).is($('tbody [type="checkbox"]'))) {
            let checked = $(e.target).prop('checked');
            let id = $(e.target).attr('data-id');
            
            if (checked) {
                checkedFilms.push(id);
            } else {
                let index = checkedFilms.indexOf(id);
                checkedFilms.splice(index, 1);
            }
        }
        
        if (checkedFilms.length > 0) {
            $('#delete-film-btn').prop('disabled', false);
        } else {
            $('#delete-film-btn').prop('disabled', true);
        }
    });

    $('#delete-film-btn').on('click', () => {
        Swal.showLoading();

        $.ajax({
            url: '/film/delete',
            method: "POST",
            data: { 
                films: checkedFilms
            },
            dataType: 'json',
            success: function(response) {
                if (response) {
                    Swal.fire({
                        title: 'Обрані фільми успішно видалені!',
                        text: '',
                        icon: 'success',
                        confirmButtonText: 'Ok'
                    });

                    $('#films tbody tr').each(function() {
                        let checkboxElement = $(this).find('[type="checkbox"]');
                        let id = checkboxElement.attr('data-id');

                        if (checkedFilms.includes(id)) {
                            $(this).remove();
                        }
                    });

                    checkedFilms = [];
                    $('#checkAll').prop('checked', false);
                    $('#delete-film-btn').prop('disabled', true);
                } else {
                    Swal.fire({
                        title: 'Помилка!',
                        text: 'Спробуйте ще раз або зверніться до адміністратора',
                        icon: 'error',
                        confirmButtonText: 'Ok'
                    });
                }
            }
        });
    });

    $('#search-film-btn').on('click', () => {
        Swal.showLoading();

        $.ajax({
            url: '/film/search',
            method: "POST",
            data: { 
                searchBy: $('#search-by').val(),
                searchValue: $('#search-value').val()
            },
            dataType: 'json',
            success: function(response) {
                if (!response) {
                    Swal.fire({
                        title: 'Помилка!',
                        text: 'Спробуйте ще раз або зверніться до адміністратора',
                        icon: 'error',
                        confirmButtonText: 'Ok'
                    });
                } else {
                    $('#films tbody tr').remove();
                    response.forEach(function(film) {
                        $('#films tbody').append(`
                            <tr>
                                <td><input type="checkbox" data-id="${film.id}"></td>
                                <td><a href="/film/show/${film.id}">${film.title}</a></td>
                            </tr>
                        `);
                    });
                    Swal.close();
                }
            }
        });
    });

    $('#import-film-btn').on('click', () => {
        $('#input-file').click();
    });

    $('#input-file').on('change', function() {
        Swal.showLoading();
        let formData = new FormData();
        formData.append('file', $(this).prop('files')[0]);

        $.ajax({
            url: '/film/import',
            method: "POST",
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                response = JSON.parse(response);
                
                if (!response) {
                    Swal.fire({
                        title: 'Помилка обробки файлу.',
                        text: '',
                        icon: 'error',
                        confirmButtonText: 'Ok'
                    });
                } else {
                    Swal.fire({
                        title: `Завантажено ${response.uploaded} з ${response.total} фільмів.`,
                        text: '',
                        icon: 'success',
                        confirmButtonText: 'Ok'
                    }).then((result) => {
                        location.reload(true);
                    });
                }
            }
        });
    });
});
