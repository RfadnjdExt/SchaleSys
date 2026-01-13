import { createBrowserClient, createServerClient, isBrowser, parseCookieHeader } from '@supabase/ssr';
import { PUBLIC_SUPABASE_ANON_KEY, PUBLIC_SUPABASE_URL } from '$env/static/public';




export const createSupabaseClient = (fetch?: any) => {
    return createBrowserClient(PUBLIC_SUPABASE_URL, PUBLIC_SUPABASE_ANON_KEY, {
        global: {
            fetch,
        },
    });
};
