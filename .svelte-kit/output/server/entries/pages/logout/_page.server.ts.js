import { redirect } from "@sveltejs/kit";
const actions = {
  default: ({ cookies }) => {
    cookies.delete("custom_session", { path: "/" });
    redirect(303, "/login");
  }
};
export {
  actions
};
