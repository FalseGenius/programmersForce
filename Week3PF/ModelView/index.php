<?php 


    require_once "controller/userController.php";
    require_once "controller/productController.php";
    require_once "controller/ordersController.php";


    
    $method = $_SERVER['REQUEST_METHOD'];

    $userController = new UserController();
    $productController = new ProductController();
    $orderController = new OrderController();



    if ($method == 'GET') {

        $entity = $_GET['entity'];
        $id = $_GET['id'];

        switch ($entity) {

            case 'users':
                $userController->read($id);
                break;
            case 'products':
                $productController->read($id);
                break;
            case 'orders':
                $orderController->read($id);
                break;
        }

    } else if ($method == 'POST') {
        
        $entity = $_POST['entity'];
        $isAdmin = $_POST['isAdmin'] ?? true;
        

        function delete($id, $entity, $userController,$orderController, $categoryController, $productController) {
            switch ($entity) {
                case 'users':
                    $userController->delete($id);
                    break;
                case 'products':
                    $productController->delete($id);
                    break;
                case 'orders':
                    $orderController->delete($id);
                    break;
                default:
                    echo json_encode(['status'=>'F', 'message'=>'Entity does not exist']);
                    break;
            }
            }



        if (isset($_POST['id']) && $_POST['action'] === 'delete') {
            $id = intval($_POST['id']);
            delete($id, $entity, $userController,$orderController, $categoryController, $productController);
        } else {

            switch ($entity) {
                
                case 'users':
                    if (isset($_POST['id'])) {
                        $id = intval($_POST['id']);
                        $userController->update($id, $_POST);
                    } else {

                        $userController->create($_POST);
                    }

                    break;
                case 'products':
                    if (isset($_POST['id'])) {
                        $id = intval($_POST['id']);
                        $productController->update($id, $_POST);
                    } else {
                        $productController->create($_POST);
                    }

                    break;
                case 'orders':
                    if (isset($_POST['id'])) {
                        $id = intval($_POST['id']);
                        $orderController->update($id, $_POST);
                    } else {

                        $orderController->create($_POST);
                    }

                    break;

                default:
                    echo 'Entity does not exist';
                    break;
            }
        }
        
            
        

    }







?>