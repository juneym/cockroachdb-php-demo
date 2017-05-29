<?php
header("Content-Type: text/plain");
//cockroachdb demo code

try {

    $dbh = new PDO(
        'pgsql:host=192.168.0.105;port=26257;dbname=bank;sslmode=disable',
        'maxroach',
        null,
        array(
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_EMULATE_PREPARES => true,
        )
    );

    $acctCreated = 0;
    while ($acctCreated <= 100000) {
        try {
            $dbh->exec('INSERT INTO accounts (id, balance) VALUES (' . rand(0,999999) . ', ' . rand(1,1000000) . ')');
            $acctCreated++;
            echo "New accounts created: $acctCreated \n";
        } catch (PDOException $pdoe) {
            if ($pdoe->getCode() != '23505') {
                //give up if the exception is not "Duplicate key"
                throw $pdoe;
            } else {
                echo "Duplicate account exists\n";
            }
        }
    } //while

    print "Account balances:\r\n";
    foreach ($dbh->query('SELECT id, balance FROM accounts') as $row) {
        print $row['id'] . ': ' . $row['balance'] . "\r\n";
    }


}  catch (Exception $e) {
    print $e->getMessage() . "\r\n";
    exit(1);
}
