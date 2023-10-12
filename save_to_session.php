<?php
// Start or resume the PHP session
session_start();

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {

        $data = json_decode(file_get_contents('php://input'));
        
        $_SESSION['id'] = $data->id;
        $_SESSION['fname'] = $data->fname;
        $_SESSION['lname'] = $data->lname;
        $_SESSION['nic'] = $data->nic;
        $_SESSION['phone_no'] = $data->phone_no;
        $_SESSION['status'] = $data->status;
        $_SESSION['role'] = $data->role;
        $_SESSION['email'] = $data->email;

        echo json_encode(['success' => true]);
    }else{
        echo json_encode(['success' => false]);
    }
?>