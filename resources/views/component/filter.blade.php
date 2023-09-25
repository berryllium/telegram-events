<form class="row mb-4 d-flex justify-content-center">
    @yield('filters')
    <div class="col-auto">
        <button type="submit" class="btn btn-primary">{{ __('webapp.search') }}</button>
    </div>
</form>