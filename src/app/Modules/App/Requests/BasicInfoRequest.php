<?php

namespace App\Modules\App\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Modules\App\Models\App;

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
            'screenshots.*' => 'image|mimes:jpeg,png,jpg|max:2048',
            'app_types' => 'required|array|min:1',
            'app_types.*' => 'in:' . implode(',', array_keys(App::getAppTypeOptions())),
            'app_status' => 'required|in:completed,in_development'
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
            'screenshots.*.max' => 'スクリーンショットは2MB以下である必要があります',
            'app_types.required' => 'アプリの種類は必須です',
            'app_types.array' => 'アプリの種類は配列である必要があります',
            'app_types.min' => '少なくとも1つのアプリの種類を選択してください',
            'app_types.*.in' => '選択されたアプリの種類が無効です',
            'app_status.required' => '開発状態は必須です',
            'app_status.in' => '無効な開発状態です'
        ];
    }
} 