import bcrypt from 'bcryptjs';

const password = 'admin123';
const hash = bcrypt.hashSync(password, 10);

console.log('--- DIAGNOSTIC ---');
console.log('Password:', password);
console.log('Generated Hash:', hash);
console.log('Length:', hash.length);
console.log('Self-Verify:', bcrypt.compareSync(password, hash));

const sql = `
-- COPY THIS EXACT SQL
UPDATE users SET password = '${hash}' WHERE username = 'admin';
UPDATE users SET password = '${hash}' WHERE username = 'dosen1';
UPDATE users SET password = '${hash}' WHERE username = 'mhs1';
`;

import fs from 'fs';
fs.writeFileSync('verified_inserts.sql', sql);
console.log('Wrote verified_inserts.sql');
