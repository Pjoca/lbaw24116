<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Test Post Endpoint</title>
</head>
<body>
    <h1>Test Post Endpoint</h1>
    <form method="POST" action="{{ url('/post') }}">
        @csrf <!-- Include the CSRF token for Laravel -->

        <label for="content">Content:</label><br>
        <textarea id="content" name="content" rows="4" cols="50" required>This is a test post</textarea><br><br>

        <label for="visibility">Visibility:</label><br>
        <select id="visibility" name="visibility" required>
            <option value="public">Public</option>
            <option value="private">Private</option>
            <option value="group">Group</option>
        </select><br><br>

        <label for="id_group">Group ID (optional):</label><br>
        <input type="number" id="id_group" name="id_group"><br><br>

        <button type="submit">Submit Post</button>
    </form>
</body>
</html>
