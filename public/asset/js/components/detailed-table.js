// показываем подробную информацию по авторам
window.addEventListener('load', () => {
    $('[role="detail-button"]').on('click', function () {
        const button = $(this)
        const currentRow = button.closest('tr')

        // если список уже раскрыт - удаляем строку
        if (button.hasClass('active')) {
            currentRow.next('.detailed-row').remove()
            button.removeClass('active').text(button.data('text'))
            return
        }

        button.prop('disabled', true).addClass('active').text('Скрыть')

        const detailedRow = $(`<tr class="detailed-row"><td colspan="6" style="background:lightyellow"><table class="w-100"></table></td></tr>`)
        const table = detailedRow.find('table')

        $.ajax({
            url: button.data('url'),
            contentType: 'application/json',
            headers: {
                'Accept': 'application/json'
            },
            data: {
                ...button.data('payload'),
                _token: button.data('token')
            },
            success: function (response) {
                const headers = $("<tr>");
                response.headers.forEach(header => {
                    headers.append($("<th>").html(header));
                });
                table.append(headers);
                response.rows.forEach(row => {
                    const $tr = $("<tr>");
                    $.each(row, (_, val) => $tr.append($("<td>").html(val)));
                    table.append($tr);
                });
                currentRow.after(detailedRow)
            },
            error: () => alert('error'),
            complete: () => button.prop('disabled', false)
        })
    })
})