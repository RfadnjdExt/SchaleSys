import { redirect, fail } from "@sveltejs/kit";
const load = async ({ locals, params }) => {
  if (!locals.user || locals.user.role !== "admin") {
    redirect(303, "/mahasiswa");
  }
  const { nim } = params;
  const [dosenRes, mhsRes] = await Promise.all([
    locals.supabase.from("dosen").select("nip, nama_dosen").order("nama_dosen"),
    locals.supabase.from("mahasiswa").select("*").eq("nim", nim).single()
  ]);
  if (!mhsRes.data) {
    throw redirect(303, "/mahasiswa");
  }
  return {
    dosenList: dosenRes.data || [],
    mahasiswa: mhsRes.data
  };
};
const actions = {
  default: async ({ request, locals, params }) => {
    if (!locals.user || locals.user.role !== "admin") {
      return fail(403, { error: "Unauthorized" });
    }
    const { nim } = params;
    const formData = await request.formData();
    const nama_mahasiswa = formData.get("nama_mahasiswa");
    const fakultas = formData.get("fakultas");
    const prodi = formData.get("prodi");
    const angkatan = formData.get("angkatan");
    const foto = formData.get("foto");
    const dosen_wali_id = formData.get("dosen_wali_id") || null;
    if (!nama_mahasiswa || !prodi || !angkatan || !fakultas) {
      return fail(400, { error: "Nama, Prodi, Fakultas dan Angkatan wajib diisi.", values: Object.fromEntries(formData) });
    }
    const { error } = await locals.supabase.from("mahasiswa").update({
      nama_mahasiswa,
      fakultas,
      prodi,
      angkatan: parseInt(angkatan),
      foto: foto || null,
      dosen_wali_id
    }).eq("nim", nim);
    if (error) {
      console.error(error);
      return fail(500, { error: "Gagal mengupdate data mahasiswa.", values: Object.fromEntries(formData) });
    }
    redirect(303, "/mahasiswa");
  }
};
export {
  actions,
  load
};
