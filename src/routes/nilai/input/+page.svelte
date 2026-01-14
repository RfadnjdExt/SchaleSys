<script lang="ts">
    import { enhance } from "$app/forms";
    import type { ActionData, PageData } from "./$types";

    let { data, form }: { data: PageData; form: ActionData } = $props();

    // Local state for form inputs
    let nim_mahasiswa = $state("");
    let kode_matkul = $state("");
    let nilai_huruf = $state("");
</script>

<div class="max-w-2xl mx-auto px-4 py-8">
    <div
        class="bg-white rounded-xl shadow-lg border border-gray-100 overflow-hidden"
    >
        <div class="bg-linear-to-r from-blue-600 to-indigo-700 px-6 py-4">
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
                        d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"
                    ></path></svg
                >
                Form Input Nilai
            </h1>
        </div>

        <div class="p-8">
            {#if form?.error}
                <div
                    class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-6"
                    role="alert"
                >
                    <span class="block sm:inline">{form.error}</span>
                </div>
            {:else if form?.success}
                <div
                    class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-6"
                    role="alert"
                >
                    <span class="block sm:inline">{form.message}</span>
                </div>
            {/if}

            <form method="POST" class="space-y-6" use:enhance>
                <div>
                    <label
                        for="nim_mahasiswa"
                        class="block text-sm font-medium text-gray-700 mb-2"
                        >Pilih Mahasiswa</label
                    >
                    <select
                        id="nim_mahasiswa"
                        name="nim_mahasiswa"
                        bind:value={nim_mahasiswa}
                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50 py-2 px-3 bg-white border"
                        required
                    >
                        <option value="">-- Pilih Mahasiswa --</option>
                        {#each data.mahasiswa as m}
                            <option value={m.nim}>
                                {m.nim} - {m.nama_mahasiswa}
                            </option>
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
                        <option value="">-- Pilih Mata Kuliah --</option>
                        {#each data.matakuliah as mk}
                            <option value={mk.kode_mk}>
                                {mk.kode_mk} - {mk.nama_mk}
                            </option>
                        {/each}
                    </select>
                </div>

                <div>
                    <label
                        for="nilai_huruf"
                        class="block text-sm font-medium text-gray-700 mb-2"
                        >Nilai Huruf</label
                    >
                    <input
                        type="text"
                        id="nilai_huruf"
                        name="nilai_huruf"
                        bind:value={nilai_huruf}
                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50 py-2 px-3 border"
                        placeholder="Contoh: A, B, C"
                        required
                    />
                    <p class="mt-1 text-sm text-gray-500">
                        Masukkan nilai A, B, C, D, atau E.
                    </p>
                </div>

                <div class="flex items-center space-x-4 pt-4">
                    <button
                        type="submit"
                        class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-colors duration-200"
                    >
                        Simpan Nilai
                    </button>
                    <a
                        href="/nilai"
                        class="w-full flex justify-center py-2 px-4 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 transition-colors duration-200"
                    >
                        Kembali
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
