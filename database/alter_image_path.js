const mysql = require('mysql2/promise');

async function alterTable() {
    const connection = await mysql.createConnection({
        host: 'hayabusa.proxy.rlwy.net',
        port: 38845,
        user: 'root',
        password: 'JLLRBvUzvIUMUiYFJKwmHBhRGsdLHyYM',
        database: 'railway',
    });

    console.log('✅ Connected to Railway MySQL for altering columns!');

    // Alter projects.image_path
    await connection.execute('ALTER TABLE projects MODIFY image_path LONGTEXT');
    console.log('  ✓ Altered projects.image_path column to LONGTEXT');

    // Alter certifications.image_path
    await connection.execute('ALTER TABLE certifications MODIFY image_path LONGTEXT');
    console.log('  ✓ Altered certifications.image_path column to LONGTEXT');

    await connection.end();
    console.log('\n🎉 Column modification complete!');
}

alterTable().catch(err => {
    console.error('Fatal error:', err.message);
    process.exit(1);
});
