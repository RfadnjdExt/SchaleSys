import { a4 as ensure_array_like, a0 as stringify } from "../../../chunks/index2.js";
import "@sveltejs/kit/internal";
import "../../../chunks/exports.js";
import "../../../chunks/utils.js";
import { a as attr } from "../../../chunks/attributes.js";
import "@sveltejs/kit/internal/server";
import "../../../chunks/state.svelte.js";
import { e as escape_html } from "../../../chunks/escaping.js";
function _page($$renderer, $$props) {
  $$renderer.component(($$renderer2) => {
    let { data } = $$props;
    const limit = 20;
    $$renderer2.push(`<div class="relative z-10 max-w-[1440px] mx-auto px-4 md:px-6 py-8 md:py-12"><div class="flex flex-col md:flex-row md:items-end justify-between mb-8 gap-4"><div><h1 class="font-display text-4xl md:text-5xl font-bold text-gray-900 dark:text-white tracking-tight mb-2">Daftar <span class="text-primary">Dosen</span></h1> <p class="text-gray-500 text-sm md:text-base max-w-2xl">Tenaga pengajar akademik yang terdaftar dalam sistem.</p></div> <div class="flex flex-col sm:flex-row gap-3"><a href="/dosen/create" class="bg-primary text-white font-medium text-sm px-6 py-3 rounded-xl shadow-lg hover:bg-orange-600 transition-all flex items-center justify-center group"><span class="material-symbols-outlined mr-2 text-lg group-hover:rotate-90 transition-transform">add</span> Tambah Dosen</a></div></div> <div class="bg-white/80 dark:bg-gray-800/80 backdrop-blur-md rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden"><div class="overflow-x-auto"><table class="w-full text-left border-collapse"><thead><tr class="bg-gray-50 dark:bg-gray-700/50 text-gray-500 dark:text-gray-300 text-xs font-semibold uppercase tracking-wider border-b border-gray-100 dark:border-gray-700"><th class="p-6">NIP</th><th class="p-6">Nama Dosen</th><th class="p-6">Email</th><th class="p-6 text-center">Aksi</th></tr></thead><tbody class="divide-y divide-gray-100 dark:divide-gray-700/50 text-sm">`);
    if (data.dosen && data.dosen.length > 0) {
      $$renderer2.push("<!--[-->");
      $$renderer2.push(`<!--[-->`);
      const each_array = ensure_array_like(data.dosen);
      for (let $$index = 0, $$length = each_array.length; $$index < $$length; $$index++) {
        let d = each_array[$$index];
        $$renderer2.push(`<tr class="hover:bg-gray-50 dark:hover:bg-gray-700/30 transition-colors group"><td class="p-6 font-mono font-bold text-primary">${escape_html(d.nip)}</td><td class="p-6 font-medium text-gray-900 dark:text-white">${escape_html(d.nama_dosen)}</td><td class="p-6 text-gray-500 dark:text-gray-400">${escape_html(d.email || "-")}</td><td class="p-6"><div class="flex justify-center items-center space-x-2 opacity-0 group-hover:opacity-100 transition-opacity"><a${attr("href", `/dosen/${stringify(d.nip)}/edit`)} class="p-2 text-gray-400 hover:text-blue-600 hover:bg-blue-50 dark:hover:bg-blue-900/20 rounded-lg transition-colors" title="Edit"><span class="material-symbols-outlined text-lg">edit</span></a> <form action="?/delete" method="POST" class="inline"><input type="hidden" name="nip"${attr("value", d.nip)}/> <button type="submit" class="p-2 text-gray-400 hover:text-red-600 hover:bg-red-50 dark:hover:bg-red-900/20 rounded-lg transition-colors" title="Delete"><span class="material-symbols-outlined text-lg">delete</span></button></form></div></td></tr>`);
      }
      $$renderer2.push(`<!--]-->`);
    } else {
      $$renderer2.push("<!--[!-->");
      $$renderer2.push(`<tr><td class="p-12 text-center text-gray-500" colspan="4"><div class="flex flex-col items-center justify-center"><span class="material-symbols-outlined text-4xl text-gray-300 mb-2">person_off</span> <p>Tidak ada data dosen ditemukan.</p></div></td></tr>`);
    }
    $$renderer2.push(`<!--]--></tbody></table></div></div> `);
    if (Math.ceil(data.count / limit) > 1) {
      $$renderer2.push("<!--[-->");
      $$renderer2.push(`<div class="mt-8 flex justify-center gap-2"><button${attr("disabled", data.page <= 1, true)} class="px-4 py-2 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg text-sm font-medium text-gray-600 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 disabled:opacity-50 disabled:cursor-not-allowed transition-colors">Previous</button> <span class="px-4 py-2 bg-gray-100 dark:bg-gray-800/50 rounded-lg text-sm font-medium text-gray-600 dark:text-gray-400">Page ${escape_html(data.page)} of ${escape_html(Math.ceil(data.count / limit))}</span> <button${attr("disabled", data.page >= Math.ceil(data.count / limit), true)} class="px-4 py-2 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg text-sm font-medium text-gray-600 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 disabled:opacity-50 disabled:cursor-not-allowed transition-colors">Next</button></div>`);
    } else {
      $$renderer2.push("<!--[!-->");
    }
    $$renderer2.push(`<!--]--></div>`);
  });
}
export {
  _page as default
};
