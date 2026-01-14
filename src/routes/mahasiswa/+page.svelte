<script lang="ts">
	import { enhance } from "$app/forms";
	import type { PageData } from "./$types";

	let { data }: { data: PageData } = $props();

	// Simple pagination logic using derived for Svelte 5 (or just simple calc if static enough)
	// But page navig causes reload, so these are fine as simple consts if derived from data which changes?
	// Wait, in Svelte 5 `data` is reactive. `const limit = 20` is fine.
	// `const totalPages = Math.ceil(data.count / 20)` should either be a function or directly in template or $derived.
	// I will use direct calculation in template or a getter for simplicity.
	const limit = 20;
</script>

<div class="relative z-10 max-w-[1440px] mx-auto px-4 md:px-6 py-8 md:py-12">
	<div
		class="flex flex-col md:flex-row md:items-end justify-between mb-8 gap-4"
	>
		<div>
			<h1
				class="font-display text-4xl md:text-5xl font-bold text-gray-900 dark:text-white tracking-tight mb-2"
			>
				Daftar <span class="text-primary">Mahasiswa</span>
			</h1>
			<p class="text-gray-500 text-sm md:text-base max-w-2xl">
				Manajemen data mahasiswa, fakultas, dan program studi.
			</p>
		</div>
		<div class="flex flex-col sm:flex-row gap-3">
			{#if data.user?.role === "admin"}
				<a
					href="/mahasiswa/create"
					class="bg-primary text-white font-medium text-sm px-6 py-3 rounded-xl shadow-lg hover:bg-orange-600 transition-all flex items-center justify-center group"
				>
					<span
						class="material-symbols-outlined mr-2 text-lg group-hover:rotate-90 transition-transform"
						>add</span
					>
					Tambah Mahasiswa
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
						<th class="p-6">Foto</th>
						<th class="p-6">NIM</th>
						<th class="p-6">Nama Lengkap</th>
						<th class="p-6">Fakultas</th>
						<th class="p-6">Prodi</th>
						<th class="p-6">Angkatan</th>
						<th class="p-6 text-center">Aksi</th>
					</tr>
				</thead>
				<tbody
					class="divide-y divide-gray-100 dark:divide-gray-700/50 text-sm"
				>
					{#if data.mahasiswa && data.mahasiswa.length > 0}
						{#each data.mahasiswa as m}
							<tr
								class="hover:bg-gray-50 dark:hover:bg-gray-700/30 transition-colors group"
							>
								<td class="p-6">
									<div
										class="w-12 h-12 bg-gray-100 dark:bg-gray-700 rounded-full border border-gray-200 dark:border-gray-600 flex items-center justify-center overflow-hidden"
									>
										{#if m.foto}
											<img
												src={m.foto}
												alt={m.nama_mahasiswa}
												class="w-full h-full object-cover"
											/>
										{:else}
											<span
												class="material-symbols-outlined text-gray-400"
												>person</span
											>
										{/if}
									</div>
								</td>
								<td class="p-6 font-mono font-bold text-primary"
									>{m.nim}</td
								>
								<td
									class="p-6 font-medium text-gray-900 dark:text-white"
									>{m.nama_mahasiswa}</td
								>
								<td
									class="p-6 text-gray-500 dark:text-gray-400"
									>{m.fakultas}</td
								>
								<td
									class="p-6 text-gray-500 dark:text-gray-400"
									>{m.prodi}</td
								>
								<td
									class="p-6 text-gray-500 dark:text-gray-400"
									>{m.angkatan}</td
								>
								<td class="p-6">
									{#if data.user?.role === "admin"}
										<div
											class="flex justify-center items-center space-x-2 opacity-0 group-hover:opacity-100 transition-opacity"
										>
											<a
												href="/mahasiswa/{m.nim}/edit"
												class="p-2 text-gray-400 hover:text-blue-600 hover:bg-blue-50 dark:hover:bg-blue-900/20 rounded-lg transition-colors"
												title="Edit"
											>
												<span class="material-symbols-outlined text-lg">edit</span>
											</a>
											<form
												action="?/delete"
												method="POST"
												class="inline"
												use:enhance
											>
												<input
													type="hidden"
													name="nim"
													value={m.nim}
												/>
												<button
													type="submit"
													class="p-2 text-gray-400 hover:text-red-600 hover:bg-red-50 dark:hover:bg-red-900/20 rounded-lg transition-colors"
													title="Delete"
													onclick={(e) => {
														if (
															!confirm(
																"Hapus data mahasiswa ini?",
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
									<span class="material-symbols-outlined text-4xl text-gray-300 mb-2">face_retouching_off</span>
									<p>Tidak ada data mahasiswa ditemukan.</p>
								</div>
							</td>
						</tr>
					{/if}
				</tbody>
			</table>
		</div>
	</div>

	<!-- Pagination Actions -->
	{#if Math.ceil(data.count / limit) > 1}
		<div
			class="mt-8 flex justify-center gap-2"
		>
			<button
				disabled={data.page <= 1}
				class="px-4 py-2 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg text-sm font-medium text-gray-600 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 disabled:opacity-50 disabled:cursor-not-allowed transition-colors"
				onclick={() => window.location.href = `?page=${data.page - 1}`}
			>
				Previous
			</button>
			<span
				class="px-4 py-2 bg-gray-100 dark:bg-gray-800/50 rounded-lg text-sm font-medium text-gray-600 dark:text-gray-400"
			>
				Page {data.page} of {Math.ceil(data.count / limit)}
			</span>
			<button
				disabled={data.page >= Math.ceil(data.count / limit)}
				class="px-4 py-2 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg text-sm font-medium text-gray-600 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 disabled:opacity-50 disabled:cursor-not-allowed transition-colors"
				onclick={() => window.location.href = `?page=${data.page + 1}`}
			>
				Next
			</button>
		</div>
	{/if}
</div>


