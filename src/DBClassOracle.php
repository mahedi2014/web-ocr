<?php

/**
 * Database connection establishment.
 * Class DBClass
 */
class DBClass {
    public $host = '';
    public $userName = '';
    public $password = '';
    public $service = '';
    public $port = '';

    public $statement = '';
    public $procOut;

    private $tns, $conn;
     function  __construct() {
         $this->host = '';  //Host name or IP address
         $this->service = 'orcl';  //Service Name'orcl'
         $this->port = '1521';  //Orcl Port
         $this->userName = 'web_ocr'; //Username
         $this->password = 'web_ocr'; //Password

         $this->orcConnect();
     }


    public function orcConnect(){
        $this->tns = "(DESCRIPTION = (ADDRESS = (PROTOCOL = TCP)(HOST = $this->host)(PORT = $this->port)) (CONNECT_DATA = (SERVER = DEDICATED)(SERVICE_NAME =  $this->service)))";

        if(!$this->conn = oci_connect($this->userName , $this->password,  $this->tns)){
            $error = oci_error();
            //echo $error['message'];
            //echo $error['code'];
            die('Error: Could not connect to database.');
        }
    }

    /**
     * Run a query and execute
     * @param $query,  example: 'select * from table_name'
     * @return bool|resource, bool false if not correct sql other wise resource.
     */
    public function doQuery($query)
    {
        $result = oci_parse($this->conn, $query);
        $reCal = oci_execute($result,  OCI_NO_AUTO_COMMIT);
        if( !$reCal ){
            return false;

        }else{
            return $result;
        }
    }

    /**
     * Parse a procedure as statement
     * @param $queryProcedure example: $queryProcedure = "begin procedureName('$inputVar_1','$inputVar_2', :outputDBVariable); end;";
     * @return resource
     */
    public function doProce($queryProcedure){
        $this->statement = oci_parse($this->conn, $queryProcedure);
        return $this->statement;
    }

    /**
     * Bind data with php variable what bdVariable value want in which php variable
     * @param string $outputVariableData Example: $outputVariableData = array( 'key'=>'outputDBVariable' );
     *
     */
    public function dataBindProce($outputVariableData =''){
        foreach($outputVariableData as $key => $value){
            oci_bind_by_name($this->statement, $value,  $this->procOut[$key], 200);
        }
    }

    /**
     * Execute procedure after calling doProce() and dataBindProce();
     * @return bool, array if run properly other wise return false
     */
    public function exeProc(){
        $result = oci_execute($this->statement, OCI_NO_AUTO_COMMIT);
        $this->ociClose();

        if($result){
            return $this->procOut;
        }else{
            return false;
        }

    }

    /**
     * Commit a executed sql query
     * @param null $result
     * @return bool
     */
    public function save($result=null)
    {
        if(oci_commit($this->conn)){
            if($result != null){
                oci_free_statement($result);
            }
            return true;

        } else{
            return false;
        }

    }

    /**
     * Make a statement free from session
     * @param $result
     * @return bool
     */
    public  function statementFree($result){
        oci_free_statement($result);
        return true;
    }

    /**
     * Fetch select query parse table as array data, row fetched by sql doQuery($sql);.
     * @param $result
     * @return mixed
     */
    public function fetchArray($result)
    {
        oci_fetch_all($result, $row, null, null, OCI_FETCHSTATEMENT_BY_ROW);

        return $row;
    }

    /**
     * Count number of row fetched by sql doQuery();
     * @param $result  doQuery($sql)
     * @return int
     */
    public function numOfRows($result)
    {
        $numberOfRows =  oci_fetch_all($result, $row);
        return $numberOfRows;

    }


    /**
     * Oracle connect close
     */
    public function ociClose(){
        oci_close($this->conn);
    }
}
