<?php

namespace App\Modules\App\Services;

use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Log;
use Cloudinary\Configuration\Configuration;
use Cloudinary\Api\Upload\UploadApi;

class CloudinaryService
{
    private $uploadApi;

    public function __construct()
    {
        // 設定を明示的に初期化
        $config = new Configuration([
            'cloud' => [
                'cloud_name' => config('cloudinary.cloud_name'),
                'api_key' => config('cloudinary.api_key'),
                'api_secret' => config('cloudinary.api_secret')
            ],
            'url' => [
                'secure' => config('cloudinary.secure', true)
            ]
        ]);

        // UploadAPIインスタンスを作成
        $this->uploadApi = new UploadApi($config);

        // デバッグ用ログ
        Log::info('CloudinaryService initialized with config', [
            'cloud_name' => config('cloudinary.cloud_name'),
            'api_key' => config('cloudinary.api_key'),
            'has_secret' => !empty(config('cloudinary.api_secret')),
            'secret_length' => strlen(config('cloudinary.api_secret')),
            'secure' => config('cloudinary.secure', true)
        ]);
    }

    /**
     * 複数の画像をアップロード
     * @param UploadedFile[] $files
     * @return array
     */
    public function uploadMultipleToTemp(array $files): array
    {
        $results = [];
        $errors = [];

        foreach ($files as $index => $file) {
            try {
                Log::info('Attempting to upload file', [
                    'index' => $index,
                    'filename' => $file->getClientOriginalName(),
                    'size' => $file->getSize(),
                    'mime' => $file->getMimeType()
                ]);

                $result = $this->uploadApi->upload($file->getRealPath(), [
                    'folder' => env('CLOUDINARY_FOLDER', 'ore_app/screenshots'),
                    'resource_type' => 'image'
                ]);
                
                Log::info('Upload success:', [
                    'index' => $index,
                    'public_id' => $result['public_id']
                ]);

                $results[] = [
                    'public_id' => $result['public_id'],
                    'url' => $result['secure_url'],
                    'width' => $result['width'] ?? 0,
                    'height' => $result['height'] ?? 0
                ];

            } catch (\Exception $e) {
                Log::error('Screenshot upload failed:', [
                    'index' => $index,
                    'error' => $e->getMessage(),
                    'file' => $file->getClientOriginalName()
                ]);
                
                $errors[] = [
                    'index' => $index,
                    'filename' => $file->getClientOriginalName(),
                    'error' => $e->getMessage()
                ];
            }
        }

        // エラーがあった場合でも、成功した分は返す
        if (!empty($errors)) {
            Log::warning('Some uploads failed:', ['errors' => $errors]);
        }

        return [
            'success' => $results,
            'errors' => $errors
        ];
    }

    public function uploadToTemp(UploadedFile $file)
    {
        try {
            Log::info('Attempting to upload file', [
                'filename' => $file->getClientOriginalName(),
                'size' => $file->getSize(),
                'mime' => $file->getMimeType()
            ]);

            // Facadeの代わりに直接UploadAPIを使用
            $result = $this->uploadApi->upload($file->getRealPath(), [
                'folder' => config('cloudinary.folder', 'ore_app/screenshots'),
                'resource_type' => 'image'
            ]);
            
            Log::info('Upload result:', ['result' => $result]);

            return [
                'public_id' => $result['public_id'],
                'url' => $result['secure_url'],
                'width' => $result['width'] ?? 0,
                'height' => $result['height'] ?? 0
            ];

        } catch (\Exception $e) {
            Log::error('Upload failed:', [
                'error' => $e->getMessage(),
                'file' => $file->getClientOriginalName()
            ]);
            throw $e;
        }
    }

    public function delete(string $publicId): bool
    {
        try {
            $this->uploadApi->destroy($publicId);
            return true;
        } catch (\Exception $e) {
            Log::error('Delete failed:', ['error' => $e->getMessage()]);
            return false;
        }
    }

    public function moveToProduction(string $tempPublicId): array
    {
        try {
            // 設定を明示的に行う
            Configuration::instance([
                'cloud' => [
                    'cloud_name' => config('cloudinary.cloud_name'),
                    'api_key' => config('cloudinary.api_key'),
                    'api_secret' => config('cloudinary.api_secret')
                ]
            ]);

            // 一時保存のままで本番用として扱う
            // typeパラメータを追加
            $result = $this->uploadApi->explicit($tempPublicId, [
                'type' => 'upload'
            ]);
            
            Log::info('Move to production result:', ['result' => $result]);

            return [
                'public_id' => $result['public_id'],
                'url' => $result['secure_url'],
                'width' => $result['width'] ?? 0,
                'height' => $result['height'] ?? 0
            ];

        } catch (\Exception $e) {
            Log::error('Move to production failed:', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            throw $e;
        }
    }
} 