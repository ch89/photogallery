<template>
	<div v-if="expired" v-text="countdown.message"></div>
	<div v-else>
		<span>{{ days }} Days,</span>
		<span>{{ hours }} Hours,</span>
		<span>{{ minutes }} Minutes,</span>
		<span>{{ seconds }} Seconds</span>
		<span>until <strong v-text="countdown.message"></strong></span>
	</div>
</template>

<script>
	export default {
		props: ["countdown"],
		data() {
			return {
				days: 0,
				hours: 0,
				minutes: 0,
				seconds: 0,
				expired: false
			}
		},
		created() {
			let until = new Date(this.countdown.until).getTime()

			let timer = setInterval(() => {
				let remaining = until - new Date().getTime()

				if(remaining <= 0) {
  					clearInterval(timer)
  					this.expired = true
  				}
  				else {
					this.days = Math.floor(remaining / (1000 * 60 * 60 * 24))
	  				this.hours = Math.floor((remaining % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60))
					this.minutes = Math.floor((remaining % (1000 * 60 * 60)) / (1000 * 60))
	  				this.seconds = Math.floor((remaining % (1000 * 60)) / 1000)
	  			}
			}, 1000)
		}
	}
</script>