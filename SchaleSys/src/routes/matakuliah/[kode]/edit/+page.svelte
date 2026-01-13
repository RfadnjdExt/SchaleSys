<script lang="ts">
    import { enhance } from "$app/forms";
    import type { ActionData, PageData } from "./$types";

    export let data: PageData;
    export let form: ActionData;

    // Reactive form values
    $: nama_mk = data.mk.nama_mk;
    $: sks = data.mk.sks;
    $: semester = data.mk.semester;
    $: hari = data.mk.hari;
    $: jam_mulai = data.mk.jam_mulai;
    $: jam_selesai = data.mk.jam_selesai;
    $: ruangan = data.mk.ruangan || "";
</script>

<div
    class="fixed inset-0 opacity-[0.08] pointer-events-none z-50 bg-noise mix-blend-overlay"
></div>
<div class="fixed inset-0 z-0 overflow-hidden pointer-events-none">
    <div
        class="absolute -top-[10%] -left-[10%] w-[70%] h-[120%] bg-slate-300 dark:bg-slate-800 transform -rotate-12 origin-top-left mix-blend-multiply dark:mix-blend-overlay opacity-50"
    ></div>
    <div
        class="absolute top-[40%] right-[-5%] w-[30%] h-[2px] bg-primary transform rotate-12"
    ></div>
    <div
        class="absolute bottom-[-10%] right-[-10%] w-[60%] h-[60%] bg-gray-300 dark:bg-gray-800 transform skew-x-12 translate-y-20 mix-blend-multiply dark:mix-blend-normal opacity-40"
    ></div>
</div>

<nav
    class="relative z-20 w-full border-b-4 border-slate-900 dark:border-white bg-surface-light/80 dark:bg-surface-dark/80 backdrop-blur-md"
>
    <div class="max-w-7xl mx-auto px-6 h-20 flex items-center justify-between">
        <div class="flex items-center space-x-8">
            <a
                href="/"
                class="flex items-center space-x-2 hover:opacity-80 transition-opacity"
            >
                <div class="w-8 h-8 bg-primary"></div>
                <span
                    class="font-display text-2xl tracking-tighter uppercase text-slate-900 dark:text-white"
                    >SchaleSys</span
                >
            </a>
            <div
                class="hidden md:flex space-x-6 font-display text-sm tracking-widest uppercase"
            >
                <span class="text-slate-500">System / Courses / Edit</span>
            </div>
        </div>
        <div class="flex items-center space-x-4">
            <a
                href="/matakuliah"
                class="bg-slate-900 dark:bg-white text-white dark:text-slate-900 font-display text-sm px-8 py-4 border-4 border-slate-900 dark:border-white hover:opacity-80 transition-all flex items-center justify-center"
            >
                Back to List
            </a>
            <form action="/logout" method="POST">
                <button
                    type="submit"
                    class="bg-red-600 text-white px-4 py-2 font-display text-xs tracking-widest uppercase flex items-center hover:bg-red-700 transition-colors"
                >
                    <span class="material-symbols-outlined text-sm">logout</span
                    >
                </button>
            </form>
        </div>
    </div>
</nav>

<div class="max-w-5xl mx-auto px-4 py-12 relative z-10">
    <div class="mb-8">
        <h1
            class="font-display text-5xl text-slate-900 dark:text-white uppercase tracking-tighter leading-none"
        >
            Update<br />
            <span class="text-primary">Course Data</span>
        </h1>
    </div>

    <div
        class="bg-white dark:bg-slate-900 border-4 border-slate-900 dark:border-white relative"
    >
        <div
            class="bg-white/90 dark:bg-surface-dark/95 backdrop-blur-sm shadow-2xl relative z-10 border-l-8 border-l-primary border-b-4 border-slate-700 border-r-2 border-t-2 p-8"
        ></div>
        <div
            class="absolute -bottom-3 -right-3 w-6 h-6 bg-slate-900 dark:bg-white border-2 border-slate-900 dark:border-white"
        ></div>

        <div
            class="bg-slate-100 dark:bg-slate-800 px-8 py-4 border-b-4 border-slate-900 dark:border-white relative overflow-hidden"
        >
            <!-- Warning strip for edit mode -->
            <div class="absolute top-0 right-0 p-2 opacity-10">
                <span
                    class="font-display text-[4rem] text-slate-900 dark:text-white font-bold leading-none"
                    >EDIT</span
                >
            </div>
            <h2
                class="font-display text-lg tracking-widest uppercase text-slate-700 dark:text-slate-300 flex items-center relative z-10"
            >
                <span class="material-symbols-outlined mr-3">edit_note</span>
                Edit Data Mata Kuliah
            </h2>
        </div>

        <div class="p-8">
            {#if form?.error}
                <div
                    class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6 font-mono text-sm"
                    role="alert"
                >
                    <p class="font-bold">ERROR_DETECTED:</p>
                    <p>{form.error}</p>
                </div>
            {/if}

            <form method="POST" class="space-y-8" use:enhance>
                <!-- Section 1 -->
                <div
                    class="border-2 border-slate-200 dark:border-slate-700 p-6 relative"
                >
                    <div
                        class="absolute -top-3 left-4 bg-white dark:bg-slate-900 px-2 font-display text-xs tracking-widest text-primary uppercase"
                    >
                        Course Information
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-2">
                        <div>
                            <label
                                for="kode_mk"
                                class="block font-display text-xs tracking-widest text-slate-500 uppercase mb-2"
                            >
                                Kode Mata Kuliah (Locked)
                            </label>
                            <input
                                type="text"
                                id="kode_mk"
                                name="kode_mk"
                                value={data.mk.kode_mk}
                                class="w-full bg-slate-200 dark:bg-slate-700 border-2 border-slate-300 dark:border-slate-600 p-4 font-mono text-slate-500 dark:text-slate-400 outline-none cursor-not-allowed"
                                readonly
                            />
                            <p
                                class="mt-2 font-mono text-[10px] text-primary uppercase tracking-wider"
                            >
                                // FIELD_LOCKED_BY_SYSTEM_ADMIN //
                            </p>
                        </div>
                        <div>
                            <label
                                for="nama_mk"
                                class="block font-display text-xs tracking-widest text-slate-500 uppercase mb-2"
                            >
                                Nama Mata Kuliah
                            </label>
                            <input
                                type="text"
                                id="nama_mk"
                                name="nama_mk"
                                bind:value={nama_mk}
                                class="w-full bg-slate-50 dark:bg-slate-800 border-2 border-slate-300 dark:border-slate-600 focus:border-primary dark:focus:border-primary p-4 font-mono text-slate-900 dark:text-white outline-none transition-colors"
                                required
                            />
                        </div>

                        <div>
                            <label
                                for="sks"
                                class="block font-display text-xs tracking-widest text-slate-500 uppercase mb-2"
                            >
                                SKS
                            </label>
                            <input
                                type="number"
                                id="sks"
                                name="sks"
                                bind:value={sks}
                                class="w-full bg-slate-50 dark:bg-slate-800 border-2 border-slate-300 dark:border-slate-600 focus:border-primary dark:focus:border-primary p-4 font-mono text-slate-900 dark:text-white outline-none transition-colors"
                                min="1"
                                max="6"
                                required
                            />
                        </div>
                        <div>
                            <label
                                for="semester"
                                class="block font-display text-xs tracking-widest text-slate-500 uppercase mb-2"
                            >
                                Semester
                            </label>
                            <input
                                type="number"
                                id="semester"
                                name="semester"
                                bind:value={semester}
                                class="w-full bg-slate-50 dark:bg-slate-800 border-2 border-slate-300 dark:border-slate-600 focus:border-primary dark:focus:border-primary p-4 font-mono text-slate-900 dark:text-white outline-none transition-colors"
                                min="1"
                                max="8"
                                required
                            />
                        </div>
                    </div>
                </div>

                <!-- Section 2 -->
                <div
                    class="border-2 border-slate-200 dark:border-slate-700 p-6 relative"
                >
                    <div
                        class="absolute -top-3 left-4 bg-white dark:bg-slate-900 px-2 font-display text-xs tracking-widest text-primary uppercase"
                    >
                        Schedule Configuration
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mt-2">
                        <div>
                            <label
                                for="hari"
                                class="block font-display text-xs tracking-widest text-slate-500 uppercase mb-2"
                            >
                                Hari
                            </label>
                            <div class="relative">
                                <select
                                    id="hari"
                                    name="hari"
                                    bind:value={hari}
                                    class="w-full bg-slate-50 dark:bg-slate-800 border-2 border-slate-300 dark:border-slate-600 focus:border-primary dark:focus:border-primary p-4 font-mono text-slate-900 dark:text-white outline-none transition-colors appearance-none"
                                    required
                                >
                                    <option value="">-- SELECT_DAY --</option>
                                    {#each ["Senin", "Selasa", "Rabu", "Kamis", "Jumat", "Sabtu"] as day}
                                        <option value={day}>{day}</option>
                                    {/each}
                                </select>
                                <div
                                    class="absolute inset-y-0 right-0 flex items-center px-4 pointer-events-none text-slate-500"
                                >
                                    <span class="material-symbols-outlined"
                                        >expand_more</span
                                    >
                                </div>
                            </div>
                        </div>
                        <div>
                            <label
                                for="jam_mulai"
                                class="block font-display text-xs tracking-widest text-slate-500 uppercase mb-2"
                            >
                                Jam Mulai
                            </label>
                            <input
                                type="time"
                                id="jam_mulai"
                                name="jam_mulai"
                                bind:value={jam_mulai}
                                class="w-full bg-slate-50 dark:bg-slate-800 border-2 border-slate-300 dark:border-slate-600 focus:border-primary dark:focus:border-primary p-4 font-mono text-slate-900 dark:text-white outline-none transition-colors"
                                required
                            />
                        </div>
                        <div>
                            <label
                                for="jam_selesai"
                                class="block font-display text-xs tracking-widest text-slate-500 uppercase mb-2"
                            >
                                Jam Selesai
                            </label>
                            <input
                                type="time"
                                id="jam_selesai"
                                name="jam_selesai"
                                bind:value={jam_selesai}
                                class="w-full bg-slate-50 dark:bg-slate-800 border-2 border-slate-300 dark:border-slate-600 focus:border-primary dark:focus:border-primary p-4 font-mono text-slate-900 dark:text-white outline-none transition-colors"
                                required
                            />
                        </div>
                    </div>

                    <div class="mt-6">
                        <label
                            for="ruangan"
                            class="block font-display text-xs tracking-widest text-slate-500 uppercase mb-2"
                        >
                            Ruangan
                        </label>
                        <input
                            type="text"
                            id="ruangan"
                            name="ruangan"
                            bind:value={ruangan}
                            class="w-full bg-slate-50 dark:bg-slate-800 border-2 border-slate-300 dark:border-slate-600 focus:border-primary dark:focus:border-primary p-4 font-mono text-slate-900 dark:text-white outline-none transition-colors"
                            placeholder="Ex: R.204"
                            required
                        />
                    </div>
                </div>

                <div class="pt-6 flex gap-4">
                    <button
                        type="submit"
                        class="flex-1 bg-primary text-white font-display text-sm px-8 py-4 border-[3px] border-slate-900 dark:border-white hover:bg-orange-600 transition-all uppercase tracking-widest shadow-[4px_4px_0px_0px_rgba(15,23,42,1)] active:translate-y-[2px] active:shadow-none"
                    >
                        Update Data
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
