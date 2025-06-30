<?php

namespace App\Filament\Resources\CompanyResource\Pages;

use App\Filament\Resources\CompanyResource;
use App\Models\Company;
use App\Models\CompanyChatList;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\ViewField;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\Concerns\InteractsWithRecord;
use Filament\Resources\Pages\Page;
use Filament\Actions\Action;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Livewire\WithFileUploads;

class CompanyChat extends Page
{
    use InteractsWithRecord, WithFileUploads;

    protected static string $resource = CompanyResource::class;

    protected static string $view = 'filament.company.company-chat';

    protected static ?string $title = 'Chat';

    public $sendMessageData = [
        'message' => '',
    ];
    
    public $uploadedFile = null;
    public $uploadedFileName = '';

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
                ->url(fn(): string => CompanyResource::getUrl('edit', ['record' => $this->record])),

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
                ->visible(fn() => $this->getUnreadClientMessages() > 0),
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

                        ViewField::make('custom_file_upload')
                            ->view('filament.company.custom-file-upload')
                            ->label('Attach File'),
                    ])
                    ->statePath('sendMessageData')
            ),
        ];
    }

    protected function sendMessageForm(Form $form): Form
    {
        return $form;
    }

    public function removeFile(): void
    {
        $this->uploadedFile = null;
        $this->uploadedFileName = '';
        $this->dispatch('file-removed');
    }

    public function sendMessage(): void
    {
        $data = $this->sendMessageForm->getState();

        // Validate that either message or file is provided
        if (empty($data['message']) && !$this->uploadedFile) {
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
        if ($this->uploadedFile) {
            $path = $this->uploadedFile->store('company-chat', 'public');
            
            $chatData['file_path'] = $path;
            $chatData['file_name'] = $this->uploadedFile->getClientOriginalName();
            $chatData['file_size'] = $this->uploadedFile->getSize();
            $chatData['file_type'] = $this->uploadedFile->getMimeType();
        }

        CompanyChatList::create($chatData);

        // Clear everything
        $this->sendMessageData = ['message' => ''];
        $this->uploadedFile = null;
        $this->uploadedFileName = '';
        $this->dispatch('clear-file-input');

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