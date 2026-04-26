import { redirect, fail } from "@sveltejs/kit";
const load = async ({ locals }) => {
  if (!locals.user || locals.user.role !== "admin" && locals.user.role !== "dosen") {
    redirect(303, "/nilai");
  }
  const { data: semData } = await locals.supabase.from("semester_config").select("nama_semester").eq("is_aktif", 1).single();
  const semester_aktif = semData?.nama_semester || "Unknown";
  const { data: mahasiswa } = await locals.supabase.from("mahasiswa").select("nim, nama_mahasiswa").order("nama_mahasiswa");
  let mkQuery = locals.supabase.from("mata_kuliah").select("kode_mk, nama_mk").order("nama_mk");
  if (locals.user.role === "dosen") {
    const { data: pengampu } = await locals.supabase.from("dosen_pengampu").select("kode_matkul").eq("nip_dosen", locals.user.nip);
    const kodeMkList = pengampu?.map((p) => p.kode_matkul) || [];
    if (kodeMkList.length > 0) {
      mkQuery = mkQuery.in("kode_mk", kodeMkList);
    } else {
      mkQuery = mkQuery.in("kode_mk", []);
    }
  }
  const { data: matakuliah } = await mkQuery;
  return {
    mahasiswa: mahasiswa || [],
    matakuliah: matakuliah || [],
    semester_aktif
  };
};
const actions = {
  default: async ({ request, locals }) => {
    if (!locals.user || locals.user.role !== "admin" && locals.user.role !== "dosen") {
      return fail(403, { error: "Unauthorized" });
    }
    const formData = await request.formData();
    const nim_mahasiswa = formData.get("nim_mahasiswa");
    const kode_matkul = formData.get("kode_matkul");
    const nilai_huruf = formData.get("nilai_huruf");
    if (!nim_mahasiswa || !kode_matkul || !nilai_huruf) {
      return fail(400, { error: "Semua kolom wajib diisi.", values: Object.fromEntries(formData) });
    }
    const validGrades = ["A", "B", "C", "D", "E"];
    if (!validGrades.includes(nilai_huruf.toUpperCase())) {
      return fail(400, { error: "Nilai harus A, B, C, D, atau E.", values: Object.fromEntries(formData) });
    }
    const { data: semData } = await locals.supabase.from("semester_config").select("nama_semester").eq("is_aktif", 1).single();
    const semester_aktif = semData?.nama_semester;
    const { data: krsCheck } = await locals.supabase.from("krs").select("*").eq("nim_mahasiswa", nim_mahasiswa).eq("kode_matkul", kode_matkul).eq("semester", semester_aktif).single();
    if (!krsCheck) {
      return fail(400, { error: `Mahasiswa ini belum mengambil mata kuliah tersebut di semester ini (${semester_aktif}).`, values: Object.fromEntries(formData) });
    }
    const { error } = await locals.supabase.from("nilai").insert({
      nim_mahasiswa,
      kode_matkul,
      nilai_huruf: nilai_huruf.toUpperCase()
    });
    if (error) {
      console.error(error);
      return fail(500, { error: "Gagal menyimpan nilai.", values: Object.fromEntries(formData) });
    }
    return { success: true, message: "Nilai berhasil disimpan." };
  }
};
export {
  actions,
  load
};
