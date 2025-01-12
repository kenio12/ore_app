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
} 