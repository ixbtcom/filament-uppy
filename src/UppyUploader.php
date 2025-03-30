<?php

namespace STS\FilamentUppy;

use Closure;
use Illuminate\Support\Facades\App;
use Filament\Forms\Components\Field;
use Filament\Forms\Components\Concerns;

class UppyUploader extends Field
{
    use Concerns\HasUploadingMessage;

    // Defaults to the built-in companion instance's upload route.
    protected Closure|string $uploadEndpoint = '';

    protected Closure|string $successEndpoint = '';

    protected Closure|string $deleteEndpoint = '';

    protected Closure|array $restrictions = [];

    protected Closure|string $emptyIcon = 'heroicon-o-cloud-arrow-up';

    protected Closure|string $emptyMessage = 'Drop files here or click to upload.';
    
    protected Closure|bool $showDownloadLinks = false;
    
    protected Closure|bool $showOpenLinks = false;
    
    protected Closure|array $downloadLabels = [
        'en' => 'Download',
        'ru' => 'Скачать'
    ];
    
    protected Closure|array $openLabels = [
        'en' => 'Open',
        'ru' => 'Открыть'
    ];
    
    protected Closure|array $fileLabels = [
        'en' => 'File',
        'ru' => 'Файл'
    ];
    
    protected Closure|array $sizeLabels = [
        'en' => 'Size',
        'ru' => 'Размер'
    ];
    
    protected Closure|array $addFilesLabels = [
        'en' => 'Add files',
        'ru' => 'Добавить файлы'
    ];
    
    protected Closure|array $removeLabels = [
        'en' => 'Remove',
        'ru' => 'Удалить'
    ];
    
    protected Closure|array $cancelLabels = [
        'en' => 'Cancel',
        'ru' => 'Отмена'
    ];
    
    protected Closure|array $errorLabels = [
        'en' => 'Error',
        'ru' => 'Ошибка'
    ];

    protected string $view = 'filament-uppy::uppy-uploader';

    /**
     * Manually provide endpoints for the Uppy instance and for success and delete callbacks.
     *
     * @param Closure|string $upload The base endpoint the Uppy instance will use to sign uploads.
     * @param Closure|string $success The endpoint to hit after a successful upload. (optional)
     * @param Closure|string $delete The endpoint to hit after deleting a file. (optional)
     * @return $this
     */
    public function endpoints(Closure|string $upload = '', Closure|string $success = '', Closure|string $delete = ''): static
    {
        if (!empty($upload)) {
            $this->uploadEndpoint = $upload;
        }

        if (!empty($success)) {
            $this->successEndpoint = $success;
        }

        if (!empty($delete)) {
            $this->deleteEndpoint = $delete;
        }

        return $this;
    }

    /**
     * The icon to display when there are no files uploaded.
     * Defaults to 'heroicon-o-cloud-arrow-up'.
     *
     * @param Closure|string $icon
     * @return $this
     */
    public function emptyIcon(Closure|string $icon): static
    {
        $this->emptyIcon = $icon;

        return $this;
    }

    /**
     * The message to display when there are no files uploaded.
     * Defaults to 'Drop files here or click to upload.'.
     *
     * @param Closure|string $message
     * @return $this
     */
    public function emptyMessage(Closure|string $message): static
    {
        $this->emptyMessage = $message;

        return $this;
    }

    /**
     * The array of restrictions to apply to the Uppy instance. Expects an associative array
     * with the keys being the restriction name and the value being the restriction value to be passed
     * to the Uppy instance.
     *
     * Allowed keys are:
     * maxFileSize - number - maximum file size in bytes for each individual file
     * minFileSize - number - minimum file size in bytes for each individual file
     * maxTotalFileSize - number - maximum file size in bytes for all the files that can be selected for upload
     * maxNumberOfFiles - number - total number of files that can be selected
     * minNumberOfFiles - number - minimum number of files that must be selected before the upload
     * allowedFileTypes - array of strings - wildcards image/*, or exact mime types image/jpeg, or file extensions .jpg: ['image/*', '.jpg', '.jpeg', '.png', '.gif']
     * requiredMetaFields - array of strings - make keys from the meta object in every file required before uploading
     *
     * Source: https://uppy.io/docs/uppy/#restrictions
     *
     * @param Closure|array $restrictions
     * @return $this
     */
    public function restrictions(Closure|array $restrictions): static
    {
        $this->restrictions = $restrictions;

        return $this;
    }

    public function getUploadEndpoint(): string
    {
        return $this->evaluate($this->uploadEndpoint);
    }

    public function getSuccessEndpoint(): string
    {
        return $this->evaluate($this->successEndpoint);
    }

    public function getDeleteEndpoint(): string
    {
        return $this->evaluate($this->deleteEndpoint);
    }

    public function getEmptyIcon(): string
    {
        return $this->evaluate($this->emptyIcon);
    }

    public function getEmptyMessage(): string
    {
        return $this->evaluate($this->emptyMessage);
    }

    public function getRestrictions(): array
    {
        return $this->evaluate($this->restrictions);
    }
    
    public function showDownloadLinks(bool|Closure $condition = true): static
    {
        $this->showDownloadLinks = $condition;
        
        return $this;
    }
    
    public function showOpenLinks(bool|Closure $condition = true): static
    {
        $this->showOpenLinks = $condition;
        
        return $this;
    }
    
    public function getShowDownloadLinks(): bool
    {
        return (bool) $this->evaluate($this->showDownloadLinks);
    }
    
    public function getShowOpenLinks(): bool
    {
        return (bool) $this->evaluate($this->showOpenLinks);
    }
    
    /**
     * Получает текущую локаль из Laravel
     * 
     * @return string
     */
    protected function getLocale(): string
    {
        $locale = App::getLocale();
        
        // Проверяем, поддерживается ли локаль, если нет - возвращаем 'en'
        return in_array($locale, ['en', 'ru']) ? $locale : 'en';
    }
    
    public function getDownloadLabel(): string
    {
        $labels = $this->evaluate($this->downloadLabels);
        $locale = $this->getLocale();
        
        return $labels[$locale] ?? $labels['en'];
    }
    
    public function getOpenLabel(): string
    {
        $labels = $this->evaluate($this->openLabels);
        $locale = $this->getLocale();
        
        return $labels[$locale] ?? $labels['en'];
    }
    
    public function getFileLabel(): string
    {
        $labels = $this->evaluate($this->fileLabels);
        $locale = $this->getLocale();
        
        return $labels[$locale] ?? $labels['en'];
    }
    
    public function getSizeLabel(): string
    {
        $labels = $this->evaluate($this->sizeLabels);
        $locale = $this->getLocale();
        
        return $labels[$locale] ?? $labels['en'];
    }
    
    public function getAddFilesLabel(): string
    {
        $labels = $this->evaluate($this->addFilesLabels);
        $locale = $this->getLocale();
        
        return $labels[$locale] ?? $labels['en'];
    }
    
    public function getRemoveLabel(): string
    {
        $labels = $this->evaluate($this->removeLabels);
        $locale = $this->getLocale();
        
        return $labels[$locale] ?? $labels['en'];
    }
    
    public function getCancelLabel(): string
    {
        $labels = $this->evaluate($this->cancelLabels);
        $locale = $this->getLocale();
        
        return $labels[$locale] ?? $labels['en'];
    }
    
    public function getErrorLabel(): string
    {
        $labels = $this->evaluate($this->errorLabels);
        $locale = $this->getLocale();
        
        return $labels[$locale] ?? $labels['en'];
    }
}
