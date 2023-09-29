<form class="row mb-4 d-flex justify-content-center">
    @yield('filters')
    <div class="col-lg-3 col-6 my-1">
        <button type="submit" class="btn btn-primary">{{ __('webapp.search') }}</button>
    </div>
</form>