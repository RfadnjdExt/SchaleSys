
import type { PageServerLoad, Actions } from './$types';
import { Momo } from '$lib/server/momo';
import { fail } from '@sveltejs/kit';

export const load: PageServerLoad = async ({ locals, params }) => {
    if (!locals.user) return { messages: [], conversationId: params.id };

    const conversationId = params.id;
    const messages = await Momo.getMessages(locals.supabase, conversationId);

    // Ensure username/id is defined and is a string. Both are optional in type definition.
    const userId = (locals.user.username || locals.user.id || '') as string;

    if (userId) {
        await Momo.markAsRead(locals.supabase, conversationId, userId);
    }

    return {
        messages,
        conversationId,
        user: locals.user
    };
};

export const actions: Actions = {
    default: async ({ request, locals, params }) => {
        if (!locals.user) {
            return fail(401, { error: 'Unauthorized' });
        }

        const formData = await request.formData();
        const content = formData.get('content') as string;
        const conversationId = params.id;

        if (!content || !conversationId) {
            return fail(400, { missing: true });
        }

        const userId = (locals.user.username || locals.user.id || '') as string;
        if (!userId) {
            return fail(400, { error: 'Invalid user identifier' });
        }

        try {
            await Momo.sendMessage(locals.supabase, conversationId, userId, content);
            return { success: true };
        } catch (e) {
            return fail(500, { error: 'Failed to send' });
        }
    }
};
