process.env.API_BASE_URL =
  process.env.API_BASE_URL ?? "http://localhost:8081";

const cookieStore = new Map<string, string>();

const buildCookieHeader = (): string =>
  Array.from(cookieStore.entries())
    .map(([key, value]) => `${key}=${value}`)
    .join("; ");

const parseAndStoreCookies = (cookies: string[]) => {
  cookies.forEach((cookie) => {
    const [pair] = cookie.split(";");
    if (!pair) return;
    const [key, value] = pair.split("=");
    if (!key || value === undefined) return;
    cookieStore.set(key.trim(), value.trim());
  });
};

const originalFetch: typeof fetch = global.fetch;

global.fetch = (async (input, init = {}) => {
  const headers = new Headers(init.headers);

  if (init.credentials === "include" && cookieStore.size > 0) {
    headers.set("cookie", buildCookieHeader());
  }

  const response = await originalFetch(input, { ...init, headers });

  const headerLike = response.headers as unknown as {
    getSetCookie?: () => string[];
    raw?: () => Record<string, string[]>;
  };

  const setCookies =
    headerLike.getSetCookie?.() ??
    headerLike.raw?.()["set-cookie"] ??
    (response.headers.get("set-cookie")
      ? [response.headers.get("set-cookie") as string]
      : []);

  parseAndStoreCookies(setCookies);

  return response;
}) as typeof fetch;
