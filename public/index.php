<?php
session_start();
ob_start(); // Enable output buffer

// Include common files at the start of the script
include('../config/functions.php');
include('../config/variables.php');
include('../config/php-BDD-connect.php');

// Handle the display of the requested page
if (isset($_GET['page'])) {
    // Special pages that don't require header/footer loading
    $specialPages = ['delete_absence_post', 'add-absence-post', 'delete_trainee-post'];
    if (in_array($_GET['page'], $specialPages)) {
        include('../common/' . $_GET['page'] . '.php');
        exit();
    }

    // Set the page title
    $title = $_GET['page'];

    // Include head and header
    include('../public/head.php');
    include('../public/header.php');

    // Check if user is logged in (except for login page)
    if (!isset($_SESSION['LOGGED_USER']) && $_GET['page'] != 'login') {
        header('Location: index.php?page=dashbord');
        exit();
    }

    // List of allowed pages
    $allowedPages = [
        'dashbord',
        'trainees',
        'add_trainee',
        'modifier_trainee',
        'delete_trainee',
        'absences',
        'head',
        'bienvenue',
        'menu',
        'header',
        'header-post',
        'footer',
        'add-absence',
        'modifier_absence',
        'delete_absence',
        'delete_absence_confirmation',
        'delete_trainee_confirmation'
    ];

    // Include the requested page if it's allowed
    if (in_array($_GET['page'], $allowedPages)) {
        include("../pages/" . $_GET['page'] . '.php');
    } else {
        echo "Page not found or access denied";
    }
} else {
    // If no page is specified
    include('../public/head.php');
    include('../public/header.php');

    if (isset($_SESSION['LOGGED_USER'])) {
        // Redirect to dashboard if user is logged in
        header('Location: index.php?page=dashbord');
        exit();
    } else {
        // Show login page if user is not logged in
        include('../pages/bienvenue.php');
        include('../common/login.php');
    }
}

// Include footer
include('../public/footer.php');
