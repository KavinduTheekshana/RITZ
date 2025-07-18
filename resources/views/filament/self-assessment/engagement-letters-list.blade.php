<div class="space-y-4">
    @if($engagementLetters->isEmpty())
        <div class="text-center py-8">
            <x-heroicon-o-document-text class="w-12 h-12 mx-auto text-gray-400 mb-3" />
            <p class="text-gray-500 dark:text-gray-400">No engagement letters have been sent yet.</p>
        </div>
    @else
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="border-b dark:border-gray-700">
                        <th class="text-left py-3 px-4 font-medium text-gray-700 dark:text-gray-300">Date Sent</th>
                        <th class="text-left py-3 px-4 font-medium text-gray-700 dark:text-gray-300">Status</th>
                        <th class="text-left py-3 px-4 font-medium text-gray-700 dark:text-gray-300">Signed By</th>
                        <th class="text-left py-3 px-4 font-medium text-gray-700 dark:text-gray-300">Signed Date</th>
                        <th class="text-left py-3 px-4 font-medium text-gray-700 dark:text-gray-300">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($engagementLetters as $letter)
                        <tr class="border-b dark:border-gray-700">
                            <td class="py-3 px-4">
                                {{ $letter->sent_at->format('M d, Y H:i') }}
                            </td>
                            <td class="py-3 px-4">
                                @if($letter->is_signed)
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200">
                                        <x-heroicon-s-check-circle class="w-3 h-3 mr-1" />
                                        Signed
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200">
                                        <x-heroicon-s-clock class="w-3 h-3 mr-1" />
                                        Pending
                                    </span>
                                @endif
                            </td>
                            <td class="py-3 px-4">
                                {{ $letter->signer_full_name ?? '-' }}
                            </td>
                            <td class="py-3 px-4">
                                {{ $letter->signed_at ? $letter->signed_at->format('M d, Y') : '-' }}
                            </td>
                            <td class="py-3 px-4">
                                <div class="flex space-x-2">
                                    <a href="{{ asset('storage/' . $letter->file_path) }}" 
                                       target="_blank"
                                       class="text-primary-600 hover:text-primary-700 dark:text-primary-400 dark:hover:text-primary-300">
                                        <x-heroicon-o-eye class="w-5 h-5" />
                                    </a>
                                    @if($letter->signed_file_path)
                                        <a href="{{ asset('storage/' . $letter->signed_file_path) }}" 
                                           target="_blank"
                                           class="text-green-600 hover:text-green-700 dark:text-green-400 dark:hover:text-green-300">
                                            <x-heroicon-o-document-check class="w-5 h-5" />
                                        </a>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        
        <!-- Letter Details -->
        @if($engagementLetters->where('is_signed', true)->count() > 0)
            <div class="mt-6 p-4 bg-green-50 dark:bg-green-900/20 rounded-lg">
                <h4 class="text-sm font-medium text-green-900 dark:text-green-100 mb-2">Signature Details</h4>
                @php
                    $signedLetter = $engagementLetters->where('is_signed', true)->first();
                @endphp
                @if($signedLetter)
                    <div class="text-sm text-green-700 dark:text-green-300 space-y-1">
                        <p><strong>IP Address:</strong> {{ $signedLetter->ip }}</p>
                        <p><strong>Email:</strong> {{ $signedLetter->signer_email }}</p>
                        <p><strong>Date:</strong> {{ $signedLetter->signed_at->format('F d, Y H:i:s') }}</p>
                    </div>
                @endif
            </div>
        @endif
    @endif
</div>