<?php
// app/core/Model.php

class Model {
    protected $db;

    public function __construct() {
        error_log("Model constructor called");
        $database = new Database();
        $this->db = $database->connect();
        error_log("Database connection status: " . ($this->db ? "Connected" : "Failed"));
    }
}