window.addEventListener('load', () => {
    const webApp =  window.Telegram.WebApp
    const form = $('#webapp-form')
    const spinner = $('[data-role="spinner"]')
    let ajaxObj = false
    webApp.expand()
    webApp.MainButton.text = "Send";
    webApp.MainButton.show()

    Telegram.WebApp.onEvent("mainButtonClicked", async function(){
        if(ajaxObj) return

        if(!validateForm()) {
            alert(form.data('error-message') ? form.data('error-message') : 'Заполните обязательные поля!')
            return
        }

        spinner.removeClass('d-none')
        const formData = new FormData(form[0])
        if(typeof fileCollection != "undefined" && fileCollection &&  Object.keys(fileCollection).length) {
            for (let i in fileCollection) {
                formData.append("files[]", fileCollection[i]);
            }
        }

        ajaxObj = $.ajax({
            url: form.attr("action"),
            type: 'POST',
            data: formData,
            success: function (response) {
                if(response.message_id) {
                    webApp.sendData(JSON.stringify(response))
                } else if(response.error) {
                    alert(response.error)
                }
            },
            error: function () {
                alert('Unexpected error')
            },
            complete: function() {
                ajaxObj = false
                spinner.addClass('d-none')
            },
            cache: false,
            contentType: false,
            processData: false
        });
    });

    $('[name="place"]').change(function (){
        $('[name="address"]').val($(this).val()).trigger('change')
    })

    $('[data-role="tags"] select').on('select2:select', function (e) {
        const textarea =  $(this).closest('[data-role="tags"]').find('textarea')
        textarea.val(textarea.val() + ' ' + e.params.data.id)
    }).on('select2:unselect', function (e) {
        const textarea =  $(this).closest('[data-role="tags"]').find('textarea')
        const pattern = new RegExp(`[ ,]*${e.params.data.id}`)
        textarea.val(textarea.val().replace(pattern, ''))
    });

    $('[name="only_date"]').change(function(){
        const dateInput =  $('[name="date"]')
        const current = dateInput.val()
        if($(this).prop('checked')) {
            dateInput.attr('type', 'date').val(current ? current.split('T')[0] : '')
        } else {
            dateInput.attr('type', 'datetime-local').val(current ? current + 'T00:00' : '')
        }
    })

    $('[name="price_type"]').change(function(){
        const block = $(this).closest('.price')
        const blockPrice =  block.find('[data-role="price"]')
        const blockPriceTo =  block.find('[data-role="price_to"]')
        const blockFrom =  blockPrice.find('[data-role="from"]')
        const blockTo =  blockPriceTo.find('[data-role="to"]')

        if($(this).val() === 'range') {
            blockFrom.show()
            blockPriceTo.show().find('input').attr('data-required', 1)
            blockTo.show()
        } else if($(this).val() === 'min') {
            blockFrom.show()
            blockPriceTo.hide().find('input').removeAttr('data-required')
            blockTo.hide()
        } else {
            blockFrom.hide()
            blockPriceTo.hide().find('input').removeAttr('data-required')
            blockTo.hide()
        }
    })

    function validateForm() {
        let isValid = true
        form.find('[data-required]').each(function(){
            console.log($(this))
            const el = $(this)
            if(!el.val()) {
                setError(el)
                isValid = false
            } else {
                unsetError(el)
            }
        })
        return isValid
    }

    function setError(el) {
        el.addClass('is-invalid')
    }

    function unsetError(el) {
        el.removeClass('is-invalid')
    }

})