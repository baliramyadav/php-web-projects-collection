<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Document</title>
</head>
<body>
    <h2>Hi <?=$member->name?></h2>
    <p>You can now reset your password.</p>
    <h1>Your verify code is <b><?=$code?></b></h1>
    <button><a style="text-decoration: none;" href="<?=base_url('login/resetpasswordconfirm?'.trim($username_or_email))?>">Click Here Reset Password</a></button>
    <p>Thank You</p>
</body>
</html>