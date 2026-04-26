<script lang="ts">
    import { enhance } from "$app/forms";
    import type { PageData } from "./$types";

    let { data }: { data: PageData } = $props();
</script>

<div class="relative z-10 max-w-[1440px] mx-auto px-4 md:px-6 py-8 md:py-12">
    <div
        class="flex flex-col md:flex-row md:items-end justify-between mb-8 gap-4"
    >
        <div>
            <h1
                class="font-display text-4xl md:text-5xl font-bold text-gray-900 dark:text-white tracking-tight mb-2"
            >
                Daftar <span class="text-primary">Mata Kuliah</span>
            </h1>
            <p class="text-gray-500 text-sm md:text-base max-w-2xl">
                Katalog mata kuliah yang tersedia untuk semua program studi.
            </p>
        </div>
        <div class="flex flex-col sm:flex-row gap-3">
            {#if data.user?.role === "admin"}
                <a
                    href="/matakuliah/create"
                    class="bg-primary text-white font-medium text-sm px-6 py-3 rounded-xl shadow-lg hover:bg-orange-600 transition-all flex items-center justify-center group"
                >
                    <span
                        class="material-symbols-outlined mr-2 text-lg group-hover:rotate-90 transition-transform"
                        >add</span
                    >
                    Tambah Matkul
                </a>
            {/if}
        </div>
    </div>

    <!-- Main Content Card -->
    <div
        class="bg-white/80 dark:bg-gray-800/80 backdrop-blur-md rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden"
    >
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr
                        class="bg-gray-50 dark:bg-gray-700/50 text-gray-500 dark:text-gray-300 text-xs font-semibold uppercase tracking-wider border-b border-gray-100 dark:border-gray-700"
                    >
                        <th class="p-6">Kode MK</th>
                        <th class="p-6">Nama Mata Kuliah</th>
                        <th class="p-6">SKS</th>
                        <th class="p-6">Semester</th>
                        <th class="p-6">Jadwal</th>
                        <th class="p-6">Ruangan</th>
                        <th class="p-6 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody
                    class="divide-y divide-gray-100 dark:divide-gray-700/50 text-sm"
                >
                    {#if data.matakuliah && data.matakuliah.length > 0}
                        {#each data.matakuliah as mk}
                            <tr
                                class="hover:bg-gray-50 dark:hover:bg-gray-700/30 transition-colors group"
                            >
                                <td class="p-6 font-mono font-bold text-primary"
                                    >{mk.kode_mk}</td
                                >
                                <td
                                    class="p-6 font-medium text-gray-900 dark:text-white"
                                    >{mk.nama_mk}</td
                                >
                                <td
                                    class="p-6 text-gray-500 dark:text-gray-400 font-bold"
                                    >{mk.sks}</td
                                >
                                <td
                                    class="p-6 text-gray-500 dark:text-gray-400"
                                    >{mk.semester}</td
                                >
                                <td
                                    class="p-6 text-gray-500 dark:text-gray-400"
                                >
                                    {#if mk.hari}
                                        <div class="flex flex-col">
                                            <span class="font-medium text-gray-900 dark:text-white">{mk.hari}</span>
                                            <span class="text-xs text-gray-400 dark:text-gray-500"
                                                >{mk.jam_mulai?.substring(0, 5)}
                                                - {mk.jam_selesai?.substring(
                                                    0,
                                                    5,
                                                )}</span
                                            >
                                        </div>
                                    {:else}
                                        <span class="text-gray-300">-</span>
                                    {/if}
                                </td>
                                <td
                                    class="p-6 text-gray-500 dark:text-gray-400 font-medium"
                                    >{mk.ruangan || "-"}</td
                                >
                                <td class="p-6">
                                    {#if data.user?.role === "admin"}
                                        <div
                                            class="flex justify-center items-center space-x-2 opacity-0 group-hover:opacity-100 transition-opacity"
                                        >
                                            <a
                                                href="/matakuliah/{mk.kode_mk}/edit"
                                                class="p-2 text-gray-400 hover:text-blue-600 hover:bg-blue-50 dark:hover:bg-blue-900/20 rounded-lg transition-colors"
                                                title="Edit"
                                            >
                                                <span
                                                    class="material-symbols-outlined text-lg"
                                                    >edit</span
                                                >
                                            </a>
                                            <form
                                                action="?/delete"
                                                method="POST"
                                                class="inline"
                                                use:enhance
                                            >
                                                <input
                                                    type="hidden"
                                                    name="kode_mk"
                                                    value={mk.kode_mk}
                                                />
                                                <button
                                                    type="submit"
                                                    class="p-2 text-gray-400 hover:text-red-600 hover:bg-red-50 dark:hover:bg-red-900/20 rounded-lg transition-colors"
                                                    title="Delete"
                                                    onclick={(e) => {
                                                        if (
                                                            !confirm(
                                                                "Hapus mata kuliah ini?",
                                                            )
                                                        )
                                                            e.preventDefault();
                                                    }}
                                                >
                                                    <span
                                                        class="material-symbols-outlined text-lg"
                                                        >delete</span
                                                    >
                                                </button>
                                            </form>
                                        </div>
                                    {/if}
                                </td>
                            </tr>
                        {/each}
                    {:else}
                        <tr>
                            <td
                                class="p-12 text-center text-gray-500"
                                colspan="7"
                            >
                                <div class="flex flex-col items-center justify-center">
                                    <span class="material-symbols-outlined text-4xl text-gray-300 mb-2">menu_book</span>
                                    <p>Tidak ada data mata kuliah ditemukan.</p>
                                </div>
                            </td>
                        </tr>
                    {/if}
                </tbody>
            </table>
        </div>
    </div>
</div>


