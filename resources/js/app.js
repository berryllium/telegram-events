import $ from 'jquery'
import './bootstrap'
import select2 from 'select2'
import ClassicEditor from '@ckeditor/ckeditor5-build-classic';

window.$ = $
select2();

window.addEventListener('load', function(){
    $('[data-select="2"]').select2()
    $('[data-role="copy-block"]').click(function(){
        const clone = $(this).prev().clone()
        clone.find('input').each(function() {
            $(this).val('')
        })
        $(this).prev().after(clone)
    })

    $(document).on('click', '[data-role="delete-block"]', function(){
        if($('[data-role="block"]').length > 1) {
            $(this).closest('[data-role="block"]').remove()
        } else {
            alert('Должна быть хотя бы одна дата отправки!')
        }
    })

    $('[data-action="delete"]').submit(function(){
        if(!confirm(($(this).data('text')) ? ($(this).data('text')) : 'Delete?')) {
            return false
        }
    })

    if($('[data-editor="ck"]').length) {
        ClassicEditor
            .create( document.querySelector( '[data-editor="ck"]' ), {
                toolbar: ['bold', 'italic', 'link'],
                coreStyles_bold: { element: 'b', overrides: 'strong' }
            })
            .then( editor => {
                window.editor = editor;
            } )
            .catch( error => {
                console.error( 'There was a problem initializing the editor.', error );
            } );
    }

})