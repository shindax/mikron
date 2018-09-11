<?php
error_reporting( E_ALL );

function getZakArray( $pdo )
{
    $arr = [];

    $query = "
                        SELECT
                        okb_db_zak_type.description,
                        okb_db_zak.ID AS id,
                        okb_db_zak.DSE_NAME AS dse_name,
                        okb_db_zak.`NAME` AS zak_name
                        FROM
                        okb_db_zak
                        INNER JOIN okb_db_zak_type ON okb_db_zak.TID = okb_db_zak_type.id
                        WHERE
                        #okb_db_zak.PID = 0 
                        #AND
                        okb_db_zak.EDIT_STATE = 0 
                        AND
                        okb_db_zak.INSZ = 1
                        ORDER BY
                        zak_name ASC
                    ";

        try
        {
            $stmt = $pdo->prepare( $query );
            $stmt->execute();
        }
        catch (PDOException $e)
        {
            die("Error in :".__FILE__." file, at ".__LINE__." line. Can't get data : " . $e->getMessage());
        }

        while ( $row = $stmt->fetch(PDO::FETCH_LAZY ) )
            $arr [] =
        [
            'zak_id' => conv( $row['id'] ),
            'zak_type' => conv( $row['description'] ),
            'zak_name' => $row['zak_name'],
            'dse_name' => conv( $row['dse_name'] )
        ];
    return $arr ;
}

