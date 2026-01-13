import { fail, redirect } from '@sveltejs/kit';
import type { PageServerLoad, Actions } from './$types';

export const load: PageServerLoad = async ({ locals, params }) => {
    if (!locals.user || locals.user.role !== 'admin') {
        redirect(303, '/dosen');
    }

    const { nip } = params;
    const { data: dosen } = await locals.supabase.from('dosen').select('*').eq('nip', nip).single();

    if (!dosen) {
        throw redirect(303, '/dosen');
    }

    return { dosen };
};

export const actions: Actions = {
    default: async ({ request, locals, params }) => {
        if (!locals.user || locals.user.role !== 'admin') {
            return fail(403, { error: 'Unauthorized' });
        }

        const { nip } = params;
        const formData = await request.formData();
        const nama_dosen = formData.get('nama_dosen') as string;
        const email = formData.get('email') as string;

        if (!nama_dosen) {
            return fail(400, { error: 'Nama Dosen wajib diisi.', values: Object.fromEntries(formData) });
        }

        const { error } = await locals.supabase
            .from('dosen')
            .update({
                nama_dosen,
                email: email || null
            })
            .eq('nip', nip);

        if (error) {
            console.error(error);
            return fail(500, { error: 'Gagal mengupdate data dosen.', values: Object.fromEntries(formData) });
        }

        redirect(303, '/dosen');
    }
};
