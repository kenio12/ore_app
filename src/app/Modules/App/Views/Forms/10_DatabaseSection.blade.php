<div class="bg-white p-8 rounded-lg shadow mb-8">
    <h2 class="text-2xl font-bold mb-6">データベース環境</h2>

    <!-- データベース種類 -->
    <div class="mb-6">
        <label class="block text-sm font-medium text-gray-700 mb-2">
            データベース
        </label>
        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4 bg-gray-50 p-4 rounded-lg">
            @foreach([
                'mysql' => 'MySQL',
                'postgresql' => 'PostgreSQL',
                'mongodb' => 'MongoDB',
                'redis' => 'Redis',
                'sqlite' => 'SQLite',
                'oracle' => 'Oracle',
                'sqlserver' => 'SQL Server',
                'other' => 'その他'
            ] as $value => $label)
                <label class="flex items-center gap-2">
                    <input
                        type="checkbox"
                        name="databases[]"
                        value="{{ $value }}"
                        {{ in_array($value, old('databases', $app->databases ?? [])) ? 'checked' : '' }}
                        class="rounded border-gray-300 text-blue-600 focus:ring-blue-500"
                        disabled
                    >
                    <span class="text-gray-700">{{ $label }}</span>
                </label>
            @endforeach
        </div>
        <!-- その他の場合の入力欄 -->
        <div class="mt-2">
            <input 
                type="text" 
                name="other_database" 
                id="other_database"
                value="{{ old('other_database', $app->other_database ?? '') }}"
                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                placeholder="その他のデータベースを入力"
                readonly
            >
        </div>
        @error('databases')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>

    <!-- ORMとクエリビルダー -->
    <div class="mb-6">
        <label class="block text-sm font-medium text-gray-700 mb-2">
            ORMとクエリビルダー
        </label>
        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4 bg-gray-50 p-4 rounded-lg">
            @foreach([
                'eloquent' => 'Eloquent',
                'doctrine' => 'Doctrine',
                'sequelize' => 'Sequelize',
                'prisma' => 'Prisma',
                'mongoose' => 'Mongoose',
                'typeorm' => 'TypeORM',
                'other' => 'その他'
            ] as $value => $label)
                <label class="flex items-center gap-2">
                    <input
                        type="checkbox"
                        name="orms[]"
                        value="{{ $value }}"
                        {{ in_array($value, old('orms', $app->orms ?? [])) ? 'checked' : '' }}
                        class="rounded border-gray-300 text-blue-600 focus:ring-blue-500"
                        disabled
                    >
                    <span class="text-gray-700">{{ $label }}</span>
                </label>
            @endforeach
        </div>
        <!-- その他の場合の入力欄 -->
        <div class="mt-2">
            <input 
                type="text" 
                name="other_orm" 
                id="other_orm"
                value="{{ old('other_orm', $app->other_orm ?? '') }}"
                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                placeholder="その他のORMを入力"
                readonly
            >
        </div>
        @error('orms')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>

    <!-- キャッシュ -->
    <div class="mb-6">
        <label class="block text-sm font-medium text-gray-700 mb-2">
            キャッシュ
        </label>
        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4 bg-gray-50 p-4 rounded-lg">
            @foreach([
                'redis' => 'Redis',
                'memcached' => 'Memcached',
                'file' => 'ファイルキャッシュ',
                'other' => 'その他'
            ] as $value => $label)
                <label class="flex items-center gap-2">
                    <input
                        type="checkbox"
                        name="caches[]"
                        value="{{ $value }}"
                        {{ in_array($value, old('caches', $app->caches ?? [])) ? 'checked' : '' }}
                        class="rounded border-gray-300 text-blue-600 focus:ring-blue-500"
                        disabled
                    >
                    <span class="text-gray-700">{{ $label }}</span>
                </label>
            @endforeach
        </div>
        <!-- その他の場合の入力欄 -->
        <div class="mt-2">
            <input 
                type="text" 
                name="other_cache" 
                id="other_cache"
                value="{{ old('other_cache', $app->other_cache ?? '') }}"
                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                placeholder="その他のキャッシュシステムを入力"
                readonly
            >
        </div>
        @error('caches')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>

    <!-- データベース構成の説明 -->
    <div class="mb-6">
        <label for="database_description" class="block text-sm font-medium text-gray-700">
            データベース構成の説明
        </label>
        <textarea 
            name="database_description" 
            id="database_description"
            rows="8"
            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
            placeholder="例：
- データベースの選定理由
- テーブル設計の特徴
- インデックスの活用
- キャッシュ戦略
- シャーディング/レプリケーション"
            readonly
        >{{ old('database_description', $app->database_description ?? '') }}</textarea>
        @error('database_description')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>

    <!-- バージョン情報 -->
    <div class="mb-6">
        <label for="database_versions" class="block text-sm font-medium text-gray-700">
            バージョン情報
        </label>
        <textarea 
            name="database_versions" 
            id="database_versions"
            rows="8"
            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
            placeholder="例：
MySQL 8.0
Redis 7.0"
            readonly
        >{{ old('database_versions', $app->database_versions ?? '') }}</textarea>
        @error('database_versions')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>

    <!-- データベースホスティングサービス -->
    <div class="mb-6">
        <label class="block text-sm font-medium text-gray-700 mb-2">
            データベースホスティングサービス
        </label>
        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4 bg-gray-50 p-4 rounded-lg">
            @foreach([
                'upstash' => 'Upstash (Redis)',
                'planetscale' => 'PlanetScale (MySQL)',
                'mongodb_atlas' => 'MongoDB Atlas',
                'aws_rds' => 'AWS RDS',
                'heroku_postgres' => 'Heroku Postgres',
                'firebase_realtime' => 'Firebase Realtime DB',
                'supabase' => 'Supabase',
                'cockroachdb' => 'CockroachDB',
                'other' => 'その他'
            ] as $value => $label)
                <label class="flex items-center gap-2">
                    <input
                        type="checkbox"
                        name="db_hosting_services[]"
                        value="{{ $value }}"
                        {{ in_array($value, old('db_hosting_services', $app->db_hosting_services ?? [])) ? 'checked' : '' }}
                        class="rounded border-gray-300 text-blue-600 focus:ring-blue-500"
                        disabled
                    >
                    <span class="text-gray-700">{{ $label }}</span>
                </label>
            @endforeach
        </div>
        <!-- その他の場合の入力欄 -->
        <div class="mt-2">
            <input 
                type="text" 
                name="other_db_hosting" 
                id="other_db_hosting"
                value="{{ old('other_db_hosting', $app->other_db_hosting ?? '') }}"
                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                placeholder="その他のデータベースホスティングサービスを入力"
                readonly
            >
        </div>
        @error('db_hosting_services')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>
</div> 