<script lang="ts">
    export let isMe: boolean = false;
    export let content: string;
    export let time: string;
    export let senderName: string = "";
    export let avatar: string = "";
</script>

<div
    class="flex gap-2.5 mb-5 {isMe
        ? 'flex-row-reverse'
        : 'flex-row'} items-end group"
>
    {#if !isMe}
        <img
            src={avatar}
            alt={senderName}
            class="w-8 h-8 rounded-full bg-white dark:bg-gray-800 border border-gray-100 dark:border-gray-700 mb-1 object-cover shadow-sm"
        />
    {/if}

    <div class="flex flex-col gap-1 max-w-[75%]">
        {#if !isMe}
            <span class="text-[10px] text-gray-400 ml-1 font-bold"
                >{senderName}</span
            >
        {/if}

        <div
            class="relative px-5 py-3 shadow-sm text-sm leading-relaxed tracking-wide font-medium
                    {isMe
                ? 'bg-linear-to-br from-orange-500 to-orange-600 text-white rounded-2xl rounded-br-sm shadow-orange-200 dark:shadow-none'
                : 'bg-white dark:bg-gray-800 text-gray-700 dark:text-gray-200 border border-gray-100 dark:border-gray-700 rounded-2xl rounded-bl-sm'}"
        >
            {content}
        </div>

        <span
            class="text-[10px] text-gray-400 font-mono opacity-0 group-hover:opacity-100 transition-opacity {isMe
                ? 'text-right mr-1'
                : 'ml-1'}"
        >
            {time}
        </span>
    </div>
</div>
