<x-filament-widgets::widget>
    <x-filament::section>
        <x-slot name="heading">
            <div class="flex items-center space-x-2">
                <x-heroicon-o-bolt class="w-5 h-5 text-primary-600" />
                <span class="font-semibold">Quick Actions</span>
            </div>
        </x-slot>
        
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            @foreach($actions as $action)
                <a href="{{ $action['url'] }}" 
                   class="group flex flex-col items-center p-4 rounded-lg border transition-all duration-200 hover:shadow-md {{ 
                       match($action['color']) {
                           'primary' => 'bg-blue-50 border-blue-200 hover:bg-blue-100 dark:bg-blue-900/20 dark:border-blue-800 dark:hover:bg-blue-900/30',
                           'success' => 'bg-green-50 border-green-200 hover:bg-green-100 dark:bg-green-900/20 dark:border-green-800 dark:hover:bg-green-900/30',
                           'warning' => 'bg-yellow-50 border-yellow-200 hover:bg-yellow-100 dark:bg-yellow-900/20 dark:border-yellow-800 dark:hover:bg-yellow-900/30',
                           'info' => 'bg-cyan-50 border-cyan-200 hover:bg-cyan-100 dark:bg-cyan-900/20 dark:border-cyan-800 dark:hover:bg-cyan-900/30',
                           default => 'bg-gray-50 border-gray-200 hover:bg-gray-100 dark:bg-gray-900/20 dark:border-gray-800 dark:hover:bg-gray-900/30'
                       }
                   }}">
                    
                    <div class="w-12 h-12 mb-3 rounded-full flex items-center justify-center {{ 
                        match($action['color']) {
                            'primary' => 'bg-blue-100 text-blue-600 dark:bg-blue-900 dark:text-blue-400',
                            'success' => 'bg-green-100 text-green-600 dark:bg-green-900 dark:text-green-400',
                            'warning' => 'bg-yellow-100 text-yellow-600 dark:bg-yellow-900 dark:text-yellow-400',
                            'info' => 'bg-cyan-100 text-cyan-600 dark:bg-cyan-900 dark:text-cyan-400',
                            default => 'bg-gray-100 text-gray-600 dark:bg-gray-900 dark:text-gray-400'
                        }
                    }}">
                        @switch($action['icon'])
                            @case('heroicon-o-user-plus')
                                <x-heroicon-o-user-plus class="w-6 h-6" />
                                @break
                            @case('heroicon-o-building-office-2')
                                <x-heroicon-o-building-office-2 class="w-6 h-6" />
                                @break
                            @case('heroicon-o-document-plus')
                                <x-heroicon-o-document-plus class="w-6 h-6" />
                                @break
                            @case('heroicon-o-chat-bubble-left-right')
                                <x-heroicon-o-chat-bubble-left-right class="w-6 h-6" />
                                @break
                            @default
                                <x-heroicon-o-plus class="w-6 h-6" />
                        @endswitch
                    </div>
                    
                    <span class="text-sm font-medium text-center mb-1 {{ 
                        match($action['color']) {
                            'primary' => 'text-blue-900 dark:text-blue-100 group-hover:text-blue-700',
                            'success' => 'text-green-900 dark:text-green-100 group-hover:text-green-700',
                            'warning' => 'text-yellow-900 dark:text-yellow-100 group-hover:text-yellow-700',
                            'info' => 'text-cyan-900 dark:text-cyan-100 group-hover:text-cyan-700',
                            default => 'text-gray-900 dark:text-gray-100 group-hover:text-gray-700'
                        }
                    }}">
                        {{ $action['label'] }}
                    </span>
                    
                    <span class="text-xs text-gray-500 dark:text-gray-400 text-center leading-tight">
                        {{ $action['description'] }}
                    </span>
                </a>
            @endforeach
        </div>
    </x-filament::section>
</x-filament-widgets::widget>