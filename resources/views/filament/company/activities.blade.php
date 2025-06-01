{{-- resources/views/filament/company/activities.blade.php --}}

<div class="space-y-6">
    <div class="border-b pb-4">
        <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">
            Company Activities
        </h3>
        <p class="text-sm text-gray-600 dark:text-gray-400">
            All activities and documents related to {{ $company->company_name }}
        </p>
    </div>

    {{-- Engagement Letters Section --}}
    <div class="space-y-4">
        <div class="flex items-center justify-between">
            <h4 class="text-base font-medium text-gray-900 dark:text-gray-100">
                Engagement Letters
            </h4>
            @if ($engagementLetters->count() > 0)
                <span
                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200">
                    {{ $engagementLetters->count() }} {{ Str::plural('letter', $engagementLetters->count()) }}
                </span>
            @endif
        </div>

        @if ($engagementLetters->count() > 0)
            <div class="space-y-3">
                @foreach ($engagementLetters as $letter)
                    <div class="bg-gray-50 dark:bg-gray-800 rounded-lg p-4 border border-gray-200 dark:border-gray-700">
                        <div class="flex items-start justify-between">
                            <div class="flex-1">
                                <div class="flex items-center space-x-3">
                                    <div class="flex-shrink-0">
                                        @if ($letter->is_signed)
                                            <div
                                                class="w-8 h-8 bg-green-100 dark:bg-green-900 rounded-full flex items-center justify-center">
                                                <svg class="w-5 h-5 text-green-600 dark:text-green-400"
                                                    fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd"
                                                        d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                                        clip-rule="evenodd"></path>
                                                </svg>
                                            </div>
                                        @else
                                            <div
                                                class="w-8 h-8 bg-yellow-100 dark:bg-yellow-900 rounded-full flex items-center justify-center">
                                                <svg class="w-5 h-5 text-yellow-600 dark:text-yellow-400"
                                                    fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd"
                                                        d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z"
                                                        clip-rule="evenodd"></path>
                                                </svg>
                                            </div>
                                        @endif
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <p class="text-sm font-medium text-gray-900 dark:text-gray-100">
                                            {{ $letter->file_name ?? 'Engagement Letter' }}
                                        </p>
                                        <div
                                            class="mt-1 flex items-center space-x-4 text-xs text-gray-500 dark:text-gray-400">
                                            <span>
                                                Sent: {{ $letter->sent_at->format('M j, Y g:i A') }}
                                            </span>
                                            @if ($letter->sent_by)
                                                <span>
                                                    By: {{ $letter->sent_by }}
                                                </span>
                                            @endif
                                        </div>
                                        @if ($letter->is_signed)
                                            <div class="mt-2 space-y-1">
                                                <div
                                                    class="flex items-center space-x-4 text-xs text-gray-500 dark:text-gray-400">
                                                    @if ($letter->signer_full_name)
                                                        <span>
                                                            Signed by: {{ $letter->signer_full_name }}
                                                        </span>
                                                    @endif
                                                    @if ($letter->signed_date)
                                                        <span>
                                                            Date:
                                                            {{ \Carbon\Carbon::parse($letter->signed_date)->format('M j, Y') }}
                                                        </span>
                                                    @endif
                                                </div>
                                                @if ($letter->signer_email)
                                                    <div class="text-xs text-gray-500 dark:text-gray-400">
                                                        Email: {{ $letter->signer_email }}
                                                    </div>
                                                @endif
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="flex items-center space-x-2">
                                {{-- Status Badge --}}
                                {{-- Status Badge --}}
                                @if ($letter->is_signed)
                                    <span
                                        style="display: inline-flex; align-items: center; padding: 2px 10px; border-radius: 20px;margin: 0px 4px; font-size: 12px; font-weight: 500; background-color: #dcfce7; color: #166534; border: 1px solid #bbf7d0;">
                                        Signed
                                    </span>
                                @else
                                    <span
                                        style="display: inline-flex; align-items: center; padding: 2px 10px; border-radius: 20px; font-size: 12px;margin: 0px 4px; font-weight: 500; background-color: #fef3c7; color: #92400e; border: 1px solid #fde68a;">
                                        Pending
                                    </span>
                                @endif

                                {{-- Actions --}}
                                <div class="flex space-x-1">
                                    @if ($letter->file_path && Storage::disk('public')->exists($letter->file_path))
                                        <a href="{{ Storage::disk('public')->url($letter->file_path) }}" target="_blank"
                                            title="Download Original PDF"
                                            style="display: inline-flex; align-items: center; padding: 6px;margin: 0px 4px; border: none; border-radius: 6px; box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05); color: white; background-color: #2563eb; text-decoration: none; transition: background-color 0.2s;"
                                            onmouseover="this.style.backgroundColor='#1d4ed8'"
                                            onmouseout="this.style.backgroundColor='#2563eb'">
                                            <svg style="width: 16px; height: 16px;" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                                </path>
                                            </svg>
                                        </a>
                                    @endif

                                    @if ($letter->is_signed && $letter->signed_file_path && Storage::disk('public')->exists($letter->signed_file_path))
                                        <a href="{{ Storage::disk('public')->url($letter->signed_file_path) }}"
                                            target="_blank" title="Download Signed PDF"
                                            style="display: inline-flex; align-items: center; padding: 6px;margin: 0px 4px; border: none; border-radius: 6px; box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05); color: white; background-color: #16a34a; text-decoration: none; transition: background-color 0.2s;"
                                            onmouseover="this.style.backgroundColor='#15803d'"
                                            onmouseout="this.style.backgroundColor='#16a34a'">
                                            <svg style="width: 16px; height: 16px;" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                            </svg>
                                        </a>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="text-center py-8">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width: 100px">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                    </path>
                </svg>
                <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-gray-100">No engagement letters</h3>
                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">No engagement letters have been sent to this
                    company yet.</p>
            </div>
        @endif
    </div>

    {{-- Future Activities Placeholder --}}
    <div class="space-y-4">
        {{-- <div class="border-t pt-4">
            <h4 class="text-base font-medium text-gray-900 dark:text-gray-100">
                Other Activities
            </h4>
            <div class="mt-3 text-center py-8">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                </svg>
                <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-gray-100">More activities coming soon</h3>
                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Additional activity types will be added here in future updates.</p>
            </div>
        </div> --}}
    </div>
</div>
