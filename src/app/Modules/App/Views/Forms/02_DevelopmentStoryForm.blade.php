<div class="bg-white p-8 rounded-lg shadow mb-8">
    <h2 class="text-2xl font-bold mb-6">開発ストーリー</h2>

    <!-- 開発動機 -->
    <div class="mb-6">
        <label for="motivation" class="block text-sm font-medium text-gray-700">
            開発動機
        </label>
        <textarea 
            name="motivation" 
            id="motivation"
            rows="8"
            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
            placeholder="例：
- このアプリを作ろうと思ったきっかけ
- 解決したかった課題
- 目指した理想の状態
- 個人的な興味や関心"
        >{{ old('motivation', $app->motivation ?? '') }}</textarea>
        @error('motivation')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>

    <!-- 苦労した点・課題 -->
    <div class="mb-6">
        <label for="challenges" class="block text-sm font-medium text-gray-700">
            苦労した点・課題
        </label>
        <textarea 
            name="challenges" 
            id="challenges"
            rows="8"
            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
            placeholder="例：
- 技術的な課題
  - 認証システムの実装で苦戦
  - パフォーマンスの最適化に時間がかかった
- プロジェクト管理の課題
  - スケジュール管理の難しさ
  - 優先順位付けの判断
- 設計上の課題
  - データベース設計の見直し
  - スケーラビリティの考慮"
        >{{ old('challenges', $app->challenges ?? '') }}</textarea>
        @error('challenges')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>

    <!-- 工夫した点 -->
    <div class="mb-6">
        <label for="devised_points" class="block text-sm font-medium text-gray-700">
            工夫した点
        </label>
        <textarea 
            name="devised_points" 
            id="devised_points"
            rows="8"
            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
            placeholder="例：
- ユーザビリティの向上
  - レスポンシブデザインの徹底
  - エラーメッセージの分かりやすさ
- コードの品質維持
  - テストの自動化
  - コードレビューの実施
- 開発効率の改善
  - CIツールの導入
  - ドキュメント整備"
        >{{ old('devised_points', $app->devised_points ?? '') }}</textarea>
        @error('devised_points')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>

    <!-- 学んだこと -->
    <div class="mb-6">
        <label for="learnings" class="block text-sm font-medium text-gray-700">
            学んだこと
        </label>
        <textarea 
            name="learnings" 
            id="learnings"
            rows="8"
            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
            placeholder="例：
- 技術面での成長
  - 新しいフレームワークの習得
  - セキュリティ対策の重要性
- プロジェクト管理スキル
  - タスク分割の重要性
  - 時間見積もりの考え方
- チーム開発の経験
  - コミュニケーションの大切さ
  - ドキュメント作成の意義"
        >{{ old('learnings', $app->learnings ?? '') }}</textarea>
        @error('learnings')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>

    <!-- 今後の展望 -->
    <div class="mb-6">
        <label for="future_plans" class="block text-sm font-medium text-gray-700">
            今後の展望
        </label>
        <textarea 
            name="future_plans" 
            id="future_plans"
            rows="8"
            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
            placeholder="例：
- 機能面での改善
  - モバイルアプリ版の開発
  - AIを活用した機能の追加
- 技術面での向上
  - パフォーマンスの最適化
  - セキュリティの強化
- ビジネス面での展開
  - ユーザー数の拡大
  - 収益化の検討"
        >{{ old('future_plans', $app->future_plans ?? '') }}</textarea>
        @error('future_plans')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>

    <!-- 総合感想 -->
    <div class="mb-6">
        <label for="overall_thoughts" class="block text-sm font-medium text-gray-700">
            総合感想
        </label>
        <textarea 
            name="overall_thoughts" 
            id="overall_thoughts"
            rows="8"
            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
            placeholder="このアプリケーション開発を通じての総合的な感想をお書きください"
        >{{ old('overall_thoughts', $app->overall_thoughts ?? '') }}</textarea>
        @error('overall_thoughts')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>
</div> 