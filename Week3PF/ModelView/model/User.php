<?php
require_once 'database.php';

class User extends ConnectAndCreate {


    public function read($id) {
        $query = "SELECT * FROM users WHERE uid = $id";
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
        $name = $this->escape($data['name']);
        $email = $this->escape($data['email']);
        $created_at = date('Y-m-d H:i:s'); // Get the current timestamp
        $query = "INSERT INTO users (name, email, created_at) VALUES ('$name', '$email', '$created_at') RETURNING uid";
        $result = $this->query($query);

        if ($result) {
            $newUserId = pg_fetch_result($result, 0, 'uid');
            return $newUserId;
        } else {
            // Handle the error case when the query fails
            return null;
        }
    }

    public function update($id, $data) {
        $name = $this->escape($data['name']);
        $email = $this->escape($data['email']);
        $query = "UPDATE users SET name = '$name', email = '$email' WHERE uid = $id";
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
        $query = "DELETE FROM users WHERE uid = $id";
        $result = $this->query($query);

        if ($result) {
            // Check the number of affected rows to determine if the delete was successful
            $rowsAffected = pg_affected_rows($result);
            if ($rowsAffected > 0) {
                return json_encode(["status"=>"S", "message"=>"Deleted Successfully"]);;
            } else {
                return "User not found";
            }
        } else {
            // Handle the error case when the query fails
            return null;
        }
    }
}
?>
