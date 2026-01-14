
import { SupabaseClient, Session } from '@supabase/supabase-js';

declare global {
	namespace App {
		// Define custom User interface that extends or replaces Supabase User for our app needs
		interface User {
			id_user?: number; // From our PostgreSQL 'users' table if needed
			username?: string;
			password?: string;
			nama_lengkap: string;
			role: 'admin' | 'dosen' | 'mahasiswa';
			nim?: string;
			nip?: string;
			// Allow optional standard Supabase fields if we ever mix them, but primarily we use the above
			id?: string;
			email?: string;
			app_metadata?: any;
			user_metadata?: any;
		}

		interface Locals {
			supabase: SupabaseClient;
			getSession: () => Promise<Session | null>;
			user: App.User | null;
		}
		interface PageData {
			session: Session | null;
			user: App.User | null;
		}
		interface Error {
			message: string;
		}
		// interface PageState {}
		// interface Platform {}
	}
}

export { };
