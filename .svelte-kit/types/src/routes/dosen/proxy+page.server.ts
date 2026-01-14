// @ts-nocheck
import { redirect, fail } from '@sveltejs/kit';
import type { PageServerLoad, Actions } from './$types';

export const load = async ({ locals, url }: Parameters<PageServerLoad>[0]) => {
    if (!locals.user) {
        redirect(303, '/login');
    }

    // Role check: Only admin can manage dosen
    if (locals.user.role !== 'admin') {
        redirect(303, '/');
    }

    const page = Number(url.searchParams.get('page')) || 1;
    const limit = 20;
    const from = (page - 1) * limit;
    const to = from + limit - 1;

    const { data: dosen, count, error } = await locals.supabase
        .from('dosen')
        .select('*', { count: 'exact' })
        .order('nama_dosen', { ascending: true })
        .range(from, to);

    if (error) {
        console.error('Error fetching dosen:', error);
    }

    return {
        dosen: dosen || [],
        count: count || 0,
        page,
        user: locals.user
    };
};

export const actions = {
    delete: async ({ request, locals }: import('./$types').RequestEvent) => {
        if (!locals.user || locals.user.role !== 'admin') {
            return fail(403, { error: 'Unauthorized' });
        }

        const formData = await request.formData();
        const nip = formData.get('nip') as string;

        if (!nip) {
            return fail(400, { error: 'NIP required' });
        }

        const { error } = await locals.supabase
            .from('dosen')
            .delete()
            .eq('nip', nip);

        if (error) {
            return fail(500, { error: 'Gagal menghapus dosen. Pastikan tidak ada data terkait (mk, mahasiswa, dll).' });
        }

        return { success: true };
    }
};
;null as any as Actions;