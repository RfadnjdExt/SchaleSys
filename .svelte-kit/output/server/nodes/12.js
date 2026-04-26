import * as server from '../entries/pages/mahasiswa/create/_page.server.ts.js';

export const index = 12;
let component_cache;
export const component = async () => component_cache ??= (await import('../entries/pages/mahasiswa/create/_page.svelte.js')).default;
export { server };
export const server_id = "src/routes/mahasiswa/create/+page.server.ts";
export const imports = ["_app/immutable/nodes/12.CdfQZjUL.js","_app/immutable/chunks/DsnmJJEf.js","_app/immutable/chunks/DzRt-7dB.js","_app/immutable/chunks/D_zEB1TM.js","_app/immutable/chunks/CYQBSB63.js","_app/immutable/chunks/CIznRJTs.js","_app/immutable/chunks/CgiePsJ1.js","_app/immutable/chunks/1iJmiE8L.js","_app/immutable/chunks/CwHpJG8Z.js","_app/immutable/chunks/DvXjWyV5.js","_app/immutable/chunks/CqFEKUfF.js","_app/immutable/chunks/B07tQaqf.js","_app/immutable/chunks/DwwvudJH.js"];
export const stylesheets = [];
export const fonts = [];
