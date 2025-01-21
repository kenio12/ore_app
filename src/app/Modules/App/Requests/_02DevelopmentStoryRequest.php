<?php

namespace App\Modules\App\Requests;

use Illuminate\Foundation\Http\FormRequest;

class _02DevelopmentStoryRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'motivation' => 'nullable|string|max:10000',
            'challenges' => 'nullable|string|max:10000',
            'devised_points' => 'nullable|string|max:10000',
            'learnings' => 'nullable|string|max:10000',
            'future_plans' => 'nullable|string|max:10000',
            'overall_thoughts' => 'nullable|string|max:10000'
        ];
    }

    public function messages(): array
    {
        return [
            'motivation.max' => '開発動機は10000文字以内で入力してください',
            'challenges.max' => '苦労した点・課題は10000文字以内で入力してください',
            'devised_points.max' => '工夫した点は10000文字以内で入力してください',
            'learnings.max' => '学んだことは10000文字以内で入力してください',
            'future_plans.max' => '今後の展望は10000文字以内で入力してください',
            'overall_thoughts.max' => '総合感想は10000文字以内で入力してください'
        ];
    }
} 