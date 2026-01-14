import { fail, redirect } from '@sveltejs/kit';
import type { PageServerLoad, Actions } from './$types';

export const load: PageServerLoad = async ({ locals }) => {
    if (!locals.user || locals.user.role !== 'admin') {
        redirect(303, '/dosen');
    }

    const { data: dosenList } = await locals.supabase.from('dosen').select('nip, nama_dosen').order('nama_dosen');
    const { data: mkList } = await locals.supabase.from('mata_kuliah').select('kode_mk, nama_mk').order('nama_mk');

    const { data: assignments } = await locals.supabase
        .from('dosen_pengampu')
        .select(`
            id_pengampu,
            dosen (nama_dosen),
            mata_kuliah (nama_mk)
        `);

    // Sort manual or use DB sort order if simple. Joined sort is tricky in Supabase JS basic syntax without RCP or complex query.
    // Client side sort or simple sort is fine.

    return {
        dosenList: dosenList || [],
        mkList: mkList || [],
        assignments: assignments || []
    };
};

export const actions: Actions = {
    assign: async ({ request, locals }) => {
        if (!locals.user || locals.user.role !== 'admin') {
            return fail(403, { error: 'Unauthorized' });
        }

        const formData = await request.formData();
        const nip_dosen = formData.get('nip_dosen') as string;
        const kode_matkul = formData.get('kode_matkul') as string;

        if (!nip_dosen || !kode_matkul) {
            return fail(400, { error: 'Pilih Dosen dan Mata Kuliah.', values: Object.fromEntries(formData) });
        }

        const { error } = await locals.supabase
            .from('dosen_pengampu')
            .insert({ nip_dosen, kode_matkul });

        if (error) {
            console.error(error);
            if (error.code === '23505') { // Unique violation
                return fail(400, { error: 'Dosen sudah ditugaskan ke mata kuliah tersebut.', values: Object.fromEntries(formData) });
            }
            return fail(500, { error: 'Gagal menugaskan dosen.', values: Object.fromEntries(formData) });
        }

        return { success: true };
    },
    delete: async ({ request, locals }) => {
        if (!locals.user || locals.user.role !== 'admin') {
            return fail(403, { error: 'Unauthorized' });
        }

        const formData = await request.formData();
        const id_pengampu = formData.get('id_pengampu') as string;

        if (!id_pengampu) return fail(400, { error: 'ID invalid' });

        const { error } = await locals.supabase
            .from('dosen_pengampu')
            .delete()
            .eq('id_pengampu', id_pengampu);

        if (error) return fail(500, { error: 'Gagal menghapus penugasan.' });

        return { success: true };
    }
};
