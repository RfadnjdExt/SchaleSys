import { fail, redirect } from '@sveltejs/kit';
import type { PageServerLoad, Actions } from './$types';

export const load: PageServerLoad = async ({ locals }) => {
    if (!locals.user || locals.user.role !== 'admin') {
        redirect(303, '/mahasiswa');
    }

    // Fetch Dosen list for dropdown
    const { data: dosenList } = await locals.supabase
        .from('dosen')
        .select('nip, nama_dosen')
        .order('nama_dosen');

    return {
        dosenList: dosenList || []
    };
};

export const actions: Actions = {
    default: async ({ request, locals }) => {
        if (!locals.user || locals.user.role !== 'admin') {
            return fail(403, { error: 'Unauthorized' });
        }

        const formData = await request.formData();
        const nim = formData.get('nim') as string;
        const nama_mahasiswa = formData.get('nama_mahasiswa') as string;
        const fakultas = formData.get('fakultas') as string;
        const prodi = formData.get('prodi') as string;
        const angkatan = formData.get('angkatan') as string;
        const foto = formData.get('foto') as string;
        const dosen_wali_id = formData.get('dosen_wali_id') as string || null;

        // Validation
        if (!nim || !nama_mahasiswa || !prodi || !angkatan || !fakultas) {
            return fail(400, { error: 'NIM, Nama, Prodi, Fakultas dan Angkatan wajib diisi.', values: Object.fromEntries(formData) });
        }

        if (isNaN(Number(nim)) || nim.length < 5) {
            return fail(400, { error: 'NIM harus angka dan minimal 5 digit.', values: Object.fromEntries(formData) });
        }

        const { error } = await locals.supabase
            .from('mahasiswa')
            .insert({
                nim,
                nama_mahasiswa,
                fakultas,
                prodi,
                angkatan: parseInt(angkatan),
                foto: foto || null,
                dosen_wali_id: dosen_wali_id
            });

        if (error) {
            console.error(error);
            if (error.code === '23505') { // Unique violation
                return fail(400, { error: `NIM '${nim}' sudah terdaftar.`, values: Object.fromEntries(formData) });
            }
            return fail(500, { error: 'Gagal menambahkan mahasiswa.', values: Object.fromEntries(formData) });
        }

        redirect(303, '/mahasiswa');
    }
};
