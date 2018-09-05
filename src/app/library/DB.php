<?php
/**
 * Created by PhpStorm.
 * User: davit
 * Date: 16-Aug-18
 * Time: 10:08 PM
 */

namespace app\library;


use app\Job;
use app\models\Jobs;

class DB
{

    protected $dbh;

    function __construct()
    {
        // connection settings
        $this->db_host = DB_HOST;
        $this->db_user = DB_USER;
        $this->db_pass = DB_PASSWORD;
        $this->db_name = DB_NAME;

        $this->dbh = $this->dbConnect();
    }


    /**
     * @return \PDO
     */
    function dbConnect()
    {

        try {

            /**
             * connects to the database -
             * the last line makes a persistent connection, which
             * caches the connection instead of closing it
             */
            $dbh = new \PDO("mysql:host=$this->db_host;dbname=$this->db_name",
                $this->db_user, $this->db_pass);


            return $dbh;

        } catch (PDOException $e) {
            print "Error!: " . $e->getMessage() . "<br/>";


        }
    }


    public function insert(string $table, array $data)
    {
        $query = 'INSERT INTO ' . Jobs::TABLE . ' SET url = :url, status = :status, priority = :priority';

        $statement = $this->dbh->prepare($query);

        return $statement->execute($data);

    }

    /**
     * @param string $table
     * @param array $columns
     * @param array $where
     * @param string $sortBy
     * @param string $sortType
     * @param int $limit
     * @param int $page
     * @return array|mixed
     */
    public function select(
        string $table,
        array $columns,
        array $where = [],
        string $sortBy = 'id',
        string $sortType = 'DESC',
        int $limit = 1,
        int $page = 1
    )
    {
        $selectMultiple = ($limit > 1);
        // connect to db


        $whereColumns = [];
        $whereValues = [];

        $whereStr = "";
        if (!empty($where)) {

            foreach ($where as $col => $val) {
                $col = "$col = $val";
                array_push($whereColumns, $col);
                array_push($whereValues, $val);
            }

            $whereColumns = implode(' AND ', $whereColumns);
            $whereStr = " WHERE " . $whereColumns;
        }

        // comma separated list
        $columns = implode(",", $columns);

        $query = "SELECT $columns FROM $table $whereStr  ORDER BY $sortBy $sortType";

        $stmt = $this->dbh->prepare($query);
        $stmt->execute();

        if (!$selectMultiple) {
            if ($result = $stmt->fetch(\PDO::FETCH_OBJ)) {
                return $result;
            }

            return [];
        } else {
            $results = [];
            while ($row = $stmt->fetch(DB::FETCH_OBJ)) {
                array_push($results, $row);
            }

            return $results;
        }
    }

    /**
     * @param string $table
     * @param int $id
     * @param array $data
     * @return bool
     */
    public function update(string $table, int $id, array $data = [])
    {
        $updateFields = [];
        foreach ($data as $key => $item) {
            $updateFields[] = $key . "='$item'";
        }

        $updateStr = implode(", ", $updateFields);

        $query = "UPDATE $table SET $updateStr WHERE id=?";

        $stmt = $this->dbh->prepare($query);
        $stmt->execute([$id]);

        if ($stmt->rowCount()) {
            return true;
        }
        return false;
    }

    /**
     * @param string $query
     * @param array $params
     * @return mixed
     */
    public function execute(string $query, array $params = [])
    {

        try {
            $this->dbh->beginTransaction();
            $this->dbh->query($query); // no need for prepare/execute since there are no parameters
            $stmt = $this->dbh->query("SELECT @update_id as id");
            $res = $stmt->fetch(\PDO::FETCH_ASSOC);
            $id = $res['id'];
            $this->dbh->commit();
        } catch (Exception $e) {
            $this->dbh->rollBack();
        }

        return $id;
    }
}