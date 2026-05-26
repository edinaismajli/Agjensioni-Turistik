const bookingCards = document.getElementById("bookingCards");
const refreshBookings = document.getElementById("refreshBookings");

async function loadBookings() {
    if (!bookingCards) {
        return;
    }

    bookingCards.innerHTML = "<p>Loading bookings...</p>";

    try {
        const response = await fetch("../acc/get-bookings.php");
        const data = await response.json();

        if (!data.success) {
            bookingCards.innerHTML = `<p>${data.message}</p>`;
            return;
        }

        if (data.bookings.length === 0) {
            bookingCards.innerHTML = "<p>No bookings found.</p>";
            return;
        }

        let html = `
            <table border="1" cellpadding="10" cellspacing="0" style="width:100%; font-size:1.5rem; background:#fff;">
                <tr>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th>Destination</th>
                    <th>Guests</th>
                    <th>Arrivals</th>
                    <th>Leaving</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
        `;

        data.bookings.forEach(booking => {
            html += `
                <tr id="booking-${booking.id}">
                    <td>${booking.name}</td>
                    <td>${booking.email}</td>
                    <td>${booking.phone}</td>
                    <td>${booking.destination_name}</td>
                    <td>${booking.guests}</td>
                    <td>${booking.arrivals}</td>
                    <td>${booking.leaving}</td>
                    <td>${booking.status ?? "pending"}</td>
                    <td>
                        <button class="delete-booking-btn" data-id="${booking.id}">
                            Delete
                        </button>
                    </td>
                </tr>
            `;
        });

        html += "</table>";
        bookingCards.innerHTML = html;

        document.querySelectorAll(".delete-booking-btn").forEach(button => {
            button.addEventListener("click", deleteBooking);
        });
    } catch (error) {
        bookingCards.innerHTML = "<p>Error loading bookings.</p>";
    }
}

async function deleteBooking() {
    if (!confirm("A je i sigurt qe deshiron me fshi kete booking?")) {
        return;
    }

    const id = this.dataset.id;

    try {
        const response = await fetch("../acc/delete-booking.php", {
            method: "POST",
            headers: {
                "Content-Type": "application/x-www-form-urlencoded"
            },
            body: "id=" + encodeURIComponent(id)
        });

        const data = await response.json();

        if (data.success) {
            document.getElementById("booking-" + id).remove();
        } else {
            alert(data.message);
        }
    } catch (error) {
        alert("Error deleting booking.");
    }
}

if (refreshBookings) {
    refreshBookings.addEventListener("click", loadBookings);
}

document.querySelector('[data-section-id="bookings"]').addEventListener("click", loadBookings);s