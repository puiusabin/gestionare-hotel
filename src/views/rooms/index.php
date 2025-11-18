<div class="rooms-container">
    <h1>Available Rooms</h1>

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
                            <a href="/rooms/delete?id=<?php echo $room['id']; ?>" class="btn btn-secondary" onclick="return confirm('Are you sure?')">Delete</a>
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