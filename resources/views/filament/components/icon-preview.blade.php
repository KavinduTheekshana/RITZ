@if (is_string($getState()))
    <div class="mt-3">
        <p class="text-sm text-gray-500">Icon Preview:</p>
        <div class="w-12 h-12">{!! $getState() !!}</div>
    </div>
@endif
