import { redirect, fail } from "@sveltejs/kit";
const load = async ({ locals }) => {
  if (!locals.user || locals.user.role !== "admin") {
    redirect(303, "/mahasiswa");
  }
  const { data: dosenList } = await locals.supabase.from("dosen").select("nip, nama_dosen").order("nama_dosen");
  return {
    dosenList: dosenList || []
  };
};
const actions = {
  default: async ({ request, locals }) => {
    if (!locals.user || locals.user.role !== "admin") {
      return fail(403, { error: "Unauthorized" });
    }
    const formData = await request.formData();
    const nim = formData.get("nim");
    const nama_mahasiswa = formData.get("nama_mahasiswa");
    const fakultas = formData.get("fakultas");
    const prodi = formData.get("prodi");
    const angkatan = formData.get("angkatan");
    const foto = formData.get("foto");
    const dosen_wali_id = formData.get("dosen_wali_id") || null;
    if (!nim || !nama_mahasiswa || !prodi || !angkatan || !fakultas) {
      return fail(400, { error: "NIM, Nama, Prodi, Fakultas dan Angkatan wajib diisi.", values: Object.fromEntries(formData) });
    }
    if (isNaN(Number(nim)) || nim.length < 5) {
      return fail(400, { error: "NIM harus angka dan minimal 5 digit.", values: Object.fromEntries(formData) });
    }
    const { error } = await locals.supabase.from("mahasiswa").insert({
      nim,
      nama_mahasiswa,
      fakultas,
      prodi,
      angkatan: parseInt(angkatan),
      foto: foto || null,
      dosen_wali_id
    });
    if (error) {
      console.error(error);
      if (error.code === "23505") {
        return fail(400, { error: `NIM '${nim}' sudah terdaftar.`, values: Object.fromEntries(formData) });
      }
      return fail(500, { error: "Gagal menambahkan mahasiswa.", values: Object.fromEntries(formData) });
    }
    redirect(303, "/mahasiswa");
  }
};
export {
  actions,
  load
};
