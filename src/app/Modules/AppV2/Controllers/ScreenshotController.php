<?php

namespace App\Modules\AppV2\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\AppV2\Services\CloudinaryService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ScreenshotController extends Controller
{
    private $cloudinaryService;

    public function __construct(CloudinaryService $cloudinaryService)
    {
        $this->cloudinaryService = $cloudinaryService;
    }

    public function upload(Request $request)
    {
        try {
            $request->validate([
                'screenshot' => 'required|image|max:5120' // 5MB
            ]);

            $file = $request->file('screenshot');
            if (!$file) {
                throw new \Exception('ファイルが見つかりません');
            }

            $result = $this->cloudinaryService->uploadToTemp($file);

            return response()->json([
                'success' => true,
                'public_id' => $result['public_id'],
                'url' => $result['url']
            ]);

        } catch (\Exception $e) {
            Log::error('Screenshot upload failed:', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'アップロードに失敗しました: ' . $e->getMessage()
            ], 500);
        }
    }

    public function delete(Request $request)
    {
        try {
            $request->validate([
                'public_id' => 'required|string'
            ]);

            $result = $this->cloudinaryService->delete($request->public_id);

            return response()->json([
                'success' => true
            ]);
        } catch (\Exception $e) {
            Log::error('Screenshot delete failed:', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => '削除に失敗しました: ' . $e->getMessage()
            ], 500);
        }
    }
} 