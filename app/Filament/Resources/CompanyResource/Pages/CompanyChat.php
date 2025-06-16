<?php

namespace App\Filament\Resources\CompanyResource\Pages;

use App\Filament\Resources\CompanyResource;
use App\Models\Company;
use App\Models\CompanyChatList;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\Concerns\InteractsWithRecord;
use Filament\Resources\Pages\Page;
use Filament\Actions\Action;
use Illuminate\Support\Facades\Auth;

class CompanyChat extends Page
{
    use InteractsWithRecord;

    protected static string $resource = CompanyResource::class;

    protected static string $view = 'filament.company.company-chat';

    protected static ?string $title = 'Chat';

    public $sendMessageData = [];

    public function mount(int | string $record): void
    {
        $this->record = $this->resolveRecord($record);
        
        // Mark all client messages as read for this company
        CompanyChatList::where('company_id', $this->record->id)
            ->where('sender_type', 'client')
            ->where('is_read', false)
            ->update(['is_read' => true]);
    }

    protected function getHeaderActions(): array
    {
        return [
            Action::make('back')
                ->label('Back to Company')
                ->icon('heroicon-o-arrow-left')
                ->url(fn (): string => CompanyResource::getUrl('edit', ['record' => $this->record])),
                
            Action::make('markAllRead')
                ->label('Mark All Read')
                ->icon('heroicon-o-check-circle')
                ->color('success')
                ->action(function () {
                    CompanyChatList::where('company_id', $this->record->id)
                        ->where('is_read', false)
                        ->update(['is_read' => true]);
                        
                    Notification::make()
                        ->title('All messages marked as read')
                        ->success()
                        ->send();
                })
                ->visible(fn () => $this->getUnreadClientMessages() > 0),
        ];
    }

    protected function getForms(): array
    {
        return [
            'sendMessageForm' => $this->sendMessageForm(
                $this->makeForm()
                    ->schema([
                        Textarea::make('message')
                            ->label('Message')
                            ->placeholder('Type your message here...')
                            ->rows(3)
                            ->maxLength(1000),

                        FileUpload::make('file')
                            ->label('Attach File')
                            ->directory('company-chat')
                            ->preserveFilenames()
                            ->maxSize(10240) // 10MB
                            ->acceptedFileTypes([
                                'application/pdf',
                                'application/msword',
                                'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
                                'application/vnd.ms-excel',
                                'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                                'image/jpeg',
                                'image/png',
                                'image/gif',
                                'text/plain',
                            ])
                            ->helperText('Supported formats: PDF, DOC, DOCX, XLS, XLSX, JPG, PNG, GIF, TXT (Max: 10MB)'),
                    ])
                    ->statePath('sendMessageData')
            ),
        ];
    }

    protected function sendMessageForm(Form $form): Form
    {
        return $form;
    }

    public function sendMessage(): void
    {
        $data = $this->sendMessageForm->getState();

        // Validate that either message or file is provided
        if (empty($data['message']) && empty($data['file'])) {
            Notification::make()
                ->title('Error')
                ->body('Please provide either a message or attach a file.')
                ->danger()
                ->send();
            return;
        }

        $chatData = [
            'company_id' => $this->record->id,
            'user_id' => Auth::id(),
            'sender_type' => 'admin',
            'sender_name' => Auth::user()->name,
            'sender_email' => Auth::user()->email,
            'message' => $data['message'] ?? null,
        ];

        // Handle file upload
        if (!empty($data['file'])) {
            $file = $data['file'];
            $chatData['file_path'] = $file;
            $chatData['file_name'] = basename($file);
            
            // Get file info
            $fullPath = storage_path('app/public/' . $file);
            if (file_exists($fullPath)) {
                $chatData['file_size'] = filesize($fullPath);
                $chatData['file_type'] = mime_content_type($fullPath);
            }
        }

        CompanyChatList::create($chatData);

        // Reset form
        $this->sendMessageForm->fill([]);
        $this->sendMessageData = [];

        Notification::make()
            ->title('Message Sent')
            ->body('Your message has been sent successfully.')
            ->success()
            ->send();
    }

    public function getChatMessages()
    {
        return CompanyChatList::where('company_id', $this->record->id)
            ->with(['user', 'client'])
            ->orderBy('created_at', 'asc')
            ->get();
    }

    public function getUnreadClientMessages(): int
    {
        return CompanyChatList::where('company_id', $this->record->id)
            ->where('sender_type', 'client')
            ->where('is_read', false)
            ->count();
    }

    public function getTitle(): string
    {
        $unreadCount = $this->getUnreadClientMessages();
        $title = "Chat - {$this->record->company_name}";
        
        if ($unreadCount > 0) {
            $title .= " ({$unreadCount} unread)";
        }
        
        return $title;
    }
}