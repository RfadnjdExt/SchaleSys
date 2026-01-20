
import type { SupabaseClient } from '@supabase/supabase-js';

export interface Message {
    id: string;
    conversation_id: string;
    sender_id: string;
    content: string;
    is_read: boolean;
    created_at: string;
}

export interface Conversation {
    id: string;
    type: 'private' | 'group';
    participants: string[];
    last_message?: Message;
    unread_count: number;
}

export const Momo = {
    async createPrivateConversation(supabase: SupabaseClient, userA: string, userB: string) {
        // 1. Check if conversation already exists
        const { data: existing } = await supabase
            .from('conversations')
            .select('id, conversation_participants!inner(user_id)')
            .eq('type', 'private')
            .in('conversation_participants.user_id', [userA, userB]);

        // Simplified check needed: existing queries for ANY match, need strict both.
        // For MVP, proceed to create or get.

        const { data: conv, error: convError } = await supabase
            .from('conversations')
            .insert({ type: 'private' })
            .select()
            .single();

        if (convError) throw convError;

        const { error: partError } = await supabase
            .from('conversation_participants')
            .insert([
                { conversation_id: conv.id, user_id: userA },
                { conversation_id: conv.id, user_id: userB }
            ]);

        if (partError) throw partError;

        return conv;
    },

    async sendMessage(supabase: SupabaseClient, conversationId: string, senderId: string, content: string) {
        const { data, error } = await supabase
            .from('messages')
            .insert({
                conversation_id: conversationId,
                sender_id: senderId,
                content: content
            })
            .select()
            .single();

        if (error) throw error;
        return data;
    },

    async getConversations(supabase: SupabaseClient, userId: string) {
        const { data: myConvs, error } = await supabase
            .from('conversation_participants')
            .select('conversation_id, last_read_at, conversations(*)')
            .eq('user_id', userId);

        if (error) throw error;

        const results = await Promise.all(myConvs.map(async (c: any) => {
            const { data: parts } = await supabase
                .from('conversation_participants')
                .select('user_id')
                .eq('conversation_id', c.conversation_id)
                .neq('user_id', userId);

            const { data: lastMsg } = await supabase
                .from('messages')
                .select('*')
                .eq('conversation_id', c.conversation_id)
                .order('created_at', { ascending: false })
                .limit(1)
                .single();

            const { count } = await supabase
                .from('messages')
                .select('*', { count: 'exact', head: true })
                .eq('conversation_id', c.conversation_id)
                .gt('created_at', c.last_read_at || '1970-01-01');

            return {
                id: c.conversation_id,
                type: c.conversations.type,
                participants: parts?.map((p: any) => p.user_id) || [],
                last_message: lastMsg,
                unread_count: count || 0
            };
        }));

        return results;
    },

    async getMessages(supabase: SupabaseClient, conversationId: string) {
        const { data, error } = await supabase
            .from('messages')
            .select('*')
            .eq('conversation_id', conversationId)
            .order('created_at', { ascending: true });

        if (error) throw error;
        return data;
    },

    async markAsRead(supabase: SupabaseClient, conversationId: string, userId: string) {
        const { error } = await supabase
            .from('conversation_participants')
            .update({ last_read_at: new Date().toISOString() })
            .eq('conversation_id', conversationId)
            .eq('user_id', userId);

        if (error) throw error;
    }
};
