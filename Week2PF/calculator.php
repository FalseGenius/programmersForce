<?php 
    

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        if (!isset($_POST['int1']) or !isset($_POST['int2']) or !isset($_POST['op'])) {
            http_response_code(400);
            print json_encode(["status"=>'F', "message"=>'One of the fields are empty']);
        } else {
            $calculator = new Calculator($_POST['int1'], $_POST['int2'], $_POST['op']);
            print json_encode([
                "status"=>'F',
                 "message"=>$calculator->calculate_result()]);
        }
    }
    class Calculator {
        private $int1;
        private $int2;
        private $result;
        private $operator;

        function __construct($firstInt, $secondInt, $mathematicalOperator) {
            $this->int1 = $firstInt;
            $this->int2 = $secondInt;
            $this->operator = $mathematicalOperator;
        } 

        public function calculate_result() {
            $calculate = new Calculator($this->int1, $this->int2, $this->operator);
            print $calculate->result();
        }

        public function result() {
            switch ($this->operator) {
                case '*':
                    $this->result = $this->int1 * $this->int2;
                    break;
                case '+':                        
                    $this->result = $this->int1 + $this->int2;
                    break;
                case '-':
                    $this->result = $this->int1 - $this->int2;
                    break;
                case '/':
                    if ($this->int2 == 0) {
                        $this->result = -1;
                    } else {
                        $this->result = $this->int1 / $this->int2;
                    }
                    break;
                default:
                    $this->result = 0;
            }

            return $this->result;
        }

    }

?>