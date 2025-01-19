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
            'app_types' => 'nullable|array',
            'app_types.*' => 'in:' . implode(',', array_keys(App::getAppTypeOptions())),
            'app_status' => 'required|in:completed,in_development',
            'development_start_date' => 'required|date',
            'development_end_date' => 'nullable|date|after_or_equal:development_start_date',
            'development_period_years' => 'required|integer|min:0',
            'development_period_months' => 'required|integer|min:0|max:11',
            'genres' => 'nullable|array',
            'genres.*' => 'in:sns,netshop,matching,learning_service,work,entertainment,daily_life,communication,healthcare,finance,news_media,food,travel,real_estate,education,recruitment,literature,art,music,pet,game,sports,academic,development_tool,api_service,cms,blog,portfolio,other'
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
            'app_status.in' => '無効な開発状態です',
            'development_start_date.required' => '開発開始日は必須です',
            'development_start_date.date' => '有効な日付を入力してください',
            'development_end_date.required' => '開発終了日は必須です',
            'development_end_date.date' => '有効な日付を入力してください',
            'development_end_date.after_or_equal' => '開発終了日は開始日以降である必要があります',
            'development_period_years.required' => '開発期間（年）は必須です',
            'development_period_years.integer' => '開発期間（年）は整数である必要があります',
            'development_period_years.min' => '開発期間（年）は0以上である必要があります',
            'development_period_months.required' => '開発期間（月）は必須です',
            'development_period_months.integer' => '開発期間（月）は整数である必要があります',
            'development_period_months.min' => '開発期間（月）は0以上である必要があります',
            'development_period_months.max' => '開発期間（月）は11以下である必要があります',
            'genres.required' => 'ジャンルは必須です',
            'genres.array' => 'ジャンルは配列である必要があります',
            'genres.min' => '少なくとも1つのジャンルを選択してください',
            'genres.*.in' => '選択されたジャンルが無効です'
        ];
    }
} 