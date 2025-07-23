<?php

namespace App\Filament\Resources\SelfAssessmentResource\Pages;

use App\Filament\Resources\SelfAssessmentResource;
use App\Models\SelfAssessment;
use App\Models\SelfAssessmentChatList;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\Page;
use Illuminate\Support\Facades\Auth;
use Livewire\WithFileUploads;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;
use Filament\Forms\Components\ViewField;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;

class SelfAssessmentChat extends Page
{
    use WithFileUploads;

    protected static string $resource = SelfAssessmentResource::class;
    protected static string $view = 'filament.self-assessment.self-assessment-chat';
    
    public SelfAssessment $record;
    public ?array $sendMessageData = ['message' => '', 'requires_signature' => false];
    public $uploadedFile = null;
    public $uploadedFileName = '';

    public function mount(SelfAssessment $record): void
    {
        $this->record = $record;
        static::authorizeResourceAccess();
    }

    protected function getHeaderActions(): array
    {
        return [];
    }

    public function getTitle(): string
    {
        return "Chat with {$this->record->assessment_name}";
    }

    protected function getForms(): array
    {
        return [
            'sendMessageForm' => $this->makeForm()
                ->schema($this->getFormSchema())
                ->statePath('sendMessageData')
        ];
    }

    protected function getFormSchema(): array
    {
        return [
            Textarea::make('message')
                ->label('Message')
                ->placeholder('Type your message here...')
                ->rows(3)
                ->maxLength(1000),

            ViewField::make('custom_file_upload')
                ->view('filament.company.custom-file-upload')
                ->label('Attach File'),
                
            Toggle::make('requires_signature')
                ->label('Require client signature')
                ->helperText('Check this if the client needs to sign the attached PDF document')
                ->visible(fn () => $this->uploadedFile !== null && $this->uploadedFile->getMimeType() === 'application/pdf')
                ->default(false),
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
            'self_assessment_id' => $this->record->id,
            'user_id' => Auth::id(),
            'sender_type' => 'admin',
            'sender_name' => Auth::user()->name,
            'sender_email' => Auth::user()->email,
            'message' => $data['message'] ?? null,
            'sent_at' => now(),
            'is_read' => false,
        ];

        // Handle file upload
        if ($this->uploadedFile) {
            $path = $this->uploadedFile->store('self-assessment-chat', 'public');
            
            $chatData['file_path'] = $path;
            $chatData['file_name'] = $this->uploadedFile->getClientOriginalName();
            $chatData['file_size'] = $this->uploadedFile->getSize();
            $chatData['file_type'] = $this->uploadedFile->getMimeType();
            
            // Check if signature is required
            $chatData['requires_signature'] = $data['requires_signature'] ?? false;
        }

        SelfAssessmentChatList::create($chatData);

        // Clear everything
        $this->sendMessageData = ['message' => '', 'requires_signature' => false];
        $this->uploadedFile = null;
        $this->uploadedFileName = '';
        $this->dispatch('clear-file-input');
        
        // Refresh the chat messages
        $this->dispatch('$refresh');

        Notification::make()
            ->title('Message Sent')
            ->body('Your message has been sent successfully.')
            ->success()
            ->send();
    }

    public function loadMoreMessages(): void
    {
        // Implementation for loading more messages
        $this->dispatch('$refresh');
    }

    public function updatedUploadedFile($value): void
    {
        if ($value) {
            $this->uploadedFileName = $value->getClientOriginalName();
        }
    }

    public function getMessagesProperty()
    {
        return SelfAssessmentChatList::where('self_assessment_id', $this->record->id)
            ->with(['user', 'client'])
            ->orderBy('sent_at', 'asc') // Changed from 'desc' to 'asc' for chronological order
            ->limit(50)
            ->get();
    }

    public static function authorizeResourceAccess(): void
    {
        abort_unless(static::getResource()::canViewAny(), 403);
    }
}