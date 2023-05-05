<?php
include "config.php";
include "DB.php";

ini_set('display_errors', '0');
error_reporting(0);

use \Firebase\JWT\JWT;
use Firebase\JWT\Key;

date_default_timezone_set('Europe/Istanbul');
class MYSQL_DB extends DB
{
    public function register($user)
    {
        $user['password'] = password_hash($user["password"], PASSWORD_DEFAULT);
        $this->query("INSERT INTO users (name, email, password, status) VALUES (:n, :e, :p, 1)", array("n" => $user['name'], "e" => $user['email'], "p" => $user['password']));
        $user['id'] = $this->get_last_id();
        unset($user['password']);
        return $this->generateToken($user);
    }

    public function login($email)
    {
        return $this->row("SELECT * FROM users WHERE email = :e", array("e" => $email));
    }

    public function generateToken($user) {
        $payload = array(
            "iss" => "localhost",
            "aud" => "localhost",
            "iat" => time(),
            "nbf" => time(),
            "exp" => time() + 86400,
            "data" => $user
        );
        $token = JWT::encode($payload, SECRET_KEY, "HS256");
        return $token;
    }
    
    public function decode($token) {
        try {
            $decoded = JWT::decode($token, SECRET_KEY, array("HS256"));
            return $decoded;
        } catch (\Exception $th) {
            return false;
        }
    }

    public function getUser($id)
    {
        $user = $this->row("SELECT * FROM users WHERE id = :id", array("id" => $id));
        unset($user['password']);
        return $user;
    }

    public function create($post)
    {
        $this->query("INSERT INTO posts (title, description, userId, image) VALUES (:t, :d, :u, 'drawer5.jpg')", array(
            "t" => $post['title'],
            "d" => $post['description'],
            "u" => $post['userId'],
        ));
        $post['id'] = $this->get_last_id();
        return $post;
    }

    public function findAll()
    {
        $posts = $this->query("SELECT * FROM posts");
        for ($i = 0; $i < count($posts); $i++) {
            $posts[$i]['user'] = $this->getUser($posts[$i]['userId']);
            $posts[$i]['comments'] = $this->getComments($posts[$i]['id']);
        }
        return $posts;
    }

    public function getComments($postId)
    {
        return $this->query("SELECT u.name as 'username', c.* FROM comments c LEFT JOIN users u ON u.id = c.userId WHERE c.postId = :postid ", array("postid" => $postId));
    }

    public function addComments($comment)
    {
        $this->query("INSERT INTO comments (postId, userId, description) VALUES (:p, :u, :d)", array(
            "p" => $comment['postId'],
            "u" => $comment['userId'],
            "d" => $comment['description']
        ));
        $comment['id'] = $this->get_last_id();
        return $comment;
    }
}
