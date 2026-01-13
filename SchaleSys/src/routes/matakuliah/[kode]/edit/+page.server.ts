import { fail, redirect } from '@sveltejs/kit';
import type { PageServerLoad, Actions } from './$types';

export const load: PageServerLoad = async ({ locals, params }) => {
    if (!locals.user || locals.user.role !== 'admin') {
        redirect(303, '/matakuliah');
    }

    const { kode } = params;
    const { data: mk } = await locals.supabase.from('mata_kuliah').select('*').eq('kode_mk', kode).single();

    if (!mk) {
        throw redirect(303, '/matakuliah');
    }

    return { mk };
};

export const actions: Actions = {
    default: async ({ request, locals, params }) => {
        if (!locals.user || locals.user.role !== 'admin') {
            return fail(403, { error: 'Unauthorized' });
        }

        const { kode } = params;
        const formData = await request.formData();
        const nama_mk = formData.get('nama_mk') as string;
        const sks = formData.get('sks') as string;
        const semester = formData.get('semester') as string;
        const hari = formData.get('hari') as string;
        const jam_mulai = formData.get('jam_mulai') as string;
        const jam_selesai = formData.get('jam_selesai') as string;
        const ruangan = formData.get('ruangan') as string;

        if (!nama_mk || !sks || !semester) {
            return fail(400, { error: 'Nama, SKS, dan Semester wajib diisi.', values: Object.fromEntries(formData) });
        }

        const { error } = await locals.supabase
            .from('mata_kuliah')
            .update({
                nama_mk,
                sks: parseInt(sks),
                semester: parseInt(semester),
                hari: hari || null,
                jam_mulai: jam_mulai || null,
                jam_selesai: jam_selesai || null,
                ruangan: ruangan || null
            })
            .eq('kode_mk', kode);

        if (error) {
            console.error(error);
            return fail(500, { error: 'Gagal mengupdate mata kuliah.', values: Object.fromEntries(formData) });
        }

        redirect(303, '/matakuliah');
    }
};
