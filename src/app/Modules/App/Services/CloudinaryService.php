<?php

namespace App\Modules\App\Services;

use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Log;
use Cloudinary\Configuration\Configuration;

class CloudinaryService
{
    public function uploadToTemp(UploadedFile $file): array
    {
        if (!$file->isValid()) {
            throw new \Exception('Invalid file provided');
        }

        try {
            // Cloudinaryの設定を明示的に行う
            Configuration::instance([
                'cloud' => [
                    'cloud_name' => config('cloudinary.cloud_name'),
                    'api_key' => config('cloudinary.api_key'),
                    'api_secret' => config('cloudinary.api_secret')
                ]
            ]);

            $uploadApi = new \Cloudinary\Api\Upload\UploadApi();
            $result = $uploadApi->upload($file->getRealPath(), [
                'folder' => config('cloudinary.folder')
            ]);
            
            Log::info('Upload result:', ['result' => $result]);

            return [
                'temp_public_id' => $result['public_id'],
                'url' => $result['secure_url'],
                'width' => $result['width'] ?? 0,
                'height' => $result['height'] ?? 0
            ];

        } catch (\Exception $e) {
            Log::error('Upload failed:', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            throw $e;
        }
    }

    public function delete(string $publicId): bool
    {
        try {
            $uploadApi = new \Cloudinary\Api\Upload\UploadApi();
            $uploadApi->destroy($publicId);
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

            $uploadApi = new \Cloudinary\Api\Upload\UploadApi();
            
            // 一時保存のままで本番用として扱う
            // typeパラメータを追加
            $result = $uploadApi->explicit($tempPublicId, [
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