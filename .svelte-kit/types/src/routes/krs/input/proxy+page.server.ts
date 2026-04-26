// @ts-nocheck
import { fail, redirect } from '@sveltejs/kit';
import type { Actions, PageServerLoad } from './$types';

export const load = async ({ locals }: Parameters<PageServerLoad>[0]) => {
    if (!locals.user || locals.user.role !== 'mahasiswa') {
        redirect(303, '/krs');
    }

    const nim = locals.user.nim;
    if (!nim) return { error: 'NIM missing' };

    // Get Active Semester
    const { data: semData } = await locals.supabase
        .from('semester_config')
        .select('nama_semester')
        .eq('is_aktif', 1)
        .single();

    const semester_aktif = semData?.nama_semester || 'Unknown';

    // Get All MK
    const { data: mkList } = await locals.supabase
        .from('mata_kuliah')
        .select('*')
        .order('semester')
        .order('nama_mk');

    // Get Taken KRS
    const { data: krsTaken } = await locals.supabase
        .from('krs')
        .select('kode_matkul, mata_kuliah(sks)')
        .eq('nim_mahasiswa', nim)
        .eq('semester', semester_aktif);

    const takenCodes = krsTaken?.map((k: any) => k.kode_matkul) || [];
    const totalSks = krsTaken?.reduce((sum: number, k: any) => sum + k.mata_kuliah.sks, 0) || 0;

    return {
        mkList: mkList || [],
        takenCodes,
        totalSks,
        semester_aktif,
        user: locals.user
    };
};

export const actions = {
    default: async ({ request, locals }: import('./$types').RequestEvent) => {
        if (!locals.user || locals.user.role !== 'mahasiswa') {
            return fail(403, { error: 'Unauthorized' });
        }

        const nim = locals.user.nim;
        if (!nim) return fail(400, { error: 'NIM not associated' });

        const formData = await request.formData();
        const matkul_diambil = formData.getAll('matkul_diambil') as string[];

        // Fetch active semester
        const { data: semData } = await locals.supabase
            .from('semester_config')
            .select('nama_semester')
            .eq('is_aktif', 1)
            .single();
        const semester_aktif = semData?.nama_semester;

        if (!semester_aktif) return fail(500, { error: 'Semester aktif tidak valid.' });

        // Calculate SKS
        // 1. Existing SKS (Wait, we are overwriting? Or just Adding? existing PHP uses INSERT without deleting old, but ignores duplicates)
        // However, standard KRS input usually lets you uncheck to Drop.
        // The PHP code only does INSERT IGNORE. It does NOT delete unchecked items.
        // Wait, carefully check PHP `input_krs.php`.
        // It Insert Loop. It checks total SKS = Existing + New Selected (Wait, logic flaw in PHP maybe? It sums existing THEN adds new selected. If I select ALREADY selected, does it count double? SQL check duplicates prevents insert but SKS check logic might be double counting if not careful. PHP: `SUM(sks) as total_sks ... WHERE nim ...` (Existing). `SELECT SUM(sks) ... WHERE kode_mk IN (selected)` (New).
        // Yes, if I select a course I already have, PHP might double count SKS in check logic, but then DB ignore insert.
        // But SvelteKit approach: Best is to "Sync". But simpler to match PHP: Just add selected.
        // But user experience: usually you want to tick/untick.
        // I will implement "Checkbox State" -> "Sync".
        // DELETE FROM krs WHERE nim AND semester AND kode NOT IN (selected)?
        // Or just Insert New, Delete Missing?

        // Let's stick to strict PHP migration for now: Only ADDING.
        // "Silakan pilih mata kuliah yang akan diambil"
        // But if I want to DROP? PHP `tampil_krs` doesn't have Drop button?
        // PHP `input_krs.php` does NOT seem to allow dropping via uncheck. It only INSERTS.
        // "Berhasil mengambil ... mata kuliah".

        // I will implement safer logic:
        // Filter `matkul_diambil` to only those NOT yet in DB to avoid SKS double counting in validation.

        // 1. Get current taken
        const { data: currentKrs } = await locals.supabase
            .from('krs')
            .select('kode_matkul, mata_kuliah(sks)')
            .eq('nim_mahasiswa', nim)
            .eq('semester', semester_aktif);

        const currentCodes = currentKrs?.map((k: any) => k.kode_matkul) || [];
        const currentSks = currentKrs?.reduce((sum: number, k: any) => sum + k.mata_kuliah.sks, 0) || 0;

        // 2. Identify NEW ones
        const newCodes = matkul_diambil.filter(c => !currentCodes.includes(c));

        if (newCodes.length === 0) {
            return fail(400, { error: 'Tidak ada mata kuliah baru yang dipilih (atau semua sudah diambil).' });
        }

        // 3. Calc New SKS
        const { data: newMkData } = await locals.supabase
            .from('mata_kuliah')
            .select('sks')
            .in('kode_mk', newCodes);

        const newSks = newMkData?.reduce((sum: number, m: any) => sum + m.sks, 0) || 0;

        if (currentSks + newSks > 24) {
            return fail(400, { error: `Total SKS melebihi batas (Maks 24). Saat ini: ${currentSks}, Ditambah: ${newSks}.` });
        }

        // 4. Insert
        const inserts = newCodes.map(code => ({
            nim_mahasiswa: nim,
            kode_matkul: code,
            semester: semester_aktif // using string from config
        }));

        const { error } = await locals.supabase
            .from('krs')
            .insert(inserts);

        if (error) {
            console.error(error);
            return fail(500, { error: 'Gagal menyimpan KRS.' });
        }

        redirect(303, '/krs');
    }
};
;null as any as Actions;