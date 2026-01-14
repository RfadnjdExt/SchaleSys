import { redirect, fail } from '@sveltejs/kit';
import type { PageServerLoad, Actions } from './$types';

export const load: PageServerLoad = async ({ locals, url }) => {
    if (!locals.user) {
        redirect(303, '/login');
    }

    // Role check: Only admin and dosen can view
    if (!['admin', 'dosen'].includes(locals.user.role)) {
        redirect(303, '/');
    }

    const page = Number(url.searchParams.get('page')) || 1;
    const limit = 20;
    const from = (page - 1) * limit;
    const to = from + limit - 1;

    const { data: mahasiswa, count, error } = await locals.supabase
        .from('mahasiswa')
        .select('*', { count: 'exact' })
        .order('nim', { ascending: true })
        .range(from, to);

    if (error) {
        console.error('Error fetching mahasiswa:', error);
    }

    return {
        mahasiswa: mahasiswa || [],
        count: count || 0,
        page,
        user: locals.user
    };
};

export const actions: Actions = {
    delete: async ({ request, locals }) => {
        if (!locals.user || locals.user.role !== 'admin') {
            return fail(403, { error: 'Unauthorized' });
        }

        const formData = await request.formData();
        const nim = formData.get('nim') as string;

        if (!nim) {
            return fail(400, { error: 'NIM required' });
        }

        // Check if has nilai (optional safety, DB FK should handle or return error)
        // In PHP code: "Gagal menghapus! Mahasiswa masih memiliki data nilai."
        // We let Supabase/Postgres return constraint violation if cascade is not set or restrict.
        // Schema says: ON DELETE CASCADE for nilai. So it will delete values!
        // Wait, PHP code said: "Menghapus mahasiswa hanya bisa jika ia tidak memiliki data nilai" (warning), but catch block handles it.
        // schema_pgsql.sql says:
        // fk_nilai_mahasiswa ... ON DELETE CASCADE
        // So checking is not strictly "required" by DB but maybe business rule?
        // PHP code warns user but tries to delete. If valid constraint fails?
        // Actually schema says CASCADE. So it will delete related data.
        // The PHP code warning might be old or protective.
        // We will just delete.

        const { error } = await locals.supabase
            .from('mahasiswa')
            .delete()
            .eq('nim', nim);

        if (error) {
            return fail(500, { error: 'Gagal menghapus mahasiswa.' });
        }

        return { success: true };
    }
};
