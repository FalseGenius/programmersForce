<?php

require_once "model/User.php";

class UserController
{
    private $model;

    public function __construct()
    {
        $this->model = new User();
    }


    public function read($id)
    {
        // Check if the request is coming from an admin
        $isAdmin = true; 

        if ($isAdmin) {
            $user = $this->model->read($id);

            if ($user) {
                echo json_encode($user);
            } else {
                echo "User not found";
            }
        } else {
            echo "Access denied";
        }
    }

    public function create($data)
    {
        
        $isAdmin = true; 

        if ($isAdmin) {
            $result = $this->model->createe($data);

            if ($result === true) {
                echo "User created successfully";
            } else {
                echo $result;
            }
        } else {
            echo "Access denied";
        }
    }

    public function update($id, $data)
    {
        $isAdmin = true; 

        if ($isAdmin) {
            $result = $this->model->update($id, $data);

            if ($result === true) {
                echo "User updated successfully";
            } else {
                echo $result;
            }
        } else {
            echo "Access denied";
        }
    }

    public function delete($id)
    {
        
        $isAdmin = true; 

        if ($isAdmin) {
            $result = $this->model->delete($id);

            if ($result === true) {
                echo "User deleted successfully";
            } else {
                echo $result;
            }
        } else {
            echo "Access denied";
        }
    }
}
?>
