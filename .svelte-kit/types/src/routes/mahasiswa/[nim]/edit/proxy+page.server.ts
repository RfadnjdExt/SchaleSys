// @ts-nocheck
import { fail, redirect } from '@sveltejs/kit';
import type { PageServerLoad, Actions } from './$types';

export const load = async ({ locals, params }: Parameters<PageServerLoad>[0]) => {
    if (!locals.user || locals.user.role !== 'admin') {
        redirect(303, '/mahasiswa');
    }

    const { nim } = params;

    const [dosenRes, mhsRes] = await Promise.all([
        locals.supabase.from('dosen').select('nip, nama_dosen').order('nama_dosen'),
        locals.supabase.from('mahasiswa').select('*').eq('nim', nim).single()
    ]);

    if (!mhsRes.data) {
        throw redirect(303, '/mahasiswa');
    }

    return {
        dosenList: dosenRes.data || [],
        mahasiswa: mhsRes.data
    };
};

export const actions = {
    default: async ({ request, locals, params }: import('./$types').RequestEvent) => {
        if (!locals.user || locals.user.role !== 'admin') {
            return fail(403, { error: 'Unauthorized' });
        }

        const { nim } = params;
        const formData = await request.formData();
        const nama_mahasiswa = formData.get('nama_mahasiswa') as string;
        const fakultas = formData.get('fakultas') as string;
        const prodi = formData.get('prodi') as string;
        const angkatan = formData.get('angkatan') as string;
        const foto = formData.get('foto') as string;
        const dosen_wali_id = formData.get('dosen_wali_id') as string || null;

        if (!nama_mahasiswa || !prodi || !angkatan || !fakultas) {
            return fail(400, { error: 'Nama, Prodi, Fakultas dan Angkatan wajib diisi.', values: Object.fromEntries(formData) });
        }

        const { error } = await locals.supabase
            .from('mahasiswa')
            .update({
                nama_mahasiswa,
                fakultas,
                prodi,
                angkatan: parseInt(angkatan),
                foto: foto || null,
                dosen_wali_id: dosen_wali_id
            })
            .eq('nim', nim);

        if (error) {
            console.error(error);
            return fail(500, { error: 'Gagal mengupdate data mahasiswa.', values: Object.fromEntries(formData) });
        }

        redirect(303, '/mahasiswa');
    }
};
;null as any as Actions;