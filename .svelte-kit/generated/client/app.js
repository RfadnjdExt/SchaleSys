export { matchers } from './matchers.js';

export const nodes = [
	() => import('./nodes/0'),
	() => import('./nodes/1'),
	() => import('./nodes/2'),
	() => import('./nodes/3'),
	() => import('./nodes/4'),
	() => import('./nodes/5'),
	() => import('./nodes/6'),
	() => import('./nodes/7'),
	() => import('./nodes/8'),
	() => import('./nodes/9'),
	() => import('./nodes/10'),
	() => import('./nodes/11'),
	() => import('./nodes/12'),
	() => import('./nodes/13'),
	() => import('./nodes/14'),
	() => import('./nodes/15'),
	() => import('./nodes/16'),
	() => import('./nodes/17'),
	() => import('./nodes/18'),
	() => import('./nodes/19'),
	() => import('./nodes/20')
];

export const server_loads = [0];

export const dictionary = {
		"/": [~2],
		"/dosen": [~3],
		"/dosen/assign": [~4],
		"/dosen/create": [~5],
		"/dosen/[nip]/edit": [~6],
		"/krs": [~7],
		"/krs/input": [~8],
		"/login": [~9],
		"/logout": [~10],
		"/mahasiswa": [~11],
		"/mahasiswa/create": [~12],
		"/mahasiswa/[nim]/edit": [~13],
		"/matakuliah": [~14],
		"/matakuliah/create": [~15],
		"/matakuliah/[kode]/edit": [~16],
		"/momotalk": [~17],
		"/momotalk/[id]": [~18],
		"/nilai": [~19],
		"/nilai/input": [~20]
	};

export const hooks = {
	handleError: (({ error }) => { console.error(error) }),
	
	reroute: (() => {}),
	transport: {}
};

export const decoders = Object.fromEntries(Object.entries(hooks.transport).map(([k, v]) => [k, v.decode]));
export const encoders = Object.fromEntries(Object.entries(hooks.transport).map(([k, v]) => [k, v.encode]));

export const hash = false;

export const decode = (type, value) => decoders[type](value);

export { default as root } from '../root.js';