import $ from 'jquery'
import './bootstrap'
import select2 from 'select2'
import 'bootstrap-select'

window.$ = $
select2();

window.addEventListener('load', function(){
    $('[data-select="2"]').select2()
})