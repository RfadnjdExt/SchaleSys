import { redirect, fail } from "@sveltejs/kit";
const load = async ({ locals, params }) => {
  if (!locals.user || locals.user.role !== "admin") {
    redirect(303, "/matakuliah");
  }
  const { kode } = params;
  const { data: mk } = await locals.supabase.from("mata_kuliah").select("*").eq("kode_mk", kode).single();
  if (!mk) {
    throw redirect(303, "/matakuliah");
  }
  return { mk };
};
const actions = {
  default: async ({ request, locals, params }) => {
    if (!locals.user || locals.user.role !== "admin") {
      return fail(403, { error: "Unauthorized" });
    }
    const { kode } = params;
    const formData = await request.formData();
    const nama_mk = formData.get("nama_mk");
    const sks = formData.get("sks");
    const semester = formData.get("semester");
    const hari = formData.get("hari");
    const jam_mulai = formData.get("jam_mulai");
    const jam_selesai = formData.get("jam_selesai");
    const ruangan = formData.get("ruangan");
    if (!nama_mk || !sks || !semester) {
      return fail(400, { error: "Nama, SKS, dan Semester wajib diisi.", values: Object.fromEntries(formData) });
    }
    const { error } = await locals.supabase.from("mata_kuliah").update({
      nama_mk,
      sks: parseInt(sks),
      semester: parseInt(semester),
      hari: hari || null,
      jam_mulai: jam_mulai || null,
      jam_selesai: jam_selesai || null,
      ruangan: ruangan || null
    }).eq("kode_mk", kode);
    if (error) {
      console.error(error);
      return fail(500, { error: "Gagal mengupdate mata kuliah.", values: Object.fromEntries(formData) });
    }
    redirect(303, "/matakuliah");
  }
};
export {
  actions,
  load
};
