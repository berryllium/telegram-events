<div class="test">
    <button role="detail-button" class="btn btn-primary" type="button" style="width: 120px" data-url="{{ $url }}"
        data-token="{{ csrf_token() }}" data-payload="{{ json_encode($payload) }}">Подробнее</button>
    @once
        <script src="{{ asset('asset/js/components/detailed-table.js') }}"></script>
    @endonce
</div>
