import { redirect, fail } from "@sveltejs/kit";
const load = async ({ locals, url }) => {
  if (!locals.user) {
    redirect(303, "/login");
  }
  let query = locals.supabase.from("mata_kuliah").select("*", { count: "exact" });
  if (locals.user.role === "dosen") {
    const { data: pengampu } = await locals.supabase.from("dosen_pengampu").select("kode_matkul").eq("nip_dosen", locals.user.nip);
    const kodeMkList = pengampu?.map((p) => p.kode_matkul) || [];
    if (kodeMkList.length > 0) {
      query = query.in("kode_mk", kodeMkList);
    } else {
      return {
        matakuliah: [],
        count: 0
      };
    }
  }
  query = query.order("semester", { ascending: true }).order("nama_mk", { ascending: true });
  const { data: matakuliah, count, error } = await query;
  if (error) {
    console.error("Error fetching matakuliah:", error);
  }
  return {
    matakuliah: matakuliah || [],
    count: count || 0,
    user: locals.user
  };
};
const actions = {
  delete: async ({ request, locals }) => {
    if (!locals.user || locals.user.role !== "admin") {
      return fail(403, { error: "Unauthorized" });
    }
    const formData = await request.formData();
    const kode_mk = formData.get("kode_mk");
    if (!kode_mk) {
      return fail(400, { error: "Kode MK required" });
    }
    const { error } = await locals.supabase.from("mata_kuliah").delete().eq("kode_mk", kode_mk);
    if (error) {
      console.error(error);
      return fail(500, { error: "Gagal menghapus mata kuliah. Pastikan tidak ada data terkait." });
    }
    return { success: true };
  }
};
export {
  actions,
  load
};
