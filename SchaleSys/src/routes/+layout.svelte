<script lang="ts">
    import favicon from "$lib/assets/favicon.svg";
    import "../app.css";
    import { page } from "$app/stores";
    import { LogOut, Menu, X, User } from "lucide-svelte";

    export let data;

    // Reactive user
    $: user = data.user;

    let mobileMenuOpen = false;
    let userOpen = false;

    // Check if we are on the login page, dashboard, or other main pages (custom navs)
    $: isLoginPage = $page.url.pathname === "/login";
    $: isDashboard = $page.url.pathname === "/";
    $: isMahasiswaPage = $page.url.pathname.startsWith("/mahasiswa");
    $: isMataKuliahPage = $page.url.pathname.startsWith("/matakuliah");
    $: isDosenPage = $page.url.pathname.startsWith("/dosen");
    $: hideNavbar =
        isLoginPage ||
        isDashboard ||
        isMahasiswaPage ||
        isMataKuliahPage ||
        isDosenPage;
</script>

<svelte:head>
    <link rel="icon" href={favicon} />
</svelte:head>

{#if hideNavbar}
    <slot />
{:else}
    <div class="min-h-screen bg-gray-50">
        <nav class="bg-white border-b border-gray-200 shadow-sm no-print">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex items-center justify-between h-16">
                    <!-- Brand -->
                    <div class="shrink-0 flex items-center">
                        <a href="/" class="flex items-center gap-3 group">
                            <img
                                src="/logo.png"
                                alt="SchaleSys Logo"
                                class="h-10 w-auto group-hover:scale-110 transition-transform duration-200"
                            />
                            <span
                                class="text-xl font-bold bg-clip-text text-transparent bg-linear-to-r from-blue-700 to-teal-600"
                            >
                                SchaleSys
                            </span>
                        </a>
                    </div>

                    {#if user}
                        <!-- Desktop Menu -->
                        <div class="hidden md:block">
                            <div class="ml-10 flex items-baseline space-x-1">
                                <a
                                    href="/"
                                    class="text-gray-600 hover:bg-gray-50 hover:text-blue-600 px-3 py-2 rounded-md text-sm font-medium transition-colors duration-200"
                                    >Dashboard</a
                                >

                                {#if user.role === "admin" || user.role === "dosen"}
                                    <a
                                        href="/mahasiswa"
                                        class="text-gray-600 hover:bg-gray-50 hover:text-blue-600 px-3 py-2 rounded-md text-sm font-medium transition-colors"
                                        >Mahasiswa</a
                                    >
                                    <a
                                        href="/matakuliah"
                                        class="text-gray-600 hover:bg-gray-50 hover:text-blue-600 px-3 py-2 rounded-md text-sm font-medium transition-colors"
                                        >Mata Kuliah</a
                                    >
                                {/if}

                                {#if user.role === "admin"}
                                    <a
                                        href="/dosen"
                                        class="text-gray-600 hover:bg-gray-50 hover:text-blue-600 px-3 py-2 rounded-md text-sm font-medium transition-colors"
                                        >Dosen</a
                                    >
                                    <!-- Admin Dropdown Placeholders -->
                                {/if}

                                {#if user.role === "mahasiswa"}
                                    <a
                                        href="/krs"
                                        class="text-gray-600 hover:bg-gray-50 hover:text-blue-600 px-3 py-2 rounded-md text-sm font-medium transition-colors"
                                        >Input KRS</a
                                    >
                                    <a
                                        href="/krs/view"
                                        class="text-gray-600 hover:bg-gray-50 hover:text-blue-600 px-3 py-2 rounded-md text-sm font-medium transition-colors"
                                        >Lihat KRS</a
                                    >
                                    <a
                                        href="/transkrip"
                                        class="text-gray-600 hover:bg-gray-50 hover:text-blue-600 px-3 py-2 rounded-md text-sm font-medium transition-colors"
                                        >Transkrip</a
                                    >
                                {/if}
                            </div>
                        </div>

                        <!-- User Menu -->
                        <div class="hidden md:block">
                            <div class="ml-4 flex items-center md:ml-6">
                                <div class="relative ml-3">
                                    <div>
                                        <button
                                            onclick={() =>
                                                (userOpen = !userOpen)}
                                            class="max-w-xs bg-white rounded-full flex items-center text-sm focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 p-1 pr-3 border border-gray-200 hover:bg-gray-50 transition-colors"
                                        >
                                            <div
                                                class="h-8 w-8 rounded-full bg-linear-to-br from-blue-500 to-indigo-600 flex items-center justify-center text-white font-bold mr-2 shadow-sm"
                                            >
                                                {user.nama_lengkap.charAt(0)}
                                            </div>
                                            <span
                                                class="text-gray-700 text-sm font-medium"
                                                >Hi, {user.nama_lengkap}</span
                                            >
                                        </button>
                                    </div>
                                    {#if userOpen}
                                        <div
                                            class="origin-top-right absolute right-0 mt-2 w-48 rounded-md shadow-lg py-1 bg-white ring-1 ring-black ring-opacity-5 focus:outline-none z-50"
                                        >
                                            <form
                                                action="/logout"
                                                method="POST"
                                            >
                                                <button
                                                    type="submit"
                                                    class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 hover:text-blue-600"
                                                    >Sign out</button
                                                >
                                            </form>
                                        </div>
                                    {/if}
                                </div>
                            </div>
                        </div>

                        <!-- Mobile menu button -->
                        <div class="-mr-2 flex md:hidden">
                            <button
                                onclick={() =>
                                    (mobileMenuOpen = !mobileMenuOpen)}
                                type="button"
                                class="bg-white inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-blue-500"
                            >
                                <span class="sr-only">Open main menu</span>
                                {#if !mobileMenuOpen}
                                    <Menu class="block h-6 w-6" />
                                {:else}
                                    <X class="block h-6 w-6" />
                                {/if}
                            </button>
                        </div>
                    {/if}
                    <!-- End if user -->
                </div>
            </div>

            <!-- Mobile Menu -->
            {#if user && mobileMenuOpen}
                <div class="md:hidden bg-white border-t border-gray-200">
                    <div class="px-2 pt-2 pb-3 space-y-1 sm:px-3">
                        <a
                            href="/"
                            class="text-gray-600 hover:bg-gray-50 hover:text-blue-600 block px-3 py-2 rounded-md text-base font-medium"
                            >Dashboard</a
                        >
                        <!-- Mobile links same logic as desktop detailed above -->
                        <form action="/logout" method="POST">
                            <button
                                type="submit"
                                class="w-full text-left bg-transparent text-gray-600 hover:bg-gray-50 hover:text-blue-600 block px-3 py-2 rounded-md text-base font-medium"
                                >Sign out</button
                            >
                        </form>
                    </div>
                </div>
            {/if}
        </nav>

        <main>
            <slot />
        </main>
    </div>
{/if}
