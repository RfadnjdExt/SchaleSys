<script lang="ts">
    import { fade, fly, slide } from "svelte/transition";
    import { enhance } from "$app/forms";

    let isOpen = false;
    let isThinking = false;
    let query = "";
    let chatHistory: { role: "user" | "arona"; text: string }[] = [
        {
            role: "arona",
            text: "Hello Sensei! I am Arona. How can I help you with your Schale duties today?",
        },
    ];
    let chatContainer: HTMLElement;

    // Use a public placeholder for Arona for now if local asset missing
    // Or check if user has asset.
    // Let's use a nice CSS circle or a reliable placeholder URL.
    const ARONA_AVATAR =
        "https://api.dicebear.com/7.x/avataaars/svg?seed=Arona&style=circle&backgroundColor=b6e3f4";

    async function handleSubmit() {
        if (!query.trim()) return;

        const userMsg = query;
        chatHistory = [...chatHistory, { role: "user", text: userMsg }];
        query = "";
        isThinking = true;

        scrollToBottom();

        try {
            const res = await fetch("/api/arona", {
                method: "POST",
                headers: { "Content-Type": "application/json" },
                body: JSON.stringify({ query: userMsg }),
            });

            const data = await res.json();

            if (data.error) throw new Error(data.error);

            chatHistory = [
                ...chatHistory,
                { role: "arona", text: data.response },
            ];
        } catch (e) {
            chatHistory = [
                ...chatHistory,
                {
                    role: "arona",
                    text: "Sorry Sensei, I lost connection to the server...",
                },
            ];
        } finally {
            isThinking = false;
            scrollToBottom();
        }
    }

    function scrollToBottom() {
        setTimeout(() => {
            if (chatContainer)
                chatContainer.scrollTop = chatContainer.scrollHeight;
        }, 100);
    }
</script>

<div
    class="fixed bottom-6 right-6 z-50 flex flex-col items-end pointer-events-none"
>
    <!-- Chat Window -->
    {#if isOpen}
        <div
            transition:fly={{ y: 20, duration: 300 }}
            class="pointer-events-auto w-80 md:w-96 bg-white/60 dark:bg-gray-900/60 backdrop-blur-xl border border-orange-200/50 dark:border-orange-900/50 rounded-2xl shadow-2xl mb-4 overflow-hidden flex flex-col"
            style="height: 450px;"
        >
            <!-- Header -->
            <div
                class="h-14 bg-orange-500/10 border-b border-orange-100 dark:border-orange-900/30 flex items-center px-5 justify-between relative overflow-hidden"
            >
                <div
                    class="absolute inset-0 bg-linear-to-r from-orange-500/5 to-transparent pointer-events-none"
                ></div>
                <div class="flex items-center gap-3 relative z-10">
                    <div
                        class="w-8 h-8 rounded-lg bg-orange-500 flex items-center justify-center text-white shadow-sm shadow-orange-200 dark:shadow-none"
                    >
                        <span class="material-symbols-outlined text-lg"
                            >smart_toy</span
                        >
                    </div>
                    <div>
                        <h3
                            class="font-bold text-gray-800 dark:text-gray-100 font-display text-sm tracking-tight"
                        >
                            Schale AI
                        </h3>
                        <p
                            class="text-[10px] text-orange-600 dark:text-orange-400 font-mono tracking-widest uppercase font-bold"
                        >
                            A.R.O.N.A System
                        </p>
                    </div>
                </div>
                <button
                    on:click={() => (isOpen = false)}
                    class="text-gray-400 hover:text-orange-500 transition-colors relative z-10"
                >
                    <span class="material-symbols-outlined">close</span>
                </button>
            </div>

            <!-- Messages -->
            <div
                class="flex-1 overflow-y-auto p-4 space-y-4"
                bind:this={chatContainer}
            >
                {#each chatHistory as msg}
                    <div
                        class="flex {msg.role === 'user'
                            ? 'justify-end'
                            : 'justify-start'} animate-fade-in-up"
                    >
                        {#if msg.role === "arona"}
                            <div
                                class="w-8 h-8 rounded-full bg-white dark:bg-gray-800 border border-orange-100 dark:border-gray-700 mr-2 shrink-0 overflow-hidden shadow-sm p-0.5"
                            >
                                <img
                                    src={ARONA_AVATAR}
                                    alt="Arona"
                                    class="w-full h-full object-cover rounded-full"
                                />
                            </div>
                        {/if}
                        <div
                            class="max-w-[85%] rounded-2xl px-4 py-3 text-sm shadow-sm leading-relaxed
                            {msg.role === 'user'
                                ? 'bg-linear-to-br from-orange-500 to-orange-600 text-white rounded-br-none shadow-orange-200 dark:shadow-none'
                                : 'bg-white/80 dark:bg-gray-800/80 text-gray-700 dark:text-gray-200 border border-orange-50 dark:border-gray-700 rounded-bl-none'}"
                        >
                            {msg.text}
                        </div>
                    </div>
                {/each}
                {#if isThinking}
                    <div class="flex justify-start animate-pulse">
                        <div
                            class="w-8 h-8 rounded-full bg-white dark:bg-gray-800 border border-orange-100 dark:border-gray-700 mr-2 shrink-0 flex items-center justify-center shadow-sm"
                        >
                            <span
                                class="animate-spin h-4 w-4 border-2 border-orange-400 border-t-transparent rounded-full"
                            ></span>
                        </div>
                        <div
                            class="text-xs text-orange-400 italic self-center font-mono"
                        >
                            Processing query...
                        </div>
                    </div>
                {/if}
            </div>

            <!-- Input -->
            <div
                class="p-4 bg-white/40 dark:bg-gray-900/40 border-t border-orange-100 dark:border-gray-800 backdrop-blur-md"
            >
                <form
                    on:submit|preventDefault={handleSubmit}
                    class="flex gap-2 relative"
                >
                    <input
                        type="text"
                        bind:value={query}
                        placeholder="Ask Arona..."
                        class="flex-1 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-orange-500/20 focus:border-orange-500 focus:outline-none transition-all shadow-sm"
                    />
                    <button
                        type="submit"
                        class="bg-orange-500 hover:bg-orange-600 text-white rounded-xl px-4 py-2 transition-all shadow-md hover:shadow-lg hover:-translate-y-0.5 active:translate-y-0 disabled:opacity-50 disabled:cursor-not-allowed"
                    >
                        <span class="material-symbols-outlined">send</span>
                    </button>
                </form>
            </div>
        </div>
    {/if}

    <!-- Floating Trigger -->
    <button
        on:click={() => (isOpen = !isOpen)}
        class="pointer-events-auto group relative flex items-center justify-center w-16 h-16 transition-transform hover:scale-105 active:scale-95"
    >
        <span
            class="absolute inset-0 bg-orange-500 rounded-full animate-ping opacity-20 group-hover:opacity-40"
        ></span>
        <div
            class="absolute inset-0 bg-white dark:bg-gray-800 rounded-full shadow-lg border-2 border-orange-100 dark:border-gray-700 overflow-hidden flex items-center justify-center z-10"
        >
            <img
                src={ARONA_AVATAR}
                alt="Arona"
                class="w-14 h-14 object-cover transform translate-y-1 group-hover:scale-110 transition-transform duration-300"
            />
        </div>
        {#if !isOpen}
            <div
                class="absolute top-0 right-0 w-4 h-4 bg-orange-500 rounded-full border-2 border-white dark:border-gray-900 flex items-center justify-center"
            >
                <span
                    class="block w-1.5 h-1.5 bg-white rounded-full animate-pulse"
                ></span>
            </div>
        {/if}
    </button>
</div>
