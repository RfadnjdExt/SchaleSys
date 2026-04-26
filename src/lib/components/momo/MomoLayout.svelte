<script lang="ts">
    import { page } from "$app/stores";
</script>

<div
    class="h-[calc(100vh-140px)] w-full overflow-hidden bg-white/60 dark:bg-gray-800/60 backdrop-blur-xl border border-gray-100 dark:border-gray-700 rounded-2xl shadow-xl flex flex-col md:flex-row mt-6"
>
    <!-- Sidebar -->
    <div
        class="w-full md:w-80 border-b md:border-b-0 md:border-r border-gray-100 dark:border-gray-700 shrink-0 bg-white/50 dark:bg-gray-900/50 backdrop-blur-sm flex flex-col"
    >
        <div
            class="h-16 flex items-center px-6 border-b border-gray-100 dark:border-gray-700 bg-orange-50/50 dark:bg-orange-900/10"
        >
            <h1
                class="text-lg font-bold flex items-center gap-2 font-display text-gray-800 dark:text-white tracking-tight"
            >
                <div
                    class="w-8 h-8 rounded-lg bg-orange-500 flex items-center justify-center text-white shadow-sm shadow-orange-200 dark:shadow-none"
                >
                    <span class="material-symbols-outlined text-lg">forum</span>
                </div>
                <span>MomoTalk</span>
            </h1>
        </div>
        <div class="flex-1 overflow-y-auto p-3 space-y-2">
            <slot name="sidebar" />
        </div>
    </div>

    <!-- Main Chat Area -->
    <div class="flex-1 flex flex-col bg-[#fffbfd] dark:bg-gray-900/80 relative">
        <div
            class="absolute inset-0 pointer-events-none opacity-5 bg-[radial-gradient(ellipse_at_top_right,var(--tw-gradient-stops))] from-orange-300 via-transparent to-transparent"
        ></div>
        <slot name="content" />
    </div>
</div>
