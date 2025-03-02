from django import forms
from .models import AppGallery
from .constants.hardware import (
    PC_TYPES,
    DEVICE_TYPES,
    OS_TYPES,
    CPU_TYPES,
    MEMORY_SIZES,
    STORAGE_TYPES,
    MONITOR_COUNTS,
    MONITOR_SIZES,
    MAKER_EXAMPLES,
    INTERNET_TYPES
)
from .constants.development import (
    TEAM_SIZES,
    VIRTUALIZATION_TOOLS,
    EDITORS,
    VERSION_CONTROL,
    COMMUNICATION_TOOLS,
    INFRASTRUCTURE,
    CI_CD,
    API_TOOLS,
    MONITORING_TOOLS
)
from .constants.tech_stack import (
    FRONTEND_LANGUAGES,
    FRONTEND_FRAMEWORKS,
    BACKEND_LANGUAGES,
    BACKEND_FRAMEWORKS,
    DATABASE_TYPES
)
from .constants.architecture import (
    ARCHITECTURE_PATTERNS,
    DESIGN_PATTERNS,
    SECURITY_MEASURES,
    TESTING_TOOLS,
    CODE_QUALITY_TOOLS
)

class TechnicalForm(forms.ModelForm):
    # ハードウェア関連のフィールド
    pc_type = forms.ChoiceField(
        choices=[(k, v) for k, v in PC_TYPES.items()],
        required=False,
        widget=forms.RadioSelect(attrs={'class': 'form-check-input cyber-radio'})
    )
    device_type = forms.ChoiceField(
        choices=[(k, v) for k, v in DEVICE_TYPES.items()],
        required=False,
        widget=forms.RadioSelect(attrs={'class': 'form-check-input cyber-radio'})
    )
    os_type = forms.ChoiceField(
        choices=[(k, v) for k, v in OS_TYPES.items()],
        required=False,
        widget=forms.RadioSelect(attrs={'class': 'form-check-input cyber-radio'})
    )
    cpu_type = forms.ChoiceField(
        choices=[(k, v) for k, v in CPU_TYPES.items()],
        required=False,
        widget=forms.RadioSelect(attrs={'class': 'form-check-input cyber-radio'})
    )
    memory_size = forms.ChoiceField(
        choices=[(k, v) for k, v in MEMORY_SIZES.items()],
        required=False,
        widget=forms.RadioSelect(attrs={'class': 'form-check-input cyber-radio'})
    )
    storage_type = forms.ChoiceField(
        choices=[(k, v) for k, v in STORAGE_TYPES.items()],
        required=False,
        widget=forms.RadioSelect(attrs={'class': 'form-check-input cyber-radio'})
    )
    monitor_count = forms.ChoiceField(
        choices=[(k, v) for k, v in MONITOR_COUNTS.items()],
        required=False,
        widget=forms.RadioSelect(attrs={'class': 'form-check-input cyber-radio'})
    )
    monitor_size = forms.ChoiceField(
        choices=[(k, v) for k, v in MONITOR_SIZES.items()],
        required=False,
        widget=forms.RadioSelect(attrs={'class': 'form-check-input cyber-radio'})
    )
    internet_type = forms.ChoiceField(
        choices=[(k, v) for k, v in INTERNET_TYPES.items()],
        required=False,
        widget=forms.RadioSelect(attrs={'class': 'form-check-input cyber-radio'})
    )
    maker_model = forms.CharField(
        required=False,
        widget=forms.TextInput(attrs={
            'class': 'form-control cyber-input',
            'placeholder': '例: MacBook Pro (2020), ThinkPad X1 Carbon など'
        })
    )

    # 開発環境関連のフィールド
    editors = forms.MultipleChoiceField(
        choices=[(k, v) for k, v in EDITORS.items()],
        required=False,
        widget=forms.CheckboxSelectMultiple(attrs={'class': 'form-check-input cyber-checkbox'})
    )
    
    version_control = forms.MultipleChoiceField(
        choices=[(k, v) for k, v in VERSION_CONTROL.items()],
        required=False,
        widget=forms.CheckboxSelectMultiple(attrs={'class': 'form-check-input cyber-checkbox'})
    )
    
    ci_cd = forms.MultipleChoiceField(
        choices=[(k, v) for k, v in CI_CD.items()],
        required=False,
        widget=forms.CheckboxSelectMultiple(attrs={'class': 'form-check-input cyber-checkbox'})
    )
    
    virtualization = forms.MultipleChoiceField(
        choices=[(k, v) for k, v in VIRTUALIZATION_TOOLS.items()],
        required=False,
        widget=forms.CheckboxSelectMultiple(attrs={'class': 'form-check-input cyber-checkbox'})
    )
    
    team_size = forms.ChoiceField(
        choices=[(k, v) for k, v in TEAM_SIZES.items()],
        required=False,
        widget=forms.RadioSelect(attrs={'class': 'form-check-input cyber-radio'})
    )
    
    communication_tools = forms.MultipleChoiceField(
        choices=[(k, v) for k, v in COMMUNICATION_TOOLS.items()],
        required=False,
        widget=forms.CheckboxSelectMultiple(attrs={'class': 'form-check-input cyber-checkbox'})
    )
    
    infrastructure = forms.MultipleChoiceField(
        choices=[(k, v) for k, v in INFRASTRUCTURE.items()],
        required=False,
        widget=forms.CheckboxSelectMultiple(attrs={'class': 'form-check-input cyber-checkbox'})
    )
    
    api_tools = forms.MultipleChoiceField(
        choices=[(k, v) for k, v in API_TOOLS.items()],
        required=False,
        widget=forms.CheckboxSelectMultiple(attrs={'class': 'form-check-input cyber-checkbox'})
    )
    
    monitoring_tools = forms.MultipleChoiceField(
        choices=[(k, v) for k, v in MONITORING_TOOLS.items()],
        required=False,
        widget=forms.CheckboxSelectMultiple(attrs={'class': 'form-check-input cyber-checkbox'})
    )

    # 開発環境関連のフィールドリスト（クラス変数として定義）
    development_fields = [
        'editors', 'version_control', 'ci_cd', 'virtualization',
        'team_size', 'communication_tools', 'infrastructure',
        'api_tools', 'monitoring_tools'
    ]

    def clean(self):
        cleaned_data = super().clean()
        
        # ハードウェアスペックのバリデーション
        hardware_specs = {}
        for field_name in ['pc_type', 'device_type', 'os_type', 'cpu_type',
                          'memory_size', 'storage_type', 'monitor_count',
                          'monitor_size', 'internet_type', 'maker_model']:
            value = cleaned_data.get(field_name)
            if value:
                hardware_specs[field_name] = value

        # 最低限必要な情報のチェック
        if not hardware_specs.get('pc_type'):
            raise forms.ValidationError('PCタイプは必須項目です。')

        # 開発環境のバリデーション
        dev_env = {}
        for field_name in self.development_fields:
            value = cleaned_data.get(field_name)
            if value:
                if isinstance(value, list) and not value:  # 空リストのチェック
                    continue
                dev_env[field_name] = value

        # 最低限必要な情報のチェック
        if not dev_env.get('editors'):
            raise forms.ValidationError('使用エディタは最低1つ選択してください。')
        if not dev_env.get('version_control'):
            raise forms.ValidationError('バージョン管理システムは最低1つ選択してください。')

        return cleaned_data

    def save(self, commit=True):
        instance = super().save(commit=False)
        
        # hardware_specsの保存処理
        hardware_specs = {}
        for field_name in [f for f in self.Meta.fields if f not in self.development_fields]:
            if self.cleaned_data.get(field_name):
                hardware_specs[field_name] = self.cleaned_data[field_name]
        instance.hardware_specs = hardware_specs
        
        # development_environmentの保存処理
        dev_env = {}
        for field_name in self.development_fields:
            if self.cleaned_data.get(field_name):
                dev_env[field_name] = self.cleaned_data[field_name]
        instance.development_environment = dev_env
        
        if commit:
            instance.save()
        return instance

    def __init__(self, *args, **kwargs):
        super().__init__(*args, **kwargs)
        instance = kwargs.get('instance')
        
        # フィールドのカスタマイズ
        for field_name in self.fields:
            # 必須フィールドの設定
            if field_name in ['pc_type', 'editors', 'version_control']:
                self.fields[field_name].required = True
            
            # プレースホルダーの設定
            if field_name == 'maker_model':
                self.fields[field_name].widget.attrs['placeholder'] = '例: MacBook Pro (2020), ThinkPad X1 Carbon など'
        
        # インスタンスがある場合の初期値設定
        if instance:
            # ハードウェアスペックの初期値設定
            if hasattr(instance, 'hardware_specs'):
                for field_name in self.Meta.fields:
                    if field_name in instance.hardware_specs:
                        self.fields[field_name].initial = instance.hardware_specs[field_name]
            
            # 開発環境の初期値設定
            if hasattr(instance, 'development_environment'):
                dev_env = instance.development_environment or {}
                for field_name in self.development_fields:
                    if field_name in dev_env:
                        self.fields[field_name].initial = dev_env[field_name] 