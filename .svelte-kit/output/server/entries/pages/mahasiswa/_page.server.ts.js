import { redirect, fail } from "@sveltejs/kit";
const load = async ({ locals, url }) => {
  if (!locals.user) {
    redirect(303, "/login");
  }
  if (!["admin", "dosen"].includes(locals.user.role)) {
    redirect(303, "/");
  }
  const page = Number(url.searchParams.get("page")) || 1;
  const limit = 20;
  const from = (page - 1) * limit;
  const to = from + limit - 1;
  const { data: mahasiswa, count, error } = await locals.supabase.from("mahasiswa").select("*", { count: "exact" }).order("nim", { ascending: true }).range(from, to);
  if (error) {
    console.error("Error fetching mahasiswa:", error);
  }
  return {
    mahasiswa: mahasiswa || [],
    count: count || 0,
    page,
    user: locals.user
  };
};
const actions = {
  delete: async ({ request, locals }) => {
    if (!locals.user || locals.user.role !== "admin") {
      return fail(403, { error: "Unauthorized" });
    }
    const formData = await request.formData();
    const nim = formData.get("nim");
    if (!nim) {
      return fail(400, { error: "NIM required" });
    }
    const { error } = await locals.supabase.from("mahasiswa").delete().eq("nim", nim);
    if (error) {
      return fail(500, { error: "Gagal menghapus mahasiswa." });
    }
    return { success: true };
  }
};
export {
  actions,
  load
};
