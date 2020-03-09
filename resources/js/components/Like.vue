<template>
	<a @click="like" :class="{ liked: photo.liked }">
    	<i class="fas fa-heart"></i> {{ photo.likes_count }}
    </a>
</template>

<script>
	export default {
		props: ["photo"],
		methods: {
			like() {
                axios.post(`/photos/${this.photo.id}/like`)
                    .then(response => {
                        this.photo.liked = ! this.photo.liked
                        this.photo.liked ? this.photo.likes_count++ : this.photo.likes_count--

                        // this.photo.likes_count += this.photo.liked ? 1 : -1
                    })
            }
		}
	}
</script>

<style scoped>
	a {
        cursor: pointer;
        color: #777 !important;
    }

    a:hover, .liked {
        color: #d9534f !important;
    }
</style>