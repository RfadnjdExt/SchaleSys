import type { PageServerLoad } from './$types';
import { Momo } from '$lib/server/momo';

export const load: PageServerLoad = async ({ locals }) => {
    if (!locals.user) return { conversations: [] };

    // Ensure username is a string, fallback to ID if username is missing
    const userId = (locals.user.username || locals.user.id || '') as string;

    const conversations = await Momo.getConversations(locals.supabase, userId);

    // Simplistic name resolution: Just show the OTHER person's ID for now since we don't have a robust User Context here yet.
    // Ideally we fetch names.
    const hydratedConversations = conversations.map(c => ({
        ...c,
        participants: c.participants // This is already filtered to exclude me in getConversations? Let's check logic.
        // In momo.ts: participants: parts?.map(p => p.user_id) || [], which excludes me.
    }));

    return {
        conversations: hydratedConversations
    };
};
