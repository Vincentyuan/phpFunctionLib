//with  pdo 
function tableExists($connec, $table){
  try{
    $queryStr = "SELECT 1 FROM $table LIMIT 1";
      // error_log($queryStr);
    $statement=$connec->prepare($queryStr);
    $statement->execute();
    $output=$statement->fetchAll(PDO::FETCH_ASSOC);
  }
  catch(Exception $e){
    return false;
  }
  return $output!== false;
}


    function selectByConditionFromTable($connec, $table, $condition, $keyId, $cols){
        if($cols == null)
            $cols ="*";
        $queryStr = "SELECT $cols FROM $table $condition";

        //  error_log($queryStr);
        $statement=$connec->prepare($queryStr);
        $statement->execute();
        $output=$statement->fetchAll(PDO::FETCH_ASSOC);
        if($keyId == null)
            return $output;
        else {
            $response = null;
            foreach ($output as $o){
                $response[$o[$keyId]] = $o;
            }
            return $response;
        }

    }

   function selectColsByConditionFromTable($connec, $table, $cols, $condition, $keyId){

        $queryStr = "SET SQL_BIG_SELECTS=1";
        $statement=$connec->prepare($queryStr);
          // error_log($queryStr);
        $statement->execute();

        $queryStr = "SELECT $cols FROM $table $condition";
          // error_log($queryStr);
        $statement=$connec->prepare($queryStr);
        $statement->execute();
        $output=$statement->fetchAll(PDO::FETCH_ASSOC);
        if($keyId == null)
            return $output;
        else {
            $response = null;
            foreach ($output as $o){
                $response[$o[$keyId]] = $o;
            }
            return $response;
        }

    }

    function selectColumnsNameFromTable($connec, $table){
        $queryStr = "SHOW COLUMNS FROM $table";
        $statement=$connec->prepare($queryStr);
        $statement->execute();
        $output=$statement->fetchAll(PDO::FETCH_ASSOC);
        return $output;
    }


    function selectOneByConditionFromTable($connec, $table, $condition){

    $results = selectByConditionFromTable($connec, $table, $condition, null, null);
    $data = reset( $results);
    if($data == false){
        echo("Could not find any data in table : $table, with condition : $condition");
        return null;
    }
    else return $data;
    }
function isDataExistInTable($connec,$table,$columns,$data){
  //$connec = getDbConnection();
  $where = "where ";
  $dataLength = count($columns);
  for ($i=0; $i <$dataLength ; $i++) {
    $where=$where.$columns[$i]."=".$data[$i];
    if (($i+1) < $dataLength) {
      $where = $where." and ";
    }
  }

  $result = selectByConditionFromTable($connec,$table,$where,"","");

  //closeConnection($connec);
  if (count($result) > 0) {
    return true;
  }else {
    return false;
  }



}