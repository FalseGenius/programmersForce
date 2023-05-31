<?php 

    require_once "model/Order.php";

    class OrderController {
        private $model;

        public function __construct() {
            $this->model = new Order();

        }

        public function read($id) {
            $isAdmin = true; 

            if ($isAdmin) {
                $user = $this->model->read($id);

                if ($user) {
                    echo json_encode($user);
                } else {
                    echo "Product not found";
                }
            } else {
                echo "Access denied";
            }
        }
        
        public function create($data) {
            $isAdmin = true; 

            if ($isAdmin) {
                $result = $this->model->createe($data);

                if ($result === true) {
                    echo "Order created successfully";
                } else {
                    echo $result;
                }
            } else {
                echo "Access denied";
            }
        }

        public function update($id, $data) {
            $isAdmin = true; 

            if ($isAdmin) {
                $result = $this->model->update($id, $data);

                if ($result === true) {
                    echo "Order updated successfully";
                } else {
                    echo $result;
                }
            } else {
                echo "Access denied";
            }
        }

        public function delete($id) {
            $isAdmin = true; 

            if ($isAdmin) {
                $result = $this->model->delete($id);

                if ($result === true) {
                    echo "Order deleted successfully";
                } else {
                    echo $result;
                }
            } else {
                echo "Access denied";
            }
            }
        }   


?>

