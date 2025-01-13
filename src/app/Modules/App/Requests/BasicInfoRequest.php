<?php

namespace App\Modules\App\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BasicInfoRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title' => 'required|string|max:255',
            'description' => 'required|string|max:1000',
            'demo_url' => 'nullable|url|max:255',
            'github_url' => 'nullable|url|max:255',
            'status' => 'required|in:draft,published',
            'screenshots.*' => 'image|mimes:jpeg,png,jpg|max:2048'
        ];
    }

    public function messages(): array
    {
        return [
            'title.required' => 'タイトルは必須です',
            'title.max' => 'タイトルは255文字以内で入力してください',
            'description.required' => '説明は必須です',
            'description.max' => '説明は1000文字以内で入力してください',
            'demo_url.url' => '有効なURLを入力してください',
            'github_url.url' => '有効なURLを入力してください',
            'status.required' => '公開状態は必須です',
            'status.in' => '無効な公開状態です',
            'screenshots.*.image' => 'スクリーンショットは画像ファイルである必要があります',
            'screenshots.*.mimes' => 'スクリーンショットはJPEG、PNG、JPG形式である必要があります',
            'screenshots.*.max' => 'スクリーンショットは2MB以下である必要があります'
        ];
    }
} 