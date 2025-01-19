<?php

namespace App\Modules\App\Helpers;

class ColorHelper
{
    /**
     * 文字列から一貫性のあるカラーコードを生成
     *
     * @param string $string
     * @return string
     */
    public static function generateColorFromString(string $string): string
    {
        // 文字列のハッシュ値を取得
        $hash = md5($string);
        
        // カラーパレット（見やすい色のみ）
        $colors = [
            '#4F46E5', // インディゴ
            '#7C3AED', // バイオレット
            '#DB2777', // ピンク
            '#059669', // エメラルド
            '#2563EB', // ブルー
            '#DC2626', // レッド
            '#D97706', // アンバー
            '#4338CA', // インディゴ（濃）
        ];
        
        // ハッシュ値を数値に変換してインデックスを取得
        $index = hexdec(substr($hash, 0, 8)) % count($colors);
        
        return $colors[$index];
    }

    /**
     * アプリタイプ用の色を取得
     * 文字列または配列を受け取れるように修正
     *
     * @param string|array|null $type
     * @return string
     */
    public static function getAppTypeColor($type): string
    {
        $colors = [
            'web_app' => '#4F46E5',      // インディゴ
            'ios_app' => '#059669',      // エメラルド
            'android_app' => '#2563EB',  // ブルー
            'windows_app' => '#7C3AED',  // バイオレット
            'mac_app' => '#DB2777',      // ピンク
            'linux_app' => '#DC2626',    // レッド
            'game' => '#D97706',         // アンバー
            'other' => '#9CA3AF'         // グレー
        ];

        // 配列の場合は最初の要素を使用
        if (is_array($type)) {
            $type = $type[0] ?? null;
        }

        return $colors[$type ?? 'other'] ?? '#9CA3AF';
    }

    // ステータス用の色を取得
    public static function getStatusColor(string $status): string
    {
        $colors = [
            'completed' => '#059669',    // エメラルド（完成）
            'in_development' => '#D97706' // アンバー（開発中）
        ];

        return $colors[$status] ?? '#9CA3AF';
    }
} 