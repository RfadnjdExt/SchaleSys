import { redirect, fail } from "@sveltejs/kit";
const load = async ({ locals }) => {
  if (!locals.user || locals.user.role !== "mahasiswa") {
    redirect(303, "/krs");
  }
  const nim = locals.user.nim;
  if (!nim) return { error: "NIM missing" };
  const { data: semData } = await locals.supabase.from("semester_config").select("nama_semester").eq("is_aktif", 1).single();
  const semester_aktif = semData?.nama_semester || "Unknown";
  const { data: mkList } = await locals.supabase.from("mata_kuliah").select("*").order("semester").order("nama_mk");
  const { data: krsTaken } = await locals.supabase.from("krs").select("kode_matkul, mata_kuliah(sks)").eq("nim_mahasiswa", nim).eq("semester", semester_aktif);
  const takenCodes = krsTaken?.map((k) => k.kode_matkul) || [];
  const totalSks = krsTaken?.reduce((sum, k) => sum + k.mata_kuliah.sks, 0) || 0;
  return {
    mkList: mkList || [],
    takenCodes,
    totalSks,
    semester_aktif,
    user: locals.user
  };
};
const actions = {
  default: async ({ request, locals }) => {
    if (!locals.user || locals.user.role !== "mahasiswa") {
      return fail(403, { error: "Unauthorized" });
    }
    const nim = locals.user.nim;
    if (!nim) return fail(400, { error: "NIM not associated" });
    const formData = await request.formData();
    const matkul_diambil = formData.getAll("matkul_diambil");
    const { data: semData } = await locals.supabase.from("semester_config").select("nama_semester").eq("is_aktif", 1).single();
    const semester_aktif = semData?.nama_semester;
    if (!semester_aktif) return fail(500, { error: "Semester aktif tidak valid." });
    const { data: currentKrs } = await locals.supabase.from("krs").select("kode_matkul, mata_kuliah(sks)").eq("nim_mahasiswa", nim).eq("semester", semester_aktif);
    const currentCodes = currentKrs?.map((k) => k.kode_matkul) || [];
    const currentSks = currentKrs?.reduce((sum, k) => sum + k.mata_kuliah.sks, 0) || 0;
    const newCodes = matkul_diambil.filter((c) => !currentCodes.includes(c));
    if (newCodes.length === 0) {
      return fail(400, { error: "Tidak ada mata kuliah baru yang dipilih (atau semua sudah diambil)." });
    }
    const { data: newMkData } = await locals.supabase.from("mata_kuliah").select("sks").in("kode_mk", newCodes);
    const newSks = newMkData?.reduce((sum, m) => sum + m.sks, 0) || 0;
    if (currentSks + newSks > 24) {
      return fail(400, { error: `Total SKS melebihi batas (Maks 24). Saat ini: ${currentSks}, Ditambah: ${newSks}.` });
    }
    const inserts = newCodes.map((code) => ({
      nim_mahasiswa: nim,
      kode_matkul: code,
      semester: semester_aktif
      // using string from config
    }));
    const { error } = await locals.supabase.from("krs").insert(inserts);
    if (error) {
      console.error(error);
      return fail(500, { error: "Gagal menyimpan KRS." });
    }
    redirect(303, "/krs");
  }
};
export {
  actions,
  load
};
