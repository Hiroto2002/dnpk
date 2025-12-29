import { useCallback, useEffect, useState } from "react";
import { fetcher } from "../utils/fetcher";

export type User = {
  id: string;
  name: string | null;
} | null;

const toUser = (payload: {
  userId: string | null;
  userName: string | null;
}): User => {
  if (!payload.userId) {
    return null;
  }
  return {
    id: payload.userId,
    name: payload.userName,
  };
};

export function useLoginSession() {
  const [me, setMe] = useState<User>(null);

  const fetchUser = useCallback(async () => {
    try {
      const response = await fetcher("UserController", "me");
      setMe(toUser(response));
    } catch (error) {
      console.error("Failed to fetch initial data", error);
    }
  }, []);

  useEffect(() => {
    void fetchUser();
  }, [fetchUser]);

  const login = useCallback(
    async (userId: string, userName?: string | null) => {
      try {
        const result = await fetcher("UserController", "login", {
          method: "POST",
          body: { user: userId, userName },
        });
        setMe(toUser(result));
      } catch (error) {
        console.error("Login failed", error);
      }
    },
    []
  );

  const logout = useCallback(async () => {
    try {
      const result = await fetcher("UserController", "logout", {
        method: "POST",
      });
      setMe(null);
      return result.ok;
    } catch (error) {
      console.error("Logout failed", error);
      return false;
    }
  }, []);

  return {
    me,
    isLogin: me !== null,
    login,
    logout,
  } as const;
}
