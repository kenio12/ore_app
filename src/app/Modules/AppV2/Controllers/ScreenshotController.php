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
                'public_id' => 'required|string',
                'app_id' => 'required|integer'  // app_idを必須に
            ]);

            // 削除前の状態をログ
            Log::info('スクショ削除開始:', [
                'timestamp' => now()->format('Y-m-d H:i:s'),
                'app_id' => $request->app_id,
                'deleting_public_id' => $request->public_id,
                'current_screenshots_count' => \App\Modules\AppV2\Models\Screenshot::where('app_id', $request->app_id)->count(),
                'all_screenshots' => \App\Modules\AppV2\Models\Screenshot::where('app_id', $request->app_id)
                    ->select('id', 'cloudinary_public_id', 'created_at')
                    ->get()
            ]);

            $result = $this->cloudinaryService->delete($request->public_id);
            
            // 削除後の状態もログ
            Log::info('スクショ削除完了:', [
                'timestamp' => now()->format('Y-m-d H:i:s'),
                'app_id' => $request->app_id,
                'deleted_public_id' => $request->public_id,
                'cloudinary_result' => $result,
                'remaining_screenshots_count' => \App\Modules\AppV2\Models\Screenshot::where('app_id', $request->app_id)->count(),
                'remaining_screenshots' => \App\Modules\AppV2\Models\Screenshot::where('app_id', $request->app_id)
                    ->select('id', 'cloudinary_public_id', 'created_at')
                    ->get()
            ]);

            return response()->json([
                'success' => true,
                'message' => 'よっしゃ！スクショ削除したで！',
                'deleted_at' => now()->format('Y-m-d H:i:s'),
                'app_id' => $request->app_id,
                'deleted_public_id' => $request->public_id
            ]);

        } catch (\Exception $e) {
            Log::error('スクショ削除失敗:', [
                'timestamp' => now()->format('Y-m-d H:i:s'),
                'app_id' => $request->app_id,
                'error' => $e->getMessage(),
                'public_id' => $request->public_id
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'あかん...削除できひんかった: ' . $e->getMessage()
            ], 500);
        }
    }
} 