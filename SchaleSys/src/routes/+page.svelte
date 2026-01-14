<script lang="ts">
	import type { PageData } from "./$types";

	let { data }: { data: PageData } = $props();
</script>

<div class="relative z-10 max-w-[1440px] mx-auto px-4 md:px-6 py-8 md:py-12">
	<header class="mb-12">
		<h1
			class="font-display text-4xl md:text-5xl font-bold text-gray-900 dark:text-white tracking-tight mb-4"
		>
			Ringkasan <span class="text-primary">Sistem</span>
		</h1>
		<p class="text-gray-500 text-sm md:text-base max-w-2xl leading-relaxed">
			Data real-time dari seluruh entitas akademik yang terdaftar dalam pangkalan data SchaleSys.
		</p>
	</header>

	<!-- Stat Cards -->
	<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-12">
		<!-- Card 1: Mahasiswa -->
		<div class="group relative bg-white dark:bg-gray-800 rounded-2xl shadow-sm hover:shadow-xl transition-all duration-300 border border-gray-100 dark:border-gray-700 overflow-hidden">
			<div class="p-6 md:p-8">
				<div class="flex justify-between items-start mb-4">
					<div class="p-3 bg-blue-50 dark:bg-blue-900/20 rounded-xl text-blue-600 dark:text-blue-400">
						<span class="material-symbols-outlined text-2xl">groups</span>
					</div>
					<span class="text-xs font-bold text-gray-400 uppercase tracking-wider">Mahasiswa</span>
				</div>
				<div class="flex items-baseline space-x-2">
					<span class="font-display text-4xl md:text-5xl font-bold text-gray-900 dark:text-white">
						{data.stats.total_mahasiswa}
					</span>
					<span class="text-sm text-gray-500 font-medium">Aktif</span>
				</div>
			</div>
			<div class="bg-gray-50 dark:bg-gray-700/30 px-6 md:px-8 py-3 border-t border-gray-100 dark:border-gray-700">
				<div class="flex items-center text-xs text-blue-600 dark:text-blue-400 font-medium cursor-pointer group-hover:translate-x-1 transition-transform">
					Lihat Detail <span class="material-symbols-outlined text-sm ml-1">arrow_forward</span>
				</div>
			</div>
		</div>

		<!-- Card 2: Mata Kuliah -->
		<div class="group relative bg-white dark:bg-gray-800 rounded-2xl shadow-sm hover:shadow-xl transition-all duration-300 border border-gray-100 dark:border-gray-700 overflow-hidden">
			<div class="p-6 md:p-8">
				<div class="flex justify-between items-start mb-4">
					<div class="p-3 bg-purple-50 dark:bg-purple-900/20 rounded-xl text-purple-600 dark:text-purple-400">
						<span class="material-symbols-outlined text-2xl">menu_book</span>
					</div>
					<span class="text-xs font-bold text-gray-400 uppercase tracking-wider">Mata Kuliah</span>
				</div>
				<div class="flex items-baseline space-x-2">
					<span class="font-display text-4xl md:text-5xl font-bold text-gray-900 dark:text-white">
						{data.stats.total_matkul}
					</span>
					<span class="text-sm text-gray-500 font-medium">Terdaftar</span>
				</div>
			</div>
			<div class="bg-gray-50 dark:bg-gray-700/30 px-6 md:px-8 py-3 border-t border-gray-100 dark:border-gray-700">
				<div class="flex items-center text-xs text-purple-600 dark:text-purple-400 font-medium cursor-pointer group-hover:translate-x-1 transition-transform">
					Lihat Katalog <span class="material-symbols-outlined text-sm ml-1">arrow_forward</span>
				</div>
			</div>
		</div>

		<!-- Card 3: Dosen -->
		<div class="group relative bg-white dark:bg-gray-800 rounded-2xl shadow-sm hover:shadow-xl transition-all duration-300 border border-gray-100 dark:border-gray-700 overflow-hidden">
			<div class="p-6 md:p-8">
				<div class="flex justify-between items-start mb-4">
					<div class="p-3 bg-orange-50 dark:bg-orange-900/20 rounded-xl text-orange-600 dark:text-orange-400">
						<span class="material-symbols-outlined text-2xl">person</span>
					</div>
					<span class="text-xs font-bold text-gray-400 uppercase tracking-wider">Dosen</span>
				</div>
				<div class="flex items-baseline space-x-2">
					<span class="font-display text-4xl md:text-5xl font-bold text-gray-900 dark:text-white">
						{data.stats.total_dosen}
					</span>
					<span class="text-sm text-gray-500 font-medium">Pengajar</span>
				</div>
			</div>
			<div class="bg-gray-50 dark:bg-gray-700/30 px-6 md:px-8 py-3 border-t border-gray-100 dark:border-gray-700">
				<div class="flex items-center text-xs text-orange-600 dark:text-orange-400 font-medium cursor-pointer group-hover:translate-x-1 transition-transform">
					Lihat Data <span class="material-symbols-outlined text-sm ml-1">arrow_forward</span>
				</div>
			</div>
		</div>
	</div>

	<div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
		<!-- Kinerja Table -->
		<div class="lg:col-span-2 space-y-6">
			<div class="flex items-center justify-between pb-2 border-b border-gray-200 dark:border-gray-800">
				<h2 class="font-display text-xl font-bold text-gray-900 dark:text-white flex items-center">
					<span class="material-symbols-outlined mr-2 text-primary">analytics</span>
					Kinerja Akademik
				</h2>
			</div>

			<div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden">
				{#if data.kinerja && data.kinerja.length > 0}
					<table class="w-full text-left text-sm text-gray-600 dark:text-gray-400">
						<thead class="bg-gray-50 dark:bg-gray-700 text-xs uppercase font-semibold text-gray-500 dark:text-gray-300">
							<tr>
								<th class="px-6 py-4">Program Studi</th>
								<th class="px-6 py-4 text-right">IPK Rata-Rata</th>
							</tr>
						</thead>
						<tbody class="divide-y divide-gray-100 dark:divide-gray-700">
							{#each data.kinerja as item}
								<tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors">
									<td class="px-6 py-4 font-medium text-gray-900 dark:text-white">{item.prodi}</td>
									<td class="px-6 py-4 text-right font-mono font-bold text-primary">{item.ipk_rata_rata.toFixed(2)}</td>
								</tr>
							{/each}
						</tbody>
					</table>
				{:else}
					<div class="p-12 text-center">
						<span class="material-symbols-outlined text-5xl text-gray-300 mb-3">folder_off</span>
						<p class="text-sm text-gray-500">Belum ada data nilai untuk dianalisis.</p>
					</div>
				{/if}
			</div>
		</div>

		<!-- Quick Access -->
		<div class="space-y-6">
			<div class="flex items-center justify-between pb-2 border-b border-gray-200 dark:border-gray-800">
				<h2 class="font-display text-xl font-bold text-gray-900 dark:text-white flex items-center">
					<span class="material-symbols-outlined mr-2 text-primary">link</span>
					Akses Cepat
				</h2>
			</div>

			<div class="space-y-3">
				{#if data.user?.role !== "mahasiswa"}
					<a href="/nilai/create" class="flex items-center justify-between p-4 bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 hover:border-primary hover:shadow-md transition-all group">
						<div class="flex items-center space-x-3">
							<div class="p-2 bg-green-50 dark:bg-green-900/20 text-green-600 rounded-lg group-hover:bg-green-100 transition-colors">
								<span class="material-symbols-outlined text-xl">add_box</span>
							</div>
							<span class="font-medium text-gray-700 dark:text-gray-200 text-sm group-hover:text-primary transition-colors">Tambah Nilai</span>
						</div>
						<span class="material-symbols-outlined text-gray-400 group-hover:text-primary group-hover:translate-x-1 transition-all text-sm">chevron_right</span>
					</a>
					<a href="/mahasiswa" class="flex items-center justify-between p-4 bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 hover:border-primary hover:shadow-md transition-all group">
						<div class="flex items-center space-x-3">
							<div class="p-2 bg-blue-50 dark:bg-blue-900/20 text-blue-600 rounded-lg group-hover:bg-blue-100 transition-colors">
								<span class="material-symbols-outlined text-xl">person_search</span>
							</div>
							<span class="font-medium text-gray-700 dark:text-gray-200 text-sm group-hover:text-primary transition-colors">Data Mahasiswa</span>
						</div>
						<span class="material-symbols-outlined text-gray-400 group-hover:text-primary group-hover:translate-x-1 transition-all text-sm">chevron_right</span>
					</a>
				{:else}
					<a href="/krs" class="flex items-center justify-between p-4 bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 hover:border-primary hover:shadow-md transition-all group">
						<div class="flex items-center space-x-3">
							<div class="p-2 bg-blue-50 dark:bg-blue-900/20 text-blue-600 rounded-lg group-hover:bg-blue-100 transition-colors">
								<span class="material-symbols-outlined text-xl">edit_calendar</span>
							</div>
							<span class="font-medium text-gray-700 dark:text-gray-200 text-sm group-hover:text-primary transition-colors">Isi KRS</span>
						</div>
						<span class="material-symbols-outlined text-gray-400 group-hover:text-primary group-hover:translate-x-1 transition-all text-sm">chevron_right</span>
					</a>
					<a href="/nilai" class="flex items-center justify-between p-4 bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 hover:border-primary hover:shadow-md transition-all group">
						<div class="flex items-center space-x-3">
							<div class="p-2 bg-purple-50 dark:bg-purple-900/20 text-purple-600 rounded-lg group-hover:bg-purple-100 transition-colors">
								<span class="material-symbols-outlined text-xl">history_edu</span>
							</div>
							<span class="font-medium text-gray-700 dark:text-gray-200 text-sm group-hover:text-primary transition-colors">Transkrip Nilai</span>
						</div>
						<span class="material-symbols-outlined text-gray-400 group-hover:text-primary group-hover:translate-x-1 transition-all text-sm">chevron_right</span>
					</a>
				{/if}
			</div>
		</div>
	</div>
</div>
