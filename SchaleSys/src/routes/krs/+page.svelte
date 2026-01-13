<script lang="ts">
    import type { PageData } from "./$types";

    let { data }: { data: PageData } = $props();
</script>

<div class="max-w-5xl mx-auto px-4 py-8">
    <div
        class="bg-white rounded-xl shadow-lg border border-gray-100 overflow-hidden"
    >
        <div
            class="bg-linear-to-r from-blue-600 to-indigo-700 px-6 py-4 flex flex-col sm:flex-row justify-between items-center"
        >
            <div>
                <h2 class="text-xl font-bold text-white flex items-center">
                    <svg
                        class="w-6 h-6 mr-2"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24"
                        ><path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"
                        ></path></svg
                    >
                    Kartu Rencana Studi (KRS)
                </h2>
                <p class="text-blue-200 text-sm mt-1 sm:ml-8">
                    {data.semester_aktif}
                </p>
            </div>
            <div class="mt-2 sm:mt-0">
                <span
                    class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-500 bg-opacity-25 text-white border border-blue-400 capitalize"
                >
                    {data.user?.role}
                </span>
            </div>
        </div>

        <div class="p-8">
            <div class="mb-8 p-6 bg-gray-50 rounded-lg border border-gray-200">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="flex items-center">
                        <span
                            class="w-24 text-sm font-medium text-gray-500 uppercase tracking-wider"
                            >NIM</span
                        >
                        <span class="text-gray-900 font-semibold"
                            >: {data.user?.nim || "-"}</span
                        >
                    </div>
                    <div class="flex items-center">
                        <span
                            class="w-24 text-sm font-medium text-gray-500 uppercase tracking-wider"
                            >Nama</span
                        >
                        <span class="text-gray-900 font-semibold"
                            >: {data.user?.nama_lengkap}</span
                        >
                    </div>
                </div>
            </div>

            <div class="overflow-hidden border border-gray-200 rounded-lg mb-8">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-800 text-white">
                        <tr>
                            <th
                                scope="col"
                                class="px-6 py-3 text-center text-xs font-medium uppercase tracking-wider w-12"
                                >No</th
                            >
                            <th
                                scope="col"
                                class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider"
                                >Kode MK</th
                            >
                            <th
                                scope="col"
                                class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider"
                                >Nama Mata Kuliah</th
                            >
                            <th
                                scope="col"
                                class="px-6 py-3 text-center text-xs font-medium uppercase tracking-wider w-16"
                                >Sem</th
                            >
                            <th
                                scope="col"
                                class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider"
                                >Jadwal</th
                            >
                            <th
                                scope="col"
                                class="px-6 py-3 text-center text-xs font-medium uppercase tracking-wider w-16"
                                >SKS</th
                            >
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        {#if data.krs && data.krs.length > 0}
                            {#each data.krs as mk, i}
                                <tr class="hover:bg-gray-50 transition-colors">
                                    <td
                                        class="px-6 py-4 whitespace-nowrap text-center text-sm text-gray-500"
                                        >{i + 1}</td
                                    >
                                    <td
                                        class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 font-mono"
                                        >{mk.kode_mk}</td
                                    >
                                    <td
                                        class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900"
                                        >{mk.nama_mk}</td
                                    >
                                    <td
                                        class="px-6 py-4 whitespace-nowrap text-center text-sm text-gray-500"
                                        >{mk.semester}</td
                                    >
                                    <td
                                        class="px-6 py-4 whitespace-nowrap text-sm text-gray-600"
                                    >
                                        <div>
                                            {mk.hari || "-"}
                                            {mk.jam_mulai
                                                ? `${mk.jam_mulai.substring(0, 5)}-${mk.jam_selesai.substring(0, 5)}`
                                                : ""}
                                        </div>
                                        <div class="text-xs text-gray-400">
                                            R. {mk.ruangan || "-"}
                                        </div>
                                    </td>
                                    <td
                                        class="px-6 py-4 whitespace-nowrap text-center text-sm font-bold text-gray-900"
                                        >{mk.sks}</td
                                    >
                                </tr>
                            {/each}
                        {:else}
                            <tr>
                                <td
                                    colspan="6"
                                    class="px-6 py-8 text-center text-gray-500 italic"
                                    >Belum ada mata kuliah yang diambil.</td
                                >
                            </tr>
                        {/if}
                    </tbody>
                    <tfoot class="bg-gray-50">
                        <tr>
                            <td
                                colspan="5"
                                class="px-6 py-3 text-right text-sm font-bold text-gray-900 uppercase"
                                >Total SKS</td
                            >
                            <td
                                class="px-6 py-3 text-center text-sm font-bold text-blue-600 border-t border-gray-200 bg-blue-50"
                                >{data.totalSks}</td
                            >
                        </tr>
                    </tfoot>
                </table>
            </div>

            <div
                class="flex flex-col sm:flex-row justify-center gap-4 no-print"
            >
                <button
                    onclick={() => window.print()}
                    class="inline-flex justify-center items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors"
                >
                    <svg
                        class="w-5 h-5 mr-2 text-gray-500"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24"
                        ><path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"
                        ></path></svg
                    >
                    Cetak KRS
                </button>
                {#if data.user?.role === "mahasiswa"}
                    <a
                        href="/krs/input"
                        class="inline-flex justify-center items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors"
                    >
                        <svg
                            class="w-5 h-5 mr-2"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                            ><path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"
                            ></path></svg
                        >
                        Edit / Tambah Mata Kuliah
                    </a>
                {/if}
            </div>
        </div>
    </div>
</div>
