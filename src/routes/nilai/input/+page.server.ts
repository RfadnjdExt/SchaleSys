import { fail, redirect } from '@sveltejs/kit';
import type { Actions, PageServerLoad } from './$types';

export const load: PageServerLoad = async ({ locals }) => {
    if (!locals.user || (locals.user.role !== 'admin' && locals.user.role !== 'dosen')) {
        redirect(303, '/nilai');
    }

    // Get Active Semester for KRS validation
    const { data: semData } = await locals.supabase
        .from('semester_config')
        .select('nama_semester')
        .eq('is_aktif', 1)
        .single();
    const semester_aktif = semData?.nama_semester || 'Unknown';

    // Get Mahasiswa List
    const { data: mahasiswa } = await locals.supabase
        .from('mahasiswa')
        .select('nim, nama_mahasiswa')
        .order('nama_mahasiswa');

    // Get MK List (Filtered if Dosen)
    let mkQuery = locals.supabase
        .from('mata_kuliah')
        .select('kode_mk, nama_mk')
        .order('nama_mk');

    if (locals.user.role === 'dosen') {
        const { data: pengampu } = await locals.supabase
            .from('dosen_pengampu')
            .select('kode_matkul')
            .eq('nip_dosen', locals.user.nip); // assuming user.nip

        const kodeMkList = pengampu?.map((p: any) => p.kode_matkul) || [];
        if (kodeMkList.length > 0) {
            mkQuery = mkQuery.in('kode_mk', kodeMkList);
        } else {
            // No classes
            // We can return empty list or fail. 
            // But simpler to return empty list.
            mkQuery = mkQuery.in('kode_mk', []);
        }
    }

    const { data: matakuliah } = await mkQuery;

    return {
        mahasiswa: mahasiswa || [],
        matakuliah: matakuliah || [],
        semester_aktif
    };
};

export const actions: Actions = {
    default: async ({ request, locals }) => {
        if (!locals.user || (locals.user.role !== 'admin' && locals.user.role !== 'dosen')) {
            return fail(403, { error: 'Unauthorized' });
        }

        const formData = await request.formData();
        const nim_mahasiswa = formData.get('nim_mahasiswa') as string;
        const kode_matkul = formData.get('kode_matkul') as string;
        const nilai_huruf = formData.get('nilai_huruf') as string;

        if (!nim_mahasiswa || !kode_matkul || !nilai_huruf) {
            return fail(400, { error: 'Semua kolom wajib diisi.', values: Object.fromEntries(formData) });
        }

        const validGrades = ['A', 'B', 'C', 'D', 'E'];
        if (!validGrades.includes(nilai_huruf.toUpperCase())) {
            return fail(400, { error: 'Nilai harus A, B, C, D, atau E.', values: Object.fromEntries(formData) });
        }

        // Get Active Semester
        const { data: semData } = await locals.supabase
            .from('semester_config')
            .select('nama_semester')
            .eq('is_aktif', 1)
            .single();
        const semester_aktif = semData?.nama_semester;

        // Check Valid KRS
        const { data: krsCheck } = await locals.supabase
            .from('krs')
            .select('*')
            .eq('nim_mahasiswa', nim_mahasiswa)
            .eq('kode_matkul', kode_matkul)
            .eq('semester', semester_aktif) // Check against CURRENT semester
            .single();

        if (!krsCheck) {
            return fail(400, { error: `Mahasiswa ini belum mengambil mata kuliah tersebut di semester ini (${semester_aktif}).`, values: Object.fromEntries(formData) });
        }

        // Insert Nilai
        const { error } = await locals.supabase
            .from('nilai')
            .insert({
                nim_mahasiswa,
                kode_matkul,
                nilai_huruf: nilai_huruf.toUpperCase()
            });

        if (error) {
            console.error(error);
            return fail(500, { error: 'Gagal menyimpan nilai.', values: Object.fromEntries(formData) });
        }

        // Success - maybe clear form or show success
        return { success: true, message: 'Nilai berhasil disimpan.' };
    }
};
