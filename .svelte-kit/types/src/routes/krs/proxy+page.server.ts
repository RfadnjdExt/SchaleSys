// @ts-nocheck
import { redirect } from '@sveltejs/kit';
import type { PageServerLoad } from './$types';

export const load = async ({ locals }: Parameters<PageServerLoad>[0]) => {
    if (!locals.user) {
        redirect(303, '/login');
    }

    // Role check: Admin (can view any? later), Dosen (maybe), Mahasiswa (own)
    // For now, assume Mahasiswa view own
    let nim = locals.user.nim;

    // Fallback if admin wants to view specific student (via query param? later)

    if (!nim && locals.user.role === 'mahasiswa') {
        // Should not happen if data integrity is good, but handle safety
        return { error: 'NIM not found for user.' };
    }

    // Get Active Semester
    const { data: semData } = await locals.supabase
        .from('semester_config')
        .select('nama_semester')
        .eq('is_aktif', 1)
        .single();

    const semester_aktif = semData?.nama_semester || 'Unknown';

    let krs = [];
    let totalSks = 0;

    if (nim) {
        const { data } = await locals.supabase
            .from('krs')
            .select(`
                tanggal_ambil,
                mata_kuliah (
                    kode_mk,
                    nama_mk,
                    sks,
                    semester,
                    hari,
                    jam_mulai,
                    jam_selesai,
                    ruangan
                )
            `)
            .eq('nim_mahasiswa', nim)
            .eq('semester', semester_aktif);

        // Flatten data
        if (data) {
            krs = data.map((item: any) => ({
                ...item.mata_kuliah,
                tanggal_ambil: item.tanggal_ambil
            })).sort((a: any, b: any) => {
                if (a.semester !== b.semester) return a.semester - b.semester;
                return a.nama_mk.localeCompare(b.nama_mk);
            });

            totalSks = krs.reduce((sum: number, item: any) => sum + item.sks, 0);
        }
    }

    return {
        krs,
        totalSks,
        semester_aktif,
        user: locals.user
    };
};
