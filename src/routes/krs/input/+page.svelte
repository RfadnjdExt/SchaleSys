<script lang="ts">
    import { enhance } from "$app/forms";
    import type { ActionData, PageData } from "./$types";

    export let data: PageData;
    export let form: ActionData;

    // Client-side SKS calculation
    let selectedSks = 0;

    // Reactive sync
    $: selectedSks = data.totalSks || 0;

    function calculateSks(e: Event) {
        const checkbox = e.target as HTMLInputElement;
        const sks = parseInt(checkbox.getAttribute("data-sks") || "0");
        if (checkbox.checked) {
            selectedSks += sks;
        } else {
            selectedSks -= sks;
        }
    }
</script>

<div class="max-w-7xl mx-auto px-4 py-8">
    <div
        class="bg-white rounded-xl shadow-lg border border-gray-100 overflow-hidden"
    >
        <div
            class="bg-linear-to-r from-blue-600 to-indigo-700 px-6 py-4 flex flex-col md:flex-row justify-between items-center gap-4"
        >
            <h1 class="text-xl font-bold text-white flex items-center">
                <svg
                    class="w-6 h-6 mr-2"
                    fill="none"
                    stroke="currentColor"
                    viewBox="0 0 24 24"
                    ><path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"
                    ></path></svg
                >
                Input KRS
            </h1>
            <div class="flex items-center space-x-3">
                <span
                    class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-yellow-100 text-yellow-800 border border-yellow-200 shadow-sm"
                >
                    SKS: {selectedSks} / 24
                </span>
                <span
                    class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800 border border-blue-200 shadow-sm"
                >
                    {data.semester_aktif}
                </span>
            </div>
        </div>

        <div class="p-8">
            {#if form?.error}
                <div
                    class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-6"
                    role="alert"
                >
                    <span class="block sm:inline">{form.error}</span>
                </div>
            {/if}

            <div class="mb-8 bg-gray-50 rounded-lg p-6 border border-gray-200">
                <h5 class="text-lg font-semibold text-gray-800 mb-2">
                    Halo, {data.user?.nama_lengkap}
                    <span class="text-gray-500 text-sm font-normal"
                        >({data.user?.nim})</span
                    >
                </h5>
                <p class="text-gray-600 mb-4">
                    Silakan pilih mata kuliah yang akan diambil semester ini
                    (Maksimal 24 SKS).
                </p>
            </div>

            <form method="POST" use:enhance>
                <div
                    class="overflow-hidden border border-gray-200 rounded-lg mb-6"
                >
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-800 text-white">
                            <tr>
                                <th
                                    scope="col"
                                    class="px-6 py-3 text-center text-xs font-medium uppercase tracking-wider w-16"
                                    >Pilih</th
                                >
                                <th
                                    scope="col"
                                    class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider w-24"
                                    >Kode</th
                                >
                                <th
                                    scope="col"
                                    class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider"
                                    >Mata Kuliah</th
                                >
                                <th
                                    scope="col"
                                    class="px-6 py-3 text-center text-xs font-medium uppercase tracking-wider w-16"
                                    >SKS</th
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
                                    class="px-6 py-3 text-center text-xs font-medium uppercase tracking-wider w-32"
                                    >Status</th
                                >
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            {#if data.mkList && data.mkList.length > 0}
                                {#each data.mkList as mk}
                                    {@const isTaken = (
                                        data.takenCodes || []
                                    ).includes(mk.kode_mk)}
                                    <tr
                                        class={isTaken
                                            ? "bg-green-50"
                                            : "hover:bg-gray-50 transition-colors"}
                                    >
                                        <td
                                            class="px-6 py-4 whitespace-nowrap text-center"
                                        >
                                            {#if isTaken}
                                                <input
                                                    type="checkbox"
                                                    checked
                                                    disabled
                                                    class="h-4 w-4 text-green-600 rounded border-gray-300 focus:ring-green-500 cursor-not-allowed opacity-50"
                                                />
                                            {:else}
                                                <input
                                                    type="checkbox"
                                                    name="matkul_diambil"
                                                    value={mk.kode_mk}
                                                    data-sks={mk.sks}
                                                    onchange={calculateSks}
                                                    class="h-4 w-4 text-blue-600 rounded border-gray-300 focus:ring-blue-500 cursor-pointer"
                                                />
                                            {/if}
                                        </td>
                                        <td
                                            class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 font-mono"
                                            >{mk.kode_mk}</td
                                        >
                                        <td
                                            class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900"
                                            >{mk.nama_mk}</td
                                        >
                                        <td
                                            class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 text-center font-bold"
                                            >{mk.sks}</td
                                        >
                                        <td
                                            class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center"
                                            >{mk.semester}</td
                                        >
                                        <td
                                            class="px-6 py-4 whitespace-nowrap text-sm text-gray-600"
                                        >
                                            <div class="font-medium">
                                                {mk.hari || "-"}, {mk.jam_mulai
                                                    ? `${mk.jam_mulai.substring(0, 5)}-${mk.jam_selesai.substring(0, 5)}`
                                                    : ""}
                                            </div>
                                            <div class="text-xs text-gray-400">
                                                R. {mk.ruangan || "-"}
                                            </div>
                                        </td>
                                        <td
                                            class="px-6 py-4 whitespace-nowrap text-center"
                                        >
                                            {#if isTaken}
                                                <span
                                                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 border border-green-200"
                                                >
                                                    ● Terambil
                                                </span>
                                            {:else}
                                                <span
                                                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800"
                                                >
                                                    Belum
                                                </span>
                                            {/if}
                                        </td>
                                    </tr>
                                {/each}
                            {:else}
                                <tr>
                                    <td
                                        colspan="7"
                                        class="px-6 py-8 text-center text-gray-500 italic"
                                        >Belum ada data mata kuliah.</td
                                    >
                                </tr>
                            {/if}
                        </tbody>
                    </table>
                </div>

                <div class="flex justify-end mt-6">
                    <button
                        type="submit"
                        class="inline-flex justify-center py-2 px-6 border border-transparent shadow-sm text-base font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors duration-200"
                    >
                        Simpan Rencana Studi
                    </button>
                    <a
                        href="/krs"
                        class="ml-4 inline-flex justify-center py-2 px-6 border border-gray-300 shadow-sm text-base font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50"
                    >
                        Batal
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
