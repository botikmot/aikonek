<template>
    <div>
        <form>
            <div class="form-group">
                <label for="caption">Caption</label>
                <textarea class="form-control" id="caption" rows="3" placeholder="What's on your mind?"></textarea>
            </div>
            <div class="form-group">
                <label for="image">Image</label>
                <input type="file" class="form-control-file" id="image">
                <small class="form-text text-muted">You can add up to 10 photos or videos.</small>
            </div>
            <div class="form-group">
                <label for="location">Location</label>
                <input type="text" class="form-control" id="location" placeholder="Add a location">
            </div>
            <div class="form-group">
                <label for="privacy">Privacy</label>
                <select class="form-control" id="privacy">
                    <option>Public</option>
                    <option>Friends</option>
                    <option>Only Me</option>
                </select>
            </div>
            <div class="form-group">
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" value="" id="save-to-collection">
                    <label class="form-check-label" for="save-to-collection">
                        Save to Collection
                    </label>
                </div>
            </div>
            <button type="submit" class="btn btn-primary">Post</button>
        </form>
    </div>
</template>

<script>
    export default {
        props: ['user'],
        mounted() {
            console.log('Component mounted.', this.user)
        }
    }
</script>
