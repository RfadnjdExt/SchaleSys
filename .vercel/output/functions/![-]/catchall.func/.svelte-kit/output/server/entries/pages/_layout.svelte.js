import { Z as store_get, _ as head, $ as attr_class, a0 as stringify, a1 as slot, a2 as unsubscribe_stores, a3 as bind_props } from "../../chunks/index2.js";
import { g as getContext } from "../../chunks/context.js";
import "clsx";
import "@sveltejs/kit/internal";
import "../../chunks/exports.js";
import "../../chunks/utils.js";
import "@sveltejs/kit/internal/server";
import "../../chunks/state.svelte.js";
import { a as attr } from "../../chunks/attributes.js";
import { e as escape_html } from "../../chunks/escaping.js";
const favicon = "data:image/svg+xml,%3csvg%20xmlns='http://www.w3.org/2000/svg'%20width='107'%20height='128'%20viewBox='0%200%20107%20128'%3e%3ctitle%3esvelte-logo%3c/title%3e%3cpath%20d='M94.157%2022.819c-10.4-14.885-30.94-19.297-45.792-9.835L22.282%2029.608A29.92%2029.92%200%200%200%208.764%2049.65a31.5%2031.5%200%200%200%203.108%2020.231%2030%2030%200%200%200-4.477%2011.183%2031.9%2031.9%200%200%200%205.448%2024.116c10.402%2014.887%2030.942%2019.297%2045.791%209.835l26.083-16.624A29.92%2029.92%200%200%200%2098.235%2078.35a31.53%2031.53%200%200%200-3.105-20.232%2030%2030%200%200%200%204.474-11.182%2031.88%2031.88%200%200%200-5.447-24.116'%20style='fill:%23ff3e00'/%3e%3cpath%20d='M45.817%20106.582a20.72%2020.72%200%200%201-22.237-8.243%2019.17%2019.17%200%200%201-3.277-14.503%2018%2018%200%200%201%20.624-2.435l.49-1.498%201.337.981a33.6%2033.6%200%200%200%2010.203%205.098l.97.294-.09.968a5.85%205.85%200%200%200%201.052%203.878%206.24%206.24%200%200%200%206.695%202.485%205.8%205.8%200%200%200%201.603-.704L69.27%2076.28a5.43%205.43%200%200%200%202.45-3.631%205.8%205.8%200%200%200-.987-4.371%206.24%206.24%200%200%200-6.698-2.487%205.7%205.7%200%200%200-1.6.704l-9.953%206.345a19%2019%200%200%201-5.296%202.326%2020.72%2020.72%200%200%201-22.237-8.243%2019.17%2019.17%200%200%201-3.277-14.502%2017.99%2017.99%200%200%201%208.13-12.052l26.081-16.623a19%2019%200%200%201%205.3-2.329%2020.72%2020.72%200%200%201%2022.237%208.243%2019.17%2019.17%200%200%201%203.277%2014.503%2018%2018%200%200%201-.624%202.435l-.49%201.498-1.337-.98a33.6%2033.6%200%200%200-10.203-5.1l-.97-.294.09-.968a5.86%205.86%200%200%200-1.052-3.878%206.24%206.24%200%200%200-6.696-2.485%205.8%205.8%200%200%200-1.602.704L37.73%2051.72a5.42%205.42%200%200%200-2.449%203.63%205.79%205.79%200%200%200%20.986%204.372%206.24%206.24%200%200%200%206.698%202.486%205.8%205.8%200%200%200%201.602-.704l9.952-6.342a19%2019%200%200%201%205.295-2.328%2020.72%2020.72%200%200%201%2022.237%208.242%2019.17%2019.17%200%200%201%203.277%2014.503%2018%2018%200%200%201-8.13%2012.053l-26.081%2016.622a19%2019%200%200%201-5.3%202.328'%20style='fill:%23fff'/%3e%3c/svg%3e";
const getStores = () => {
  const stores$1 = getContext("__svelte__");
  return {
    /** @type {typeof page} */
    page: {
      subscribe: stores$1.page.subscribe
    },
    /** @type {typeof navigating} */
    navigating: {
      subscribe: stores$1.navigating.subscribe
    },
    /** @type {typeof updated} */
    updated: stores$1.updated
  };
};
const page = {
  subscribe(fn) {
    const store = getStores().page;
    return store.subscribe(fn);
  }
};
const dashboardBg = "/_app/immutable/assets/dashboard-bg.DTEPPB3D.png";
function _layout($$renderer, $$props) {
  $$renderer.component(($$renderer2) => {
    var $$store_subs;
    let user, isLoginPage;
    let data = $$props["data"];
    user = data.user;
    isLoginPage = store_get($$store_subs ??= {}, "$page", page).url.pathname === "/login";
    head("12qhfyh", $$renderer2, ($$renderer3) => {
      $$renderer3.title(($$renderer4) => {
        $$renderer4.push(`<title>SchaleSys</title>`);
      });
      $$renderer3.push(`<link rel="icon"${attr("href", favicon)}/>`);
    });
    if (!isLoginPage) {
      $$renderer2.push("<!--[-->");
      $$renderer2.push(`<div class="fixed inset-0 z-0 overflow-hidden pointer-events-none"><img${attr("src", dashboardBg)} alt="" class="absolute inset-0 w-full h-full object-cover opacity-30 mix-blend-multiply dark:mix-blend-overlay"/> <div class="absolute inset-0 bg-linear-to-b from-transparent to-white/50 dark:to-gray-900/50"></div></div> <nav class="relative z-50 border-b border-gray-200 dark:border-gray-800 bg-white/80 dark:bg-gray-900/80 backdrop-blur-md transition-all duration-300"><div class="max-w-[1440px] mx-auto px-4 md:px-6 h-20 flex items-center justify-between"><div class="flex items-center space-x-8"><a href="/" class="flex items-center space-x-3 group cursor-pointer"><div class="relative w-8 h-8"><span class="material-symbols-outlined text-3xl text-primary transition-transform group-hover:scale-110">school</span></div> <span class="font-display text-xl font-bold tracking-tight text-gray-900 dark:text-white">SchaleSys</span></a> `);
      if (user) {
        $$renderer2.push("<!--[-->");
        $$renderer2.push(`<div class="hidden md:flex space-x-6 text-sm font-medium"><a${attr_class(`${stringify(store_get($$store_subs ??= {}, "$page", page).url.pathname === "/" ? "text-primary" : "text-gray-500 hover:text-gray-900 dark:hover:text-white")} transition-colors`)} href="/">Dashboard</a> `);
        if (user.role !== "mahasiswa") {
          $$renderer2.push("<!--[-->");
          $$renderer2.push(`<a${attr_class(`${stringify(store_get($$store_subs ??= {}, "$page", page).url.pathname.startsWith("/mahasiswa") ? "text-primary" : "text-gray-500 hover:text-gray-900 dark:hover:text-white")} transition-colors`)} href="/mahasiswa">Mahasiswa</a> <a${attr_class(`${stringify(store_get($$store_subs ??= {}, "$page", page).url.pathname.startsWith("/matakuliah") ? "text-primary" : "text-gray-500 hover:text-gray-900 dark:hover:text-white")} transition-colors`)} href="/matakuliah">Mata Kuliah</a> `);
          if (user.role === "admin") {
            $$renderer2.push("<!--[-->");
            $$renderer2.push(`<a${attr_class(`${stringify(store_get($$store_subs ??= {}, "$page", page).url.pathname.startsWith("/dosen") ? "text-primary" : "text-gray-500 hover:text-gray-900 dark:hover:text-white")} transition-colors`)} href="/dosen">Dosen</a>`);
          } else {
            $$renderer2.push("<!--[!-->");
          }
          $$renderer2.push(`<!--]-->`);
        } else {
          $$renderer2.push("<!--[!-->");
        }
        $$renderer2.push(`<!--]--> `);
        if (user.role === "mahasiswa") {
          $$renderer2.push("<!--[-->");
          $$renderer2.push(`<a${attr_class(`${stringify(store_get($$store_subs ??= {}, "$page", page).url.pathname.startsWith("/krs") ? "text-primary" : "text-gray-500 hover:text-gray-900 dark:hover:text-white")} transition-colors`)} href="/krs">KRS</a> <a${attr_class(`${stringify(store_get($$store_subs ??= {}, "$page", page).url.pathname.startsWith("/nilai") ? "text-primary" : "text-gray-500 hover:text-gray-900 dark:hover:text-white")} transition-colors`)} href="/nilai">Transkrip</a>`);
        } else {
          $$renderer2.push("<!--[!-->");
        }
        $$renderer2.push(`<!--]--></div>`);
      } else {
        $$renderer2.push("<!--[!-->");
      }
      $$renderer2.push(`<!--]--></div> `);
      if (user) {
        $$renderer2.push("<!--[-->");
        $$renderer2.push(`<div class="flex items-center space-x-4"><div class="text-right hidden sm:block"><p class="text-[10px] uppercase font-bold text-gray-400">Authenticated</p> <p class="text-xs font-bold text-gray-700 dark:text-gray-200">${escape_html(user.nama_lengkap ?? "User")}</p></div> <div class="w-9 h-9 bg-linear-to-br from-primary to-orange-600 flex items-center justify-center text-white font-bold rounded-full shadow-sm">${escape_html(user.nama_lengkap?.charAt(0) ?? "U")}</div> <form action="/logout" method="POST" class="ml-2"><button type="submit" class="material-symbols-outlined text-gray-400 hover:text-red-500 transition-colors text-xl" title="Logout">logout</button></form></div>`);
      } else {
        $$renderer2.push("<!--[!-->");
      }
      $$renderer2.push(`<!--]--></div></nav>`);
    } else {
      $$renderer2.push("<!--[!-->");
    }
    $$renderer2.push(`<!--]--> <main class="min-h-screen"><!--[-->`);
    slot($$renderer2, $$props, "default", {});
    $$renderer2.push(`<!--]--></main>`);
    if ($$store_subs) unsubscribe_stores($$store_subs);
    bind_props($$props, { data });
  });
}
export {
  _layout as default
};
