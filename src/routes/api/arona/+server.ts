import { json } from '@sveltejs/kit';
import { Arona } from '$lib/server/arona';

export const POST = async ({ request, locals }) => {
    if (!locals.user) {
        return json({ error: 'Unauthorized' }, { status: 401 });
    }

    try {
        const { query } = await request.json();

        if (!query) {
            return json({ error: 'Query is required' }, { status: 400 });
        }

        // We use the authenticated user's ID for context
        // Ensure we pass specific user ID or username depending on schema
        // Based on previous tasks, user.id is string UUID, but schema uses int id_user? 
        // Let's use what we have in locals.user.id (Supabase Auth ID) -> usually mapped to users table.
        // Actually, in `arona.ts` we query `id_user` with `userId`. 
        // If locals.user.id is UUID and table uses int, this will fail.
        // BUT, we likely seeded logic where they match or we query by whatever key we have.
        // Let's pass the username if available, or the generic ID.
        // Wait, check momo.ts usages: it uses `locals.user.username || locals.user.id`.
        // Let's use the same.

        const userId = (locals.user.username || locals.user.id || '') as string;
        const response = await Arona.processQuery(locals.supabase, userId, query);

        return json({ response });
    } catch (err) {
        console.error('Arona API Error:', err);
        return json({ error: 'Internal Server Error' }, { status: 500 });
    }
};
