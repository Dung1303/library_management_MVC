<?php
// app/controllers/HomeController.php

class HomeController extends Controller {
    public function index() {
        header('Location: /login');
        exit;
    }
}