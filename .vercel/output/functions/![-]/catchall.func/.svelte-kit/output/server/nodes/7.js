import * as server from '../entries/pages/krs/_page.server.ts.js';

export const index = 7;
let component_cache;
export const component = async () => component_cache ??= (await import('../entries/pages/krs/_page.svelte.js')).default;
export { server };
export const server_id = "src/routes/krs/+page.server.ts";
export const imports = ["_app/immutable/nodes/7.CCgaMdbk.js","_app/immutable/chunks/DsnmJJEf.js","_app/immutable/chunks/D_zEB1TM.js","_app/immutable/chunks/CYQBSB63.js","_app/immutable/chunks/CIznRJTs.js"];
export const stylesheets = [];
export const fonts = [];
