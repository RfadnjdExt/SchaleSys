import { createServerClient } from '@supabase/ssr';
import { type Handle } from '@sveltejs/kit';
import { PUBLIC_SUPABASE_URL, PUBLIC_SUPABASE_ANON_KEY } from '$env/static/public';

export const handle: Handle = async ({ event, resolve }) => {
    event.locals.supabase = createServerClient(PUBLIC_SUPABASE_URL, PUBLIC_SUPABASE_ANON_KEY, {
        cookies: {
            getAll: () => event.cookies.getAll(),
            setAll: (cookiesToSet) => {
                cookiesToSet.forEach(({ name, value, options }) => {
                    event.cookies.set(name, value, { ...options, path: '/' });
                });
            }
        }
    });

    /**
     * A convenience helper so we can just call await locals.getSession()
     */
    event.locals.getSession = async () => {
        const {
            data: { session }
        } = await event.locals.supabase.auth.getSession();
        return session;
    };

    const {
        data: { user }
    } = await event.locals.supabase.auth.getUser();

    // If Supabase Auth user is not found, check for custom session cookie
    if (user) {
        event.locals.user = {
            id: user.id,
            email: user.email,
            role: user.app_metadata.role || 'mahasiswa', // Fallback
            nama_lengkap: user.user_metadata.full_name || 'User',
            username: user.email?.split('@')[0],
        } as App.User;
    } else {
        const customSession = event.cookies.get('custom_session');
        if (customSession) {
            try {
                event.locals.user = JSON.parse(customSession) as App.User;
            } catch (e) {
                event.locals.user = null;
            }
        } else {
            event.locals.user = null;
        }
    }

    return resolve(event, {
        filterSerializedResponseHeaders(name) {
            return name === 'content-range' || name === 'x-supabase-api-version';
        }
    });
};
