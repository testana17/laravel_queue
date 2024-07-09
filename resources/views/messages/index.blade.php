<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Messages</title>
</head>
<body>
    <h1>Messages</h1>
    <table>
        <thead>
            <tr>
                <th>Username</th>
                <th>Content</th>
                <th>Sent At</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($messages as $message)
                <tr>
                    <td>{{ $message->user->name }}</td>
                    <td>{{ $message->content }}</td>
                    <td>{{ $message->created_at }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
