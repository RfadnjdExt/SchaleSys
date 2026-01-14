<script lang="ts">
	import { enhance } from "$app/forms";
	import type { ActionData, PageData } from "./$types";

	export let data: PageData;
	export let form: ActionData;

	$: dosenList = data.dosenList;
	$: mahasiswa = data.mahasiswa;

	// Initialize local state
	let nim = mahasiswa.nim; // Readonly
	let nama_mahasiswa = mahasiswa.nama_mahasiswa;
	let fakultas = mahasiswa.fakultas;
	let prodi = mahasiswa.prodi;
	let angkatan = mahasiswa.angkatan;
	let foto = mahasiswa.foto || "";
	let dosen_wali_id = mahasiswa.dosen_wali_id;
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
				<span class="text-slate-500">System / Students / Edit</span>
			</div>
		</div>
		<div class="flex items-center space-x-4">
			<a
				href="/mahasiswa"
				class="bg-slate-900 dark:bg-white text-white dark:text-slate-900 px-6 py-2 font-display text-xs tracking-widest uppercase hover:opacity-80 transition-opacity"
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

<div class="max-w-4xl mx-auto px-4 py-12 relative z-10">
	<div class="mb-8">
		<h1
			class="font-display text-5xl text-slate-900 dark:text-white uppercase tracking-tighter leading-none"
		>
			Update<br />
			<span class="text-primary">Data Entry</span>
		</h1>
	</div>

	<div
		class="bg-white dark:bg-slate-900 border-4 border-slate-900 dark:border-white relative"
	>
		<!-- Deco squares -->
		<div
			class="absolute -top-3 -left-3 w-6 h-6 bg-primary border-2 border-slate-900 dark:border-white"
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
				Edit Data Mahasiswa
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

			<form method="POST" class="space-y-6" use:enhance>
				<div>
					<label
						for="nim"
						class="block font-display text-xs tracking-widest text-slate-500 uppercase mb-2"
					>
						NIM (Locked)
					</label>
					<input
						type="text"
						id="nim"
						value={nim}
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
						for="nama_mahasiswa"
						class="block font-display text-xs tracking-widest text-slate-500 uppercase mb-2"
					>
						Nama Lengkap
					</label>
					<input
						type="text"
						id="nama_mahasiswa"
						name="nama_mahasiswa"
						bind:value={nama_mahasiswa}
						class="w-full bg-slate-50 dark:bg-slate-800 border-2 border-slate-300 dark:border-slate-600 focus:border-primary dark:focus:border-primary p-4 font-mono text-slate-900 dark:text-white outline-none transition-colors"
						required
					/>
				</div>

				<div class="grid grid-cols-1 md:grid-cols-2 gap-6">
					<div>
						<label
							for="fakultas"
							class="block font-display text-xs tracking-widest text-slate-500 uppercase mb-2"
						>
							Fakultas
						</label>
						<input
							type="text"
							id="fakultas"
							name="fakultas"
							bind:value={fakultas}
							class="w-full bg-slate-50 dark:bg-slate-800 border-2 border-slate-300 dark:border-slate-600 focus:border-primary dark:focus:border-primary p-4 font-mono text-slate-900 dark:text-white outline-none transition-colors"
							required
						/>
					</div>
					<div>
						<label
							for="prodi"
							class="block font-display text-xs tracking-widest text-slate-500 uppercase mb-2"
						>
							Program Studi
						</label>
						<input
							type="text"
							id="prodi"
							name="prodi"
							bind:value={prodi}
							class="w-full bg-slate-50 dark:bg-slate-800 border-2 border-slate-300 dark:border-slate-600 focus:border-primary dark:focus:border-primary p-4 font-mono text-slate-900 dark:text-white outline-none transition-colors"
							required
						/>
					</div>
				</div>

				<div>
					<label
						for="angkatan"
						class="block font-display text-xs tracking-widest text-slate-500 uppercase mb-2"
					>
						Angkatan
					</label>
					<input
						type="number"
						id="angkatan"
						name="angkatan"
						bind:value={angkatan}
						class="w-full bg-slate-50 dark:bg-slate-800 border-2 border-slate-300 dark:border-slate-600 focus:border-primary dark:focus:border-primary p-4 font-mono text-slate-900 dark:text-white outline-none transition-colors"
						min="2000"
						required
					/>
				</div>

				<div>
					<label
						for="foto"
						class="block font-display text-xs tracking-widest text-slate-500 uppercase mb-2"
					>
						URL Foto (Opsional)
					</label>
					<input
						type="text"
						id="foto"
						name="foto"
						bind:value={foto}
						class="w-full bg-slate-50 dark:bg-slate-800 border-2 border-slate-300 dark:border-slate-600 focus:border-primary dark:focus:border-primary p-4 font-mono text-slate-900 dark:text-white outline-none transition-colors"
						placeholder="https://..."
					/>
				</div>

				<div>
					<label
						for="dosen_wali_id"
						class="block font-display text-xs tracking-widest text-slate-500 uppercase mb-2"
					>
						Pilih Dosen Wali (Opsional)
					</label>
					<div class="relative">
						<select
							id="dosen_wali_id"
							name="dosen_wali_id"
							bind:value={dosen_wali_id}
							class="w-full bg-slate-50 dark:bg-slate-800 border-2 border-slate-300 dark:border-slate-600 focus:border-primary dark:focus:border-primary p-4 font-mono text-slate-900 dark:text-white outline-none transition-colors appearance-none"
						>
							<option value="">-- NO_SELECTION --</option>
							{#each dosenList as dosen}
								<option value={dosen.nip}>
									{dosen.nama_dosen}
								</option>
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
