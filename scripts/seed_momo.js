
import { createClient } from '@supabase/supabase-js';

const supabaseUrl = process.env.PUBLIC_SUPABASE_URL;
const supabaseKey = process.env.PUBLIC_SUPABASE_PUBLISHABLE_DEFAULT_KEY; // Using anon key for client-side like ops, but for seed we might need service_role if RLS blocks. 
// Assuming Admin/Anon has enough rights or RLS isn't strict yet. 
// If RLS blocks, user might need to provide SERVICE_ROLE_KEY.
// Let's try with what we have.

if (!supabaseUrl || !supabaseKey) {
    console.error('Error: Supabase URL or Key not found in environment variables.');
    process.exit(1);
}

const supabase = createClient(supabaseUrl, supabaseKey);

async function seedMomo() {
    console.log('🌱 Seeding MomoTalk Data...');

    // 1. Ensure Users Exist (Admin is default, let's make Arona)
    const { error: userError } = await supabase
        .from('users')
        .upsert({
            username: 'arona',
            password: 'hashed_password_placeholder', // Dummy
            nama_lengkap: 'Arona (AI)',
            role: 'dosen' // Pretend admin/system
        }, { onConflict: 'username' });

    if (userError) console.warn('User warning:', userError.message);

    const userA = 'admin';
    const userB = 'arona';

    // 2. Create Conversation
    console.log(`Creating conversation between ${userA} and ${userB}...`);

    // Check existing
    const { data: existing } = await supabase
        .from('conversation_participants')
        .select('conversation_id')
        .eq('user_id', userA);

    // Simple check: just create a new one for testing, clean up later if needed
    const { data: conv, error: convError } = await supabase
        .from('conversations')
        .insert({ type: 'private' })
        .select()
        .single();

    if (convError) {
        console.error('Failed to create conversation:', convError);
        return;
    }

    console.log('Conversation created:', conv.id);

    // 3. Add Participants
    const { error: partError } = await supabase
        .from('conversation_participants')
        .insert([
            { conversation_id: conv.id, user_id: userA },
            { conversation_id: conv.id, user_id: userB }
        ]);

    if (partError) console.error('Failed to add participants:', partError);

    // 4. Add Messages
    const messages = [
        { conversation_id: conv.id, sender_id: userB, content: 'Welcome back, Sensei! 🎈' },
        { conversation_id: conv.id, sender_id: userB, content: 'Do you have any work for Schale today?' },
        { conversation_id: conv.id, sender_id: userA, content: 'Just checking the system, Arona.' },
        { conversation_id: conv.id, sender_id: userB, content: 'Understood! All systems green. 🟢' }
    ];

    const { error: msgError } = await supabase
        .from('messages')
        .insert(messages);

    if (msgError) {
        console.error('Failed to insert messages:', msgError);
    } else {
        console.log('✅ Messages seeded successfully!');
    }
}

seedMomo();
