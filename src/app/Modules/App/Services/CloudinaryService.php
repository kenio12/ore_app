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
    public function uploadMultipleToTemp(array $files)
    {
        try {
            Log::info('Starting bulk upload:', ['total_files' => count($files)]);
            
            $results = [];
            $uploadParams = [
                'folder' => 'ore_app/screenshots',
                'transformation' => [
                    'width' => 1920,
                    'height' => 1080,
                    'crop' => 'limit',
                    'quality' => 'auto:good'
                ]
            ];

            // 各ファイルを個別にアップロード
            foreach ($files as $file) {
                try {
                    if (!$file instanceof UploadedFile) {
                        Log::warning('Invalid file object:', ['file' => $file]);
                        continue;
                    }

                    $result = $this->uploadApi->upload($file->getRealPath(), $uploadParams);
                    
                    $results[] = [
                        'public_id' => $result['public_id'],
                        'url' => $result['secure_url'],
                        'width' => $result['width'],
                        'height' => $result['height']
                    ];
                    
                    Log::info('Successfully uploaded file:', [
                        'filename' => $file->getClientOriginalName(),
                        'result' => $result['public_id']
                    ]);
                    
                } catch (\Exception $e) {
                    Log::error('Failed to upload individual file:', [
                        'filename' => $file->getClientOriginalName(),
                        'error' => $e->getMessage()
                    ]);
                    // 個別のファイルの失敗は全体の処理を止めない
                    continue;
                }
            }

            Log::info('Bulk upload completed:', [
                'successful_uploads' => count($results)
            ]);

            return $results;

        } catch (\Exception $e) {
            Log::error('Bulk upload failed:', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            throw new \RuntimeException('Failed to upload images: ' . $e->getMessage());
        }
    }

    public function uploadToTemp(UploadedFile $file)
    {
        try {
            $result = $this->uploadApi->upload($file->getRealPath(), [
                'folder' => 'ore_app/screenshots',
                'transformation' => [
                    'width' => 1920,  // 最大幅を指定
                    'height' => 1080, // 最大高さを指定
                    'crop' => 'limit',  // 最大サイズを超えないように
                    'quality' => 'auto:good'  // 画質は自動最適化
                ]
            ]);

            Log::info('Upload result:', [
                'original_file' => [
                    'name' => $file->getClientOriginalName(),
                    'size' => $file->getSize(),
                    'mime' => $file->getMimeType()
                ],
                'upload_result' => $result
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