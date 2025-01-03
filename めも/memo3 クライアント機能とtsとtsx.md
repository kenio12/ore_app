'use client' が必要なケース：
ブラウザのAPIを使う（localStorage, window など）
イベントハンドラを使う（onClick, onChange など）
Reactのフック（useState, useEffect など）を使う
'use client'
// ブラウザの機能使うからclient必要
例
function BrowserStuff() {
  // localStorage使う
  const saveData = () => localStorage.setItem('key', 'value');
  
  // window使う
  const screenWidth = window.innerWidth;
  
  // ブラウザのアラート
  const showAlert = () => alert('へい！');
}
例１
'use client'
function UserInteraction() {
  // クリックイベント
  const handleClick = () => console.log('クリック！');
  
  // フォーム入力
  const handleChange = (e) => console.log(e.target.value);
  
  return (
    <div>
      <button onClick={handleClick}>ボタン</button>
      <input onChange={handleChange} />
    </div>
  );
}
例２
'use client'
function UserInteraction() {
  // クリックイベント
  const handleClick = () => console.log('クリック！');
  
  // フォーム入力
  const handleChange = (e) => console.log(e.target.value);
  
  return (
    <div>
      <button onClick={handleClick}>ボタン</button>
      <input onChange={handleChange} />
    </div>
  );
}

例３
'use client'
function StateExample() {
  // 状態管理
  const [count, setCount] = useState(0);
  
  // 副作用
  useEffect(() => {
    console.log('マウント時に実行！');
  }, []);
  
  // カスタムフック
  const { data } = useSWR('/api/data');
}

関係ないケース：
単純な計算や変換の関数
型定義
サーバーサイドだけで動く処理
例１
// 単純な計算
function calculateTotal(prices: number[]) {
  return prices.reduce((sum, p) => sum + p, 0);
}

// 静的なJSX
function StaticView() {
  return <div>変化せえへん表示</div>;
}

// データの整形
function formatUserName(user: {first: string, last: string}) {
  return `${user.last} ${user.first}`;
}

注意点！
コンポーネントの中で一つでもクライアント機能使うなら、そのコンポーネント全体に 'use client' 必要
親コンポーネントが 'use client' なら、子コンポーネントは書かんでもOK


一方
.ts か .tsx かは「JSXを書くかどうか」で決める
次がJsx=.tsx
HTMLっぽい構文が書ける
JavaScript/TypeScriptの式も埋め込める
コンポーネントも使える

⭐️コンポーネント・・・フラッターのWidgetみたいだが、関数として直接返せるなど少し違う
⭐️TypeScript・・・型をつける
利点
バグの早期発見
入力補完が効く
APIのレスポンス型の定義
関数の引数と戻り値の型チェック
実際の開発での効果：
安全性アップ
型違いのデータを入れようとしたら即座にエラーになる
必要なプロパティの漏れがすぐわかる
開発効率アップ
補完が効くから、プロパティ名の確認いらず
APIの仕様がコードから分かる
メンテナンス性アップ
コードの意図が分かりやすい
リファクタリングが安全にできる
4. チーム開発が楽に
インターフェースが明確
他の人のコードが理解しやすい
要するに：
エラーを早く見つけられる
コードが書きやすい
安全性が高い
メンテナンスが楽
これらのメリットがあるから、最近のフロントエンド開発では TypeScript がめっちゃ使われてるんやで！

例　
JSXを使う場合 (.tsx)
export default function Header() {
  return (
    <div className="header">
      <h1>タイトル</h1>
      <nav>
        <ul>
          <li><a href="/">ホーム</a></li>
          <li><a href="/about">概要</a></li>
        </ul>
      </nav>
    </div>
  );
}

JSXを使わない場合 (.ts)
// HTMLは作らんから.tsでOK
export function calculateTotal(items: { price: number }[]) {
  return items.reduce((sum, item) => sum + item.price, 0);
}

export function formatDate(date: Date) {
  return date.toLocaleDateString('ja-JP');
}

JSXはコンポーネントも使える
これは、えっと、flutterのwidgetみたいなもんやな
