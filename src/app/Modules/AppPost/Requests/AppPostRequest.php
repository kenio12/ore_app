<?php

namespace App\Modules\AppPost\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AppPostRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            // 基本情報
            'title' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string'],
            'status' => ['required', 'string', 'in:in_development,completed'],
            'publish_status' => ['required', 'string', 'in:public,private'],
            'github_url' => ['nullable', 'url', 'max:255'],
            'demo_url' => ['nullable', 'url', 'max:255'],
            'screenshots.*' => ['nullable', 'image', 'max:2048'],

            // 開発ストーリー
            'motivation' => ['nullable', 'string'],
            'challenges' => ['nullable', 'string'],
            'devised_points' => ['nullable', 'string'],
            'learnings' => ['nullable', 'string'],
            'future_plans' => ['nullable', 'string'],

            // 環境情報
            'hardware_info.development_env' => ['nullable', 'array'],
            'hardware_info.development_env.*' => ['required', 'string'],

            'software_info.editors' => ['nullable', 'array'],
            'software_info.editors.*' => ['required', 'string'],

            'backend_info.languages' => ['nullable', 'array'],
            'backend_info.languages.*' => ['required', 'string'],
            'backend_info.frameworks' => ['nullable', 'array'],
            'backend_info.frameworks.*' => ['required', 'string'],
            'backend_info.libraries' => ['nullable', 'string'],

            'frontend_info.languages' => ['nullable', 'array'],
            'frontend_info.languages.*' => ['required', 'string'],
            'frontend_info.frameworks' => ['nullable', 'array'],
            'frontend_info.frameworks.*' => ['required', 'string'],
            'frontend_info.libraries' => ['nullable', 'string'],

            'database_info.databases' => ['nullable', 'array'],
            'database_info.databases.*' => ['required', 'string'],
            'database_info.orms' => ['nullable', 'array'],
            'database_info.orms.*' => ['required', 'string'],
            'database_info.libraries' => ['nullable', 'string'],

            'architecture_info.patterns' => ['nullable', 'array'],
            'architecture_info.patterns.*' => ['required', 'string'],
            'architecture_info.design_methods' => ['nullable', 'array'],
            'architecture_info.design_methods.*' => ['required', 'string'],
            'architecture_info.notes' => ['nullable', 'string'],

            'other_info.infrastructure' => ['nullable', 'array'],
            'other_info.infrastructure.*' => ['required', 'string'],
            'other_info.ci_cd' => ['nullable', 'array'],
            'other_info.ci_cd.*' => ['required', 'string'],
            'other_info.containers' => ['nullable', 'array'],
            'other_info.containers.*' => ['required', 'string'],
            'other_info.tools' => ['nullable', 'string'],
        ];
    }

    public function attributes(): array
    {
        return [
            'title' => 'タイトル',
            'description' => '説明',
            'status' => '開発状況',
            'publish_status' => '公開状態',
            'github_url' => 'GitHubリンク',
            'demo_url' => 'デモサイトリンク',
            'screenshots' => 'スクリーンショット',
            'motivation' => '開発動機',
            'challenges' => '苦労した点・課題',
            'devised_points' => '工夫した点',
            'learnings' => '学んだこと',
            'future_plans' => '今後の展望',
        ];
    }

    public function messages(): array
    {
        return [
            'required' => ':attributeは必須です',
            'string' => ':attributeは文字列で入力してください',
            'max' => ':attributeは:max文字以内で入力してください',
            'url' => ':attributeは有効なURLを入力してください',
            'image' => ':attributeは画像ファイルを選択してください',
            'in' => ':attributeは有効な値を選択してください',
            'array' => ':attributeは配列で入力してください',
        ];
    }
} 