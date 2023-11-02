<div class="price">
    <div class="form-group mb-3">
        <label for="price_type">{{ __('webapp.price_type') }}</label>
        <select class="form-control" id="price_type" name="price_type">
            @foreach(\App\Models\Form::$price_types as $type)
                <option value="{{ $type }}" {{ $defaultType == $type ? 'selected' : '' }}>{{ __("webapp.price_$type") }}</option>
            @endforeach
        </select>
    </div>
    <div class="form-group mb-3" data-role="price" style="display:{{ $showPrice ? 'initial' : 'none' }};">
        <label for="price" class="form-label">
            {{ __('webapp.price') }}
            <span data-role="from" style="display:{{ $showFrom ? 'initial' : 'none' }};">
                {{ __('webapp.from') }}
            </span>
        </label>
        <input id="price" class="form-control" name="price" type="number" value="" placeholder="" {{ $isRequired ? 'data-required' : '' }}>
    </div>
    <div class="form-group mb-3" style="display:{{ $showPriceTo ? 'initial' : 'none' }};" data-role="price_to">
        <label for="price_to" class="form-label">
            {{ __('webapp.price') }}
            <span data-role="to" style="display:{{ $showTo ? 'initial' : 'none' }};">
                {{ __('webapp.to') }}
            </span>
        </label>
        <input id="price_to" class="form-control" name="price_to" type="number" value="" placeholder="">
    </div>
</div>