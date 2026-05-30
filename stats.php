<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Mood Statistics</title>
    <link rel="stylesheet" href="homestyle.css">
    <style>
        body { background-color: #fff0f3; font-family: sans-serif; }
        
        .stats-container {
            max-width: 600px;
            margin: 40px auto;
            background: white;
            padding: 30px;
            border-radius: 20px;
            box-shadow: 0 10px 25px rgba(0,0,0,0.1);
        }

        h1 { text-align: center; color: #333; margin-bottom: 30px; }

        /* The Row for each mood */
        .stat-row {
            margin-bottom: 20px;
        }

        .stat-label {
            display: flex;
            justify-content: space-between;
            margin-bottom: 5px;
            font-weight: bold;
            color: #555;
        }

        /* The Gray Background Bar */
        .progress-bar {
            width: 100%;
            background-color: #eee;
            border-radius: 15px;
            height: 25px;
            overflow: hidden; /* Ensures the inner bar stays inside curves */
        }

        /* The Colored Inner Bar */
        .progress-fill {
            height: 100%;
            width: 0%; /* Starts at 0, JS will change this */
            text-align: right;
            padding-right: 10px;
            color: white;
            font-size: 12px;
            line-height: 25px; /* Vertically center text */
            transition: width 1s ease-in-out; /* Smooth animation */
        }

        /* Colors matching your theme */
        .bg-happy { background-color: #f39c12; }
        .bg-sad { background-color: #3498db; }
        .bg-angry { background-color: #e74c3c; }
        .bg-neutral { background-color: #95a5a6; }
        .bg-excited { background-color: #9b59b6; }
        
        #totalCount { text-align: center; color: #888; margin-top: 20px; }
    </style>
</head>
<body>
    <?php include 'navbar.php'; ?>

    <div class="stats-container">
        <h1>Mood Breakdown</h1>

        <div class="stat-row">
            <div class="stat-label">
                <span>😊 Happy</span>
                <span id="label-happy">0%</span>
            </div>
            <div class="progress-bar">
                <div id="bar-happy" class="progress-fill bg-happy"></div>
            </div>
        </div>

        <div class="stat-row">
            <div class="stat-label">
                <span>🤩 Excited</span>
                <span id="label-excited">0%</span>
            </div>
            <div class="progress-bar">
                <div id="bar-excited" class="progress-fill bg-excited"></div>
            </div>
        </div>

        <div class="stat-row">
            <div class="stat-label">
                <span>😐 Neutral</span>
                <span id="label-neutral">0%</span>
            </div>
            <div class="progress-bar">
                <div id="bar-neutral" class="progress-fill bg-neutral"></div>
            </div>
        </div>

        <div class="stat-row">
            <div class="stat-label">
                <span>😢 Sad</span>
                <span id="label-sad">0%</span>
            </div>
            <div class="progress-bar">
                <div id="bar-sad" class="progress-fill bg-sad"></div>
            </div>
        </div>

        <div class="stat-row">
            <div class="stat-label">
                <span>😠 Angry</span>
                <span id="label-angry">0%</span>
            </div>
            <div class="progress-bar">
                <div id="bar-angry" class="progress-fill bg-angry"></div>
            </div>
        </div>

        <p id="totalCount">Loading data...</p>
    </div>

    <script>
        async function calculateStats() {
            try {
                // 1. Fetch Data
                const response = await fetch('api.php');
                const data = await response.json();
                
                const total = data.length;
                if (total === 0) {
                    document.getElementById('totalCount').innerText = "No entries yet.";
                    return;
                }

                document.getElementById('totalCount').innerText = `Total Entries: ${total}`;

                // 2. Count Moods
                let counts = { 'happy': 0, 'sad': 0, 'angry': 0, 'neutral': 0, 'excited': 0 };
                
                data.forEach(entry => {
                    if (counts[entry.mood] !== undefined) {
                        counts[entry.mood]++;
                    }
                });

                // 3. Update HTML (The Math Logic)
                updateBar('happy', counts.happy, total);
                updateBar('excited', counts.excited, total);
                updateBar('neutral', counts.neutral, total);
                updateBar('sad', counts.sad, total);
                updateBar('angry', counts.angry, total);

            } catch (error) {
                console.error("Error:", error);
            }
        }

        function updateBar(mood, count, total) {
            // Calculate Percentage
            let percentage = 0;
            if(total > 0) {
                percentage = Math.round((count / total) * 100);
            }

            // Update Width
            const bar = document.getElementById(`bar-${mood}`);
            const label = document.getElementById(`label-${mood}`);
            
            bar.style.width = percentage + "%"; // This makes the bar grow!
            label.innerText = percentage + "% (" + count + ")";
        }

        calculateStats();
    </script>
</body>
</html>