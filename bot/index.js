import 'dotenv/config';
import { Client, GatewayIntentBits } from 'discord.js';
import mysql from 'mysql2/promise';

const required = ['DISCORD_BOT_API_KEY', 'DISCORD_GUILD_ID', 'DB_HOST', 'DB_PORT', 'DB_DATABASE', 'DB_USERNAME', 'DB_PASSWORD'];
for (const key of required) {
  if (!process.env[key]) {
    throw new Error(`Missing required env variable: ${key}`);
  }
}

const adminRoleIds = (process.env.DISCORD_ADMIN_ROLE_IDS || '')
  .split(',')
  .map((v) => v.trim())
  .filter(Boolean);

const intervalMs = Number(process.env.DISCORD_SYNC_INTERVAL_SECONDS || 300) * 1000;

const client = new Client({
  intents: [GatewayIntentBits.Guilds, GatewayIntentBits.GuildMembers],
});

const db = await mysql.createPool({
  host: process.env.DB_HOST,
  port: Number(process.env.DB_PORT),
  database: process.env.DB_DATABASE,
  user: process.env.DB_USERNAME,
  password: process.env.DB_PASSWORD,
  waitForConnections: true,
  connectionLimit: 10,
});

async function ensureRoleMappings() {
  for (const roleId of adminRoleIds) {
    await db.execute(
      `INSERT INTO discord_role_mappings (discord_role_id, role_name, permission_key, created_at, updated_at)
       VALUES (?, ?, 'calendar_manage', NOW(), NOW())
       ON DUPLICATE KEY UPDATE role_name = VALUES(role_name), updated_at = NOW()`,
      [roleId, 'Discord Calendar Admin']
    );
  }
}

async function syncGuildRoles() {
  const guild = await client.guilds.fetch(process.env.DISCORD_GUILD_ID);
  await guild.members.fetch();

  await db.execute('DELETE FROM discord_user_roles');

  for (const member of guild.members.cache.values()) {
    const roles = member.roles.cache.map((role) => role.id);
    for (const roleId of roles) {
      await db.execute(
        `INSERT INTO discord_user_roles (discord_user_id, role_id, created_at, updated_at)
         VALUES (?, ?, NOW(), NOW())
         ON DUPLICATE KEY UPDATE updated_at = NOW()`,
        [member.id, roleId]
      );
    }

    const isCalendarAdmin = roles.some((roleId) => adminRoleIds.includes(roleId)) ? 1 : 0;
    await db.execute(
      `UPDATE users SET is_calendar_admin = ? WHERE discord_id = ?`,
      [isCalendarAdmin, member.id]
    );
  }

  console.log(`Role sync complete for ${guild.members.cache.size} members.`);
}

client.once('ready', async () => {
  console.log(`Bot connected as ${client.user.tag}`);
  await ensureRoleMappings();
  await syncGuildRoles();
  setInterval(syncGuildRoles, intervalMs);
});

client.login(process.env.DISCORD_BOT_API_KEY);
