<script lang="ts">
	import type { PageData } from "./$types";

	let { data }: { data: PageData } = $props();
</script>

<div
	class="fixed inset-0 opacity-[0.08] pointer-events-none z-50 bg-noise mix-blend-overlay"
></div>
<div class="fixed inset-0 z-0 overflow-hidden pointer-events-none">
	<div
		class="absolute -top-[10%] -left-[10%] w-[70%] h-[120%] bg-slate-300 dark:bg-slate-800 transform -rotate-6 origin-top-left mix-blend-multiply dark:mix-blend-overlay opacity-30"
	></div>
	<div
		class="absolute top-[40%] right-[-5%] w-[40%] h-[2px] bg-primary transform rotate-12"
	></div>
	<div
		class="absolute bottom-[-10%] right-[-10%] w-[60%] h-[60%] bg-gray-300 dark:bg-gray-800 transform skew-x-6 translate-y-20 mix-blend-multiply dark:mix-blend-normal opacity-20"
	></div>
</div>

<nav
	class="relative z-20 border-b-4 border-slate-900 dark:border-white bg-surface-light/80 dark:bg-surface-dark/80 backdrop-blur-md"
>
	<div
		class="max-w-[1440px] mx-auto px-6 h-24 flex items-center justify-between"
	>
		<div class="flex items-center space-x-12">
			<div class="flex items-center space-x-3 group cursor-pointer">
				<div class="relative w-10 h-10">
					<span
						class="material-symbols-outlined text-4xl text-slate-800 dark:text-white absolute top-0 left-0"
						>school</span
					>
					<span
						class="material-symbols-outlined text-4xl text-primary absolute top-0 left-0 opacity-50 translate-x-1 translate-y-1"
						>school</span
					>
				</div>
				<span
					class="font-display text-2xl tracking-tighter uppercase text-slate-900 dark:text-white"
					>SchaleSys</span
				>
			</div>
			<div
				class="hidden lg:flex space-x-8 font-display text-sm tracking-widest uppercase"
			>
				<a class="text-primary border-b-2 border-primary" href="/"
					>Dashboard</a
				>
				{#if data.user?.role !== "mahasiswa"}
					<a
						class="text-slate-500 hover:text-slate-900 dark:hover:text-white transition-colors"
						href="/mahasiswa">Mahasiswa</a
					>
					<a
						class="text-slate-500 hover:text-slate-900 dark:hover:text-white transition-colors"
						href="/matakuliah">Mata Kuliah</a
					>
					{#if data.user?.role === "admin"}
						<a
							class="text-slate-500 hover:text-slate-900 dark:hover:text-white transition-colors"
							href="/dosen">Dosen</a
						>
					{/if}
				{/if}
				{#if data.user?.role === "mahasiswa"}
					<a
						class="text-slate-500 hover:text-slate-900 dark:hover:text-white transition-colors"
						href="/krs">KRS</a
					>
					<a
						class="text-slate-500 hover:text-slate-900 dark:hover:text-white transition-colors"
						href="/nilai">Transkrip</a
					>
				{/if}
			</div>
		</div>
		<div class="flex items-center space-x-4">
			<div class="text-right hidden sm:block">
				<p class="text-[10px] uppercase font-bold text-slate-500">
					Authenticated As
				</p>
				<p class="font-display text-xs uppercase tracking-tight">
					Hi, {data.user?.nama_lengkap ?? "User"}
				</p>
			</div>
			<div
				class="w-10 h-10 bg-primary flex items-center justify-center text-white font-display rounded-full"
			>
				{data.user?.nama_lengkap?.charAt(0) ?? "U"}
			</div>
			<!-- Logout Form (Hidden but submitted by button if needed, or simple link) -->
			<form action="/logout" method="POST" class="ml-2">
				<button
					type="submit"
					class="material-symbols-outlined text-slate-400 hover:text-red-500 transition-colors"
					title="Logout">logout</button
				>
			</form>
		</div>
	</div>
</nav>

<div class="relative z-10 max-w-[1440px] mx-auto px-6 py-12">
	<header class="mb-16">
		<div class="flex items-baseline space-x-4">
			<div class="w-2 h-12 bg-primary"></div>
			<h1
				class="font-display text-5xl md:text-7xl leading-none text-slate-900 dark:text-white uppercase tracking-tighter"
			>
				Ringkasan<br /><span class="text-primary">Sistem</span>
			</h1>
		</div>
		<p
			class="mt-4 text-slate-500 font-mono text-sm max-w-md border-l-2 border-slate-900 dark:border-white pl-4"
		>
			// SYSTEM_OVERVIEW_MODULE.v2 <br />
			Data real-time dari seluruh entitas akademik yang terdaftar dalam pangkalan
			data.
		</p>
	</header>

	<!-- Stat Cards -->
	<div class="grid grid-cols-1 md:grid-cols-3 gap-8 mb-16">
		<!-- Card 1: Mahasiswa -->
		<div class="group relative">
			<div
				class="absolute inset-0 bg-white dark:bg-gray-800 transform rotate-1 transition-transform group-hover:rotate-0"
			></div>
			<div
				class="relative bg-surface-light dark:bg-surface-dark p-8 border-l-8 border-primary shadow-xl overflow-hidden concrete-texture"
			>
				<div
					class="absolute -right-4 -top-4 opacity-5 group-hover:opacity-10 transition-opacity"
				>
					<span class="material-symbols-outlined text-9xl"
						>groups</span
					>
				</div>
				<h3
					class="font-display text-xs uppercase tracking-[0.2em] text-slate-500 mb-6 flex items-center"
				>
					<span class="w-2 h-2 bg-primary mr-2"></span>
					Total Mahasiswa
				</h3>
				<div class="flex items-baseline space-x-2">
					<span
						class="font-display text-7xl text-slate-900 dark:text-white"
						>{data.stats.total_mahasiswa}</span
					>
					<span
						class="text-xs text-slate-400 uppercase tracking-widest font-bold"
						>Mhs</span
					>
				</div>
				<div
					class="mt-8 pt-4 border-t border-slate-200 dark:border-slate-700 flex justify-between items-center text-[10px] text-slate-400"
				>
					<span>DATA_REF: STUDENT_DB</span>
					<span class="material-symbols-outlined text-sm"
						>trending_flat</span
					>
				</div>
			</div>
		</div>

		<!-- Card 2: Mata Kuliah -->
		<div class="group relative">
			<div
				class="absolute inset-0 bg-white dark:bg-gray-800 transform -rotate-1 transition-transform group-hover:rotate-0"
			></div>
			<div
				class="relative bg-slate-900 dark:bg-slate-900 p-8 border-l-8 border-slate-500 shadow-xl overflow-hidden"
			>
				<div class="absolute -right-4 -top-4 opacity-10">
					<span class="material-symbols-outlined text-9xl text-white"
						>menu_book</span
					>
				</div>
				<h3
					class="font-display text-xs uppercase tracking-[0.2em] text-slate-400 mb-6 flex items-center"
				>
					<span class="w-2 h-2 bg-slate-400 mr-2"></span>
					Total Mata Kuliah
				</h3>
				<div class="flex items-baseline space-x-2">
					<span class="font-display text-7xl text-white"
						>{data.stats.total_matkul}</span
					>
					<span
						class="text-xs text-slate-500 uppercase tracking-widest font-bold"
						>Crse</span
					>
				</div>
				<div
					class="mt-8 pt-4 border-t border-slate-800 flex justify-between items-center text-[10px] text-slate-500"
				>
					<span>DATA_REF: COURSE_CATALOG</span>
					<span class="material-symbols-outlined text-sm"
						>trending_flat</span
					>
				</div>
			</div>
		</div>

		<!-- Card 3: Dosen -->
		<div class="group relative">
			<div
				class="absolute inset-0 bg-white dark:bg-gray-800 transform rotate-2 transition-transform group-hover:rotate-0"
			></div>
			<div
				class="relative bg-surface-light dark:bg-surface-dark p-8 border-l-8 border-slate-800 dark:border-white shadow-xl overflow-hidden concrete-texture"
			>
				<div class="absolute -right-4 -top-4 opacity-5">
					<span class="material-symbols-outlined text-9xl"
						>person</span
					>
				</div>
				<h3
					class="font-display text-xs uppercase tracking-[0.2em] text-slate-500 mb-6 flex items-center"
				>
					<span class="w-2 h-2 bg-slate-800 dark:bg-white mr-2"
					></span>
					Total Dosen
				</h3>
				<div class="flex items-baseline space-x-2">
					<span
						class="font-display text-7xl text-slate-900 dark:text-white"
						>{data.stats.total_dosen}</span
					>
					<span
						class="text-xs text-slate-400 uppercase tracking-widest font-bold"
						>Lct</span
					>
				</div>
				<div
					class="mt-8 pt-4 border-t border-slate-200 dark:border-slate-700 flex justify-between items-center text-[10px] text-slate-400"
				>
					<span>DATA_REF: FACULTY_REG</span>
					<span class="material-symbols-outlined text-sm"
						>trending_flat</span
					>
				</div>
			</div>
		</div>
	</div>

	<div class="grid grid-cols-1 lg:grid-cols-3 gap-12">
		<!-- Kinerja Table / Placeholder -->
		<div class="lg:col-span-2 space-y-8">
			<div
				class="flex items-center justify-between border-b-2 border-slate-900 dark:border-white pb-4"
			>
				<h2
					class="font-display text-2xl uppercase tracking-tighter flex items-center"
				>
					<span class="material-symbols-outlined mr-3 text-primary"
						>analytics</span
					>
					Kinerja Akademik Program Studi
				</h2>
				<span class="text-[10px] text-slate-500 uppercase"
					>Status: Live</span
				>
			</div>

			<div class="relative">
				{#if data.kinerja && data.kinerja.length > 0}
					<div
						class="bg-white dark:bg-surface-dark border border-slate-200 dark:border-slate-800"
					>
						<table
							class="w-full text-left text-sm text-gray-500 dark:text-gray-400"
						>
							<thead
								class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400"
							>
								<tr>
									<th scope="col" class="px-6 py-3"
										>Program Studi</th
									>
									<th scope="col" class="px-6 py-3"
										>IPK Rata-Rata</th
									>
								</tr>
							</thead>
							<tbody>
								{#each data.kinerja as item}
									<tr
										class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600"
									>
										<td
											class="px-6 py-4 font-medium text-slate-900 dark:text-white"
										>
											{item.prodi}
										</td>
										<td
											class="px-6 py-4 font-mono font-bold text-primary"
										>
											{item.ipk_rata_rata.toFixed(2)}
										</td>
									</tr>
								{/each}
							</tbody>
						</table>
					</div>
				{:else}
					<!-- Placeholder State if Empty -->
					<div
						class="absolute -inset-2 border-2 border-dashed border-slate-300 dark:border-slate-700 pointer-events-none"
					></div>
					<div
						class="bg-white/50 dark:bg-surface-dark/50 backdrop-blur-sm border border-slate-200 dark:border-slate-800 min-h-[300px] flex items-center justify-center relative"
					>
						<div class="text-center p-8">
							<span
								class="material-symbols-outlined text-6xl text-slate-300 dark:text-slate-700 mb-4 block"
								>folder_off</span
							>
							<p
								class="font-mono text-slate-400 uppercase tracking-widest text-sm"
							>
								[ SYSTEM_MESSAGE: NO_DATA_AVAILABLE ]
							</p>
							<p class="text-xs text-slate-500 mt-2">
								Belum ada data nilai untuk dianalisis.
							</p>
						</div>
						<div class="absolute top-0 right-0 p-2 flex space-x-1">
							<div class="w-1 h-1 bg-primary"></div>
							<div class="w-1 h-1 bg-primary/50"></div>
							<div class="w-1 h-1 bg-primary/20"></div>
						</div>
					</div>
				{/if}
			</div>
		</div>

		<!-- Quick Access -->
		<div class="space-y-8">
			<div
				class="flex items-center border-b-2 border-slate-900 dark:border-white pb-4"
			>
				<h2
					class="font-display text-2xl uppercase tracking-tighter flex items-center"
				>
					<span class="material-symbols-outlined mr-3 text-primary"
						>link</span
					>
					Akses Cepat
				</h2>
			</div>
			<div class="space-y-4">
				<a
					href="/"
					class="group flex items-center justify-between p-6 bg-surface-light dark:bg-surface-dark border-r-4 border-slate-400 hover:border-primary transition-all hover:translate-x-2 concrete-texture"
				>
					<div class="flex items-center space-x-4">
						<span
							class="material-symbols-outlined text-slate-400 group-hover:text-primary transition-colors"
							>home</span
						>
						<span
							class="uppercase text-sm font-bold tracking-widest group-hover:text-primary transition-colors"
							>Halaman Utama</span
						>
					</div>
					<span
						class="material-symbols-outlined opacity-0 group-hover:opacity-100 transition-all -translate-x-2 group-hover:translate-x-0"
						>chevron_right</span
					>
				</a>

				{#if data.user?.role !== "mahasiswa"}
					<a
						href="/nilai/create"
						class="group flex items-center justify-between p-6 bg-surface-light dark:bg-surface-dark border-r-4 border-slate-400 hover:border-primary transition-all hover:translate-x-2 concrete-texture"
					>
						<div class="flex items-center space-x-4">
							<span
								class="material-symbols-outlined text-slate-400 group-hover:text-primary transition-colors"
								>add_box</span
							>
							<span
								class="uppercase text-sm font-bold tracking-widest group-hover:text-primary transition-colors"
								>Tambah Nilai Baru</span
							>
						</div>
						<span
							class="material-symbols-outlined opacity-0 group-hover:opacity-100 transition-all -translate-x-2 group-hover:translate-x-0"
							>chevron_right</span
						>
					</a>
					<a
						href="/mahasiswa"
						class="group flex items-center justify-between p-6 bg-surface-light dark:bg-surface-dark border-r-4 border-slate-400 hover:border-primary transition-all hover:translate-x-2 concrete-texture"
					>
						<div class="flex items-center space-x-4">
							<span
								class="material-symbols-outlined text-slate-400 group-hover:text-primary transition-colors"
								>person_search</span
							>
							<span
								class="uppercase text-sm font-bold tracking-widest group-hover:text-primary transition-colors"
								>Lihat Semua Mahasiswa</span
							>
						</div>
						<span
							class="material-symbols-outlined opacity-0 group-hover:opacity-100 transition-all -translate-x-2 group-hover:translate-x-0"
							>chevron_right</span
						>
					</a>
				{:else}
					<a
						href="/krs"
						class="group flex items-center justify-between p-6 bg-surface-light dark:bg-surface-dark border-r-4 border-slate-400 hover:border-primary transition-all hover:translate-x-2 concrete-texture"
					>
						<div class="flex items-center space-x-4">
							<span
								class="material-symbols-outlined text-slate-400 group-hover:text-primary transition-colors"
								>edit_calendar</span
							>
							<span
								class="uppercase text-sm font-bold tracking-widest group-hover:text-primary transition-colors"
								>Isi KRS</span
							>
						</div>
						<span
							class="material-symbols-outlined opacity-0 group-hover:opacity-100 transition-all -translate-x-2 group-hover:translate-x-0"
							>chevron_right</span
						>
					</a>
					<a
						href="/krs"
						class="group flex items-center justify-between p-6 bg-surface-light dark:bg-surface-dark border-r-4 border-slate-400 hover:border-primary transition-all hover:translate-x-2 concrete-texture"
					>
						<div class="flex items-center space-x-4">
							<span
								class="material-symbols-outlined text-slate-400 group-hover:text-primary transition-colors"
								>visibility</span
							>
							<span
								class="uppercase text-sm font-bold tracking-widest group-hover:text-primary transition-colors"
								>Lihat KRS Saya</span
							>
						</div>
						<span
							class="material-symbols-outlined opacity-0 group-hover:opacity-100 transition-all -translate-x-2 group-hover:translate-x-0"
							>chevron_right</span
						>
					</a>
					<a
						href="/nilai"
						class="group flex items-center justify-between p-6 bg-surface-light dark:bg-surface-dark border-r-4 border-slate-400 hover:border-primary transition-all hover:translate-x-2 concrete-texture"
					>
						<div class="flex items-center space-x-4">
							<span
								class="material-symbols-outlined text-slate-400 group-hover:text-primary transition-colors"
								>history_edu</span
							>
							<span
								class="uppercase text-sm font-bold tracking-widest group-hover:text-primary transition-colors"
								>Transkrip Nilai</span
							>
						</div>
						<span
							class="material-symbols-outlined opacity-0 group-hover:opacity-100 transition-all -translate-x-2 group-hover:translate-x-0"
							>chevron_right</span
						>
					</a>
				{/if}
			</div>

			<div
				class="mt-12 p-6 border border-slate-300 dark:border-slate-700 bg-transparent"
			>
				<div
					class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-2"
				>
					Technical Logs
				</div>
				<div class="space-y-1 font-mono text-[10px] text-slate-500">
					<p>
						SESSION_ID: <span class="text-primary">8X-992-AZ</span>
					</p>
					<p>TIMESTAMP: {new Date().toISOString()}</p>
					<p>IP_ORIGIN: ::1</p>
				</div>
			</div>
		</div>
	</div>
</div>
