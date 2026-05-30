<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mood Calendar</title>
    <link rel="stylesheet" href="style.css?v=4"> <link rel="stylesheet" href="homestyle.css">
    <link rel="stylesheet" href="modal.css">
</head>
<body>
    <?php include 'navbar.php'; ?>
    
    <div class="month">
        <ul>
            <li><button class="nav-a prev" id="prev">&#10094;</button></li>
            <li><button class="nav-a next" id="next">&#10095;</button></li>
            <li>
                <h2 id="monthName">
                    <span id="monthText"></span><br>
                    <small id="year"></small>
                </h2>
            </li>
        </ul>
    </div>

    <div class="weekdays">
        <ul><li>Sun</li><li>Mon</li><li>Tues</li><li>Wed</li><li>Thurs</li><li>Fri</li><li>Sat</li></ul>
    </div>

    <div class="days">
        <ul id="days"></ul>
    </div>

    <div class="modal" id="moodModal">
        <div class="modal-content">
            <span class="close" id="closeModal">&times;</span>
            <h3 id="modalTitle" style="text-decoration: underline; text-underline-offset: 5px;"></h3>

            <div id="viewSection" style="display: none;">
                <div id="viewEmoji" style="font-size: 60px; margin: 10px 0;"></div>
                <div id="viewMoodName" style="font-weight: bold; text-transform: uppercase; border: 1px solid black; margin-bottom: 15px;"></div>
                <p id="viewJournal" style="background: #f9eeeeff; padding: 15px; border-radius: 10px; border: 1px solid black; text-align: left; min-height: 50px;"></p>
                
                <button onclick="switchToEditMode()" class="action-btn edit-btn">✏️ Edit Entry</button>
            </div>

            <div id="editSection" style="display: none;">
                <p>How were you feeling?</p>
                <div class="legend">
                    <div class="mood happy" onclick="selectMood('happy')">😊 Happy</div>
                    <div class="mood sad" onclick="selectMood('sad')">😢 Sad</div>
                    <div class="mood angry" onclick="selectMood('angry')">😠 Angry</div>
                    <div class="mood neutral" onclick="selectMood('neutral')">😐 Neutral</div>
                    <div class="mood excited" onclick="selectMood('excited')">🤩 Excited</div>
                </div>

                <textarea id="journalInput" placeholder="Write a short journal entry..."></textarea>
                
                <input type="hidden" id="selectedDate">
                <input type="hidden" id="selectedMood">

                <button onclick="saveMoodData()" class="action-btn save-btn">Save Entry</button>
                <button onclick="cancelEdit()" id="cancelBtn" class="action-btn cancel-btn" style="display:none; margin-top:5px;">Cancel</button>
            </div>
        </div>
    </div>

    <script src="script.js?v=4"></script>
</body>
</html>