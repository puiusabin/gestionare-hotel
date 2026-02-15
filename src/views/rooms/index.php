    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Available Rooms</h1>
        <?php if (isLoggedIn() && getCurrentUser()['role'] === 'admin'): ?>
            <div class="btn-group">
                <a href="/export/csv" class="btn btn-outline-success btn-sm">Export CSV</a>
                <a href="/export/pdf" class="btn btn-outline-danger btn-sm">Export PDF</a>
            </div>
        <?php endif; ?>
    </div>

    <?php if (isLoggedIn() && getCurrentUser()['role'] === 'admin' && !empty($typeStats)): ?>
        <div class="row mb-5">
            <div class="col-md-6 offset-md-3">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <h5 class="card-title text-center mb-4">Room Type Distribution</h5>
                        <canvas id="roomStatsChart" width="400" height="200"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <script>
            const ctx = document.getElementById('roomStatsChart').getContext('2d');
            const typeData = <?php echo json_encode($typeStats); ?>;

            new Chart(ctx, {
                type: 'doughnut',
                data: {
                    labels: typeData.map(item => item.room_type.charAt(0).toUpperCase() + item.room_type.slice(1)),
                    datasets: [{
                        label: 'Number of Rooms',
                        data: typeData.map(item => item.count),
                        backgroundColor: [
                            'rgba(54, 162, 235, 0.7)',
                            'rgba(255, 206, 86, 0.7)',
                            'rgba(75, 192, 192, 0.7)'
                        ],
                        borderColor: [
                            'rgba(54, 162, 235, 1)',
                            'rgba(255, 206, 86, 1)',
                            'rgba(75, 192, 192, 1)'
                        ],
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            position: 'bottom',
                        }
                    }
                }
            });
        </script>
    <?php endif; ?>

    <div class="room-filters">
        <a href="/rooms" class="btn <?php echo !isset($_GET['type']) ? 'btn-primary' : 'btn-secondary'; ?>">All</a>
        <a href="/rooms?type=single" class="btn <?php echo (($_GET['type'] ?? '') === 'single') ? 'btn-primary' : 'btn-secondary'; ?>">Single</a>
        <a href="/rooms?type=double" class="btn <?php echo (($_GET['type'] ?? '') === 'double') ? 'btn-primary' : 'btn-secondary'; ?>">Double</a>
        <a href="/rooms?type=suite" class="btn <?php echo (($_GET['type'] ?? '') === 'suite') ? 'btn-primary' : 'btn-secondary'; ?>">Suite</a>
    </div>

    <?php if (empty($rooms)): ?>
        <p>No rooms available.</p>
    <?php else: ?>
        <div class="rooms-grid">
            <?php foreach ($rooms as $room): ?>
                <div class="room-card">
                    <h3>Room <?php echo htmlspecialchars($room['room_number']); ?></h3>
                    <div class="room-details">
                        <p><strong>Type:</strong> <?php echo ucfirst($room['room_type']); ?></p>
                        <p><strong>Capacity:</strong> <?php echo $room['capacity']; ?> guest(s)</p>
                        <p><strong>Price:</strong> $<?php echo number_format($room['price_per_night'], 2); ?> /night</p>
                        <?php if ($room['description']): ?>
                            <p><strong>Description:</strong> <?php echo htmlspecialchars($room['description']); ?></p>
                        <?php endif; ?>
                        <p><strong>Status:</strong>
                            <span class="status-<?php echo $room['is_available'] ? 'available' : 'unavailable'; ?>">
                                <?php echo $room['is_available'] ? 'Available' : 'Unavailable'; ?>
                            </span>
                        </p>
                    </div>
                    <?php if (isLoggedIn() && getCurrentUser()['role'] === 'admin'): ?>
                        <div class="room-actions">
                            <a href="/rooms/edit?id=<?php echo $room['id']; ?>" class="btn btn-secondary">Edit</a>
                            <form method="POST" action="/rooms/delete" style="display:inline;">
                                <input type="hidden" name="csrf_token" value="<?php echo generateCsrfToken(); ?>">
                                <input type="hidden" name="id" value="<?php echo $room['id']; ?>">
                                <button type="submit" class="btn btn-secondary" onclick="return confirm('Are you sure you want to delete this room?')">Delete</button>
                            </form>
                        </div>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

    <?php if (isLoggedIn() && getCurrentUser()['role'] === 'admin'): ?>
        <div style="margin-top: 30px;">
            <a href="/rooms/create" class="btn btn-primary">Add New Room</a>
        </div>
    <?php endif; ?>
</div>