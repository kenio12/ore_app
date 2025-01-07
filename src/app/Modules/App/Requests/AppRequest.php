<?php

namespace App\Modules\App\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AppRequest extends FormRequest
{
    public function rules()
    {
        return [
            'security_measures' => 'nullable|array',
            'security_measures.*' => 'string',
            'performance_optimizations' => 'nullable|array',
            'performance_optimizations.*' => 'string',
            'testing_tools' => 'nullable|array',
            'testing_tools.*' => 'string',
            'monitoring_tools' => 'nullable|array',
            'monitoring_tools.*' => 'string',
            'code_quality_tools' => 'nullable|array',
            'code_quality_tools.*' => 'string',
            'security_notes' => 'nullable|string|max:10000',
        ];
    }
} 