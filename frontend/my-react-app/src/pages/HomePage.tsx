import { useCallback, type MouseEvent } from "react";
import styles from "./HomePage.module.css";
import LoginSection from "../components/common/LoginSection";
import { useLoginSession } from "../hooks/useLoginSession";

export default function HomePage() {
  const { isLogin, logout } = useLoginSession();

  const handleLogout = useCallback(async () => {
    if (!isLogin) {
      return false;
    }
    return logout();
  }, [isLogin, logout]);

  return (
    <div className={styles.container}>
      <header className={styles.header}>
        <p className={styles.title}>どんぷく オーダーエントリー</p>
      </header>

      <main className={styles.main}>
        {isLogin ? (
          <div className={styles.widget}>
            <div className={styles.widgetList}>
              <a className={styles.link} href="registercoming.php?p=1">
                来店登録
              </a>
            </div>
            <div className={styles.widgetList}>
              <a className={styles.link} href="order_con.php#tyumonmati">
                注文待ちリスト
              </a>
            </div>
            <div className={styles.under}>
              <div className={styles.widgetList}>
                <a className={styles.link} href="order_con.php#tyumonzumi">
                  注文済みリスト
                </a>
              </div>
              <div className={styles.widgetList}>
                <a className={styles.link} href="order_con.php#teikyozumi">
                  提供済みリスト
                </a>
              </div>
              <div className={styles.widgetList}>
                <a className={styles.link} href="administrator.php">
                  管理者
                </a>
              </div>
              <div className={styles.widgetList}>
                <LogoutLink onLogout={handleLogout} />
              </div>
            </div>
          </div>
        ) : (
          <LoginSection />
        )}
      </main>
    </div>
  );
}

function LogoutLink({ onLogout }: { onLogout: () => Promise<boolean> }) {
  const handleClick = useCallback(
    async (event: MouseEvent<HTMLAnchorElement>) => {
      event.preventDefault();
      const ok = await onLogout();
      if (!ok) {
        alert("ログアウトに失敗しました。もう一度お試しください。");
      }
    },
    [onLogout]
  );

  return (
    <a href="#" className={styles.link} onClick={handleClick}>
      ログアウト
    </a>
  );
}
