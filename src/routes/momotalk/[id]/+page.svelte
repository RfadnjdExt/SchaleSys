<script lang="ts">
    import { enhance } from "$app/forms";
    import { invalidateAll } from "$app/navigation";
    import { onMount, onDestroy } from "svelte";
    import MomoLayout from "$lib/components/momo/MomoLayout.svelte";
    import ChatListItem from "$lib/components/momo/ChatListItem.svelte";
    import MessageBubble from "$lib/components/momo/MessageBubble.svelte";
    import { page } from "$app/stores";

    export let data;

    // Supabase Realtime logic
    let channel: any;

    onMount(() => {
        // We need the supabase client here. It usually comes from data usually or $page.data.supabase if set in layout.
        // Assuming we rely on invalidateAll to refresh data on new message for now (simplest for MVP)
        // OR better: use Supabase Realtime channel.
        // Since we didn't inject supabase client into the page via root layout properly in the previous analysis (it was in locals but not returned in root layout load function explicitly as 'supabase'),
        // we might not have 'data.supabase' here.
        // However, standard SvelteKit Supabase setup usually provides it.
        // Let's implement basic polling or assume standard client.
        // MVP: Just use auto-scroll and form enhance
    });

    // Auto-scroll to bottom
    let chatContainer: HTMLElement;

    $: if (data.messages && chatContainer) {
        setTimeout(() => {
            chatContainer.scrollTop = chatContainer.scrollHeight;
        }, 0);
    }
</script>

<MomoLayout>
    <div slot="sidebar">
        <!-- We need to fetch conversations again for the sidebar? 
             Ideally the layout handles this, or we load it in parent layout. 
             For now, let's assume we want to show the list here too.
             But the recursive load might be tricky if we didn't put it in proper layout.
             
             Temporary workaround: "Back" button or Link to /momotalk.
             Or duplicate load logic (not ideal).
             
             Let's just put a "Back to List" button if we are deep in structure without parent data.
        -->
        <div class="mb-4">
            <a
                href="/momotalk"
                class="text-sm text-orange-500 font-bold hover:underline"
                >← Back to Chat List</a
            >
        </div>

        <!-- Ideally we pass conversations from a parent layout load function. -->
    </div>

    <div slot="content" class="h-full flex flex-col">
        <!-- Header -->
        <div
            class="h-16 border-b border-gray-100 dark:border-gray-700 flex items-center px-6 bg-white/50 dark:bg-gray-900/50 backdrop-blur-sm justify-between"
        >
            <div class="flex items-center gap-3">
                <div class="md:hidden">
                    <a
                        href="/momotalk"
                        class="p-2 -ml-2 text-gray-400 hover:text-gray-600 dark:text-gray-500 dark:hover:text-gray-300"
                    >
                        <span class="material-symbols-outlined">arrow_back</span
                        >
                    </a>
                </div>
                <!-- Avatar with Schale Accent -->
                <div
                    class="w-10 h-10 rounded-full bg-orange-100 dark:bg-orange-900/30 flex items-center justify-center text-orange-500 dark:text-orange-400"
                >
                    <span class="material-symbols-outlined text-xl">person</span
                    >
                </div>
                <div>
                    <h3
                        class="font-bold text-gray-800 dark:text-white text-sm font-display tracking-tight"
                    >
                        Conversation
                    </h3>
                    <p
                        class="text-[10px] text-orange-500 uppercase font-bold tracking-widest font-mono"
                    >
                        Schale Secure Line
                    </p>
                </div>
            </div>
            <button
                class="text-gray-400 hover:text-orange-500 transition-colors"
            >
                <span class="material-symbols-outlined">more_vert</span>
            </button>
        </div>

        <!-- Messages -->
        <div
            class="flex-1 overflow-y-auto p-4 md:p-6 space-y-2 scroll-smooth"
            bind:this={chatContainer}
        >
            {#each data.messages as msg}
                <MessageBubble
                    isMe={msg.sender_id === (data.user?.username || "")}
                    content={msg.content}
                    time={new Date(msg.created_at).toLocaleTimeString([], {
                        hour: "2-digit",
                        minute: "2-digit",
                    })}
                    senderName={msg.sender_id}
                    avatar={`https://api.dicebear.com/7.x/fun-emoji/svg?seed=${msg.sender_id}`}
                />
            {/each}
        </div>

        <!-- Input -->
        <!-- Input -->
        <div
            class="p-4 bg-white dark:bg-gray-800 border-t border-gray-100 dark:border-gray-700 rounded-br-2xl"
        >
            <form
                method="POST"
                use:enhance={() => {
                    return async ({ update }) => {
                        await update();
                        // Scroll to bottom after update
                    };
                }}
                class="flex gap-3"
            >
                <input
                    type="text"
                    name="content"
                    placeholder="Type a message..."
                    class="flex-1 bg-gray-50 dark:bg-gray-900 border border-gray-200 dark:border-gray-700 rounded-xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-orange-500/20 focus:border-orange-500 text-sm text-gray-900 dark:text-white transition-all font-mono"
                    autocomplete="off"
                />
                <button
                    type="submit"
                    class="bg-orange-500 hover:bg-orange-600 text-white rounded-xl px-4 py-2 flex items-center justify-center transition-all shadow-md shadow-orange-200 dark:shadow-none hover:shadow-lg hover:-translate-y-0.5 active:translate-y-0"
                >
                    <span class="material-symbols-outlined">send</span>
                </button>
            </form>
        </div>
    </div>
</MomoLayout>
