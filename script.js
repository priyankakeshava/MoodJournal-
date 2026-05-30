const monthNames = ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"];
let currentDate = new Date();
const today = new Date();
today.setHours(0,0,0,0);

const daysBox = document.getElementById("days");
const modal = document.getElementById("moodModal");
const viewSection = document.getElementById("viewSection");
const editSection = document.getElementById("editSection");
const cancelBtn = document.getElementById("cancelBtn");

let moodData = {}; 

function getEmoji(mood) {
    const emojis = { 'happy': '😊', 'sad': '😢', 'angry': '😠', 'neutral': '😐', 'excited': '🤩' };
    return emojis[mood] || '';
}

async function fetchMoods() {
    try {
        const response = await fetch('api.php');
        const data = await response.json();
        moodData = {};
        data.forEach(entry => { moodData[entry.date] = entry; });
        drawCalendar(); 
    } catch (error) { console.error("Error fetching moods:", error); }
}

function drawCalendar() {
    daysBox.innerHTML = "";
    let y = currentDate.getFullYear();
    let m = currentDate.getMonth();
    
    document.getElementById("monthText").textContent = monthNames[m];
    document.getElementById("year").textContent = y;
    
    let startDay = new Date(y, m, 1).getDay();
    let totalDay = new Date(y, m + 1, 0).getDate();

    for (let i = 0; i < startDay; i++) {
        let li = document.createElement("li");
        li.style.visibility = "hidden";
        daysBox.appendChild(li);
    }

    for (let i = 1; i <= totalDay; i++) {
        let li = document.createElement("li");
        let dateKey = `${y}-${String(m + 1).padStart(2, '0')}-${String(i).padStart(2, '0')}`;
        let cellDate = new Date(y, m, i);
        let hasEntry = moodData[dateKey] ? true : false;

        // --- CALENDAR CARD HTML ---
        // Clean look: Just number and Emoji. No buttons.
        let html = `<div class="day-number">${i}</div>`;
        
        if (hasEntry) {
            let entry = moodData[dateKey];
            li.className = entry.mood; 
            html += `<div class="mood-icon">${getEmoji(entry.mood)}</div>`;
            li.title = "Click to view entry";
        }

        li.innerHTML = html;

        if (i === today.getDate() && m === today.getMonth() && y === today.getFullYear()) {
            li.classList.add("today");
        }

        if (cellDate > today) {
            li.classList.add("future");
        } else {
            li.addEventListener("click", () => openModal(dateKey, i));
        }
        daysBox.appendChild(li);
    }
}

// --- MODAL LOGIC ---

function openModal(dateStr, dayNum) {
    document.getElementById("selectedDate").value = dateStr;
    document.getElementById("modalTitle").innerText = `Mood for ${monthNames[currentDate.getMonth()]} ${dayNum}`;
    
    // Check if we have data
    if (moodData[dateStr]) {
        // SCENARIO 1: Data Exists -> SHOW VIEW MODE
        const entry = moodData[dateStr];
        
        // Fill View Data
        document.getElementById("viewEmoji").textContent = getEmoji(entry.mood);
        document.getElementById("viewMoodName").textContent = entry.mood;
        document.getElementById("viewMoodName").className = `card-mood-badge ${entry.mood}`; // Re-use badge style
        document.getElementById("viewJournal").textContent = entry.journal || "No words written.";
        
        // Show View, Hide Edit
        viewSection.style.display = 'block';
        editSection.style.display = 'none';
        cancelBtn.style.display = 'inline-block'; // Allow canceling edit to go back to view
    } else {
        // SCENARIO 2: No Data -> SHOW EDIT MODE (Fresh Entry)
        prepareEditForm(dateStr);
        cancelBtn.style.display = 'none'; // Can't cancel back to view if there is no view
    }

    modal.style.display = "block";
}

function switchToEditMode() {
    let dateStr = document.getElementById("selectedDate").value;
    prepareEditForm(dateStr);
}

function prepareEditForm(dateStr) {
    // Hide View, Show Edit
    viewSection.style.display = 'none';
    editSection.style.display = 'block';

    // Reset Form
    document.getElementById("journalInput").value = "";
    document.getElementById("selectedMood").value = "";
    document.querySelectorAll('.mood').forEach(el => el.style.border = "2px solid transparent");

    // Pre-fill if data exists (so they can edit what they wrote)
    if (moodData[dateStr]) {
        document.getElementById("journalInput").value = moodData[dateStr].journal;
        selectMood(moodData[dateStr].mood);
    }
}

function cancelEdit() {
    let dateStr = document.getElementById("selectedDate").value;
    // Go back to view mode
    if (moodData[dateStr]) {
        viewSection.style.display = 'block';
        editSection.style.display = 'none';
    } else {
        modal.style.display = 'none';
    }
}

function selectMood(mood) {
    document.getElementById("selectedMood").value = mood;
    document.querySelectorAll('.mood').forEach(el => el.style.border = "2px solid transparent");
    document.querySelector(`.${mood}`).style.border = "2px solid black";
}

document.getElementById("closeModal").onclick = () => modal.style.display = "none";

async function saveMoodData() {
    const date = document.getElementById("selectedDate").value;
    const mood = document.getElementById("selectedMood").value;
    const journal = document.getElementById("journalInput").value;

    if (!mood) { alert("Please select a mood!"); return; }

    try {
        const response = await fetch('api.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ date, mood, journal })
        });
        const result = await response.json();
        if (result.status === "success") {
            modal.style.display = "none";
            fetchMoods();
        } else { alert("Error: " + result.message); }
    } catch (error) { console.error("Error saving:", error); }
}

document.getElementById("prev").addEventListener("click", () => { currentDate.setMonth(currentDate.getMonth() - 1); drawCalendar(); });
document.getElementById("next").addEventListener("click", () => { currentDate.setMonth(currentDate.getMonth() + 1); drawCalendar(); });

fetchMoods();