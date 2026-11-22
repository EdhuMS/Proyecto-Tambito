<?php
declare(strict_types=1);

// CI-only: fresh isolated MySQL service credentials from workflow environment.
$rootPassword = getenv('TAMBITO_TEST_MYSQL_ROOT_PASSWORD');
if (!$rootPassword) {
    fwrite(STDERR, "Missing test MySQL root password\n");
    exit(1);
}
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
$db = new mysqli('127.0.0.1', 'root', $rootPassword);
$db->set_charset('utf8mb4');
if (($argv[1] ?? '') === 'promote') {
    $db->query("UPDATE tienda_tambo.usuarios SET rol='admin' WHERE email='demo@example.test'");
    if ($db->affected_rows !== 1) {
        fwrite(STDERR, "Expected one registered demo user for role promotion\n");
        exit(1);
    }
    echo "Test user promoted to admin\n";
    exit;
}

$schema = file_get_contents(__DIR__ . '/../scritpSQL/tienda_tambo.sql');
if ($schema === false || !$db->multi_query($schema)) {
    throw new RuntimeException('Could not import the integration schema');
}
do {
    if ($result = $db->store_result()) $result->free();
} while ($db->more_results() && $db->next_result());

$db->query("CREATE USER IF NOT EXISTS 'tambito_app'@'%' IDENTIFIED BY 'ephemeral-ci-app-password'");
$db->query("GRANT SELECT, INSERT, UPDATE, DELETE ON tienda_tambo.* TO 'tambito_app'@'%'");
$config = [
    'host' => '127.0.0.1',
    'port' => 3306,
    'user' => 'tambito_app',
    'password' => 'ephemeral-ci-app-password',
    'database' => 'tienda_tambo',
];
file_put_contents(__DIR__ . '/../config.local.php', "<?php\nreturn " . var_export($config, true) . ";\n");
echo "Fresh CI schema and temporary application configuration created\n";
