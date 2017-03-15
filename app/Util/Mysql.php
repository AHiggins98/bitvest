<?php

namespace App\Util;

use App\Util\Config;

/**
 * @codeCoverageIgnore
 */
class Mysql
{
    private static $mysqli;
    private $host;
    private $user;
    private $pass;
    private $name;
    
    public function __construct(Config $config)
    {
        $this->host = $config->get('dbHost');
        $this->user = $config->get('dbUser');
        $this->pass = $config->get('dbPass');
        $this->name = $config->get('dbName');
    }

    private function getMysqli()
    {
        if (!isset(self::$mysqli)) {
            self::$mysqli = new \mysqli($this->host, $this->user, $this->pass, $this->name);
            if (mysqli_connect_errno()) {
                printf("Connect failed: %s\n", mysqli_connect_error());
                exit();
            }
        }
        return self::$mysqli;
    }

    public function query($query, $types = '', $params = [])
    {
        $stmt = $this->getMysqli()->prepare($query);
        
        if (!$stmt) {
            throw new \Exception($this->getMysqli()->error);
        }
        
        if (!empty($params)) {
            $refs = [];
            
            foreach ($params as $key => $param) {
                $refs[$key] = &$params[$key];
            }
            
            $bind = array_merge([$types], $refs);
            call_user_func_array([$stmt, 'bind_param'], $bind);
        }
       
        $stmt->execute();
        
        $result = $stmt->get_result();
        
        if (!$result && $stmt->errno) {
            throw new \Exception('Mysql error: ' . $stmt->error);
        }
        
        if (!$result) {
            return $stmt->affected_rows;
        }
        
        $rows = [];
        
        while ($myrow = $result->fetch_assoc()) {
            $rows[] = $myrow;
        }
        
        $stmt->close();
        
        return $rows;
    }
    
    public function upgradeDb()
    {
        while ($this->upgradeCurrentHash());
        echo "--------------------------------------\n";
        echo "All done! Database upgrade successful.\n";
        echo "--------------------------------------\n";
    }
    
    private function upgradeCurrentHash()
    {
        $hash = $this->getDbHash();
        echo "Current DB hash is $hash\n";
        
        $mysqlParams = "-u {$this->user} -p{$this->pass} -h{$this->host} {$this->name}";
        
        $upgradeFile = __DIR__ . "/../../sql/upgrade/$hash.sql";
        
        echo "Looking for $upgradeFile... ";
        
        if (file_exists($upgradeFile)) {
            echo "Found.\n";
            echo "Creating DB backup.\n";
            $backupFile = __DIR__ . "/../../sql/backups/backup-" . date("Y-m-d-H-i-s") . ".sql";
            $dumpParams = "--single-transaction --routines --triggers --events";
            $this->run("mysqldump $mysqlParams $dumpParams > $backupFile");
            
            echo "Compressing DB backup.\n";
            $this->run("gzip $backupFile");
            $this->run("ls -l $backupFile.gz");
            
            // Sanity check
            if (filesize($backupFile . '.gz') < 1000) {
                throw new \Exception("Failed to backup (file too small? empty backup?)");
            }
            
            echo "Applying schema changes.\n";
            $this->run("cat $upgradeFile && cat $upgradeFile | mysql $mysqlParams");
            $newHash = $this->getDbHash();
            echo "New DB hash is $newHash\n";
            
            if ($newHash == $hash) {
                throw new \Exception("No changes, empty upgrade script?");
            }
            
            return true;
        } else {
            echo "Not found\n";
        }
        
        return false;
    }
    
    private function run($cmd)
    {
        echo "Running: $cmd\n";
        exec($cmd, $output, $ret);
        echo "Output: \n" . implode("\n", $output) . "\n";
        echo "Return value: " . $ret . "\n";
        if ($ret !== 0) {
            throw new \Exception("Command returned non-zero value, exiting");
        }
    }
    
    private function getDbHash()
    {
        $rows = $this->query('show tables');
        
        $tableNames = [];
        foreach ($rows as $table) {
            foreach ($table as $tableName) {
                $tableNames[] = $tableName;
            }
        }
        
        sort($tableNames);
        
        $tableCreateStatements = [];
        
        foreach ($tableNames as $tableName) {
            $output = $this->query("show create table $tableName");
            $tableCreateStatements[$tableName] = $output[0]['Create Table'];
        }
        
        $hash = md5(serialize($tableCreateStatements));
        
        return $hash;
    }
}
