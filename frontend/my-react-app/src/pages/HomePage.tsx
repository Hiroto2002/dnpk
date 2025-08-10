import { Link } from "react-router-dom";
import styles from "./HomePage.module.css"; // スタイルシートをインポート

export default function HomePage() {
  // ログアウトボタン用の仮の処理
  const handleLogout = () => {
    alert("ログアウトしました");
  };

  return (
    <div className={styles.container}>
      <h1 className={styles.title}>どんぶく オーダーエントリー</h1>

      <div className={styles.menuGrid}>
        {/* フルサイズのボタン */}
        <Link
          to="/register"
          className={`${styles.menuButton} ${styles.fullWidth}`}
        >
          来店登録
        </Link>
        <Link
          to="/waiting-list"
          className={`${styles.menuButton} ${styles.fullWidth}`}
        >
          注文待ちリスト
        </Link>

        <Link to="/ordered-list" className={styles.menuButton}>
          注文済みリスト
        </Link>
        <Link to="/served-list" className={styles.menuButton}>
          提供済みリスト
        </Link>
        <Link to="/admin" className={styles.menuButton}>
          管理者
        </Link>

        <button onClick={handleLogout} className={styles.menuButton}>
          ログアウト
        </button>
      </div>
    </div>
  );
}
