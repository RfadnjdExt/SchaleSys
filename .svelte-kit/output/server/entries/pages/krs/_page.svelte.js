import { a4 as ensure_array_like } from "../../../chunks/index2.js";
import { e as escape_html } from "../../../chunks/escaping.js";
function _page($$renderer, $$props) {
  $$renderer.component(($$renderer2) => {
    let { data } = $$props;
    $$renderer2.push(`<div class="relative z-10 max-w-[1440px] mx-auto px-4 md:px-6 py-8 md:py-12"><div class="bg-white/80 dark:bg-gray-800/80 backdrop-blur-md rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden"><div class="bg-slate-900 px-8 py-6 flex flex-col sm:flex-row justify-between items-center relative overflow-hidden"><div class="absolute inset-0 bg-primary/10 bg-noise mix-blend-overlay"></div> <div class="relative z-10"><h2 class="text-2xl font-display font-bold text-white flex items-center tracking-tight"><span class="material-symbols-outlined mr-3 text-primary">description</span> KARTU RENCANA STUDI</h2> <p class="text-slate-400 text-sm mt-1 sm:ml-9 font-mono tracking-wider uppercase">SEMESTER ${escape_html(data.semester_aktif)}</p></div> <div class="mt-4 sm:mt-0 relative z-10"><span class="inline-flex items-center px-4 py-1.5 rounded-full text-xs font-bold font-mono uppercase bg-primary/20 text-primary border border-primary/30">${escape_html(data.user?.role)}</span></div></div> <div class="p-8"><div class="mb-8 p-6 bg-gray-50 dark:bg-gray-700/30 rounded-xl border border-gray-200 dark:border-gray-600"><div class="grid grid-cols-1 md:grid-cols-2 gap-4"><div class="flex items-center"><span class="w-32 text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-widest">NIM</span> <span class="text-gray-900 dark:text-white font-mono font-medium">: ${escape_html(data.user?.nim || "-")}</span></div> <div class="flex items-center"><span class="w-32 text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-widest">Nama</span> <span class="text-gray-900 dark:text-white font-medium uppercase">: ${escape_html(data.user?.nama_lengkap)}</span></div></div></div> <div class="overflow-hidden border border-gray-200 dark:border-gray-600 rounded-xl mb-8"><table class="min-w-full divide-y divide-gray-200 dark:divide-gray-600"><thead class="bg-gray-50 dark:bg-gray-700/50"><tr><th scope="col" class="px-6 py-4 text-center text-xs font-bold text-gray-500 dark:text-gray-300 uppercase tracking-widest w-16">No</th><th scope="col" class="px-6 py-4 text-left text-xs font-bold text-gray-500 dark:text-gray-300 uppercase tracking-widest">Kode MK</th><th scope="col" class="px-6 py-4 text-left text-xs font-bold text-gray-500 dark:text-gray-300 uppercase tracking-widest">Nama Mata Kuliah</th><th scope="col" class="px-6 py-4 text-center text-xs font-bold text-gray-500 dark:text-gray-300 uppercase tracking-widest w-16">Sem</th><th scope="col" class="px-6 py-4 text-left text-xs font-bold text-gray-500 dark:text-gray-300 uppercase tracking-widest">Jadwal</th><th scope="col" class="px-6 py-4 text-center text-xs font-bold text-gray-500 dark:text-gray-300 uppercase tracking-widest w-16">SKS</th></tr></thead><tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-600">`);
    if (data.krs && data.krs.length > 0) {
      $$renderer2.push("<!--[-->");
      $$renderer2.push(`<!--[-->`);
      const each_array = ensure_array_like(data.krs);
      for (let i = 0, $$length = each_array.length; i < $$length; i++) {
        let mk = each_array[i];
        $$renderer2.push(`<tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors"><td class="px-6 py-4 whitespace-nowrap text-center text-sm text-gray-500 dark:text-gray-400">${escape_html(i + 1)}</td><td class="px-6 py-4 whitespace-nowrap text-sm font-mono font-medium text-primary">${escape_html(mk.kode_mk)}</td><td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-white uppercase">${escape_html(mk.nama_mk)}</td><td class="px-6 py-4 whitespace-nowrap text-center text-sm text-gray-600 dark:text-gray-400 font-bold">${escape_html(mk.semester)}</td><td class="px-6 py-4 whitespace-nowrap text-sm"><div class="flex flex-col"><span class="font-medium text-gray-900 dark:text-white">${escape_html(mk.hari || "-")}</span> `);
        if (mk.jam_mulai) {
          $$renderer2.push("<!--[-->");
          $$renderer2.push(`<span class="text-xs text-gray-500 dark:text-gray-400">${escape_html(mk.jam_mulai.substring(0, 5))} - ${escape_html(mk.jam_selesai.substring(0, 5))}</span>`);
        } else {
          $$renderer2.push("<!--[!-->");
        }
        $$renderer2.push(`<!--]--></div> `);
        if (mk.ruangan) {
          $$renderer2.push("<!--[-->");
          $$renderer2.push(`<div class="text-xs text-gray-400 mt-1 uppercase tracking-wider">R. ${escape_html(mk.ruangan)}</div>`);
        } else {
          $$renderer2.push("<!--[!-->");
        }
        $$renderer2.push(`<!--]--></td><td class="px-6 py-4 whitespace-nowrap text-center text-sm font-bold text-gray-900 dark:text-white">${escape_html(mk.sks)}</td></tr>`);
      }
      $$renderer2.push(`<!--]-->`);
    } else {
      $$renderer2.push("<!--[!-->");
      $$renderer2.push(`<tr><td colspan="6" class="px-6 py-12 text-center text-gray-500 dark:text-gray-400 italic"><div class="flex flex-col items-center justify-center"><span class="material-symbols-outlined text-3xl mb-2 opacity-50">toc</span> Belum ada mata kuliah yang diambil.</div></td></tr>`);
    }
    $$renderer2.push(`<!--]--></tbody><tfoot class="bg-gray-50 dark:bg-gray-700/30"><tr><td colspan="5" class="px-6 py-4 text-right text-sm font-bold text-gray-900 dark:text-white uppercase tracking-wider">Total SKS</td><td class="px-6 py-4 text-center text-base font-bold text-primary border-t-2 border-primary/20 bg-primary/5">${escape_html(data.totalSks)}</td></tr></tfoot></table></div> <div class="flex flex-col sm:flex-row justify-end gap-4 no-print"><button class="inline-flex justify-center items-center px-6 py-3 border-2 border-slate-900 dark:border-white shadow-sm text-sm font-bold uppercase tracking-wider rounded-lg text-slate-900 dark:text-white hover:bg-slate-900 hover:text-white dark:hover:bg-white dark:hover:text-slate-900 transition-all"><span class="material-symbols-outlined mr-2 text-lg">print</span> Cetak KRS</button> `);
    if (data.user?.role === "mahasiswa") {
      $$renderer2.push("<!--[-->");
      $$renderer2.push(`<a href="/krs/input" class="inline-flex justify-center items-center px-6 py-3 border-2 border-primary shadow-lg text-sm font-bold uppercase tracking-wider rounded-lg text-white bg-primary hover:bg-orange-600 border-orange-600 transition-all"><span class="material-symbols-outlined mr-2 text-lg">edit</span> Edit Rencana Studi</a>`);
    } else {
      $$renderer2.push("<!--[!-->");
    }
    $$renderer2.push(`<!--]--></div></div></div></div>`);
  });
}
export {
  _page as default
};
