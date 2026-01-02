import { fetcher } from "../../my-react-app/src/utils/fetcher";

describe("fetcher", () => {
  it("Userが未ログインの場合、meはnullを返す", async () => {
    const result = await fetcher("UserController", "me");
    expect(result).toEqual({ ok: true, user: null });
  });

  it("Userをログインさせる", async () => {
    const payload = { user: { id: "1", name: "Tester" } };
    const result = await fetcher("UserController", "login", {
      method: "POST",
      body: payload,
    });

    expect(result.ok).toBe(true);
    expect(result.user.id).toBe("1");
    expect(result.user.name).toBe("Tester");
  });

  it("ログインしたユーザーを取得できる", async () => {
    const loginResult = await fetcher("UserController", "login", {
      method: "POST",
      body: { user: { id: "1", name: "Tester" } },
    });
    expect(loginResult.ok).toBe(true);

    const meResult = await fetcher("UserController", "me");
    expect(meResult.ok).toBe(true);
    expect(meResult.user).toEqual({ id: "1", name: "Tester" });
  });
});
