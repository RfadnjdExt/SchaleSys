import { createClient } from '@supabase/supabase-js';
import fs from 'fs';
import path from 'path';
import { fileURLToPath } from 'url';

const __filename = fileURLToPath(import.meta.url);
const __dirname = path.dirname(__filename);

const envPath = path.join(__dirname, '../.env');
const envContent = fs.readFileSync(envPath, 'utf-8');
const env = {};
envContent.split('\n').forEach(line => {
    const [key, value] = line.split('=');
    if (key && value) {
        env[key.trim()] = value.trim();
    }
});

const supabase = createClient(env['PUBLIC_SUPABASE_URL'], env['PUBLIC_SUPABASE_PUBLISHABLE_DEFAULT_KEY']);

async function verify() {
    const { count, error } = await supabase
        .from('mahasiswa')
        .select('*', { count: 'exact', head: true });

    if (error) {
        console.error('Error verifying:', error);
    } else {
        console.log(`Total rows in mahasiswa: ${count}`);
    }
}

verify();
