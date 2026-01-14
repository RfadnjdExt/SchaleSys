import { redirect } from '@sveltejs/kit';
import type { Actions } from './$types';

export const actions: Actions = {
    default: ({ cookies }) => {
        cookies.delete('custom_session', { path: '/' });
        redirect(303, '/login');
    }
};
