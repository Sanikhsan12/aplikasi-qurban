// ================================
// Data Store
// ================================
let animals = [
    { id: 1, type: "Sapi", weight: "450 kg", price: "Rp 28.000.000", slots: 7, filledSlots: 7, status: "Penuh" },
    { id: 2, type: "Sapi", weight: "420 kg", price: "Rp 26.000.000", slots: 7, filledSlots: 5, status: "Tersedia" },
    { id: 3, type: "Kambing", weight: "35 kg", price: "Rp 3.500.000", slots: 1, filledSlots: 1, status: "Penuh" },
    { id: 4, type: "Kambing", weight: "38 kg", price: "Rp 3.800.000", slots: 1, filledSlots: 0, status: "Tersedia" },
    { id: 5, type: "Sapi", weight: "480 kg", price: "Rp 30.000.000", slots: 7, filledSlots: 3, status: "Tersedia" },
];

let participants = [
    { id: 1, name: "Ahmad Fauzi", phone: "0812-3456-7890", address: "Jl. Masjid No. 12", type: "Sapi", animal: "Sapi #1", amount: "Rp 4.000.000", status: "Lunas" },
    { id: 2, name: "Siti Aminah", phone: "0813-2345-6789", address: "Jl. Kenanga No. 5", type: "Kambing", animal: "Kambing #3", amount: "Rp 3.500.000", status: "Lunas" },
    { id: 3, name: "Muhammad Rizki", phone: "0857-1234-5678", address: "Jl. Melati No. 8", type: "Sapi", animal: "Sapi #2", amount: "Rp 4.000.000", status: "DP" },
    { id: 4, name: "Fatimah Zahra", phone: "0878-9012-3456", address: "Jl. Dahlia No. 15", type: "Sapi", animal: "Sapi #1", amount: "Rp 4.000.000", status: "Lunas" },
    { id: 5, name: "Umar Hadi", phone: "0821-5678-9012", address: "Jl. Anggrek No. 3", type: "Sapi", animal: "Sapi #5", amount: "Rp 4.000.000", status: "Belum Bayar" },
];

// ================================
// Mobile Menu Toggle
// ================================
function toggleMobileMenu() {
    const mobileNav = document.getElementById('mobileNav');
    mobileNav.classList.toggle('active');
}

// ================================
// Animal Management
// ================================
function toggleAnimalForm() {
    const form = document.getElementById('animalForm');
    form.style.display = form.style.display === 'none' ? 'block' : 'none';
}

function renderAnimals() {
    const grid = document.getElementById('animalGrid');
    grid.innerHTML = animals.map((animal, index) => `
        <div class="animal-card" style="animation-delay: ${index * 0.05}s">
            <div class="animal-header">
                <div class="animal-info">
                    <div class="animal-icon ${animal.type.toLowerCase()}">
                        ${animal.type === 'Sapi' ? '🐄' : '🐐'}
                    </div>
                    <div>
                        <div class="animal-name">${animal.type}</div>
                        <div class="animal-weight">${animal.weight}</div>
                    </div>
                </div>
                <span class="badge ${animal.status === 'Penuh' ? 'badge-danger' : 'badge-success'}">
                    ${animal.status}
                </span>
            </div>
            <div class="animal-price">${animal.price}</div>
            <div class="animal-price-per">
                ${animal.type === 'Sapi' ? 'Rp 4.000.000 / slot' : '1 orang'}
            </div>
            <div class="slots-info">
                <span class="slots-label">Slot Terisi</span>
                <span class="slots-value">${animal.filledSlots}/${animal.slots}</span>
            </div>
            <div class="slots-bar">
                <div class="slots-fill" style="width: ${(animal.filledSlots / animal.slots) * 100}%"></div>
            </div>
        </div>
    `).join('');
}

function addAnimal() {
    const type = document.getElementById('animalType').value;
    const weight = document.getElementById('animalWeight').value;
    const price = document.getElementById('animalPrice').value;

    if (weight && price) {
        const newAnimal = {
            id: animals.length + 1,
            type: type,
            weight: weight + ' kg',
            price: 'Rp ' + price,
            slots: type === 'Sapi' ? 7 : 1,
            filledSlots: 0,
            status: 'Tersedia'
        };
        animals.push(newAnimal);
        renderAnimals();
        
        // Reset form
        document.getElementById('animalWeight').value = '';
        document.getElementById('animalPrice').value = '';
        toggleAnimalForm();
    } else {
        alert('Mohon lengkapi semua field!');
    }
}

function deleteAnimal(id) {
    if (confirm('Apakah Anda yakin ingin menghapus hewan ini?')) {
        animals = animals.filter(a => a.id !== id);
        renderAnimals();
    }
}

// ================================
// Participant Management
// ================================
function toggleParticipantForm() {
    const form = document.getElementById('participantForm');
    form.style.display = form.style.display === 'none' ? 'block' : 'none';
}

function renderParticipants(data = participants) {
    const table = document.getElementById('participantTable');
    table.innerHTML = data.map((p, index) => `
        <tr style="animation-delay: ${index * 0.03}s">
            <td>
                <div class="participant-cell">
                    <div class="participant-avatar">${p.name.charAt(0)}</div>
                    <span>${p.name}</span>
                </div>
            </td>
            <td class="hide-mobile">
                <div class="contact-cell">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"></path>
                    </svg>
                    ${p.phone}
                </div>
            </td>
            <td class="hide-tablet">
                <div class="contact-cell">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path>
                        <circle cx="12" cy="10" r="3"></circle>
                    </svg>
                    ${p.address}
                </div>
            </td>
            <td>
                <span class="badge ${p.type === 'Sapi' ? 'badge-success' : 'badge-warning'}">
                    ${p.animal}
                </span>
            </td>
            <td><strong>${p.amount}</strong></td>
            <td>
                <span class="badge ${
                    p.status === 'Lunas' ? 'badge-success' : 
                    p.status === 'DP' ? 'badge-warning' : 'badge-danger'
                }">
                    ${p.status}
                </span>
            </td>
        </tr>
    `).join('');
}

function addParticipant() {
    const name = document.getElementById('participantName').value;
    const phone = document.getElementById('participantPhone').value;
    const address = document.getElementById('participantAddress').value;
    const type = document.getElementById('participantType').value;

    if (name && phone) {
        const newParticipant = {
            id: participants.length + 1,
            name: name,
            phone: phone,
            address: address || '-',
            type: type,
            animal: type === 'Sapi' ? 'Sapi #2' : 'Kambing #4',
            amount: type === 'Sapi' ? 'Rp 4.000.000' : 'Rp 3.800.000',
            status: 'Belum Bayar'
        };
        participants.push(newParticipant);
        renderParticipants();
        
        // Reset form
        document.getElementById('participantName').value = '';
        document.getElementById('participantPhone').value = '';
        document.getElementById('participantAddress').value = '';
        toggleParticipantForm();
    } else {
        alert('Mohon lengkapi nama dan nomor telepon!');
    }
}

function filterParticipants() {
    const query = document.getElementById('searchInput').value.toLowerCase();
    const filtered = participants.filter(p => 
        p.name.toLowerCase().includes(query) || 
        p.phone.includes(query)
    );
    renderParticipants(filtered);
}

// ================================
// Initialize on Page Load
// ================================
document.addEventListener('DOMContentLoaded', function() {
    renderAnimals();
    renderParticipants();
    
    // Close mobile menu when clicking a link
    const mobileLinks = document.querySelectorAll('.nav-mobile a');
    mobileLinks.forEach(link => {
        link.addEventListener('click', function() {
            document.getElementById('mobileNav').classList.remove('active');
        });
    });
    
    // Smooth scroll for anchor links
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function(e) {
            e.preventDefault();
            const target = document.querySelector(this.getAttribute('href'));
            if (target) {
                target.scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });
            }
        });
    });
});
