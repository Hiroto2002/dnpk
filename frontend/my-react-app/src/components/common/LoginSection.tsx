import { useCallback, useEffect, useState, type ChangeEvent } from "react";
import styles from "../../pages/HomePage.module.css";
import { useLoginSession } from "../../hooks/useLoginSession";
import { useStaff } from "../../hooks/useStaff";

export default function LoginSection() {
  const { login } = useLoginSession();
  const [selectedUser, setSelectedUser] = useState<string>("");
  const { staffs } = useStaff();

  useEffect(() => {
    if (!selectedUser && staffs.length > 0) {
      setSelectedUser(String(staffs[0].id));
    }
  }, [selectedUser, staffs]);

  const handleChange = useCallback(
    (event: ChangeEvent<HTMLSelectElement>) => {
      setSelectedUser(event.target.value);
    },
    [setSelectedUser]
  );

  const handleSubmit = () => {
    if (!selectedUser) {
      return;
    }
    void login(selectedUser);
  };

  return (
    <section aria-labelledby="login-heading" className={styles.loginWrap}>
      <h2 id="login-heading" className={styles.loginTitle}>
        ユーザーを選択してください
      </h2>
      <form className={styles.user}>
        <label htmlFor="user" className={styles.visuallyHidden}>
          ユーザー
        </label>
        <select
          id="user"
          name="user"
          value={selectedUser}
          onChange={handleChange}
          className={styles.userSelect}
        >
          {staffs.map((s) => (
            <option key={s.id} value={String(s.id)}>
              {s.name}
            </option>
          ))}
        </select>
        <button
          type="submit"
          className={styles.primaryBtn}
          disabled={!selectedUser}
          onClick={handleSubmit}
        >
          送信
        </button>
      </form>
    </section>
  );
}
