import { useCallback, type MouseEvent } from "react";
import styles from "./HomePage.module.css";
import LoginSection from "../components/common/LoginSection";
import { useLoginSession } from "../hooks/useLoginSession";
import { Flex } from "../components/ui/Flex";

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
      <div className={styles.header}>
        <p className={styles.title}>どんぷく オーダーエントリー</p>
      </div>

      <div className={styles.main}>
        {isLogin ? (
          <Flex
            style={{ width: "100%", height: "100%", marginTop: "40px" }}
            wrap="wrap"
            align="center"
            gap={16}
          >
            <WigetBox href="registercoming.php?p=1">来店登録</WigetBox>
            <WigetBox href="order_con.php#tyumonmati">注文待ちリスト</WigetBox>
            <WigetBox href="order_con.php#tyumonzumi" sub>
              注文済みリスト
            </WigetBox>
            <WigetBox href="order_con.php#teikyozumi" sub>
              提供済みリスト
            </WigetBox>
            <WigetBox href="administrator.php" sub>
              管理者
            </WigetBox>
            <div
              className={styles.widgetItem}
              style={{ width: "calc(45% - 30px)" }}
            >
              <LogoutLink onLogout={handleLogout} />
            </div>
          </Flex>
        ) : (
          <LoginSection />
        )}
      </div>
    </div>
  );
}

const WigetBox = ({
  href,
  children,
  sub,
}: {
  href: string;
  children: React.ReactNode;
  sub?: boolean;
}) => {
  return (
    <div
      className={styles.widgetItem}
      style={sub ? { width: "calc(45% - 30px)" } : {}}
    >
      <a className={styles.link} href={href}>
        {children}
      </a>
    </div>
  );
};

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
