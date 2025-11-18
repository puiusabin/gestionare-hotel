<div class="rooms-container">
    <h1>Add New Room</h1>

    <form method="POST" action="/rooms/store">
        <div class="form-group">
            <label for="room_number">Room Number *</label>
            <input type="text" id="room_number" name="room_number" required>
        </div>

        <div class="form-group">
            <label for="room_type">Room Type *</label>
            <select id="room_type" name="room_type" required>
                <option value="">Select type...</option>
                <option value="single">Single</option>
                <option value="double">Double</option>
                <option value="suite">Suite</option>
            </select>
        </div>

        <div class="form-group">
            <label for="capacity">Capacity *</label>
            <input type="number" id="capacity" name="capacity" min="1" required>
        </div>

        <div class="form-group">
            <label for="price_per_night">Price Per Night ($) *</label>
            <input type="number" id="price_per_night" name="price_per_night" step="0.01" min="0" required>
        </div>

        <div class="form-group">
            <label for="description">Description</label>
            <textarea id="description" name="description" rows="4"></textarea>
        </div>

        <div class="form-group">
            <label>
                <input type="checkbox" name="is_available" value="1" checked>
                Available for booking
            </label>
        </div>

        <div style="display: flex; gap: 10px; margin-top: 20px;">
            <button type="submit" class="btn btn-primary">Add Room</button>
            <a href="/rooms" class="btn btn-secondary">Cancel</a>
        </div>
    </form>
</div>
