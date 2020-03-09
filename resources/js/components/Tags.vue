<template>
	<!-- <a :title="photo.tags.map(tag => tag.title).join(', ')">
		<i class="fas fa-tag"></i> {{ photo.tags.length }}
	</a> -->

	<a>
		<i class="fas fa-tag"></i> {{ photo.tags.length }}
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
        color: #17bf63 !important;
    }
</style>