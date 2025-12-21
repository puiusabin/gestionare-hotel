<div class="rooms-container">
    <h1>Edit Room</h1>

    <form method="POST" action="/rooms/update">
        <?php csrfField(); ?>
        <input type="hidden" name="id" value="<?php echo htmlspecialchars($room['id']); ?>">

        <div class="form-group">
            <label for="room_number">Room Number *</label>
            <input type="text" id="room_number" name="room_number" value="<?php echo htmlspecialchars($room['room_number']); ?>" required>
        </div>

        <div class="form-group">
            <label for="room_type">Room Type *</label>
            <select id="room_type" name="room_type" required>
                <option value="">Select type...</option>
                <option value="single" <?php echo $room['room_type'] === 'single' ? 'selected' : ''; ?>>Single</option>
                <option value="double" <?php echo $room['room_type'] === 'double' ? 'selected' : ''; ?>>Double</option>
                <option value="suite" <?php echo $room['room_type'] === 'suite' ? 'selected' : ''; ?>>Suite</option>
            </select>
        </div>

        <div class="form-group">
            <label for="capacity">Capacity *</label>
            <input type="number" id="capacity" name="capacity" min="1" value="<?php echo htmlspecialchars($room['capacity']); ?>" required>
        </div>

        <div class="form-group">
            <label for="price_per_night">Price Per Night ($) *</label>
            <input type="number" id="price_per_night" name="price_per_night" step="0.01" min="0" value="<?php echo htmlspecialchars($room['price_per_night']); ?>" required>
        </div>

        <div class="form-group">
            <label for="description">Description</label>
            <textarea id="description" name="description" rows="4"><?php echo htmlspecialchars($room['description'] ?? ''); ?></textarea>
        </div>

        <div class="form-group">
            <label>
                <input type="checkbox" name="is_available" value="1" <?php echo $room['is_available'] ? 'checked' : ''; ?>>
                Available for booking
            </label>
        </div>

        <div style="display: flex; gap: 10px; margin-top: 20px;">
            <button type="submit" class="btn btn-primary">Update Room</button>
            <a href="/rooms" class="btn btn-secondary">Cancel</a>
        </div>
    </form>
</div>
