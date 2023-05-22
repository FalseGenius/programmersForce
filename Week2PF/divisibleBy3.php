<?php 

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {

        $data = json_decode(file_get_contents('php://input'), true);

        print json_encode(['status'=>'S', "message"=>divisibleByThree($data['arr'])]);


        // if (!isset($_POST['arr'])) {
        //     http_response_code(400);

        //     print json_encode(['status'=>'F', "message"=>$data['arr']]);
        //     // print json_encode(['status'=>'F', "message"=>"Input cannot be empty"]);
        // } else {
        //     $result = divisibleByThree($_POST['arr']);
        //     print json_encode(['status'=>'F', "message"=>$data]);
        //     // print json_encode(['status'=>'F', "message"=>$result]);
        // }
    }


    function divisibleByThree($arr) {
        $count = 0;
        foreach($arr as $item) {
            if ($item >= 300){
                return 0;
            }

            if ($item == 0 or $item % 3 == 0) {
                $count += 1;
            }
        }

        return $count;

}

?>

