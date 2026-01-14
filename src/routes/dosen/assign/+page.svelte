<script lang="ts">
    import { enhance } from "$app/forms";
    import type { PageData, ActionData } from "./$types";

    export let data: PageData;
    export let form: ActionData;

    $: dosenList = data.dosenList;
    $: mkList = data.mkList;
    $: assignments = data.assignments;

    // Local state for assignments list (if we want to optimistic update, but for now just display data)
    // Local state for form
    let nip_dosen = "";
    let kode_matkul = "";

    // Helper to handle Supabase relation which might be returned as array or object
    // Users reported it returns array
    function getName(obj: any, field: string) {
        if (Array.isArray(obj) && obj.length > 0) return obj[0][field];
        return obj?.[field];
    }
</script>

<div class="max-w-7xl mx-auto px-4 py-8">
    <div class="mb-6">
        {#if form?.error}
            <div
                class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative"
                role="alert"
            >
                <span class="block sm:inline">{form.error}</span>
            </div>
        {:else if form?.success}
            <div
                class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative"
                role="alert"
            >
                <span class="block sm:inline">Aksi berhasil.</span>
            </div>
        {/if}
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Form Card -->
        <div class="lg:col-span-1">
            <div
                class="bg-white rounded-xl shadow-lg border border-gray-100 overflow-hidden sticky top-8"
            >
                <div
                    class="bg-linear-to-r from-blue-600 to-indigo-700 px-6 py-4"
                >
                    <h1 class="text-xl font-bold text-white">Tugaskan Dosen</h1>
                </div>
                <div class="p-6">
                    <form
                        action="?/assign"
                        method="POST"
                        class="space-y-5"
                        use:enhance
                    >
                        <div>
                            <label
                                for="nip_dosen"
                                class="block text-sm font-medium text-gray-700 mb-2"
                                >Pilih Dosen</label
                            >
                            <select
                                id="nip_dosen"
                                name="nip_dosen"
                                bind:value={nip_dosen}
                                class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50 py-2 px-3 bg-white border"
                                required
                            >
                                <option value="">-- Pilih Dosen --</option>
                                {#each dosenList as d}
                                    <option value={d.nip}>{d.nama_dosen}</option
                                    >
                                {/each}
                            </select>
                        </div>
                        <div>
                            <label
                                for="kode_matkul"
                                class="block text-sm font-medium text-gray-700 mb-2"
                                >Pilih Mata Kuliah</label
                            >
                            <select
                                id="kode_matkul"
                                name="kode_matkul"
                                bind:value={kode_matkul}
                                class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50 py-2 px-3 bg-white border"
                                required
                            >
                                <option value="">-- Pilih Mata Kuliah --</option
                                >
                                {#each mkList as mk}
                                    <option value={mk.kode_mk}
                                        >{mk.nama_mk}</option
                                    >
                                {/each}
                            </select>
                        </div>
                        <button
                            type="submit"
                            class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors duration-200"
                        >
                            Tugaskan
                        </button>
                    </form>
                    <div class="mt-4 pt-4 border-t border-gray-200">
                        <a
                            href="/dosen"
                            class="text-blue-600 hover:text-blue-800 text-sm"
                            >Kembali ke Daftar Dosen</a
                        >
                    </div>
                </div>
            </div>
        </div>

        <!-- List Card -->
        <div class="lg:col-span-2">
            <div
                class="bg-white rounded-xl shadow-lg border border-gray-100 overflow-hidden"
            >
                <div class="bg-gray-50 border-b border-gray-200 px-6 py-4">
                    <h2 class="text-lg font-bold text-gray-800">
                        Daftar Dosen Pengampu Saat Ini
                    </h2>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th
                                    scope="col"
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                                    >Nama Dosen</th
                                >
                                <th
                                    scope="col"
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                                    >Mata Kuliah Diampu</th
                                >
                                <th
                                    scope="col"
                                    class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider"
                                    >Aksi</th
                                >
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            {#if assignments && assignments.length > 0}
                                {#each assignments as a}
                                    <tr
                                        class="hover:bg-gray-50 transition-colors"
                                    >
                                        <td
                                            class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900"
                                            >{getName(
                                                a.dosen,
                                                "nama_dosen",
                                            )}</td
                                        >
                                        <td
                                            class="px-6 py-4 whitespace-nowrap text-sm text-gray-600"
                                            >{getName(
                                                a.mata_kuliah,
                                                "nama_mk",
                                            )}</td
                                        >
                                        <td
                                            class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium"
                                        >
                                            <form
                                                action="?/delete"
                                                method="POST"
                                                class="inline"
                                                use:enhance
                                            >
                                                <input
                                                    type="hidden"
                                                    name="id_pengampu"
                                                    value={a.id_pengampu}
                                                />
                                                <button
                                                    type="submit"
                                                    class="text-red-600 hover:text-red-900 flex justify-end items-center gap-1 ml-auto"
                                                    onclick={(e) =>
                                                        !confirm(
                                                            "Yakin hapus penugasan ini?",
                                                        ) && e.preventDefault()}
                                                >
                                                    <svg
                                                        class="w-4 h-4"
                                                        fill="none"
                                                        stroke="currentColor"
                                                        viewBox="0 0 24 24"
                                                        ><path
                                                            stroke-linecap="round"
                                                            stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"
                                                        ></path></svg
                                                    >
                                                    Hapus
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                {/each}
                            {:else}
                                <tr>
                                    <td
                                        colspan="3"
                                        class="px-6 py-8 text-center text-gray-500 italic"
                                        >Belum ada dosen yang ditugaskan.</td
                                    >
                                </tr>
                            {/if}
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
