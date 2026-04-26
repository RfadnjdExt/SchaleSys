import { a4 as ensure_array_like, a3 as bind_props } from "../../../../chunks/index2.js";
import "@sveltejs/kit/internal";
import "../../../../chunks/exports.js";
import "../../../../chunks/utils.js";
import { a as attr } from "../../../../chunks/attributes.js";
import "@sveltejs/kit/internal/server";
import "../../../../chunks/state.svelte.js";
import { e as escape_html } from "../../../../chunks/escaping.js";
function _page($$renderer, $$props) {
  $$renderer.component(($$renderer2) => {
    let dosenList, mkList, assignments;
    let data = $$props["data"];
    let form = $$props["form"];
    let nip_dosen = "";
    let kode_matkul = "";
    function getName(obj, field) {
      if (Array.isArray(obj) && obj.length > 0) return obj[0][field];
      return obj?.[field];
    }
    dosenList = data.dosenList;
    mkList = data.mkList;
    assignments = data.assignments;
    $$renderer2.push(`<div class="max-w-7xl mx-auto px-4 py-8"><div class="mb-6">`);
    if (form?.error) {
      $$renderer2.push("<!--[-->");
      $$renderer2.push(`<div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert"><span class="block sm:inline">${escape_html(form.error)}</span></div>`);
    } else {
      $$renderer2.push("<!--[!-->");
      if (form?.success) {
        $$renderer2.push("<!--[-->");
        $$renderer2.push(`<div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert"><span class="block sm:inline">Aksi berhasil.</span></div>`);
      } else {
        $$renderer2.push("<!--[!-->");
      }
      $$renderer2.push(`<!--]-->`);
    }
    $$renderer2.push(`<!--]--></div> <div class="grid grid-cols-1 lg:grid-cols-3 gap-8"><div class="lg:col-span-1"><div class="bg-white rounded-xl shadow-lg border border-gray-100 overflow-hidden sticky top-8"><div class="bg-linear-to-r from-blue-600 to-indigo-700 px-6 py-4"><h1 class="text-xl font-bold text-white">Tugaskan Dosen</h1></div> <div class="p-6"><form action="?/assign" method="POST" class="space-y-5"><div><label for="nip_dosen" class="block text-sm font-medium text-gray-700 mb-2">Pilih Dosen</label> `);
    $$renderer2.select(
      {
        id: "nip_dosen",
        name: "nip_dosen",
        value: nip_dosen,
        class: "w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50 py-2 px-3 bg-white border",
        required: true
      },
      ($$renderer3) => {
        $$renderer3.option({ value: "" }, ($$renderer4) => {
          $$renderer4.push(`-- Pilih Dosen --`);
        });
        $$renderer3.push(`<!--[-->`);
        const each_array = ensure_array_like(dosenList);
        for (let $$index = 0, $$length = each_array.length; $$index < $$length; $$index++) {
          let d = each_array[$$index];
          $$renderer3.option({ value: d.nip }, ($$renderer4) => {
            $$renderer4.push(`${escape_html(d.nama_dosen)}`);
          });
        }
        $$renderer3.push(`<!--]-->`);
      }
    );
    $$renderer2.push(`</div> <div><label for="kode_matkul" class="block text-sm font-medium text-gray-700 mb-2">Pilih Mata Kuliah</label> `);
    $$renderer2.select(
      {
        id: "kode_matkul",
        name: "kode_matkul",
        value: kode_matkul,
        class: "w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50 py-2 px-3 bg-white border",
        required: true
      },
      ($$renderer3) => {
        $$renderer3.option({ value: "" }, ($$renderer4) => {
          $$renderer4.push(`-- Pilih Mata Kuliah --`);
        });
        $$renderer3.push(`<!--[-->`);
        const each_array_1 = ensure_array_like(mkList);
        for (let $$index_1 = 0, $$length = each_array_1.length; $$index_1 < $$length; $$index_1++) {
          let mk = each_array_1[$$index_1];
          $$renderer3.option({ value: mk.kode_mk }, ($$renderer4) => {
            $$renderer4.push(`${escape_html(mk.nama_mk)}`);
          });
        }
        $$renderer3.push(`<!--]-->`);
      }
    );
    $$renderer2.push(`</div> <button type="submit" class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors duration-200">Tugaskan</button></form> <div class="mt-4 pt-4 border-t border-gray-200"><a href="/dosen" class="text-blue-600 hover:text-blue-800 text-sm">Kembali ke Daftar Dosen</a></div></div></div></div> <div class="lg:col-span-2"><div class="bg-white rounded-xl shadow-lg border border-gray-100 overflow-hidden"><div class="bg-gray-50 border-b border-gray-200 px-6 py-4"><h2 class="text-lg font-bold text-gray-800">Daftar Dosen Pengampu Saat Ini</h2></div> <div class="overflow-x-auto"><table class="min-w-full divide-y divide-gray-200"><thead class="bg-gray-50"><tr><th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama Dosen</th><th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Mata Kuliah Diampu</th><th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th></tr></thead><tbody class="bg-white divide-y divide-gray-200">`);
    if (assignments && assignments.length > 0) {
      $$renderer2.push("<!--[-->");
      $$renderer2.push(`<!--[-->`);
      const each_array_2 = ensure_array_like(assignments);
      for (let $$index_2 = 0, $$length = each_array_2.length; $$index_2 < $$length; $$index_2++) {
        let a = each_array_2[$$index_2];
        $$renderer2.push(`<tr class="hover:bg-gray-50 transition-colors"><td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">${escape_html(getName(a.dosen, "nama_dosen"))}</td><td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">${escape_html(getName(a.mata_kuliah, "nama_mk"))}</td><td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium"><form action="?/delete" method="POST" class="inline"><input type="hidden" name="id_pengampu"${attr("value", a.id_pengampu)}/> <button type="submit" class="text-red-600 hover:text-red-900 flex justify-end items-center gap-1 ml-auto"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg> Hapus</button></form></td></tr>`);
      }
      $$renderer2.push(`<!--]-->`);
    } else {
      $$renderer2.push("<!--[!-->");
      $$renderer2.push(`<tr><td colspan="3" class="px-6 py-8 text-center text-gray-500 italic">Belum ada dosen yang ditugaskan.</td></tr>`);
    }
    $$renderer2.push(`<!--]--></tbody></table></div></div></div></div></div>`);
    bind_props($$props, { data, form });
  });
}
export {
  _page as default
};
