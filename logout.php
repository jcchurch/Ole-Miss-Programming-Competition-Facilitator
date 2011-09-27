<?
    unset($_SESSION['username']);
    if (isset($_COOKIE[session_name()])) {
        setcookie(session_name(), '', time()-42000, '/');
    }
    header("Location: index.php");
    session_destroy();
?>
