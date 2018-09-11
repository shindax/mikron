<?php

class krz_collection
{
    private $pdo;
    private $krzs = [];

    public function __construct( $dblocation, $dbname, $dbuser, $dbpasswd  )
    {
        $charset = 'utf8';

        $dsn = "mysql:host=$dblocation;dbname=$dbname;charset=$charset";
        $opt = [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false,
        ];

        try{
            $this -> pdo = new PDO($dsn,$dbuser, $dbpasswd, $opt);
        }
        catch (PDOException $e)
        {
          die("Error in :".__FILE__." file, at ".__LINE__." line. Can't connect : " . $e->getMessage());
        }

        $this -> getData();
    }

    private function getData()
    {
        try
        {
        $query = "SELECT
                  krz.NAME krz_NAME,
                  det.NAME det_NAME,
                  det.OBOZ det_OBOZ,
                  cl.NAME cl_NAME,
                  of.FILENAME of_file,
                  of.TIP_FAIL of_tf
                  FROM okb_db_krz krz
                  INNER JOIN okb_db_clients cl ON cl.ID = krz.ID_clients
                  INNER JOIN okb_db_krzdet det ON det.ID_krz = krz.ID
                  INNER JOIN okb_db_edo_inout_files of ON of.ID_krz = krz.ID
                  WHERE 1
                  ORDER BY cl.NAME, krz.NAME" ;

            $stmt = $this -> pdo->prepare( $query );
            $stmt->execute();
        }
        catch ( PDOException $e )
        {
          die("Error in :".__FILE__." file, at ".__LINE__." line. Can't get data : " . $e->getMessage());
        }

        while ( $row = $stmt->fetch( PDO::FETCH_LAZY ) );
            $this->krzs [] = 'z';
//                [
//                'name' => $row ['krz_NAME'];
//                ];
    }

}