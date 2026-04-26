<script lang="ts">
    export let id: string;
    export let name: string;
    export let lastMessage: string = "";
    export let timestamp: string = "";
    export let unreadCount: number = 0;
    export let active: boolean = false;
    export let avatar: string = `https://api.dicebear.com/7.x/fun-emoji/svg?seed=${name}`;
</script>

<a
    href="/momotalk/{id}"
    class="block p-3 rounded-xl transition-all duration-200 group relative overflow-hidden
          {active
        ? 'bg-orange-50 dark:bg-orange-900/20 border-l-4 border-orange-500 shadow-sm'
        : 'hover:bg-gray-50 dark:hover:bg-gray-800/60 border-l-4 border-transparent'}"
>
    <div class="flex items-center gap-3 relative z-10">
        <div class="relative">
            <img
                src={avatar}
                alt={name}
                class="w-11 h-11 rounded-full bg-white dark:bg-gray-800 border border-gray-100 dark:border-gray-700 p-0.5 object-cover"
            />
            {#if unreadCount > 0}
                <span
                    class="absolute -top-1 -right-1 bg-orange-500 text-white text-[10px] font-bold px-1.5 py-0.5 rounded-full shadow-sm animate-pulse-slow border border-white dark:border-gray-900"
                >
                    {unreadCount}
                </span>
            {/if}
        </div>
        <div class="flex-1 min-w-0">
            <div class="flex justify-between items-center mb-0.5">
                <span
                    class="font-bold text-gray-800 dark:text-gray-100 truncate text-sm"
                    >{name}</span
                >
                <span class="text-[10px] text-gray-400 font-mono"
                    >{timestamp}</span
                >
            </div>
            <p
                class="text-xs text-gray-500 dark:text-gray-400 truncate group-hover:text-orange-600 dark:group-hover:text-orange-400 transition-colors font-sans"
            >
                {lastMessage || "No messages yet"}
            </p>
        </div>
    </div>
</a>
