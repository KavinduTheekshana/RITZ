<div>
    <div class="space-y-4">
        <div>
            <p class="text-sm text-gray-500">Sender email:</p>
            <p>{{ $email }}</p>
        </div>

        <div>
            <p class="text-sm text-gray-500">Received on:</p>
            <p>{{ $created_at->format('F j, Y, g:i a') }}</p>
        </div>

        <div>
            <p class="text-sm text-gray-500">Message:</p>
            <div class="p-4 bg-gray-50 rounded-lg mt-1">
                {!! nl2br(e($message)) !!}
            </div>
        </div>
    </div>
</div>