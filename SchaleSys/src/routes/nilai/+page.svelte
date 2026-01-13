<script lang="ts">
	import type { PageData } from './$types';

	let { data }: { data: PageData } = $props();
</script>

<div class="max-w-4xl mx-auto px-4 py-8">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold text-gray-800 flex items-center">
            <span class="mr-3">📜</span> Transkrip Nilai
        </h1>
        {#if data.user?.role === 'admin' || data.user?.role === 'dosen'}
            <a href="/nilai/input" class="bg-blue-600 text-white px-4 py-2 rounded shadow hover:bg-blue-700 transition">
                + Input Nilai
            </a>
        {/if}
    </div>

    {#if data.error}
         <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-6" role="alert">
            <span class="block sm:inline">{data.error}</span>
        </div>
    {/if}

    {#if data.targetStudent}
	<div class="bg-white rounded-xl shadow-lg border border-gray-100 overflow-hidden mb-6">
		<div class="p-6 md:p-8">
			<div class="grid grid-cols-1 md:grid-cols-2 gap-4">
				<div class="flex border-b border-gray-100 pb-2 md:border-b-0 md:pb-0">
					<span class="w-32 font-semibold text-gray-600">NIM</span>
					<span class="text-gray-900">: {data.targetStudent.nim}</span>
				</div>
				<div class="flex border-b border-gray-100 pb-2 md:border-b-0 md:pb-0">
					<span class="w-32 font-semibold text-gray-600">Nama Lengkap</span>
					<span class="text-gray-900">: {data.targetStudent.nama_mahasiswa}</span>
				</div>
				<div class="flex">
					<span class="w-32 font-semibold text-gray-600">Program Studi</span>
					<span class="text-gray-900">: {data.targetStudent.prodi}</span>
				</div>
			</div>
		</div>
	</div>

	<div class="bg-white rounded-xl shadow-lg border border-gray-100 overflow-hidden mb-6">
		<div class="overflow-hidden border-b border-gray-200">
			<table class="min-w-full divide-y divide-gray-200">
				<thead class="bg-gray-800 text-white">
					<tr>
						<th scope="col" class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">Kode MK</th>
						<th scope="col" class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">Nama Mata Kuliah</th>
						<th scope="col" class="px-6 py-3 text-center text-xs font-medium uppercase tracking-wider w-16">SKS</th>
						<th scope="col" class="px-6 py-3 text-center text-xs font-medium uppercase tracking-wider w-16">Nilai</th>
						<th scope="col" class="px-6 py-3 text-center text-xs font-medium uppercase tracking-wider w-16">Bobot</th>
						<th scope="col" class="px-6 py-3 text-center text-xs font-medium uppercase tracking-wider w-24">K x B</th>
					</tr>
				</thead>
				<tbody class="bg-white divide-y divide-gray-200">
					{#if data.grades && data.grades.length > 0}
						{#each data.grades as g}
							<tr class="hover:bg-gray-50 transition-colors">
								<td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 font-mono">{g.kode_mk}</td>
								<td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{g.nama_mk}</td>
								<td class="px-6 py-4 whitespace-nowrap text-center text-sm text-gray-500">{g.sks}</td>
								<td class="px-6 py-4 whitespace-nowrap text-center text-sm font-bold {
                                    g.nilai_huruf === 'A' ? 'text-green-600' : 
                                    (g.nilai_huruf === 'B' ? 'text-blue-600' : 
                                    (g.nilai_huruf === 'C' ? 'text-yellow-600' : 'text-red-600'))
                                }">{g.nilai_huruf}</td>
								<td class="px-6 py-4 whitespace-nowrap text-center text-sm text-gray-500">{g.bobot}</td>
								<td class="px-6 py-4 whitespace-nowrap text-center text-sm font-semibold text-gray-700">{g.total_bobot}</td>
							</tr>
						{/each}
					{:else}
						<tr>
							<td colspan="6" class="px-6 py-8 text-center text-gray-500 italic">Belum ada data nilai.</td>
						</tr>
					{/if}
				</tbody>
				<tfoot class="bg-gray-100">
					<tr>
						<td colspan="2" class="px-6 py-3 text-right text-sm font-bold text-gray-900 uppercase">Total</td>
						<td class="px-6 py-3 text-center text-sm font-bold text-blue-600">{data.totalSks}</td>
						<td colspan="2"></td>
						<td class="px-6 py-3 text-center text-sm font-bold text-blue-600">{data.totalBobot}</td>
					</tr>
				</tfoot>
			</table>
		</div>
	</div>

	<div class="bg-blue-50 border border-blue-200 rounded-xl p-6 shadow-sm flex justify-end">
		<div class="text-right">
			<span class="block text-sm font-medium text-blue-600 uppercase tracking-wider">Indeks Prestasi Kumulatif (IPK)</span>
			<span class="block text-4xl font-extrabold text-blue-800">{(data.ipk || 0).toFixed(2)}</span>
		</div>
	</div>
    {:else if data.user?.role !== 'mahasiswa'}
        <div class="text-center py-12 bg-gray-50 rounded-lg">
            <h3 class="mt-2 text-sm font-medium text-gray-900">Silakan Cari Mahasiswa</h3>
             <form action="/nilai" method="GET" class="mt-4 flex justify-center gap-2">
                <input type="text" name="nim" placeholder="Masukkan NIM..." class="border rounded px-3 py-2" />
                <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Cari</button>
            </form>
        </div>
    {/if}
</div>
