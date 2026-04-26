import { redirect } from '@sveltejs/kit';
import type { PageServerLoad } from './$types';

export const load: PageServerLoad = async ({ locals }) => {
    if (!locals.user) {
        redirect(303, '/login');
    }

    const { supabase } = locals;

    // 1. Fetch Counts
    const [mhsRes, dosenRes, mkRes] = await Promise.all([
        supabase.from('mahasiswa').select('*', { count: 'exact', head: true }),
        supabase.from('dosen').select('*', { count: 'exact', head: true }),
        supabase.from('mata_kuliah').select('*', { count: 'exact', head: true })
    ]);

    // 2. Fetch Kinerja Data (Mahasiswa -> Nilai -> MK)
    // We need to fetch all students with their grades to calculate GPA
    const { data: mhsData, error: kinerjaError } = await supabase
        .from('mahasiswa')
        .select(`
            prodi,
            nilai (
                nilai_huruf,
                mata_kuliah (
                    sks
                )
            )
        `);

    // 3. Process Kinerja in JS
    const prodiStats: Record<string, { totalIpk: number; count: number }> = {};

    if (mhsData) {
        mhsData.forEach((mhs: any) => {
            const grades = mhs.nilai || [];
            if (grades.length === 0) return;

            let totalSks = 0;
            let totalPoints = 0;

            grades.forEach((n: any) => {
                const sks = n.mata_kuliah?.sks || 0;
                let point = 0;
                switch (n.nilai_huruf) {
                    case 'A': point = 4; break;
                    case 'B': point = 3; break;
                    case 'C': point = 2; break;
                    case 'D': point = 1; break;
                    default: point = 0;
                }
                totalPoints += point * sks;
                totalSks += sks;
            });

            const ipk = totalSks > 0 ? totalPoints / totalSks : 0;

            if (!prodiStats[mhs.prodi]) {
                prodiStats[mhs.prodi] = { totalIpk: 0, count: 0 };
            }
            prodiStats[mhs.prodi].totalIpk += ipk;
            prodiStats[mhs.prodi].count += 1;
        });
    }

    const kinerja = Object.entries(prodiStats).map(([prodi, stats]) => ({
        prodi,
        ipk_rata_rata: stats.totalIpk / stats.count
    })).sort((a, b) => b.ipk_rata_rata - a.ipk_rata_rata);

    return {
        stats: {
            total_mahasiswa: mhsRes.count || 0,
            total_dosen: dosenRes.count || 0,
            total_matkul: mkRes.count || 0
        },
        kinerja,
        user: locals.user
    };
};
