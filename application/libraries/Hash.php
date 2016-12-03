<?php
class Hash{
    public function hashPass($password){
        $storedPassword = password_hash($password, PASSWORD_BCRYPT,array(
                'cost' => 10
            ));
        return $storedPassword;
    }
    
    public function verifyPass($password,$storedPassword){
        if(password_verify($password,$storedPassword)){
        	return 1;
        }else{
        	return 0;
        }
    }    
}
?>