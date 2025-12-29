import { useCallback, useEffect, useMemo, useState } from "react";
import { fetcher } from "../utils/fetcher";

type Staff = { id: string | number; name: string };

export default function RegisterComing() {
  const [loading, setLoading] = useState(true);
  const [me, setMe] = useState<{
    userId: string | number | null;
    userName?: string | null;
  } | null>(null);
  const [staffs, setStaffs] = useState<Staff[]>([]);
  const [selected, setSelected] = useState<string | number | undefined>(
    undefined
  );
  const hasUser = !!me?.userId;

  const fetchStaffs = useCallback(async () => {
    const staffs = await fetcher("StaffController", "getAll");
    setStaffs(staffs.staff);
  }, []);

  useEffect(() => {
    (async () => {
      try {
        await Promise.all([fetchMe(), fetchStaffs()]);
      } finally {
        setLoading(false);
      }
    })();
  }, [fetchMe, fetchStaffs]);

  const defaultValue = useMemo(() => {
    if (selected !== undefined) return selected;
    if (staffs.length > 0) return staffs[0].id;
    return "";
  }, [selected, staffs]);

  const onLogin = useCallback(async (e: React.FormEvent) => {
    e.preventDefault();
    const user = (
      document.getElementById("rc-user") as HTMLSelectElement | null
    )?.value;
    if (!user) return;
    const r = await fetch(`${API}?action=login`, {
      method: "POST",
      headers: { "Content-Type": "application/json" },
      credentials: "include",
      body: JSON.stringify({ user }),
    });
    const j = await r.json();
    if (j.ok) {
      setMe({ userId: j.userId ?? user });
    }
  }, []);

  const onLogout = useCallback(async () => {
    await fetch(`${API}?action=logout`, {
      method: "POST",
      credentials: "include",
    });
    setMe({ userId: null });
  }, []);

  if (loading) return <p>読み込み中...</p>;

  return (
    <div style={{ padding: 16 }}>
      <h1>来店登録</h1>

      {hasUser ? (
        <div style={{ display: "grid", gap: 12 }}>
          <p>
            ログイン中: <strong>{me?.userName ?? me?.userId}</strong>
          </p>
          <button onClick={onLogout}>ログアウト</button>
          {/* ここに来店登録の本来のUIを配置（任意） */}
        </div>
      ) : (
        <form onSubmit={onLogin} style={{ display: "grid", gap: 12 }}>
          <label htmlFor="rc-user">ユーザーを選択してください</label>
          <select
            id="rc-user"
            name="user"
            value={defaultValue as any}
            onChange={(e) => setSelected(e.target.value)}
          >
            {staffs.map((s) => (
              <option key={s.id} value={s.id}>
                {s.name}
              </option>
            ))}
          </select>
          <button type="submit">送信</button>
        </form>
      )}
    </div>
  );
}
