import $ from 'jquery'
import './bootstrap'
import select2 from 'select2'
import ClassicEditor from '@ckeditor/ckeditor5-build-classic';

window.$ = $
select2();

window.addEventListener('load', function(){
    $('[data-select="2"]').select2()
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
})