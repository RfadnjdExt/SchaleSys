import type { LayoutServerLoad } from './$types';

export const load: LayoutServerLoad = async ({ locals, cookies }) => {
    // Pass user to the layout (client-side)
    // Supabase Auth session is handled by the browser client, but our custom user comes from locals.
    return {
        user: locals.user,
        // We might want to pass session info if using Supabase Auth, but for custom auth 'user' is enough
        session: await locals.getSession()
    };
};
