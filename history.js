const historyList = document.getElementById('historyList');
const loader = document.getElementById('loader');
const noDataMsg = document.getElementById('noDataMsg');

// 1. FETCH AND DISPLAY DATA
async function loadHistory() {
    try {
        const response = await fetch('api.php');
        const data = await response.json();
        
        loader.style.display = 'none';
        historyList.innerHTML = ''; // Clear current list

        if (data.length === 0) {
            noDataMsg.style.display = 'block';
            return;
        }

        data.forEach(entry => {
            const card = createCard(entry);
            historyList.appendChild(card);
        });

    } catch (error) {
        console.error("Error loading history:", error);
        loader.textContent = "Error loading data.";
    }
}

// 2. CREATE HTML CARD
function createCard(entry) {
    // Create main div
    const div = document.createElement('div');
    div.className = `history-card ${entry.mood}`; // Add mood class for border color

    // Format Date (e.g., "2023-10-05")
    const dateObj = new Date(entry.date);
    const dateString = dateObj.toDateString(); 

    div.innerHTML = `
        <div class="card-content">
            <div class="card-date">${dateString}</div>
            <span class="card-mood-badge ${entry.mood}">${getEmoji(entry.mood)} ${entry.mood}</span>
            <p class="card-journal">${entry.journal || "No details written."}</p>
        </div>
        <button class="delete-btn" onclick="deleteEntry('${entry.date}')" title="Delete Entry">
            🗑️
        </button>
    `;

    return div;
}

// Helper for Emojis
function getEmoji(mood) {
    const emojis = {
        'happy': '😊',
        'sad': '😢',
        'angry': '😠',
        'neutral': '😐',
        'excited': '🤩'
    };
    return emojis[mood] || '❓';
}

// 3. DELETE ENTRY
async function deleteEntry(date) {
    if(!confirm("Are you sure you want to delete this entry? This cannot be undone.")) {
        return;
    }

    try {
        const response = await fetch('api.php', {
            method: 'DELETE',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ date: date })
        });

        const result = await response.json();

        if (result.status === 'success') {
            // Reload the list to show it's gone
            loadHistory();
        } else {
            alert("Error deleting: " + result.message);
        }

    } catch (error) {
        console.error("Error:", error);
    }
}

// Initial Load
loadHistory();