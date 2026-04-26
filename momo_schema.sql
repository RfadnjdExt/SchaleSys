-- MomoTalk Schema

-- 1. Table: conversations
CREATE TABLE IF NOT EXISTS conversations (
  id UUID PRIMARY KEY DEFAULT gen_random_uuid(),
  type VARCHAR(20) NOT NULL CHECK (type IN ('private', 'group')) DEFAULT 'private',
  created_at TIMESTAMP WITH TIME ZONE DEFAULT NOW(),
  updated_at TIMESTAMP WITH TIME ZONE DEFAULT NOW()
);

-- 2. Table: conversation_participants
CREATE TABLE IF NOT EXISTS conversation_participants (
  conversation_id UUID NOT NULL,
  user_id VARCHAR(50) NOT NULL, -- References users(username) or users(id_user) depending on auth implementation. Assuming username based on schema_pgsql.sql
  joined_at TIMESTAMP WITH TIME ZONE DEFAULT NOW(),
  last_read_at TIMESTAMP WITH TIME ZONE DEFAULT NOW(),
  PRIMARY KEY (conversation_id, user_id),
  CONSTRAINT fk_cp_conversation FOREIGN KEY (conversation_id) REFERENCES conversations (id) ON DELETE CASCADE
  -- Note: Foreign key to users table might be tricky if ID is serial but references are mixed. 
  -- Checking schema_pgsql.sql, users.username is unique, but users.id_user is PK.
  -- Let's check how Auth is handled. schema_pgsql users table has id_user (serial).
  -- Ideally we reference id_user (int) or uuid if using Supabase Auth.
  -- Since this is "Supabase" but using custom users table? verify first.
);

-- 3. Table: messages
CREATE TABLE IF NOT EXISTS messages (
  id UUID PRIMARY KEY DEFAULT gen_random_uuid(),
  conversation_id UUID NOT NULL,
  sender_id VARCHAR(50) NOT NULL,
  content TEXT NOT NULL,
  is_read BOOLEAN DEFAULT FALSE,
  created_at TIMESTAMP WITH TIME ZONE DEFAULT NOW(),
  CONSTRAINT fk_messages_conversation FOREIGN KEY (conversation_id) REFERENCES conversations (id) ON DELETE CASCADE
);

-- Indexes for performance
CREATE INDEX IF NOT EXISTS idx_messages_conversation_id ON messages (conversation_id);
CREATE INDEX IF NOT EXISTS idx_cp_user_id ON conversation_participants (user_id);
