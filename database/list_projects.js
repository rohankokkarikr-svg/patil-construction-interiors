const mysql = require('mysql2/promise');

async function listProjects() {
    const connection = await mysql.createConnection({
        host: 'hayabusa.proxy.rlwy.net',
        port: 38845,
        user: 'root',
        password: 'JLLRBvUzvIUMUiYFJKwmHBhRGsdLHyYM',
        database: 'railway',
    });
    const [rows] = await connection.execute("SELECT id, title, category, image_path FROM projects ORDER BY id ASC");
    console.log(JSON.stringify(rows, null, 2));
    await connection.end();
}
listProjects();
