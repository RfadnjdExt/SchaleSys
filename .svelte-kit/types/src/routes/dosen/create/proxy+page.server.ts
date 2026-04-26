// @ts-nocheck
import { fail, redirect } from '@sveltejs/kit';
import type { Actions, PageServerLoad } from './$types';

export const load = async ({ locals }: Parameters<PageServerLoad>[0]) => {
    if (!locals.user || locals.user.role !== 'admin') {
        redirect(303, '/dosen');
    }
};

export const actions = {
    default: async ({ request, locals }: import('./$types').RequestEvent) => {
        if (!locals.user || locals.user.role !== 'admin') {
            return fail(403, { error: 'Unauthorized' });
        }

        const formData = await request.formData();
        const nip = formData.get('nip') as string;
        const nama_dosen = formData.get('nama_dosen') as string;
        const email = formData.get('email') as string;

        if (!nip || !nama_dosen) {
            return fail(400, { error: 'NIP dan Nama Dosen wajib diisi.', values: Object.fromEntries(formData) });
        }

        const { error } = await locals.supabase
            .from('dosen')
            .insert({
                nip,
                nama_dosen,
                email: email || null
            });

        if (error) {
            console.error(error);
            if (error.code === '23505') { // Unique violation
                return fail(400, { error: `NIP '${nip}' sudah terdaftar.`, values: Object.fromEntries(formData) });
            }
            return fail(500, { error: 'Gagal menambahkan dosen.', values: Object.fromEntries(formData) });
        }

        redirect(303, '/dosen');
    }
};
;null as any as Actions;