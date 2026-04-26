// @ts-nocheck
import { redirect } from '@sveltejs/kit';
import type { Actions } from './$types';

export const actions = {
    default: ({ cookies }: import('./$types').RequestEvent) => {
        cookies.delete('custom_session', { path: '/' });
        redirect(303, '/login');
    }
};
;null as any as Actions;