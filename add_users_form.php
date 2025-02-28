<?php 
include 'header.php';
?>
<body>
        <?php include 'navbar.php';?>
        <div class="container">
                <div class="row h-100 justify-content-center align-items-center">

                        <form action="add_users.php" method="POST" class="text-center">
                        <div class="col-12">
                            <input type="email" placeholder="E-mail" name="mail">
                        </div>
                        <div class="col-12">
                            <input type="text" placeholder="Nom" name="lastname">
                        </div>
                        <div class="col-12">
                            <input type="text" placeholder="Prénom" name="firstname">
                        </div>
                        <div class="col-12">
                            <input type="date" name="age">
                        </div>
                        <div class="col-12">
                            <input type="password" placeholder="Mot de passe" name="psw">
                        </div>
                            <button type="submit">Login</button>
                        </form>
                </div>
        </div>
</body>
</html>
