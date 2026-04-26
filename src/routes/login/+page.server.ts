import { fail, redirect } from '@sveltejs/kit';
import type { Actions, PageServerLoad } from './$types';
import bcrypt from 'bcryptjs';

export const load: PageServerLoad = async ({ locals }) => {
    if (locals.user) {
        redirect(303, '/');
    }
};

export const actions: Actions = {
    default: async ({ request, locals, cookies }) => {
        const formData = await request.formData();
        const username = formData.get('username') as string;
        const password = formData.get('password') as string;

        if (!username || !password) {
            return fail(400, { error: 'Username dan password wajib diisi.' });
        }

        // Query users table using Supabase client (assuming public access or appropriate policy)
        // We use single() to get one record
        const { data: user, error } = await locals.supabase
            .from('users')
            .select('id_user, username, nama_lengkap, password, role, nim, nip')
            .eq('username', username)
            .single();

        if (error || !user) {
            // PGRST116 is the error for "0 rows returned" when using .single()
            if (error && error.code !== 'PGRST116') {
                console.error('Login error (Supabase):', error);
            }
            return fail(400, { error: 'Username atau password salah.' });
        }

        // Verify password
        const validPassword = await bcrypt.compare(password, user.password);

        if (!validPassword) {
            return fail(400, { error: 'Username atau password salah.' });
        }

        // Create session data (excluding password)
        const sessionUser = {
            id_user: user.id_user,
            username: user.username,
            nama_lengkap: user.nama_lengkap,
            role: user.role,
            nim: user.nim,
            nip: user.nip
        };

        // Set custom session cookie
        // In a real app, this should be a signed JWT or session ID stored in DB.
        // For migration speed/simplicity, we store JSON base64 or just string.
        cookies.set('custom_session', JSON.stringify(sessionUser), {
            path: '/',
            httpOnly: true,
            sameSite: 'strict',
            secure: process.env.NODE_ENV === 'production',
            maxAge: 60 * 60 * 24 * 7 // 1 week
        });

        redirect(303, '/');
    }
};
