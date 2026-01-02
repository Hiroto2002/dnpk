import { useCallback, useEffect, useState, type ChangeEvent } from "react";
import styles from "../../pages/HomePage.module.css";
import { useStaff } from "../../hooks/useStaff";
import { Flex } from "../../components/ui/Flex";
import type { User } from "../../hooks/useLoginSession";

type LoginSectionProps = {
  onSubmit: (user: User) => Promise<void>;
};
export default function LoginSection({ onSubmit }: LoginSectionProps) {
  const [selectedUser, setSelectedUser] = useState<User>(null);
  const { staffs } = useStaff();

  useEffect(() => {
    if (!selectedUser && staffs.length > 0) {
      setSelectedUser({ id: String(staffs[0].id), name: staffs[0].name });
    }
  }, [selectedUser, staffs]);

  const handleChange = useCallback(
    (event: ChangeEvent<HTMLSelectElement>) => {
      setSelectedUser({
        id: event.target.value,
        name:
          staffs.find((s) => String(s.id) === event.target.value)?.name || "",
      });
    },
    [setSelectedUser, staffs]
  );

  const handleSubmit = () => {
    if (!selectedUser) {
      return;
    }
    void onSubmit({ id: selectedUser.id, name: selectedUser.name });
  };

  return (
    <Flex
      direction="column"
      align="center"
      justify="center"
      gap={32}
      style={{ height: "100%" }}
    >
      <div id="login-heading" className={styles.loginTitle}>
        ユーザーを選択してください
      </div>
      <div className={styles.user}>
        <label htmlFor="user" className={styles.visuallyHidden}>
          ユーザー
        </label>
        <select
          id="user"
          name="user"
          value={selectedUser?.id || ""}
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
          type="button"
          className={styles.primaryBtn}
          disabled={!selectedUser}
          onClick={handleSubmit}
        >
          送信
        </button>
      </div>
    </Flex>
  );
}
