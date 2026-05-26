document.addEventListener("DOMContentLoaded", function () {
    const sectionButtons = document.querySelectorAll("[data-section-id]");
    const sections = document.querySelectorAll(".content-section");

    const addPackageForm = document.getElementById("addPackageForm");
    const bookingCards = document.getElementById("bookingCards");
    const refreshBookings = document.getElementById("refreshBookings");
    const logoutButton = document.getElementById("logoutButton");

    function showSection(sectionId) {
        sections.forEach(section => {
            section.classList.add("hidden");
        });

        const selectedSection = document.getElementById(sectionId);

        if (selectedSection) {
            selectedSection.classList.remove("hidden");
        }

        if (sectionId === "bookings") {
            loadBookings();
        }
    }

    sectionButtons.forEach(button => {
        button.addEventListener("click", function () {
            showSection(this.dataset.sectionId);
        });
    });

    if (addPackageForm) {
        addPackageForm.addEventListener("submit", async function (event) {
            event.preventDefault();

            const formData = new FormData();
            formData.append("packageName", document.getElementById("packageName").value);
            formData.append("packageDescription", document.getElementById("packageDescription").value);
            formData.append("packageCountry", document.getElementById("packageCountry").value);
            formData.append("packageDuration", document.getElementById("packageDuration").value);
            formData.append("packagePrice", document.getElementById("packagePrice").value);

            try {
                const response = await fetch("add-package.php", {
                    method: "POST",
                    body: formData
                });

                const result = await response.json();

                if (result.success) {
                    alert("Paketa u shtua me sukses.");
                    addPackageForm.reset();
                } else {
                    alert(result.message || "Gabim gjate shtimit te paketes.");
                }
            } catch (error) {
                alert("Gabim gjate shtimit te paketes.");
            }
        });
    }

    async function loadBookings() {
        if (!bookingCards) {
            return;
        }

        bookingCards.innerHTML = "<p>Loading bookings...</p>";

        try {
            const response = await fetch("get-bookings.php");
            const result = await response.json();

            if (!result.success) {
                bookingCards.innerHTML = `<p>${result.message}</p>`;
                return;
            }

            if (result.bookings.length === 0) {
                bookingCards.innerHTML = "<p>No bookings found.</p>";
                return;
            }

            bookingCards.innerHTML = "";

            result.bookings.forEach(booking => {
                const card = document.createElement("div");
                card.className = "booking-card";

                card.innerHTML = `
                    <h3>${booking.destination_name}</h3>
                    <p><strong>Name:</strong> ${booking.name}</p>
                    <p><strong>Email:</strong> ${booking.email}</p>
                    <p><strong>Phone:</strong> ${booking.phone}</p>
                    <p><strong>Address:</strong> ${booking.address}</p>
                    <p><strong>Guests:</strong> ${booking.guests}</p>
                    <p><strong>Arrivals:</strong> ${booking.arrivals}</p>
                    <p><strong>Leaving:</strong> ${booking.leaving}</p>
                    <p><strong>Status:</strong> ${booking.status}</p>
                `;

                bookingCards.appendChild(card);
            });
        } catch (error) {
            bookingCards.innerHTML = "<p>Error loading bookings.</p>";
        }
    }

    if (refreshBookings) {
        refreshBookings.addEventListener("click", function () {
            loadBookings();
        });
    }

    if (logoutButton) {
        logoutButton.addEventListener("click", function () {
            window.location.href = "logout.php";
        });
    }

    function updateDateTime() {
        const dateElement = document.getElementById("date");
        const timeElement = document.getElementById("time");

        const now = new Date();

        if (dateElement) {
            dateElement.textContent = now.toLocaleDateString();
        }

        if (timeElement) {
            timeElement.textContent = now.toLocaleTimeString();
        }
    }

    updateDateTime();
    setInterval(updateDateTime, 1000);
});