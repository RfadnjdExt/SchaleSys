<script lang="ts">
    import favicon from "$lib/assets/favicon.svg";
    import "../app.css";
    import { page } from "$app/stores";
    import dashboardBg from "$lib/assets/dashboard-bg.png"; // Import global background

    export let data;

    // Reactive user from layout server data
    $: user = data.user;

    // Check if we are on the login page
    $: isLoginPage = $page.url.pathname === "/login";
</script>

<svelte:head>
    <title>SchaleSys</title>
    <link rel="icon" href={favicon} />
</svelte:head>

<!-- Global Background (Hidden on Login Page if it has its own, but we can reuse it if consistent. 
     Login page currently has its own specialized background, so we hide this one on login) -->
{#if !isLoginPage}
    <!-- Abstract Background -->
    <div class="fixed inset-0 z-0 overflow-hidden pointer-events-none">
        <img src={dashboardBg} alt="" class="absolute inset-0 w-full h-full object-cover opacity-30 mix-blend-multiply dark:mix-blend-overlay" />
        <div class="absolute inset-0 bg-linear-to-b from-transparent to-white/50 dark:to-gray-900/50"></div>
    </div>

    <nav
        class="relative z-50 border-b border-gray-200 dark:border-gray-800 bg-white/80 dark:bg-gray-900/80 backdrop-blur-md transition-all duration-300"
    >
        <div
            class="max-w-[1440px] mx-auto px-4 md:px-6 h-20 flex items-center justify-between"
        >
            <!-- Logo Section -->
            <div class="flex items-center space-x-8">
                <a href="/" class="flex items-center space-x-3 group cursor-pointer">
                    <div class="relative w-8 h-8">
                        <span
                            class="material-symbols-outlined text-3xl text-primary transition-transform group-hover:scale-110"
                            >school</span
                        >
                    </div>
                    <span
                        class="font-display text-xl font-bold tracking-tight text-gray-900 dark:text-white"
                        >SchaleSys</span
                    >
                </a>
                
                <!-- Desktop Navigation -->
                {#if user}
                    <div class="hidden md:flex space-x-6 text-sm font-medium">
                        <a 
                            class="{$page.url.pathname === '/' ? 'text-primary' : 'text-gray-500 hover:text-gray-900 dark:hover:text-white'} transition-colors" 
                            href="/">Dashboard</a
                        >
                        {#if user.role !== "mahasiswa"}
                            <a
                                class="{$page.url.pathname.startsWith('/mahasiswa') ? 'text-primary' : 'text-gray-500 hover:text-gray-900 dark:hover:text-white'} transition-colors"
                                href="/mahasiswa">Mahasiswa</a
                            >
                            <a
                                class="{$page.url.pathname.startsWith('/matakuliah') ? 'text-primary' : 'text-gray-500 hover:text-gray-900 dark:hover:text-white'} transition-colors"
                                href="/matakuliah">Mata Kuliah</a
                            >
                            {#if user.role === "admin"}
                                <a
                                    class="{$page.url.pathname.startsWith('/dosen') ? 'text-primary' : 'text-gray-500 hover:text-gray-900 dark:hover:text-white'} transition-colors"
                                    href="/dosen">Dosen</a
                                >
                            {/if}
                        {/if}
                        {#if user.role === "mahasiswa"}
                            <a
                                class="{$page.url.pathname.startsWith('/krs') ? 'text-primary' : 'text-gray-500 hover:text-gray-900 dark:hover:text-white'} transition-colors"
                                href="/krs">KRS</a
                            >
                            <a
                                class="{$page.url.pathname.startsWith('/nilai') ? 'text-primary' : 'text-gray-500 hover:text-gray-900 dark:hover:text-white'} transition-colors"
                                href="/nilai">Transkrip</a
                            >
                        {/if}
                    </div>
                {/if}
            </div>

            <!-- User Section -->
            {#if user}
                <div class="flex items-center space-x-4">
                    <div class="text-right hidden sm:block">
                        <p class="text-[10px] uppercase font-bold text-gray-400">
                            Authenticated
                        </p>
                        <p class="text-xs font-bold text-gray-700 dark:text-gray-200">
                            {user.nama_lengkap ?? "User"}
                        </p>
                    </div>
                    <div
                        class="w-9 h-9 bg-linear-to-br from-primary to-orange-600 flex items-center justify-center text-white font-bold rounded-full shadow-sm"
                    >
                        {user.nama_lengkap?.charAt(0) ?? "U"}
                    </div>
                    <form action="/logout" method="POST" class="ml-2">
                        <button
                            type="submit"
                            class="material-symbols-outlined text-gray-400 hover:text-red-500 transition-colors text-xl"
                            title="Logout">logout</button
                        >
                    </form>
                </div>
            {/if}
        </div>
    </nav>
{/if}

<main class="min-h-screen">
    <slot />
</main>
