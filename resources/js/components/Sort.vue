<template>
	<div class="dropdown">
        <button class="btn btn-secondary dropdown-toggle" @click="show = ! show">Sort</button>
        <div class="dropdown-menu" v-show="show">
            <a class="dropdown-item" href="#" @click.prevent="$emit('sort', 'title')">Title</a>
            <a class="dropdown-item" href="#" @click.prevent="$emit('sort', 'likes_count')">Popular</a>
            <a class="dropdown-item" href="#" @click.prevent="$emit('sort', 'created_at')">Newest</a>
        </div>
    </div>
</template>

<script>
	export default {
		data() {
			return {
				show: false
			}
		}
	}
</script>

<style scoped>
	.dropdown-menu {
		display: block;
	}
</style>