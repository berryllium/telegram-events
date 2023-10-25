<div class="form-group mb-3 file-block">
    <div>{{ $name }}</div>
    <ul class="list-group mb-2" data-role="fileList"></ul>
    <input id="file" class="form-control d-none" type="file" multiple>
    <input class="form-control d-none" type="file" name="files[]" multiple>
    <label for="file" class="btn btn-primary col-4 me-2">{{__('webapp.add_files')}}</label>
    <div class="btn btn-danger col-4" data-role="clean-files">{{__('webapp.clean')}}</div>
</div>
<script src="{{ asset('asset/js/components/uploader.js?' . rand(1, 1000)) }}"></script>