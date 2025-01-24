<?php

namespace App\Modules\AppV2\Services;

use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Log;
use Cloudinary\Configuration\Configuration;
use Cloudinary\Api\Upload\UploadApi;
use GuzzleHttp\Promise;

class CloudinaryService
{
    private $uploadApi;
    private $uploadOptions;

    public function __construct()
    {
        // Cloudinary APIの初期化
        $this->uploadApi = new \Cloudinary\Api\Upload\UploadApi();

        $this->uploadOptions = [
            'folder' => config('cloudinary.folder'),
            'resource_type' => 'image',
            'overwrite' => true,
            'unique_filename' => true
        ];
    }

    /**
     * 複数の画像をアップロード
     * @param UploadedFile[] $files
     * @return array
     */
    public function uploadMultipleToTemp(array $files)
    {
        Log::info('Starting bulk upload:', ['total_files' => count($files)]);
        
        $results = [];
        foreach ($files as $file) {
            try {
                // アップロード前のファイルサイズをログに記録
                Log::info('Original file details:', [
                    'name' => $file->getClientOriginalName(),
                    'size_mb' => round($file->getSize() / 1024 / 1024, 2) . 'MB'
                ]);

                // デフォルトの変換設定を使用
                $result = Cloudinary::upload($file->getRealPath(), $this->uploadOptions);
                $response = $result->getResponse();
                
                if ($response) {
                    $results[] = [
                        'public_id' => $response['public_id'],
                        'url' => $response['secure_url'],
                        'original_size' => round($file->getSize() / 1024 / 1024, 2) . 'MB',
                        'compressed_size' => round($response['bytes'] / 1024 / 1024, 2) . 'MB',
                        'width' => $response['width'] ?? null,
                        'height' => $response['height'] ?? null
                    ];
                }
            } catch (\Exception $e) {
                Log::error('Failed to upload:', ['error' => $e->getMessage()]);
            }
        }

        Log::info('Cloudinaryアップロード結果:', [
            'success_count' => count($results),
            'results' => $results
        ]);

        return $results;
    }

    public function uploadToTemp(UploadedFile $file)
    {
        try {
            $result = Cloudinary::upload($file->getRealPath(), $this->uploadOptions);
            
            return [
                'public_id' => $result->getPublicId(),
                'secure_url' => $result->getSecurePath()
            ];
        } catch (\Exception $e) {
            Log::error('Cloudinary upload failed:', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            throw $e;
        }
    }

    public function delete($publicId)
    {
        try {
            $result = Cloudinary::destroy($publicId);
            Log::info('Cloudinary delete result:', ['result' => $result]);
            return $result;
        } catch (\Exception $e) {
            Log::error('Cloudinary delete failed:', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            throw $e;
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