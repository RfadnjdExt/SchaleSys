import { createClient } from '@supabase/supabase-js';
import fs from 'fs';
import path from 'path';
import { fileURLToPath } from 'url';

const __filename = fileURLToPath(import.meta.url);
const __dirname = path.dirname(__filename);

// Read .env manually
const envPath = path.join(__dirname, '../.env');
const envContent = fs.readFileSync(envPath, 'utf-8');
const env = {};
envContent.split('\n').forEach(line => {
    const [key, value] = line.split('=');
    if (key && value) {
        env[key.trim()] = value.trim();
    }
});

const SUPABASE_URL = env['PUBLIC_SUPABASE_URL'];
const SUPABASE_KEY = env['PUBLIC_SUPABASE_PUBLISHABLE_DEFAULT_KEY'];

if (!SUPABASE_URL || !SUPABASE_KEY) {
    console.error('Missing Supabase credentials in .env');
    process.exit(1);
}

const supabase = createClient(SUPABASE_URL, SUPABASE_KEY);

async function seed() {
    console.log('Starting seed process...');

    // 1. Read Data
    const trickcalPath = path.join(__dirname, '../src/lib/data/trickcal.json');
    const blueArchivePath = path.join(__dirname, '../src/lib/data/blue_archive.json');

    const trickcalData = JSON.parse(fs.readFileSync(trickcalPath, 'utf-8'));
    let blueArchiveData = JSON.parse(fs.readFileSync(blueArchivePath, 'utf-8'));

    // 2. Clean Blue Archive Data
    console.log(`Processing ${blueArchiveData.length} Blue Archive characters...`);
    blueArchiveData = blueArchiveData.map(char => {
        // Example: .../40px-Michiru_(Dress).png
        // Replace 40px with 400px for better quality, or remove prefix
        let newUrl = char.foto;
        if (newUrl.includes('40px-')) {
            newUrl = newUrl.replace('40px-', '200px-'); // Try 200px, usually standard thumb size
        }
        return {
            name: char.nama,
            url: newUrl,
            fakultas: 'Schale (Blue Archive)', // Default fakultas
            prodi: 'Student'
        };
    });

    const trickcalFormatted = trickcalData.map(char => ({
        name: char.name,
        url: char.url,
        fakultas: 'Trickcal Revive',
        prodi: 'Petualang'
    }));

    const allStudents = [...trickcalFormatted, ...blueArchiveData];
    console.log(`Total students to insert: ${allStudents.length}`);

    // 3. Truncate / Delete All
    console.log('Clearing existing data...');
    // Note: Truncate might fail with RLS. effectively delete all.
    const { error: deleteError } = await supabase
        .from('mahasiswa')
        .delete()
        .neq('id', 0); // Delete where id != 0 (all rows)

    if (deleteError) {
        console.warn('Error clearing data (might be RLS):', deleteError.message);
        console.log('Attempting to continue anyway...');
    }

    // 4. Insert Batch
    // Add NIM and Angkatan
    let startNim = 2024001;
    const angkatan = 2024;

    const rows = allStudents.map((s, i) => ({
        nim: (startNim + i).toString(),
        nama_mahasiswa: s.name,
        enkripsi_id: null, // Default
        angkatan: angkatan,
        foto: s.url,
        created_at: new Date().toISOString(),
        prodi: s.prodi,
        fakultas: s.fakultas
    }));

    // Batch insert (Supabase limit is usually high, but let's do chunks of 100)
    const chunkSize = 100;
    for (let i = 0; i < rows.length; i += chunkSize) {
        const chunk = rows.slice(i, i + chunkSize);
        const { error: insertError } = await supabase
            .from('mahasiswa')
            .insert(chunk);

        if (insertError) {
            console.error(`Error inserting chunk ${i}:`, insertError.message);
        } else {
            console.log(`Inserted chunk ${i} - ${i + chunk.length}`);
        }
    }

    console.log('Seed completed!');
}

seed();
