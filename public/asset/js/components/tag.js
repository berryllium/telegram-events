window.onload = function() {
    const select =  $('[data-role="tags"] select')

    select.select2({
        templateResult: function (data, container) {
            if (data.element) {
                $(container).addClass($(data.element).attr("class"));
            }
            return data.text;
        }
    });

    select.on('select2:select', function (e) {
        const textarea =  $(this).closest('[data-role="tags"]').find('textarea')
        textarea.val(textarea.val() + ' ' + e.params.data.id)
    }).on('select2:unselect', function (e) {
        const textarea =  $(this).closest('[data-role="tags"]').find('textarea')
        const pattern = new RegExp(`[ ,]*${e.params.data.id}`)
        textarea.val(textarea.val().replace(pattern, ''))
    })

    $('[name="place"]').change(function (){
        getPlaceTagSets($(this).val())
    })

    function getPlaceTagSets(place) {
        $.ajax({
            url: `/place/${place}/tags`,
            method: 'post',
            data: {
                "_token": $('[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                $('[data-role="tags"]').find('textarea').val('')
                select.find('option').each(function(){
                    if($(this).data('type') === 'shop') {
                        $(this).remove()
                    }
                })
                if(response) {
                    for(let set_id in response) {
                        let tag_set = response[set_id]
                        let optionClass = tag_set['type'] === 'shop' ? 'text-success' : ''
                        select.append(
                            `<option value="${set_id}" data-type="${tag_set['type']}" class="${optionClass}">
                                ${ tag_set['value'] }
                            </option>`)
                    }
                }
                select.val(null).change()
            }
        })
    }
}