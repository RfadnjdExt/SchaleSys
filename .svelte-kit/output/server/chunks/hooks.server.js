import { createServerClient } from "@supabase/ssr";
import "@sveltejs/kit";
const PUBLIC_SUPABASE_URL = "https://ikharfpsmhvitigsnizd.supabase.co";
const PUBLIC_SUPABASE_PUBLISHABLE_DEFAULT_KEY = "sb_publishable_BS1r8BzCWyjXXW9HWsAOIA_vpeuHycb";
const handle = async ({ event, resolve }) => {
  event.locals.supabase = createServerClient(PUBLIC_SUPABASE_URL, PUBLIC_SUPABASE_PUBLISHABLE_DEFAULT_KEY, {
    cookies: {
      getAll: () => event.cookies.getAll(),
      setAll: (cookiesToSet) => {
        cookiesToSet.forEach(({ name, value, options }) => {
          event.cookies.set(name, value, { ...options, path: "/" });
        });
      }
    }
  });
  event.locals.getSession = async () => {
    const {
      data: { session }
    } = await event.locals.supabase.auth.getSession();
    return session;
  };
  const {
    data: { user }
  } = await event.locals.supabase.auth.getUser();
  if (user) {
    event.locals.user = {
      id: user.id,
      email: user.email,
      role: user.app_metadata.role || "mahasiswa",
      // Fallback
      nama_lengkap: user.user_metadata.full_name || "User",
      username: user.email?.split("@")[0]
    };
  } else {
    const customSession = event.cookies.get("custom_session");
    if (customSession) {
      try {
        event.locals.user = JSON.parse(customSession);
      } catch (e) {
        event.locals.user = null;
      }
    } else {
      event.locals.user = null;
    }
  }
  return resolve(event, {
    filterSerializedResponseHeaders(name) {
      return name === "content-range" || name === "x-supabase-api-version";
    }
  });
};
export {
  handle
};
