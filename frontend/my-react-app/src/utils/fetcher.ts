import { API } from "./contract";

type StaffDto = {
  id: string | number;
  name: string;
};

type ControllerResponses = {
  StaffController: {
    getAll: {
      ok: true;
      staff: StaffDto[];
    };
  };
  UserController: {
    me: {
      ok: true;
      userId: string | null;
      userName: string | null;
    };
    login: {
      ok: true;
      userId: string;
      userName: string | null;
    };
    logout: {
      ok: true;
    };
  };
};

type Controller = keyof ControllerResponses;
type ControllerActionMap = {
  [C in Controller]: Record<keyof ControllerResponses[C], string>;
};

const Controllers: Record<Controller, string> = {
  StaffController: "StaffController.php",
  UserController: "UserController.php",
};

const ACTIONS: ControllerActionMap = {
  StaffController: {
    getAll: "getAll",
  },
  UserController: {
    me: "me",
    login: "login",
    logout: "logout",
  },
};

type ActionName<C extends Controller> = keyof ControllerResponses[C];
type ControllerResponse<C extends Controller, A extends ActionName<C>> =
  ControllerResponses[C][A];

type FetcherOptions = {
  method?: "GET" | "POST";
  body?: Record<string, unknown> | FormData | string;
  headers?: Record<string, string>;
  signal?: AbortSignal;
  credentials?: RequestCredentials;
};

const isPlainObject = (value: unknown): value is Record<string, unknown> => {
  return typeof value === "object" && value !== null && value.constructor === Object;
};

type ErrorResponse = {
  ok: false;
  error?: string;
};

const isErrorResponse = (value: unknown): value is ErrorResponse => {
  return (
    typeof value === "object" &&
    value !== null &&
    "ok" in value &&
    (value as { ok?: unknown }).ok === false
  );
};

export async function fetcher<C extends Controller, A extends ActionName<C>>(
  controller: C,
  action: A,
  options: FetcherOptions = {}
): Promise<ControllerResponse<C, A>> {
  const { method = "GET", body, headers = {}, signal, credentials = "include" } = options;
  const controllerPath = Controllers[controller];
  const actionValue = ACTIONS[controller][action];
  const searchParams = new URLSearchParams({ action: String(actionValue) });
  const baseUrl = `${API}/${controllerPath}`;
  let url = baseUrl;

  const init: RequestInit = {
    method,
    credentials,
    signal,
  };

  if (method === "GET") {
    url = `${baseUrl}?${searchParams.toString()}`;
  } else {
    url = `${baseUrl}?${searchParams.toString()}`;
    if (body instanceof FormData) {
      init.body = body;
    } else if (typeof body === "string") {
      init.body = body;
      init.headers = headers;
    } else if (isPlainObject(body)) {
      init.body = JSON.stringify(body);
      init.headers = { "Content-Type": "application/json", ...headers };
    } else if (body !== undefined) {
      init.body = body as BodyInit;
      init.headers = headers;
    } else if (Object.keys(headers).length > 0) {
      init.headers = headers;
    }
  }

  if (!init.headers && Object.keys(headers).length > 0) {
    init.headers = headers;
  }

  const response = await fetch(url, init);
  const data = (await response.json().catch(() => null)) as
    | ControllerResponse<C, A>
    | ErrorResponse
    | null;

  if (!response.ok || !data || isErrorResponse(data)) {
    const message = (isErrorResponse(data) && data.error) || response.statusText || "Request failed";
    throw new Error(message);
  }

  return data;
}
