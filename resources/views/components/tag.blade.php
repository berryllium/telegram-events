<div data-role="tags">
    <div class="form-group mb-1">
        <label for="tag-{{ $k }}">{{ $field->name }}</label>
        <textarea class="form-control" id="tag-{{ $k }}" rows="3" name="{{ $field->code }}" {{ $field->required ? 'data-required' : '' }}></textarea>
    </div>
    <div class="form-group mb-3">
        <label for="field-{{ $k }}" >{{ __('webapp.tag_set') }}</label>
        <select class="form-control" id="field-{{ $k }}" data-live-search="true" data-url="{{ config('app.url') }}" multiple>
            @foreach($tag_sets as $set_id => $tag_set)
                <option value="{{ $set_id }}" data-type="{{ $tag_set['type'] }}" class="{{ $tag_set['type'] == 'shop' ? 'text-success' : '' }}">
                    {{ $tag_set['value'] }}
                </option>
            @endforeach
        </select>
    </div>
</div>
<script src="{{ asset('asset/js/components/tag.js?' . rand(1, 1000)) }}"></script>