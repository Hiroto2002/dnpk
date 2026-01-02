const CONTROLLER_PATH = "/http/Controllers";

type MaybeNodeProcess = {
  env?: Record<string, string | undefined>;
};

const normalizeUrl = (value: string): string => value.replace(/\/+$/, "");

const getNodeEnvBaseUrl = (): string | null => {
  const maybeProcess = (globalThis as { process?: MaybeNodeProcess }).process;
  const baseUrl = maybeProcess?.env?.API_BASE_URL?.trim();

  return baseUrl ? normalizeUrl(baseUrl) : null;
};

type ImportMetaEnv = {
  VITE_API_BASE_URL?: string;
};

const getImportMetaEnv = (): ImportMetaEnv | null => {
  try {
    const importMetaRetriever = new Function("return import.meta");
    const meta = importMetaRetriever() as { env?: ImportMetaEnv } | undefined;
    return meta?.env ?? null;
  } catch {
    return null;
  }
};

const getBrowserEnvBaseUrl = (): string | null => {
  const env = getImportMetaEnv();
  const baseUrl = env?.VITE_API_BASE_URL?.trim();

  return baseUrl ? normalizeUrl(baseUrl) : null;
};

const envBaseUrl = getNodeEnvBaseUrl() ?? getBrowserEnvBaseUrl();

export const API = envBaseUrl
  ? `${envBaseUrl}${CONTROLLER_PATH}`
  : CONTROLLER_PATH;
