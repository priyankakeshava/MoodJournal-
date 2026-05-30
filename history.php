<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mood History</title>
    <link rel="stylesheet" href="homestyle.css">
    <link rel="stylesheet" href="historystyle.css">
</head>
<body>
    <?php include 'navbar.php'; ?>

    <div class="history-container">
        <div class="header-section">
            <h1>Your Journey</h1>
            <p>A timeline of your thoughts and feelings.</p>
            <a href="index.php"><button class="add-btn">+ Add Entry (Go to Calendar)</button></a>
        </div>

        <div id="loader">Loading entries...</div>
        
        <div id="historyList" class="history-list"></div>
        
        <p id="noDataMsg" style="display:none; text-align:center;">No entries found. Go to the calendar to start journaling!</p>
    </div>

    <script src="history.js"></script>
</body>
</html>