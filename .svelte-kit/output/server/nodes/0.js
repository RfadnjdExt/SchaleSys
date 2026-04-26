import * as server from '../entries/pages/_layout.server.ts.js';

export const index = 0;
let component_cache;
export const component = async () => component_cache ??= (await import('../entries/pages/_layout.svelte.js')).default;
export { server };
export const server_id = "src/routes/+layout.server.ts";
export const imports = ["_app/immutable/nodes/0.B4tFvr-6.js","_app/immutable/chunks/DsnmJJEf.js","_app/immutable/chunks/DzRt-7dB.js","_app/immutable/chunks/D_zEB1TM.js","_app/immutable/chunks/CYQBSB63.js","_app/immutable/chunks/DvXjWyV5.js","_app/immutable/chunks/C0903-W8.js","_app/immutable/chunks/DwwvudJH.js","_app/immutable/chunks/CwHpJG8Z.js","_app/immutable/chunks/1iJmiE8L.js"];
export const stylesheets = ["_app/immutable/assets/0.COvBj-JH.css"];
export const fonts = [];
