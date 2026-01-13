<script lang="ts">
	import { enhance } from "$app/forms";
	import type { PageData } from "./$types";

	let { data }: { data: PageData } = $props();
	// derived or inline calc
	const limit = 20;
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
				<a
					class="hover:text-primary transition-colors text-slate-500"
					href="/">Dashboard</a
				>
				<a
					class="hover:text-primary transition-colors text-slate-500"
					href="/mahasiswa">Mahasiswa</a
				>
				<a
					class="hover:text-primary transition-colors text-slate-500"
					href="/matakuliah">Mata Kuliah</a
				>
				{#if data.user?.role === "admin"}
					<a
						class="text-primary border-b-2 border-primary"
						href="/dosen">Dosen</a
					>
				{/if}
			</div>
		</div>
		<div class="flex items-center space-x-4">
			<div
				class="bg-slate-900 dark:bg-white text-white dark:text-slate-900 px-4 py-2 font-display text-xs tracking-widest uppercase flex items-center"
			>
				<span class="material-symbols-outlined text-sm mr-2"
					>person</span
				>
				Hi, {data.user?.nama_lengkap ?? "User"}
			</div>
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

<main class="relative z-10 max-w-7xl mx-auto p-6 md:p-12">
	<div
		class="flex flex-col md:flex-row md:items-end justify-between mb-12 gap-8"
	>
		<div>
			<div class="flex items-center mb-2">
				<span class="w-12 h-1 bg-primary mr-4"></span>
				<span
					class="text-xs font-bold tracking-[0.3em] text-slate-500 uppercase"
					>System_Database / Faculty</span
				>
			</div>
			<h1
				class="font-display text-6xl md:text-8xl leading-none text-slate-900 dark:text-white uppercase tracking-tighter"
			>
				Daftar<br />
				<span class="text-primary">Dosen</span>
			</h1>
		</div>
		<div class="flex flex-col sm:flex-row gap-4">
			<a
				href="/dosen/create"
				class="bg-primary text-white font-display text-sm px-8 py-4 border-4 border-slate-900 dark:border-white hover:bg-orange-600 transition-all flex items-center justify-center group"
			>
				<span
					class="material-symbols-outlined mr-2 group-hover:rotate-90 transition-transform"
					>add</span
				>
				TAMBAH DOSEN
			</a>
			<a
				href="/"
				class="bg-slate-900 dark:bg-white text-white dark:text-slate-900 font-display text-sm px-8 py-4 border-4 border-slate-900 dark:border-white hover:opacity-80 transition-all flex items-center justify-center"
			>
				<span class="material-symbols-outlined mr-2">arrow_back</span>
				KEMBALI KE DASHBOARD
			</a>
		</div>
	</div>

	<div class="relative">
		<div
			class="absolute -top-4 -left-4 w-12 h-12 border-t-4 border-l-4 border-primary z-0"
		></div>
		<div
			class="absolute -bottom-4 -right-4 w-12 h-12 border-b-4 border-r-4 border-slate-900 dark:border-white z-0"
		></div>

		<div
			class="bg-white/90 dark:bg-surface-dark/95 backdrop-blur-sm shadow-2xl relative z-10 border-l-8 border-l-primary border-b-4 border-slate-700 border-r-2 border-t-2 overflow-hidden"
		>
			<div class="overflow-x-auto">
				<table class="w-full text-left border-collapse">
					<thead>
						<tr
							class="bg-slate-700 text-white font-display text-xs tracking-[0.2em] uppercase clip-path-header"
						>
							<th class="p-6">NIP</th>
							<th class="p-6">Nama Dosen</th>
							<th class="p-6">Email</th>
							<th class="p-6 text-center">Aksi</th>
						</tr>
					</thead>
					<tbody
						class="divide-y-2 divide-slate-200 dark:divide-slate-700 font-mono text-sm"
					>
						{#if data.dosen && data.dosen.length > 0}
							{#each data.dosen as d}
								<tr
									class="hover:bg-slate-100 dark:hover:bg-slate-800 transition-colors"
								>
									<td class="p-6 font-bold text-primary"
										>{d.nip}</td
									>
									<td
										class="p-6 font-display text-base uppercase tracking-tight text-slate-900 dark:text-white"
										>{d.nama_dosen}</td
									>
									<td
										class="p-6 text-slate-600 dark:text-slate-400"
										>{d.email || "-"}</td
									>
									<td class="p-6">
										<div
											class="flex justify-center space-x-2"
										>
											<a
												href="/dosen/{d.nip}/edit"
												class="p-2 border-2 border-slate-900 dark:border-white hover:bg-primary hover:text-white transition-all"
											>
												<span
													class="material-symbols-outlined text-sm"
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
													name="nip"
													value={d.nip}
												/>
												<button
													type="submit"
													class="p-2 border-2 border-slate-900 dark:border-white hover:bg-red-600 hover:text-white transition-all"
													onclick={(e) => {
														if (
															!confirm(
																"PERINGATAN: Menghapus dosen akan berdampak pada data terkait (Mahasiswa Wali, Pengampu, dll). Lanjutkan?",
															)
														)
															e.preventDefault();
													}}
												>
													<span
														class="material-symbols-outlined text-sm"
														>delete</span
													>
												</button>
											</form>
										</div>
									</td>
								</tr>
							{/each}
						{:else}
							<tr class="opacity-40 italic">
								<td
									class="p-6 text-center text-xs tracking-widest"
									colspan="4"
								>
									// NO_FURTHER_RECORDS_DETECTED //
								</td>
							</tr>
						{/if}
					</tbody>
				</table>
			</div>
		</div>
	</div>

	<!-- Pagination Actions (Preserved functionality, styled) -->
	{#if Math.ceil(data.count / limit) > 1}
		<div
			class="mt-8 flex justify-center gap-4 font-display text-xs uppercase tracking-widest"
		>
			{#if data.page > 1}
				<a
					href="?page={data.page - 1}"
					class="px-6 py-3 border-2 border-slate-900 dark:border-white hover:bg-slate-900 hover:text-white dark:hover:bg-white dark:hover:text-slate-900 transition-colors"
				>
					&lt; PREV
				</a>
			{/if}
			<span
				class="px-6 py-3 bg-slate-200 dark:bg-slate-800 text-slate-500"
			>
				PAGE {data.page} / {Math.ceil(data.count / limit)}
			</span>
			{#if data.page < Math.ceil(data.count / limit)}
				<a
					href="?page={data.page + 1}"
					class="px-6 py-3 border-2 border-slate-900 dark:border-white hover:bg-slate-900 hover:text-white dark:hover:bg-white dark:hover:text-slate-900 transition-colors"
				>
					NEXT &gt;
				</a>
			{/if}
		</div>
	{/if}

	<section
		class="mt-16 bg-slate-900 text-white p-8 border-4 border-slate-800 dark:border-slate-600 relative overflow-hidden"
	>
		<div
			class="absolute top-0 right-0 p-4 font-display text-[8px] opacity-20 uppercase"
		>
			Core_System_Control
		</div>
		<h3
			class="font-display text-lg tracking-widest uppercase mb-6 flex items-center"
		>
			<span class="w-3 h-3 bg-primary mr-3 animate-pulse"></span>
			Technical Logs
		</h3>
		<div
			class="grid grid-cols-1 md:grid-cols-3 gap-8 font-mono text-[10px] tracking-wider uppercase text-slate-400"
		>
			<div class="space-y-2 border-l border-slate-700 pl-4">
				<p class="text-white">[ STATUS ] ONLINE</p>
				<p>[ NODE ] 192.168.1.104</p>
				<p>[ LATENCY ] 14MS</p>
			</div>
			<div class="space-y-2 border-l border-slate-700 pl-4">
				<p class="text-white">[ SESSION ] 8X-992-AZ</p>
				<p>[ AUTH ] GRANTED_LVL_4</p>
				<p>[ KEY ] ************FA92</p>
			</div>
			<div class="space-y-2 border-l border-slate-700 pl-4">
				<p class="text-white">
					[ LAST_SYNC ] {new Date().toLocaleTimeString("en-US", {
						hour12: false,
					})}
				</p>
				<p>[ BUFFER ] 0.002%</p>
				<p>[ ENCRYPT ] AES-256</p>
			</div>
		</div>
	</section>
</main>

<style>
	.clip-path-header {
		clip-path: polygon(0 0, 100% 0, 99% 100%, 0% 100%);
	}
</style>
