<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Send Message Form</title>
</head>
<body>
    <form action="{{ route('send-message') }}" method="POST">
        @csrf
        <label for="username">Username:</label><br>
        <input type="text" id="username" name="username" required><br><br>
        
        <label for="content">Content:</label><br>
        <textarea id="content" name="content" rows="4" cols="50" required></textarea><br><br>
        
        <button type="submit">Send Message</button>
    </form>
</body>
</html>
