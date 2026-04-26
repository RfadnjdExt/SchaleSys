import { redirect, fail } from "@sveltejs/kit";
import bcrypt from "bcryptjs";
const load = async ({ locals }) => {
  if (locals.user) {
    redirect(303, "/");
  }
};
const actions = {
  default: async ({ request, locals, cookies }) => {
    const formData = await request.formData();
    const username = formData.get("username");
    const password = formData.get("password");
    if (!username || !password) {
      return fail(400, { error: "Username dan password wajib diisi." });
    }
    const { data: user, error } = await locals.supabase.from("users").select("id_user, username, nama_lengkap, password, role, nim, nip").eq("username", username).single();
    if (error || !user) {
      if (error && error.code !== "PGRST116") {
        console.error("Login error (Supabase):", error);
      }
      return fail(400, { error: "Username atau password salah." });
    }
    const validPassword = await bcrypt.compare(password, user.password);
    if (!validPassword) {
      return fail(400, { error: "Username atau password salah." });
    }
    const sessionUser = {
      id_user: user.id_user,
      username: user.username,
      nama_lengkap: user.nama_lengkap,
      role: user.role,
      nim: user.nim,
      nip: user.nip
    };
    cookies.set("custom_session", JSON.stringify(sessionUser), {
      path: "/",
      httpOnly: true,
      sameSite: "strict",
      secure: process.env.NODE_ENV === "production",
      maxAge: 60 * 60 * 24 * 7
      // 1 week
    });
    redirect(303, "/");
  }
};
export {
  actions,
  load
};
