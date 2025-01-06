<div class="bg-white p-8 rounded-lg shadow mb-8">
    <h2 class="text-2xl font-bold mb-6">開発ストーリー</h2>

    <!-- 開発動機 -->
    <div class="mb-6">
        <label for="motivation" class="block text-sm font-medium text-gray-700">
            開発動機 <span class="text-red-500">*</span>
        </label>
        <textarea 
            name="motivation" 
            id="motivation"
            rows="8"
            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
            required
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
            苦労した点・課題 <span class="text-red-500">*</span>
        </label>
        <textarea 
            name="challenges" 
            id="challenges"
            rows="8"
            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
            required
        >{{ old('challenges', $app->challenges ?? '') }}</textarea>
        @error('challenges')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>

    <!-- 工夫した点 -->
    <div class="mb-6">
        <label for="devised_points" class="block text-sm font-medium text-gray-700">
            工夫した点 <span class="text-red-500">*</span>
        </label>
        <textarea 
            name="devised_points" 
            id="devised_points"
            rows="8"
            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
            required
        >{{ old('devised_points', $app->devised_points ?? '') }}</textarea>
        @error('devised_points')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>

    <!-- 学んだこと -->
    <div class="mb-6">
        <label for="learnings" class="block text-sm font-medium text-gray-700">
            学んだこと <span class="text-red-500">*</span>
        </label>
        <textarea 
            name="learnings" 
            id="learnings"
            rows="8"
            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
            required
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
        >{{ old('future_plans', $app->future_plans ?? '') }}</textarea>
        @error('future_plans')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>
</div> 