import { redirect } from "@sveltejs/kit";
const load = async ({ locals, url }) => {
  if (!locals.user) {
    redirect(303, "/login");
  }
  let nim = locals.user.nim;
  if (locals.user.role === "admin" || locals.user.role === "dosen") {
    const queryNim = url.searchParams.get("nim");
    if (queryNim) {
      nim = queryNim;
    } else if (locals.user.role === "admin" && !nim) {
      return { user: locals.user, grades: [], ipk: 0, targetStudent: null };
    }
  }
  if (!nim) {
    return { user: locals.user, grades: [], ipk: 0, targetStudent: null };
  }
  const { data: student } = await locals.supabase.from("mahasiswa").select("nim, nama_mahasiswa, prodi").eq("nim", nim).single();
  if (!student && locals.user.role !== "mahasiswa") {
    return { user: locals.user, grades: [], ipk: 0, targetStudent: null, error: "Mahasiswa tidak ditemukan" };
  }
  const { data: gradesData } = await locals.supabase.from("nilai").select(`
            nilai_huruf,
            mata_kuliah (
                kode_mk,
                nama_mk,
                sks,
                semester
            )
        `).eq("nim_mahasiswa", nim);
  let grades = [];
  let totalSks = 0;
  let totalBobot = 0;
  if (gradesData) {
    grades = gradesData.map((g) => {
      const bobotMap = { "A": 4, "B": 3, "C": 2, "D": 1, "E": 0 };
      const nilai = g.nilai_huruf?.toUpperCase();
      const bobot = bobotMap[nilai] || 0;
      const sks = g.mata_kuliah.sks;
      totalSks += sks;
      totalBobot += sks * bobot;
      return {
        ...g.mata_kuliah,
        nilai_huruf: nilai,
        bobot,
        total_bobot: sks * bobot
      };
    }).sort((a, b) => a.semester - b.semester);
  }
  const ipk = totalSks > 0 ? totalBobot / totalSks : 0;
  return {
    grades,
    ipk,
    totalSks,
    totalBobot,
    user: locals.user,
    targetStudent: student
  };
};
export {
  load
};
