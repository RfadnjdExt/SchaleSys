<script lang="ts">
	import { enhance } from "$app/forms";
	import type { ActionData } from "./$types";

	let { form }: { form: ActionData } = $props();
</script>

<div
	class="bg-background-light dark:bg-background-dark text-slate-900 dark:text-gray-100 min-h-screen relative overflow-hidden font-mono selection:bg-primary selection:text-white transition-colors duration-500"
>
	<div
		class="fixed inset-0 opacity-[0.08] pointer-events-none z-50 bg-noise mix-blend-overlay"
	></div>

	<!-- Background Elements -->
	<div class="absolute inset-0 z-0 overflow-hidden pointer-events-none">
		<div
			class="absolute -top-[10%] -left-[10%] w-[70%] h-[120%] bg-slate-300 dark:bg-slate-800 transform -rotate-12 origin-top-left mix-blend-multiply dark:mix-blend-overlay opacity-50"
		></div>
		<div
			class="absolute top-[20%] right-[10%] w-[40%] h-[5px] bg-primary transform rotate-45"
		></div>
		<div
			class="absolute bottom-0 right-0 w-[50%] h-[60%] bg-gray-300 dark:bg-gray-800 transform skew-y-6 translate-y-20 mix-blend-multiply dark:mix-blend-normal opacity-40"
		></div>
		<div class="absolute top-10 right-10 flex space-x-2 opacity-30">
			<div class="w-1 h-32 bg-slate-900 dark:bg-white"></div>
			<div class="w-1 h-24 bg-slate-900 dark:bg-white mt-8"></div>
			<div class="w-1 h-40 bg-slate-900 dark:bg-white -mt-4"></div>
		</div>
	</div>

	<div
		class="relative z-10 w-full min-h-screen flex flex-col md:flex-row items-stretch"
	>
		<!-- Left Panel: Info -->
		<div
			class="w-full md:w-5/12 p-8 md:p-16 flex flex-col justify-between relative"
		>
			<div class="flex items-center space-x-3 group cursor-pointer">
				<div class="relative w-12 h-12">
					<span
						class="material-icons text-5xl text-slate-800 dark:text-white absolute top-0 left-0 group-hover:translate-x-1 transition-transform duration-300"
						>school</span
					>
					<span
						class="material-icons text-5xl text-primary absolute top-0 left-0 opacity-50 translate-x-1 translate-y-1 group-hover:translate-x-2 group-hover:translate-y-2 transition-transform duration-300"
						>school</span
					>
					<div
						class="absolute -top-1 -right-2 w-3 h-3 bg-primary"
					></div>
					<div
						class="absolute bottom-0 -left-2 w-2 h-2 bg-slate-800 dark:bg-white"
					></div>
				</div>
				<span
					class="font-display text-2xl tracking-tighter uppercase text-slate-900 dark:text-white"
					>SchaleSys</span
				>
			</div>

			<div class="mt-12 md:mt-0">
				<h1
					class="font-display text-5xl md:text-7xl leading-[0.85] text-slate-900 dark:text-white uppercase tracking-tighter"
				>
					Schale<br />
					<span
						class="text-transparent bg-clip-text bg-linear-to-r from-slate-600 to-slate-900 dark:from-gray-400 dark:to-white"
						>System</span
					><br />
					<span class="text-primary">Akademik</span>
				</h1>
				<div class="mt-8 w-24 h-2 bg-slate-900 dark:bg-white"></div>
				<p
					class="mt-6 font-mono text-sm md:text-base text-slate-600 dark:text-gray-400 max-w-xs border-l-2 border-primary pl-4"
				>
					// SCHALESYS_V.1.0<br />
					Silakan masuk menggunakan akun Anda untuk mengakses portal.
				</p>
			</div>

			<div
				class="hidden md:block text-xs font-mono text-slate-500 dark:text-gray-500"
			>
				SESSION ID: <span class="text-primary">SCH-992-AZ</span>
			</div>
		</div>

		<!-- Right Panel: Form -->
		<div
			class="w-full md:w-7/12 flex items-center justify-center p-6 md:p-16 relative"
		>
			<div class="w-full max-w-md relative">
				<div
					class="absolute -inset-4 bg-white dark:bg-gray-800 shadow-2xl transform -rotate-1 z-0 border border-slate-200 dark:border-gray-700"
				></div>
				<div
					class="relative z-10 bg-surface-light/90 dark:bg-surface-dark/95 backdrop-blur-sm p-8 md:p-12 border-l-8 border-primary shadow-xl"
				>
					<h2
						class="text-xl font-bold uppercase tracking-widest mb-8 flex items-center"
					>
						<span class="w-3 h-3 bg-primary mr-3 animate-pulse"
						></span>
						SchaleSys Login
					</h2>

					<!-- Error Message Display -->
					{#if form?.error}
						<div
							class="mb-4 p-4 bg-red-100 dark:bg-red-900/30 border-l-4 border-red-500 text-red-700 dark:text-red-300 text-sm font-bold"
						>
							ERROR: {form.error}
						</div>
					{/if}

					<form method="POST" use:enhance class="space-y-8">
						<div class="relative group">
							<label
								class="block text-xs font-bold uppercase tracking-wider mb-2 text-slate-500 dark:text-gray-400"
								for="username"
							>
								[01] Username / ID
							</label>
							<input
								class="raw-input block w-full bg-transparent border-slate-400 dark:border-gray-600 focus:border-primary dark:focus:border-primary text-slate-900 dark:text-white placeholder-slate-400 dark:placeholder-gray-600 py-3 text-lg font-mono focus:ring-0 rounded-none focus:outline-none"
								id="username"
								name="username"
								placeholder="admin"
								required
								type="text"
							/>
							<div
								class="absolute right-0 top-8 text-[10px] text-slate-400 opacity-0 group-hover:opacity-100 transition-opacity"
							>
								INPUT_TXT
							</div>
						</div>

						<div class="relative group md:w-[90%] md:ml-auto">
							<label
								class="block text-xs font-bold uppercase tracking-wider mb-2 text-slate-500 dark:text-gray-400"
								for="password"
							>
								[02] Password
							</label>
							<input
								class="raw-input block w-full bg-transparent border-slate-400 dark:border-gray-600 focus:border-primary dark:focus:border-primary text-slate-900 dark:text-white placeholder-slate-400 dark:placeholder-gray-600 py-3 text-lg font-mono focus:ring-0 rounded-none focus:outline-none"
								id="password"
								name="password"
								placeholder="••••••••"
								required
								type="password"
							/>
							<div
								class="absolute right-0 top-8 text-[10px] text-slate-400 opacity-0 group-hover:opacity-100 transition-opacity"
							>
								SECURE_STR
							</div>
						</div>

						<div class="flex items-center justify-between pt-4">
							<div class="flex items-center">
								<input
									class="h-4 w-4 text-primary focus:ring-primary border-gray-300 rounded-none bg-transparent"
									id="remember-me"
									name="remember-me"
									type="checkbox"
								/>
								<label
									class="ml-2 block text-sm text-slate-600 dark:text-gray-400"
									for="remember-me"
								>
									Remember me
								</label>
							</div>
							<div class="text-sm">
								<button
									type="button"
									class="font-medium text-slate-600 dark:text-gray-400 hover:text-primary decoration-primary underline decoration-2 underline-offset-4 bg-transparent border-none cursor-pointer"
								>
									Reset Access?
								</button>
							</div>
						</div>

						<div class="pt-6">
							<button
								class="group relative w-full flex justify-center py-4 px-4 border border-transparent text-sm font-bold uppercase tracking-widest text-white bg-primary hover:bg-orange-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary shadow-lg overflow-hidden transition-all duration-300"
								type="submit"
							>
								<span
									class="absolute inset-0 w-full h-full -mt-1 rounded-lg opacity-30 bg-linear-to-b from-transparent via-transparent to-black"
								></span>
								<span
									class="relative flex items-center space-x-2"
								>
									<span class="material-icons text-lg"
										>login</span
									>
									<span>Initiate Sign In</span>
								</span>
								<div
									class="absolute top-0 right-0 w-3 h-3 bg-white transform rotate-45 -translate-y-2 translate-x-2"
								></div>
								<div
									class="absolute bottom-0 left-0 w-3 h-3 bg-black transform rotate-45 translate-y-2 -translate-x-2"
								></div>
							</button>
						</div>
					</form>
				</div>
				<!-- Spinner Ornament -->
				<div
					class="absolute -bottom-12 -right-12 hidden md:flex items-center justify-center w-24 h-24 border border-dashed border-slate-400 dark:border-gray-600 rounded-full animate-[spin_10s_linear_infinite]"
				>
					<div class="w-2 h-2 bg-primary rounded-full"></div>
				</div>
			</div>
		</div>
	</div>

	<!-- Footer -->
	<div class="fixed bottom-0 left-0 w-full z-20 pointer-events-none">
		<div class="flex justify-between items-end p-4 md:p-8">
			<div
				class="hidden md:block w-32 h-1 bg-slate-900 dark:bg-white mb-2"
			></div>
			<p
				class="text-[10px] uppercase tracking-widest text-slate-500 dark:text-gray-600 text-right"
			>
				© 2026 Sistem Informasi Akademik <br />
				All Systems Operational
			</p>
		</div>
	</div>

	<!-- Theme Toggle -->
	<button
		type="button"
		class="fixed bottom-6 left-6 z-50 p-2 bg-slate-200 dark:bg-slate-800 text-slate-800 dark:text-white border border-slate-400 dark:border-slate-600 hover:scale-110 transition-transform pointer-events-auto shadow-lg"
		onclick={() => document.documentElement.classList.toggle("dark")}
		title="Toggle Dark Mode"
	>
		<span class="material-icons text-xl block dark:hidden">dark_mode</span>
		<span class="material-icons text-xl hidden dark:block">light_mode</span>
	</button>
</div>

<style>
</style>
