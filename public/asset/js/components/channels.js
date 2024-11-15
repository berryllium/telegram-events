window.addEventListener('load', function() {
    const block = $('[data-role="channels"]')
    const select =  block.find('#channels')
    const switcher = block.find('#all_channels')

    select.select2({
        placeholder: '',
        allowClear: true,
        searchInputPlaceholder: ''
    });

    select.on('select2:unselect', function() {
        switcher.prop('checked', false)
    });

    select.on('select2:clear', function() {
        switcher.prop('checked', false)
    });

    switcher.on('change', function (e) {
        if(e.target.checked) {
            select.val(select.find('option').map(function() {
                return $(this).val();
            }).get()).trigger('change');
        } else {
            select.val(null).trigger('change');
        }
    })

})