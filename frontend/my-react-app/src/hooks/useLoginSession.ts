import { useCallback, useEffect, useState } from "react";
import { fetcher } from "../utils/fetcher";
import type { LoginRequestBody } from "../features/HomePage/contract";

export type User = {
  id: string;
  name: string;
} | null;

export function useLoginSession() {
  const [me, setMe] = useState<User>(null);

  const fetchUser = useCallback(async () => {
    try {
      const response = await fetcher("UserController", "me");
      setMe(response.user);
    } catch (error) {
      console.error("Failed to fetch initial data", error);
    }
  }, []);

  useEffect(() => {
    void fetchUser();
  }, [fetchUser]);

  const login = useCallback(async (user: User) => {
    if (!user) {
      return;
    }
    try {
      const body: LoginRequestBody = { user: { id: user.id, name: user.name } };
      const result = await fetcher("UserController", "login", {
        method: "POST",
        body: body,
      });
      setMe(result.user);
    } catch (error) {
      console.error("Login failed", error);
    }
  }, []);

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
