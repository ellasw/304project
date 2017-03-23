<head>
    <meta charset="UTF-8">
    <title>Employee Account Management</title>
</head>

<header>
    <h1>Employee Account Management</h1>
    <h2>Submit Changes to Employee Account Below:</h2>
</header>

<form method="POST" action="emp_browse.php">
    <p align="right">
        <input type="submit" value="Return" name="return"/>
    </p>
</form>

<form method = "POST" action = "emp_manage.php">
    <p>
        <label for="change_email">Edit Email:</label><br>
        <input type="email" name="oldEmail" size="30">
        <input type="email" name="newEmail" size="30">
        <input type="submit" value="Update Email" name="EmailSubmit">
    </p>
</form>

<form method = "POST" action = "emp_manage.php">
    <p>
        <label for="change_name">Edit Name:</label><br>
        <input type="text" name="oldName" size="30">
        <input type="text" name="newName" size="30">
        <input type="submit" value="Update Name" name="NameSubmit">
    </p>
</form>

<form method = "POST" action = "emp_manage.php">
    <p>
        <label for="change_password">Change Password:</label><br>
        <input type="text" name="oldPassword" size="30">
        <input type="text" name="newPassword" size="30">
        <input type="submit" value="Update Password" name="PasswordSubmit">
    </p>
</form>

<form method = "POST" action = "emp_manage.php">
    <p>
        <label for="change_branch">Change Password:</label><br>
        <input type="number" name="oldBranch" size="5">
        <input type="number" name="newBranch" size="5">
        <input type="submit" value="Update Branch" name="BranchSubmit">
    </p>
</form>

<br>