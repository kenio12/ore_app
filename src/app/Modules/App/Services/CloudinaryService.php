<?php

namespace App\Modules\App\Services;

use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Log;
use Cloudinary\Configuration\Configuration;
use Cloudinary\Api\Upload\UploadApi;
use GuzzleHttp\Promise;

class CloudinaryService
{
    private $uploadApi;
    private $defaultTransformation;

    public function __construct()
    {
        // Cloudinary APIの初期化
        $this->uploadApi = new \Cloudinary\Api\Upload\UploadApi();

        // デフォルトの変換設定を最適化
        $this->defaultTransformation = [
            'folder' => config('cloudinary.folder', 'ore_app/screenshots'),
            'transformation' => [
                'quality' => config('cloudinary.transformation.quality', 'auto:eco'),
                'fetch_format' => config('cloudinary.transformation.fetch_format', 'auto'),
                'width' => config('cloudinary.transformation.width', 800),
                'crop' => config('cloudinary.transformation.crop', 'limit'),
                'compression' => config('cloudinary.transformation.compression', 'low')
            ]
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
                $result = Cloudinary::upload($file->getRealPath(), $this->defaultTransformation);
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
            $result = $this->uploadApi->upload(
                $file->getRealPath(), 
                $this->defaultTransformation
            );

            Log::info('Upload result:', [
                'original_file' => [
                    'name' => $file->getClientOriginalName(),
                    'size' => $file->getSize(),
                    'mime' => $file->getMimeType()
                ],
                'upload_result' => [
                    'public_id' => $result['public_id'],
                    'url' => $result['secure_url'],
                    'width' => $result['width'],
                    'height' => $result['height'],
                    'bytes' => $result['bytes']
                ]
            ]);

            return [
                'public_id' => $result['public_id'],
                'url' => $result['secure_url'],
                'width' => $result['width'],
                'height' => $result['height']
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
            if (!$this->uploadApi) {
                Log::warning('UploadApi not initialized');
                return false;
            }
            
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