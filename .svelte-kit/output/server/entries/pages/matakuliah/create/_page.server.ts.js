import { redirect, fail } from "@sveltejs/kit";
const load = async ({ locals }) => {
  if (!locals.user || locals.user.role !== "admin") {
    redirect(303, "/matakuliah");
  }
};
const actions = {
  default: async ({ request, locals }) => {
    if (!locals.user || locals.user.role !== "admin") {
      return fail(403, { error: "Unauthorized" });
    }
    const formData = await request.formData();
    const kode_mk = formData.get("kode_mk");
    const nama_mk = formData.get("nama_mk");
    const sks = formData.get("sks");
    const semester = formData.get("semester");
    const hari = formData.get("hari");
    const jam_mulai = formData.get("jam_mulai");
    const jam_selesai = formData.get("jam_selesai");
    const ruangan = formData.get("ruangan");
    if (!kode_mk || !nama_mk || !sks || !semester) {
      return fail(400, { error: "Kode, Nama, SKS, dan Semester wajib diisi.", values: Object.fromEntries(formData) });
    }
    const { error } = await locals.supabase.from("mata_kuliah").insert({
      kode_mk,
      nama_mk,
      sks: parseInt(sks),
      semester: parseInt(semester),
      hari: hari || null,
      jam_mulai: jam_mulai || null,
      jam_selesai: jam_selesai || null,
      ruangan: ruangan || null
    });
    if (error) {
      console.error(error);
      if (error.code === "23505") {
        return fail(400, { error: `Kode MK '${kode_mk}' sudah terdaftar.`, values: Object.fromEntries(formData) });
      }
      return fail(500, { error: "Gagal menambahkan mata kuliah.", values: Object.fromEntries(formData) });
    }
    redirect(303, "/matakuliah");
  }
};
export {
  actions,
  load
};
