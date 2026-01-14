import { redirect } from "@sveltejs/kit";
const load = async ({ locals }) => {
  if (!locals.user) {
    redirect(303, "/login");
  }
  let nim = locals.user.nim;
  if (!nim && locals.user.role === "mahasiswa") {
    return { error: "NIM not found for user." };
  }
  const { data: semData } = await locals.supabase.from("semester_config").select("nama_semester").eq("is_aktif", 1).single();
  const semester_aktif = semData?.nama_semester || "Unknown";
  let krs = [];
  let totalSks = 0;
  if (nim) {
    const { data } = await locals.supabase.from("krs").select(`
                tanggal_ambil,
                mata_kuliah (
                    kode_mk,
                    nama_mk,
                    sks,
                    semester,
                    hari,
                    jam_mulai,
                    jam_selesai,
                    ruangan
                )
            `).eq("nim_mahasiswa", nim).eq("semester", semester_aktif);
    if (data) {
      krs = data.map((item) => ({
        ...item.mata_kuliah,
        tanggal_ambil: item.tanggal_ambil
      })).sort((a, b) => {
        if (a.semester !== b.semester) return a.semester - b.semester;
        return a.nama_mk.localeCompare(b.nama_mk);
      });
      totalSks = krs.reduce((sum, item) => sum + item.sks, 0);
    }
  }
  return {
    krs,
    totalSks,
    semester_aktif,
    user: locals.user
  };
};
export {
  load
};
