import { StrictMode } from "react";
import { createRoot } from "react-dom/client";
import "./index.css";
import App from "./App.tsx";
import { createHashRouter, RouterProvider } from "react-router-dom";
import HomePage from "./pages/HomePage.tsx";

const router = createHashRouter([
  {
    path: "/",
    element: <App />,
    // childrenを設定すると、Appコンポーネント内にネストされたページを作れます
    // errorElement: <ErrorPage />, // エラーページの指定も可能
    children: [
      {
        index: true, // 👈 path: "/" の時にHomePageを表示する
        element: <HomePage />,
      },
      // { path: "about", element: <AboutPage /> },
      // { path: "contact", element: <ContactPage /> },
    ],
  },
]);
createRoot(document.getElementById("root")!).render(
  <StrictMode>
    <RouterProvider router={router} />
  </StrictMode>
);
