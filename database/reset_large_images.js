const mysql = require('mysql2/promise');

async function resetLargeImages() {
    const connection = await mysql.createConnection({
        host: 'hayabusa.proxy.rlwy.net',
        port: 38845,
        user: 'root',
        password: 'JLLRBvUzvIUMUiYFJKwmHBhRGsdLHyYM',
        database: 'railway',
    });

    console.log('✅ Connected to Railway MySQL for resetting large images!');

    // Update projects with very large base64 images to default image
    const [result] = await connection.execute(
        "UPDATE projects SET image_path = 'assets/images/projects/p1.jpg' WHERE LENGTH(image_path) > 150000"
    );
    console.log(`  ✓ Reset ${result.affectedRows} projects with large images to default p1.jpg`);

    // Update certifications as well
    const [certResult] = await connection.execute(
        "UPDATE certifications SET image_path = NULL WHERE LENGTH(image_path) > 150000"
    );
    console.log(`  ✓ Reset ${certResult.affectedRows} certifications with large images to NULL`);

    await connection.end();
    console.log('\n🎉 Large images reset complete!');
}

resetLargeImages().catch(err => {
    console.error('Fatal error:', err.message);
    process.exit(1);
});
