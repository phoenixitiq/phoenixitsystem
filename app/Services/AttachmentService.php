<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use App\Models\Attachment;
use Illuminate\Database\Eloquent\Model;

class AttachmentService
{
    public function store(UploadedFile $file, string $directory = 'attachments'): string
    {
        $filename = $this->generateFilename($file);
        return $file->storeAs($directory, $filename, 'public');
    }

    public function delete(string $path): bool
    {
        return Storage::disk('public')->delete($path);
    }

    private function generateFilename(UploadedFile $file): string
    {
        $extension = $file->getClientOriginalExtension();
        return Str::uuid() . '.' . $extension;
    }

    public function isValidFileType(UploadedFile $file, array $allowedTypes): bool
    {
        return in_array($file->getClientMimeType(), $allowedTypes);
    }

    public function getFileSize(string $path): int
    {
        return Storage::disk('public')->size($path);
    }

    public function getAllowedTypes(): array
    {
        return [
            'cv' => [
                'application/pdf',
                'application/msword',
                'application/vnd.openxmlformats-officedocument.wordprocessingml.document'
            ],
            'image' => [
                'image/jpeg',
                'image/png',
                'image/gif'
            ],
            'document' => [
                'application/pdf',
                'application/msword',
                'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
                'application/vnd.ms-excel',
                'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'
            ]
        ];
    }

    public function storeWithModel(UploadedFile $file, Model $model, string $type = 'document'): Attachment
    {
        if (!$this->isValidFileType($file, $this->getAllowedTypes()[$type])) {
            throw new \Exception('نوع الملف غير مسموح به');
        }

        $path = $this->store($file, $model->getTable());

        return Attachment::create([
            'attachable_type' => get_class($model),
            'attachable_id' => $model->id,
            'filename' => basename($path),
            'original_filename' => $file->getClientOriginalName(),
            'mime_type' => $file->getClientMimeType(),
            'size' => $file->getSize(),
            'path' => $path
        ]);
    }
} 