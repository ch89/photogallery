<template>
	<transition name="flash">
		<div v-if="message" v-text="message.text" class="alert" :class="`alert-${message.type}`"></div>
	</transition>
</template>

<script>
	export default {
		data() {
			return {
				message: null
			}
		},
		created() {
			bus.$on("flash", message => {
				this.message = message

				setTimeout(() => this.message = null, 3000)
			})
		}
	}
</script>

<style scoped>
	.alert {
		position: fixed;
		right: 25px;
		bottom: 25px;
		z-index: 100;
		transition: 1s;
	}

	.flash-enter, .flash-leave-to {
		opacity: 0;
		transform: translateX(100%);
	}
</style>