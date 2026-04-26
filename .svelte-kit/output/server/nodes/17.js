import * as server from '../entries/pages/nilai/_page.server.ts.js';

export const index = 17;
let component_cache;
export const component = async () => component_cache ??= (await import('../entries/pages/nilai/_page.svelte.js')).default;
export { server };
export const server_id = "src/routes/nilai/+page.server.ts";
export const imports = ["_app/immutable/nodes/17.DKA44Ot6.js","_app/immutable/chunks/DsnmJJEf.js","_app/immutable/chunks/D_zEB1TM.js","_app/immutable/chunks/CYQBSB63.js","_app/immutable/chunks/CIznRJTs.js","_app/immutable/chunks/C0903-W8.js"];
export const stylesheets = [];
export const fonts = [];
