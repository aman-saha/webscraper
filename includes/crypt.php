<?php
	include("database_connection.php");
	function generate_salt_string()
    {
        $secret_key = "$BmIEhZCgF/usuoL$";
        $tokenId    = base64_encode(mcrypt_create_iv(32));
        $issued_at = time();
        $notBefore  = $issuedAt + 10;  //Adding 10 seconds
        $expire     = $notBefore + 7200; // Adding 60 seconds
        $data = [
                        'iat'  => $issuedAt,         // Issued at: time when the token was generated
                        'jti'  => $tokenId,          // Json Token Id: an unique identifier for the token
                        'iss'  => $serverName,       // Issuer
                        'nbf'  => $notBefore,        // Not before
                        'exp'  => $expire,           // Expire
                        'data' => [                  // Data related to the logged user you can set your required data
                    'id'        => $user['user_id'], // id from the users table
                    'username'  => $user['username'], //  name
                    'name'      => $user['name'],
                    'email'     =>$user['email']
                                  ]
                    ];
        $token = openssl_encrypt(base64_encode(json_encode($data)), 'AES-128-CBC', $secret_key);
        echo "$token";
        echo openssl_decrypt($token, 'AES-128-CBC', $secret_key);
    }
//hashing of password
    /*function password_hashing($password)
    {
        $hash_password = array();
        $salt_string = generate_salt_string();
        $hashed_password = crypt($password,$salt_string);
        $hash_password['salt_string'] = $salt_string;
        $hash_password['hash_value'] = $hashed_password;
        return $hash_password;
    }*/
    $user = array();
    $user['user_id'] = 5;
    $user['username'] = "aman";
    $user['name'] = "amansaha";
    $user['email'] = "a@gmail.com";
    $b = generate_salt_string($user);
    echo $b;
    echo "<br/>";
    /*echo ;
    function attempt_login($username,$password,$connection)
    {
        $user = array();
        $blowfish_pre = '$2a$05$';
        $blowfish_end = '$';
        $salt_string="@!";
        $username.=$salt_string;
        //echo "$password";
        $query="SELECT user_id,name,email,salt_string,password FROM users WHERE username='$username'";
        echo "$query";
        $result = mysqli_query($connection,$query);
        if(@mysqli_num_rows($result) == 1)
        {
            $row = mysqli_fetch_assoc($result);
            $hashed_password = crypt($password, $row['salt_string']);
            echo "$hashed_password";
            if($hashed_password == $row['password'])
            {
                echo "asdj";
                echo $row['salt_string'];
                $user['user_id'] = $row['user_id'];
                $user['username'] = $username;
                $user['name'] = chop($row['name'],$salt_string);
                $user['email'] = chop($row['email'],$salt_string);
                $user['ip_addr'] = $row['ip_addr'];
                $user['success'] = true;
            }
            else
            {
                $user['success'] = false;
            }
        }
        else
        {
            $user['success'] = false;
        }
        return $user;
    }
    $user = attempt_login('jai','12345678',$connection);*/
    print_r($user);
?>