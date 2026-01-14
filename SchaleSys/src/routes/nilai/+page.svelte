<script lang="ts">
	import type { PageData } from './$types';

	let { data }: { data: PageData } = $props();
</script>

<div class="relative z-10 max-w-[1440px] mx-auto px-4 md:px-6 py-8 md:py-12">
    <div class="flex flex-col md:flex-row md:items-end justify-between mb-8 gap-4">
        <div>
            <h1 class="font-display text-4xl md:text-5xl font-bold text-gray-900 dark:text-white tracking-tight mb-2">
                Transkrip <span class="text-primary">Nilai</span>
            </h1>
            <p class="text-gray-500 text-sm md:text-base max-w-2xl">
                Rekapitulasi pencapaian akademik mahasiswa.
            </p>
        </div>
        {#if data.user?.role === 'admin' || data.user?.role === 'dosen'}
            <a href="/nilai/input" class="bg-primary text-white font-medium text-sm px-6 py-3 rounded-xl shadow-lg hover:bg-orange-600 transition-all flex items-center justify-center group">
                <span class="material-symbols-outlined mr-2 text-lg group-hover:rotate-90 transition-transform">add</span>
                Input Nilai
            </a>
        {/if}
    </div>

    {#if data.error}
         <div class="bg-red-50 border-l-4 border-red-500 p-4 mb-6 rounded-r-lg shadow-sm">
            <div class="flex">
                <div class="flex-shrink-0">
                    <span class="material-symbols-outlined text-red-500">error</span>
                </div>
                <div class="ml-3">
                    <p class="text-sm text-red-700">{data.error}</p>
                </div>
            </div>
        </div>
    {/if}

    {#if data.targetStudent}
    <div class="bg-white/80 dark:bg-gray-800/80 backdrop-blur-md rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden mb-8">
        <div class="p-8">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div>
                    <h3 class="text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-widest mb-1">NIM</h3>
                    <p class="text-lg font-mono font-bold text-gray-900 dark:text-white">{data.targetStudent.nim}</p>
                </div>
                <div>
                    <h3 class="text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-widest mb-1">Nama Mahasiswa</h3>
                    <p class="text-lg font-display font-bold text-gray-900 dark:text-white uppercase">{data.targetStudent.nama_mahasiswa}</p>
                </div>
                <div>
                    <h3 class="text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-widest mb-1">Program Studi</h3>
                    <p class="text-lg font-medium text-gray-900 dark:text-white">{data.targetStudent.prodi}</p>
                </div>
            </div>
        </div>
    </div>

	<div class="bg-white/90 dark:bg-gray-800/90 backdrop-blur-md rounded-2xl shadow-lg border border-gray-100 dark:border-gray-700 overflow-hidden mb-6 relative z-10">
		<div class="overflow-x-auto">
			<table class="w-full text-left border-collapse">
				<thead>
					<tr class="bg-slate-900 text-white">
						<th class="p-4 text-xs font-bold uppercase tracking-widest">Kode MK</th>
						<th class="p-4 text-xs font-bold uppercase tracking-widest">Nama Mata Kuliah</th>
						<th class="p-4 text-center text-xs font-bold uppercase tracking-widest w-16">SKS</th>
						<th class="p-4 text-center text-xs font-bold uppercase tracking-widest w-16">Nilai</th>
						<th class="p-4 text-center text-xs font-bold uppercase tracking-widest w-16">Bobot</th>
						<th class="p-4 text-center text-xs font-bold uppercase tracking-widest w-24">K x B</th>
					</tr>
				</thead>
				<tbody class="divide-y divide-gray-100 dark:divide-gray-700/50">
					{#if data.grades && data.grades.length > 0}
						{#each data.grades as g}
							<tr class="hover:bg-gray-50 dark:hover:bg-gray-700/30 transition-colors">
								<td class="p-4 text-sm font-mono text-primary font-bold">{g.kode_mk}</td>
								<td class="p-4 text-sm font-medium text-gray-900 dark:text-white uppercase">{g.nama_mk}</td>
								<td class="p-4 text-center text-sm text-gray-500 dark:text-gray-400 font-bold">{g.sks}</td>
								<td class="p-4 text-center">
                                    <span class="inline-flex items-center justify-center w-8 h-8 rounded-full text-sm font-bold {
                                        g.nilai_huruf === 'A' ? 'bg-green-100 text-green-700' : 
                                        (g.nilai_huruf === 'B' ? 'bg-blue-100 text-blue-700' : 
                                        (g.nilai_huruf === 'C' ? 'bg-yellow-100 text-yellow-700' : 'bg-red-100 text-red-700'))
                                    }">
                                        {g.nilai_huruf}
                                    </span>
                                </td>
								<td class="p-4 text-center text-sm text-gray-500 dark:text-gray-400">{g.bobot}</td>
								<td class="p-4 text-center text-sm font-bold text-gray-900 dark:text-white">{g.total_bobot}</td>
							</tr>
						{/each}
					{:else}
						<tr>
							<td colspan="6" class="p-12 text-center text-gray-500 dark:text-gray-400 italic">
                                <div class="flex flex-col items-center justify-center">
                                    <span class="material-symbols-outlined text-3xl mb-2 opacity-50">grade</span>
                                    Belum ada data nilai.
                                </div>
                            </td>
						</tr>
					{/if}
				</tbody>
				<tfoot class="bg-gray-50 dark:bg-gray-700/30 font-bold">
					<tr>
						<td colspan="2" class="p-4 text-right text-gray-900 dark:text-white uppercase tracking-wider text-sm">Total</td>
						<td class="p-4 text-center text-primary">{data.totalSks}</td>
						<td colspan="2"></td>
						<td class="p-4 text-center text-primary">{data.totalBobot}</td>
					</tr>
				</tfoot>
			</table>
		</div>
	</div>

	<div class="bg-slate-900 text-white rounded-2xl p-8 shadow-xl flex flex-col md:flex-row justify-between items-center border-4 border-slate-800 relative overflow-hidden">
        <div class="relative z-10">
            <h3 class="text-xl font-display font-bold uppercase tracking-widest mb-2">Indeks Prestasi Kumulatif</h3>
            <p class="text-slate-400 text-sm">Akumulasi performa akademik keseluruhan.</p>
        </div>
		<div class="text-right relative z-10 mt-4 md:mt-0">
			<span class="block text-6xl font-display font-bold text-primary tracking-tighter">{(data.ipk || 0).toFixed(2)}</span>
            <span class="text-xs font-mono text-slate-500 uppercase tracking-widest">/ 4.00 SCALE</span>
		</div>
        
        <!-- Decorative bg -->
        <div class="absolute right-0 top-0 h-full w-1/2 bg-gradient-to-l from-primary/10 to-transparent pointer-events-none"></div>
	</div>
    {:else if data.user?.role !== 'mahasiswa'}
        <div class="text-center py-20 bg-gray-50 dark:bg-gray-800/50 rounded-2xl border-2 border-dashed border-gray-200 dark:border-gray-700">
            <span class="material-symbols-outlined text-6xl text-gray-300 mb-4">search</span>
            <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-2">Cari Data Mahasiswa</h3>
            <p class="text-gray-500 dark:text-gray-400 mb-6 max-w-md mx-auto">Masukkan Nomor Induk Mahasiswa (NIM) untuk melihat transkrip nilai.</p>
             <form action="/nilai" method="GET" class="flex justify-center gap-2 max-w-sm mx-auto">
                <input type="text" name="nim" placeholder="Masukkan NIM..." class="flex-1 bg-white dark:bg-gray-900 border border-gray-300 dark:border-gray-600 rounded-xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-primary" required />
                <button type="submit" class="bg-primary text-white px-6 py-3 rounded-xl font-bold hover:bg-orange-600 transition-colors shadow-lg">Cari</button>
            </form>
        </div>
    {/if}
</div>
