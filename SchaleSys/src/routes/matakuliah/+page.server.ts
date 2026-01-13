import { redirect, fail } from '@sveltejs/kit';
import type { PageServerLoad, Actions } from './$types';

export const load: PageServerLoad = async ({ locals, url }) => {
    if (!locals.user) {
        redirect(303, '/login');
    }

    // Filter for Dosen
    let query = locals.supabase
        .from('mata_kuliah')
        .select('*', { count: 'exact' });

    if (locals.user.role === 'dosen') {
        // We need to filter by dosen_pengampu
        // Supabase doesn't support complex JOIN directly in easy syntax for this without VIEW or subquery
        // But we can fetch dosen_pengampu first or use !inner join if setup
        // Let's use logic: fetch mk codes from dosen_pengampu
        const { data: pengampu } = await locals.supabase
            .from('dosen_pengampu')
            .select('kode_matkul')
            .eq('nip_dosen', locals.user.nip); // assuming user.nip exists for dosen

        const kodeMkList = pengampu?.map(p => p.kode_matkul) || [];

        if (kodeMkList.length > 0) {
            query = query.in('kode_mk', kodeMkList);
        } else {
            // No classes assigned
            return {
                matakuliah: [],
                count: 0
            };
        }
    }

    query = query.order('semester', { ascending: true })
        .order('nama_mk', { ascending: true });

    const { data: matakuliah, count, error } = await query;

    if (error) {
        console.error('Error fetching matakuliah:', error);
    }

    return {
        matakuliah: matakuliah || [],
        count: count || 0,
        user: locals.user
    };
};

export const actions: Actions = {
    delete: async ({ request, locals }) => {
        if (!locals.user || locals.user.role !== 'admin') {
            return fail(403, { error: 'Unauthorized' });
        }

        const formData = await request.formData();
        const kode_mk = formData.get('kode_mk') as string;

        if (!kode_mk) {
            return fail(400, { error: 'Kode MK required' });
        }

        const { error } = await locals.supabase
            .from('mata_kuliah')
            .delete()
            .eq('kode_mk', kode_mk);

        if (error) {
            console.error(error);
            return fail(500, { error: 'Gagal menghapus mata kuliah. Pastikan tidak ada data terkait.' });
        }

        return { success: true };
    }
};
