<!DOCTYPE html>
<html>
<head>
	<title>TODO app</title>
	<meta charset="utf-8">
	<link rel="stylesheet" href="css/add_todo.css">
	<script src="https://code.jquery.com/jquery-3.5.1.min.js" integrity="sha256-9/aliU8dGd2tb6OSsuzixeV4y/faTqgFtohetphbbj0=" crossorigin="anonymous"></script>
	<script type="text/javascript" src="js/index.js"></script>
</head>
<body>
    <div class="container">
        <h2 class="header">Create New Task</h2>
        <form action="/index.php" method="POST">
            <label>Todo title </label>
            <input type="text" name="text" maxlength="50" required>
            <label>Date </label>
            <input type="date" name="date" required>
            <label>Description </label>
            <textarea name="description" rows="10" cols="50" maxlength="500"></textarea>
            <div class="pair">
                <label>Completed </label>
                <input type="checkbox" name="completed">
            </div>
            <input type="submit" name="add_todo" value="Add ToDo">
        </form>
    </div>

</body>