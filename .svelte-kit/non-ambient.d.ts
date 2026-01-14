
// this file is generated — do not edit it


declare module "svelte/elements" {
	export interface HTMLAttributes<T> {
		'data-sveltekit-keepfocus'?: true | '' | 'off' | undefined | null;
		'data-sveltekit-noscroll'?: true | '' | 'off' | undefined | null;
		'data-sveltekit-preload-code'?:
			| true
			| ''
			| 'eager'
			| 'viewport'
			| 'hover'
			| 'tap'
			| 'off'
			| undefined
			| null;
		'data-sveltekit-preload-data'?: true | '' | 'hover' | 'tap' | 'off' | undefined | null;
		'data-sveltekit-reload'?: true | '' | 'off' | undefined | null;
		'data-sveltekit-replacestate'?: true | '' | 'off' | undefined | null;
	}
}

export {};


declare module "$app/types" {
	export interface AppTypes {
		RouteId(): "/" | "/dosen" | "/dosen/assign" | "/dosen/create" | "/dosen/[nip]" | "/dosen/[nip]/edit" | "/krs" | "/krs/input" | "/login" | "/logout" | "/mahasiswa" | "/mahasiswa/create" | "/mahasiswa/[nim]" | "/mahasiswa/[nim]/edit" | "/matakuliah" | "/matakuliah/create" | "/matakuliah/[kode]" | "/matakuliah/[kode]/edit" | "/nilai" | "/nilai/input";
		RouteParams(): {
			"/dosen/[nip]": { nip: string };
			"/dosen/[nip]/edit": { nip: string };
			"/mahasiswa/[nim]": { nim: string };
			"/mahasiswa/[nim]/edit": { nim: string };
			"/matakuliah/[kode]": { kode: string };
			"/matakuliah/[kode]/edit": { kode: string }
		};
		LayoutParams(): {
			"/": { nip?: string; nim?: string; kode?: string };
			"/dosen": { nip?: string };
			"/dosen/assign": Record<string, never>;
			"/dosen/create": Record<string, never>;
			"/dosen/[nip]": { nip: string };
			"/dosen/[nip]/edit": { nip: string };
			"/krs": Record<string, never>;
			"/krs/input": Record<string, never>;
			"/login": Record<string, never>;
			"/logout": Record<string, never>;
			"/mahasiswa": { nim?: string };
			"/mahasiswa/create": Record<string, never>;
			"/mahasiswa/[nim]": { nim: string };
			"/mahasiswa/[nim]/edit": { nim: string };
			"/matakuliah": { kode?: string };
			"/matakuliah/create": Record<string, never>;
			"/matakuliah/[kode]": { kode: string };
			"/matakuliah/[kode]/edit": { kode: string };
			"/nilai": Record<string, never>;
			"/nilai/input": Record<string, never>
		};
		Pathname(): "/" | "/dosen" | "/dosen/" | "/dosen/assign" | "/dosen/assign/" | "/dosen/create" | "/dosen/create/" | `/dosen/${string}` & {} | `/dosen/${string}/` & {} | `/dosen/${string}/edit` & {} | `/dosen/${string}/edit/` & {} | "/krs" | "/krs/" | "/krs/input" | "/krs/input/" | "/login" | "/login/" | "/logout" | "/logout/" | "/mahasiswa" | "/mahasiswa/" | "/mahasiswa/create" | "/mahasiswa/create/" | `/mahasiswa/${string}` & {} | `/mahasiswa/${string}/` & {} | `/mahasiswa/${string}/edit` & {} | `/mahasiswa/${string}/edit/` & {} | "/matakuliah" | "/matakuliah/" | "/matakuliah/create" | "/matakuliah/create/" | `/matakuliah/${string}` & {} | `/matakuliah/${string}/` & {} | `/matakuliah/${string}/edit` & {} | `/matakuliah/${string}/edit/` & {} | "/nilai" | "/nilai/" | "/nilai/input" | "/nilai/input/";
		ResolvedPathname(): `${"" | `/${string}`}${ReturnType<AppTypes['Pathname']>}`;
		Asset(): "/logo.png" | "/robots.txt" | string & {};
	}
}