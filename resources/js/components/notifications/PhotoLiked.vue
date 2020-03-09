<template>
	<p>
		<a href="#" v-text="notificaton.data.name"></a> liked your photo <a href="#" v-text="notificaton.data.photo.title"></a>
	</p>
</template>

<script>
	export default {
		props: ["notificaton"]
	}
</script>