/* =========================
   GLOBAL NOTIFICATION SYSTEM
========================= */

function showNotification(type, message) {

    const existing = document.querySelector(".notify");
    if (existing) existing.remove();

    const div = document.createElement("div");
    div.className = `notify notify-${type}`;
    div.textContent = message;

    Object.assign(div.style, {
        position: "fixed",
        top: "20px",
        right: "20px",
        padding: "14px 18px",
        borderRadius: "6px",
        color: "#fff",
        fontSize: "0.95rem",
        zIndex: 9999,
        background: type === "success" ? "#16a34a" : "#dc2626",
        boxShadow: "0 8px 25px rgba(0,0,0,0.15)",
        opacity: "0",
        transform: "translateY(-10px)",
        transition: "0.3s ease"
    });

    document.body.appendChild(div);

    setTimeout(() => {
        div.style.opacity = "1";
        div.style.transform = "translateY(0)";
    }, 50);

    setTimeout(() => div.remove(), 3500);
}