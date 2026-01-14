import { a4 as ensure_array_like } from "../../../../chunks/index2.js";
import "@sveltejs/kit/internal";
import "../../../../chunks/exports.js";
import "../../../../chunks/utils.js";
import { a as attr } from "../../../../chunks/attributes.js";
import "@sveltejs/kit/internal/server";
import "../../../../chunks/state.svelte.js";
import { e as escape_html } from "../../../../chunks/escaping.js";
function _page($$renderer, $$props) {
  $$renderer.component(($$renderer2) => {
    let { data, form } = $$props;
    let nim_mahasiswa = "";
    let kode_matkul = "";
    let nilai_huruf = "";
    $$renderer2.push(`<div class="max-w-2xl mx-auto px-4 py-8"><div class="bg-white rounded-xl shadow-lg border border-gray-100 overflow-hidden"><div class="bg-linear-to-r from-blue-600 to-indigo-700 px-6 py-4"><h1 class="text-xl font-bold text-white flex items-center"><svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg> Form Input Nilai</h1></div> <div class="p-8">`);
    if (form?.error) {
      $$renderer2.push("<!--[-->");
      $$renderer2.push(`<div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-6" role="alert"><span class="block sm:inline">${escape_html(form.error)}</span></div>`);
    } else {
      $$renderer2.push("<!--[!-->");
      if (form?.success) {
        $$renderer2.push("<!--[-->");
        $$renderer2.push(`<div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-6" role="alert"><span class="block sm:inline">${escape_html(form.message)}</span></div>`);
      } else {
        $$renderer2.push("<!--[!-->");
      }
      $$renderer2.push(`<!--]-->`);
    }
    $$renderer2.push(`<!--]--> <form method="POST" class="space-y-6"><div><label for="nim_mahasiswa" class="block text-sm font-medium text-gray-700 mb-2">Pilih Mahasiswa</label> `);
    $$renderer2.select(
      {
        id: "nim_mahasiswa",
        name: "nim_mahasiswa",
        value: nim_mahasiswa,
        class: "w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50 py-2 px-3 bg-white border",
        required: true
      },
      ($$renderer3) => {
        $$renderer3.option({ value: "" }, ($$renderer4) => {
          $$renderer4.push(`-- Pilih Mahasiswa --`);
        });
        $$renderer3.push(`<!--[-->`);
        const each_array = ensure_array_like(data.mahasiswa);
        for (let $$index = 0, $$length = each_array.length; $$index < $$length; $$index++) {
          let m = each_array[$$index];
          $$renderer3.option({ value: m.nim }, ($$renderer4) => {
            $$renderer4.push(`${escape_html(m.nim)} - ${escape_html(m.nama_mahasiswa)}`);
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
        const each_array_1 = ensure_array_like(data.matakuliah);
        for (let $$index_1 = 0, $$length = each_array_1.length; $$index_1 < $$length; $$index_1++) {
          let mk = each_array_1[$$index_1];
          $$renderer3.option({ value: mk.kode_mk }, ($$renderer4) => {
            $$renderer4.push(`${escape_html(mk.kode_mk)} - ${escape_html(mk.nama_mk)}`);
          });
        }
        $$renderer3.push(`<!--]-->`);
      }
    );
    $$renderer2.push(`</div> <div><label for="nilai_huruf" class="block text-sm font-medium text-gray-700 mb-2">Nilai Huruf</label> <input type="text" id="nilai_huruf" name="nilai_huruf"${attr("value", nilai_huruf)} class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50 py-2 px-3 border" placeholder="Contoh: A, B, C" required/> <p class="mt-1 text-sm text-gray-500">Masukkan nilai A, B, C, D, atau E.</p></div> <div class="flex items-center space-x-4 pt-4"><button type="submit" class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-colors duration-200">Simpan Nilai</button> <a href="/nilai" class="w-full flex justify-center py-2 px-4 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 transition-colors duration-200">Kembali</a></div></form></div></div></div>`);
  });
}
export {
  _page as default
};
