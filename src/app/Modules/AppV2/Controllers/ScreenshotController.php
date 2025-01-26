<?php

namespace App\Modules\AppV2\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\AppV2\Services\CloudinaryService;
use App\Modules\AppV2\Models\App;
use App\Modules\AppV2\Models\Screenshot;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

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
                'screenshot' => 'required|image|max:5120', // 5MB
                'app_id' => 'required|integer'  // app_idを必須に
            ]);

            $file = $request->file('screenshot');
            if (!$file) {
                throw new \Exception('ファイルが見つかりません');
            }

            DB::beginTransaction();
            try {
                // Cloudinaryにアップロード
                $result = $this->cloudinaryService->uploadToTemp($file);

                // データベースに保存
                $app = App::findOrFail($request->app_id);
                $screenshot = $app->screenshots()->create([
                    'cloudinary_public_id' => $result['public_id'],
                    'url' => $result['url'],
                    'order' => 0  // とりあえず0で
                ]);

                DB::commit();

                return response()->json([
                    'success' => true,
                    'public_id' => $result['public_id'],
                    'url' => $result['url'],
                    'id' => $screenshot->id  // スクリーンショットのIDも返す
                ]);

            } catch (\Exception $e) {
                DB::rollBack();
                throw $e;
            }

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
                'app_id' => 'required|integer',
                'screenshot_id' => 'required|integer'  // screenshot_idも必須に
            ]);

            DB::beginTransaction();
            try {
                // データベースから削除
                $screenshot = Screenshot::where('app_id', $request->app_id)
                    ->where('id', $request->screenshot_id)
                    ->firstOrFail();

                // Cloudinaryから削除
                $result = $this->cloudinaryService->delete($request->public_id);

                // データベースから削除
                $screenshot->delete();

                DB::commit();

                return response()->json([
                    'success' => true,
                    'message' => 'よっしゃ！スクショ削除したで！'
                ]);

            } catch (\Exception $e) {
                DB::rollBack();
                throw $e;
            }

        } catch (\Exception $e) {
            Log::error('スクショ削除失敗:', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'あかん...削除できひんかった: ' . $e->getMessage()
            ], 500);
        }
    }
} 