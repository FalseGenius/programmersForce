<?php
require_once 'database.php';

class Order extends ConnectAndCreate {


    public function read($id) {
        $query = "SELECT * FROM orders WHERE orderid = $id";
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
        $userid = $this->escape($data['userid']);
        $productId = $this->escape($data['productid']);

        $created_at = date('Y-m-d H:i:s'); // Get the current timestamp
        $query = "INSERT INTO orders (userid, productid) VALUES ('$userid', '$productId') RETURNING orderid";
        $result = $this->query($query);

        if ($result) {
            $newProductId = pg_fetch_result($result, 0, 'orderid');
            return json_encode(['status'=>'S', 'message'=>'Order with id: '.$newProductId. " successfully created"]);
        } else {
            // Handle the error case when the query fails
            return null;
        }
    }

    public function update($id, $data) {
        $productid = $this->escape($data['productid']);
        $query = "UPDATE orders SET productid = '$productid' WHERE orderid = $id";
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
        $query = "DELETE FROM orders WHERE orderid = $id";
        $result = $this->query($query);

        if ($result) {
            // Check the number of affected rows to determine if the delete was successful
            $rowsAffected = pg_affected_rows($result);
            if ($rowsAffected > 0) {
                return json_encode(["status"=>"S", "message"=>"Deleted Successfully"]);;
            } else {
                return "Order not found";
            }
        } else {
            // Handle the error case when the query fails
            return null;
        }
    }
}
?>
