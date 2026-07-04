const mysql = require('mysql2/promise');

async function updateImages() {
    const connection = await mysql.createConnection({
        host: 'hayabusa.proxy.rlwy.net',
        port: 38845,
        user: 'root',
        password: 'JLLRBvUzvIUMUiYFJKwmHBhRGsdLHyYM',
        database: 'railway',
    });

    console.log('✅ Connected to Railway MySQL for updating project images!');

    // Update Project ID 1
    await connection.execute(
        "UPDATE projects SET image_path = 'assets/images/projects/uploaded_3.jpg' WHERE id = 1"
    );
    console.log('  ✓ Updated Project 1 with Photo 3');

    // Update Project ID 2
    await connection.execute(
        "UPDATE projects SET image_path = 'assets/images/projects/uploaded_2.jpg' WHERE id = 2"
    );
    console.log('  ✓ Updated Project 2 with Photo 2');

    // Update Project ID 3
    await connection.execute(
        "UPDATE projects SET image_path = 'assets/images/projects/uploaded_4.jpg' WHERE id = 3"
    );
    console.log('  ✓ Updated Project 3 with Photo 4');

    // Update Project ID 5
    await connection.execute(
        "UPDATE projects SET image_path = 'assets/images/projects/uploaded_1.jpg' WHERE id = 5"
    );
    console.log('  ✓ Updated Project 5 with Photo 1');

    // Update Project ID 6
    await connection.execute(
        "UPDATE projects SET image_path = 'assets/images/projects/uploaded_5.jpg' WHERE id = 6"
    );
    console.log('  ✓ Updated Project 6 with Photo 5');

    await connection.end();
    console.log('\n🎉 Project images update complete!');
}

updateImages().catch(err => {
    console.error('Fatal error:', err.message);
    process.exit(1);
});
