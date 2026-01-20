<script lang="ts">
    import MomoLayout from "$lib/components/momo/MomoLayout.svelte";
    import ChatListItem from "$lib/components/momo/ChatListItem.svelte";

    export let data;
    // Assuming data will have 'conversations'
</script>

<MomoLayout>
    <div slot="sidebar">
        {#each data.conversations as conv}
            <ChatListItem
                id={conv.id}
                name={conv.participants[0]}
                lastMessage={conv.last_message?.content}
                timestamp={conv.last_message?.created_at
                    ? new Date(conv.last_message.created_at).toLocaleTimeString(
                          [],
                          { hour: "2-digit", minute: "2-digit" },
                      )
                    : ""}
                unreadCount={conv.unread_count}
            />
        {/each}
        {#if data.conversations.length === 0}
            <div class="p-4 text-center text-slate-400 text-sm">
                No conversations yet.
            </div>
        {/if}
    </div>

    <div
        slot="content"
        class="h-full flex flex-col items-center justify-center text-slate-400"
    >
        <div
            class="w-24 h-24 bg-orange-100 dark:bg-orange-900/20 rounded-full flex items-center justify-center mb-4"
        >
            <span class="text-4xl">💬</span>
        </div>
        <h2 class="text-lg font-semibold text-slate-600">
            Welcome to MomoTalk
        </h2>
        <p class="text-sm">Select a conversation to start chatting</p>
    </div>
</MomoLayout>
