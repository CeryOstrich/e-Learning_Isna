function setActive(el) {
    document.querySelectorAll(".sidebar a").forEach(a => a.classList.remove("active"));
    el.classList.add("active");
}

document.querySelectorAll(".sidebar a").forEach(el => {
    el.addEventListener("click", function() {
        setActive(this);
    });
});

function loadPage(page) {
    let content = document.getElementById("content");

    if (page === "dashboard") {
        content.innerHTML = `
        <h2>Dashboard</h2>
        <div class="grid">
            <div class="card">📚 Materi <h3>10</h3></div>
            <div class="card">📝 Tugas <h3>5</h3></div>
            <div class="card">📅 Absen <h3>90%</h3></div>
        </div>`;
    }

    if (page === "ruangan") {
        content.innerHTML = `
        <h2>Ruangan</h2>
        <div class="card">Kelas 7A</div>
        <div class="card">Kelas 8B</div>`;
    }

    if (page === "materi") {
        content.innerHTML = `
        <h2>Materi</h2>
        <div class="card">Matematika - Aljabar <br><button>Download</button></div>`;
    }

    if (page === "absen") {
        content.innerHTML = `
        <h2>Absen</h2>
        <button onclick="alert('Absen berhasil!')">Klik Absen</button>`;
    }

    if (page === "tugas") {
        content.innerHTML = `
        <h2>Tugas</h2>
        <div class="card">
        <input type="file"><br><br>
        <button>Upload</button>
        </div>`;
    }

    if (page === "profil") {
        content.innerHTML = `
        <h2>Profil</h2>
        <div class="card">
        Email: ${email}<br>
        Role: ${role}
        </div>`;
    }
}

window.onload = () => loadPage('dashboard');