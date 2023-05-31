<?php
require_once 'database.php';

class Product extends ConnectAndCreate {


    public function read($id) {
        $query = "SELECT * FROM products WHERE product_id = $id";
        $result = $this->query($query);
        
        if ($result) {
            $userData = pg_fetch_assoc($result);
            return $userData;
        } else {
            // Handle the error case when the query fails
            return null;
        }
    }


    public function createe($data) {
        $name = $this->escape($data['productName']);

        $created_at = date('Y-m-d H:i:s'); // Get the current timestamp
        $query = "INSERT INTO products (productName) VALUES ('$name') RETURNING product_id";
        $result = $this->query($query);

        if ($result) {
            $newProductId = pg_fetch_result($result, 0, 'product_id');
            return json_encode(['status'=>'S', 'message'=>'Product with id: '.$newProductId. " successfully created"]);
        } else {
            // Handle the error case when the query fails
            return null;
        }
    }

    public function update($id, $data) {
        $name = $this->escape($data['product_name']);
        $query = "UPDATE products SET productname = '$name' WHERE product_id = $id";
        $result = $this->query($query);

        if ($result) {
            // Check the number of affected rows to determine if the update was successful
            $rowsAffected = pg_affected_rows($result);
            if ($rowsAffected > 0) {
                return json_encode(["status"=>"S", "message"=>"Updated Successfully"]);
            } else {
                return "User not found";
            }
        } else {
            // Handle the error case when the query fails
            return null;
        }
    }

    public function delete($id) {
        $query = "DELETE FROM products WHERE product_id = $id";
        $result = $this->query($query);

        if ($result) {
            // Check the number of affected rows to determine if the delete was successful
            $rowsAffected = pg_affected_rows($result);
            if ($rowsAffected > 0) {
                return json_encode(["status"=>"S", "message"=>"Deleted Successfully"]);;
            } else {
                return "Product not found";
            }
        } else {
            // Handle the error case when the query fails
            return null;
        }
    }
}
?>
