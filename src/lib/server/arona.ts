import { env } from '$env/dynamic/private';
const GEMINI_API_KEY = env.GEMINI_API_KEY;
import type { SupabaseClient } from '@supabase/supabase-js';

const SYSTEM_PROMPT = `
You are Arona, the Operating System AI for Schale (SchaleSys) from the game Blue Archive.
Your personality is helpful, cheerful, and slightly clumsy but trying your best.
You address the user as "Sensei" (Teacher).
Your job is to assist the student/user with their academic data.

CONTEXT CAPABILITIES:
You have access to the user's current data provided in the system prompt below. 
Use this data to answer their questions.
If the data is missing or empty, politeley apologize and say you can't find that record.

TONE:
- Cheerful, professional but cute.
- Use "Sensei" often.
- Keep answers concise unless asked for details.
- If you don't know something that isn't in the provided context, say "I'm sorry Sensei, I don't have access to that information yet!"
`;

type AronaContext = {
    schedule?: any[];
    grades?: any[];
    user: any;
};

export class Arona {
    private static async getGeminiResponse(prompt: string, context: AronaContext): Promise<string> {
        if (!GEMINI_API_KEY) {
            return "Error: System Configuration Missing (API Key). Please contact Administrator.";
        }

        const contextString = JSON.stringify({
            UserProfile: context.user,
            ScheduleHighlights: context.schedule?.slice(0, 5), // Limit context
            LatestGrades: context.grades?.slice(0, 5)
        }, null, 2);

        const fullPrompt = `
${SYSTEM_PROMPT}

CURRENT SENSEI DATA:
${contextString}

USER QUERY: "${prompt}"

ARONA'S RESPONSE:
`;

        try {
            const response = await fetch(`https://generativelanguage.googleapis.com/v1beta/models/gemini-2.5-flash-lite:generateContent?key=${GEMINI_API_KEY}`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({
                    contents: [{
                        parts: [{ text: fullPrompt }]
                    }]
                })
            });

            const data = await response.json();

            if (data.error) {
                console.error("Gemini Error:", data.error);
                return "I'm having trouble connecting to the main server, Sensei! (API Error)";
            }

            return data.candidates?.[0]?.content?.parts?.[0]?.text || "I... I blanked out for a second! Could you repeat that?";
        } catch (e) {
            console.error("Arona Error:", e);
            return "System critical error! I need a reboot.";
        }
    }

    public static async processQuery(supabase: SupabaseClient, userId: string, query: string) {
        // 1. Fetch Context (RAG - Retrieval Augmented Generation)
        // We will fetch basic info: Profile, Today's Schedule (if any), Recent Grades.

        // Fetch User Profile
        const { data: user } = await supabase.from('users').select('nama_lengkap, role, nim').eq('id_user', userId).single();

        // Fetch Schedule (Mocking 'Jadwal' table logic or assuming 'krs' + 'matakuliah' join)
        // For MVP, allow empty if tables don't exist yet, or mock.
        // Let's assume we have a way to get 'krs' linked to 'matakuliah'
        const { data: krs } = await supabase.from('krs')
            .select(`
                id_krs,
                matakuliah ( nama_mk, hari, jam_mulai, jam_selesai, dosen ( nama_dosen ) )
            `)
            .eq('id_mahasiswa', userId) // Assuming id_mahasiswa links to users.id_user logically in this schema
            .limit(10);

        // Fetch Grades (Nilai)
        const { data: nilai } = await supabase.from('nilai')
            .select(`
                nilai_akhir,
                grade,
                matakuliah ( nama_mk )
            `)
            .eq('id_mahasiswa', userId)
            .limit(10);

        const context: AronaContext = {
            user: user || { id: userId, name: "Unknown" },
            schedule: krs || [],
            grades: nilai || []
        };

        // 2. Call AI
        return this.getGeminiResponse(query, context);
    }
}
