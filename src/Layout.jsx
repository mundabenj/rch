import { Navbar } from "./Components/Navbar";
import { Outlet } from "react-router-dom";

export function Layout() {
  return (
    <>
      <Navbar />
    <main className="container">
      <Outlet />
    </main>
    </>
  );
}