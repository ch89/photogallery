<template>
	<p>
		<a href="#" v-text="notificaton.data.name"></a> rated your photo <a href="#" v-text="notificaton.data.photo.title"></a> {{ notificaton.data.rating }} stars
	</p>
</template>

<script>
	export default {
		props: ["notificaton"]
	}
</script>