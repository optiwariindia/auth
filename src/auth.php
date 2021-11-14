<?php

namespace optiwariindia;

class auth
{
    private static $instance, $db, $table, $dashboard;
    private function __construct()
    {
        if (!isset(self::$instance)) {
            session_start();
            self::$instance = new \optiwariindia\auth();
            self::install();
        }
    }
    public static function init()
    {
        new auth();
        return self::$instance;
    }
    public static function config($db)
    {
        self::$db = new database($db);
    }
    public static function login()
    {
        if (isset($_POST['user'])) {
            $temp = self::$db->select(self::$table, "*", "where user like '{$_POST['user']}' and pass = md5('{$_POST['pass']}')");
            switch (count($temp)) {
                case 1:
                    $_SESSION['user'] = $temp[0];
                    header("Location: " . self::$dashboard);
                    break;

                default:
                    return [
                        "error" => "Invalid Username or Password",
                        "status" => "error"
                    ];
                    break;
            }
        }
        return false;
    }
    public static function logout()
    {
        session_destroy();
        header("Location: " . self::$dashboard);
    }
    public static function isLoggedIn()
    {
        if (isset($_SESSION['user'])) {
            return true;
        }
        return false;
    }
    public static function dashboard($dashboard)
    {
        self::$dashboard = $dashboard;
    }
    public static function install()
    {
        if (in_array(self::$table, self::$db->tables())) {
            return true;
        }
        self::$db->query("
        create table " . self::$table . "(
            id int(11) not null auto_increment,
            user varchar(255) not null,
            pass varchar(32) not null,
            name text not null,
            email text not null,
            phone text not null,
            primary key(id)
        )
        ");
        return true;
    }
    public static function forgotPassword($user="",$email="",$phone=""){
        if(empty($user) && empty($email) && empty($phone)){
            return [
                "error" => "Please enter your username, email or phone number",
                "status" => "error"
            ];
        }
        if($_SESSION['otp'])return $_SESSION['otp'];
        $otp=self::createOTP();
        $_SESSION['otp']=$otp;
        return $otp;
    }
    public static function createOTP(){
        $allowedChars="abcdefghijkmnpqrtABCDEGHJKLMNPQRT123456789";
        $otp=substr(str_shuffle($allowedChars),0,6);
        return $otp;
    }
    public static function updatePassword($user="",$pass="",$npass="",$otp=""){
        if(empty($user) && empty($pass) && empty($npass) && empty($otp)){
            return [
                "error" => "Please enter your username, password, new password and OTP",
                "status" => "error"
            ];
        }
        if($pass !== $npass){
            return [
                "error" => "Please check passwords, New password and confirm password should be same",
                "status" => "error"
            ];
        }
        if(!$_SESSION['otp']){
            return [
                "error" => "Please enter a valid OTP",
                "status" => "error"
            ];
        }
        if($_SESSION['otp']!=$otp){
            return [
                "error" => "Please enter a valid OTP",
                "status" => "error"
            ];
        }
        $temp=self::$db->select(self::$table,"*","where user like '{$user}' or email like '{$user}' or phone like '{$user}'");
        if(count($temp)==1){
            self::$db->update(self::$table,["pass"=>md5($npass)],"where user like {$user}");
            return [
                "message" => "Password updated successfully",
                "status" => "success"
            ];
        }
        return [
            "error" => "Invalid Username",
            "status" => "error"
        ];   
    }
    public static function register($user="",$pass="",$name="",$email="",$phone=""){
        if(empty($user) && empty($pass) && empty($name) && empty($email) && empty($phone)){
            return [
                "error" => "Please enter your username, password, name, email and phone number",
                "status" => "error"
            ];
        }
        $temp=self::$db->select(self::$table,"*","where user like '{$user}' or email like '{$email}' or phone like '{$phone}'");
        if(count($temp)>0){
            return [
                "error" => "Username, Email or Phone number already exists",
                "status" => "error"
            ];
        }
        self::$db->insert(self::$table,[
            "user"=>$user,
            "pass"=>$pass,
            "name"=>$name,
            "email"=>$email,
            "phone"=>$phone
        ]);
        return [
            "message" => "User registered successfully. Please login and continue",
            "status" => "success"
        ];
    }
    public static function getUser($user=""){
        if(empty($user)){
            return [
                "error" => "Please enter your username",
                "status" => "error"
            ];
        }
        $temp=self::$db->select(self::$table,"*","where user like '{$user}' or email like '{$user}' or phone like '{$user}'");
        if(count($temp)==1){
            return $temp[0];
        }
        return [
            "error" => "Invalid Username",
            "status" => "error"
        ];
    }
    public static function getUserByID($id=""){
        if(empty($id)){
            return [
                "error" => "Please enter your ID",
                "status" => "error"
            ];
        }
        $temp=self::$db->select(self::$table,"*","where id like '{$id}'");
        if(count($temp)==1){
            return $temp[0];
        }
        return [
            "error" => "Invalid ID",
            "status" => "error"
        ];
    }
    public static function list(){
        return self::$db->select(self::$table,"*");
    }
    public static function delete($id=""){
        if(empty($id)){
            return [
                "error" => "Please enter your ID",
                "status" => "error"
            ];
        }
        self::$db->delete(self::$table,"where id like '{$id}'");
        return [
            "message" => "User deleted successfully",
            "status" => "success"
        ];
    }
}


