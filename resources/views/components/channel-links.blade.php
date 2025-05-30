<div>
    <h3>{{ __('webapp.channel_link_header') }}</h3>
    <div class="mb-2">{{ __('webapp.channel_link_description') }}</div>
    <table class="table table-bordered align-middle" id="channel-links-table">
        <thead>
            <tr>
                <th>{{ __('webapp.channel_link_text') }}</th>
                <th>{{ __('webapp.channel_link_url') }}</th>
                <th style="width: 50px;"></th>
            </tr>
        </thead>
        <tbody>
            @if(isset($channelLinks) && $channelLinks)
                @foreach ($channelLinks as $i => $link)
                    <tr>
                        <td>
                            <input type="hidden" name="channel_links[{{ $i }}][id]" value="{{ $link->id }}">
                            <input type="text" name="channel_links[{{ $i }}][name]" class="form-control" value="{{ $link->name }}">
                        </td>
                        <td>
                            <input type="text" name="channel_links[{{ $i }}][link]" class="form-control" value="{{ $link->link }}">
                        </td>
                        <td class="text-center">
                            <button type="button" class="btn btn-sm btn-outline-danger remove-row">
                                &times;
                            </button>
                        </td>
                    </tr>
                @endforeach
            @endif
        </tbody>
    </table>

    <button type="button" class="btn btn-primary" id="add-link">{{ __('webapp.channel_link_add') }}</button>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        let index = {{ count($channelLinks) }};
        const tableBody = document.querySelector('#channel-links-table tbody');
        const addButton = document.querySelector('#add-link');

        addButton.addEventListener('click', () => {
            const row = document.createElement('tr');
            row.innerHTML = `
                <td>
                    <input type="hidden" name="channel_links[${index}][id]" value="">
                    <input type="text" name="channel_links[${index}][name]" class="form-control">
                </td>
                <td>
                    <input type="text" name="channel_links[${index}][link]" class="form-control">
                </td>
                <td class="text-center">
                    <button type="button" class="btn btn-sm btn-outline-danger remove-row">&times;</button>
                </td>
            `;
            tableBody.appendChild(row);
            index++;
        });

        tableBody.addEventListener('click', function (e) {
            if (e.target.classList.contains('remove-row')) {
                e.target.closest('tr').remove();
            }
        });
    });
</script>
