const load = async ({ locals, cookies }) => {
  return {
    user: locals.user,
    // We might want to pass session info if using Supabase Auth, but for custom auth 'user' is enough
    session: await locals.getSession()
  };
};
export {
  load
};
