<template>
	<a @click="$emit('comment')">
    	<i class="fas fa-comment"></i> {{ photo.comments.length }}
    </a>
</template>

<script>
	export default {
		props: ["photo"]
	}
</script>

<style scoped>
	a {
    	cursor: pointer;
    	color: #777 !important;
    }

    a:hover {
        color: #1da1f2 !important;
    }
</style>