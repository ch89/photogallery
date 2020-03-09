<template>
	<div class="rating">
        <a v-for="rating in ratings" @click="rate(rating)" :class="{ rated: rating == photo.rating }">
            <i class="fas fa-star"></i>
        </a>
    </div>
</template>

<script>
	export default {
		props: ["photo"],
		data() {
            return {
                ratings: [5, 4, 3, 2, 1]
            }
        },
		methods: {
			rate(rating) {
                axios.post(`/photos/${this.photo.id}/rate`, { rating })
                    .then(response => this.photo.rating = rating)
            }
		}
	}
</script>

<style scoped>
	.rating {
        display: flex;
        flex-direction: row-reverse;
    }

    .rating :not(:first-child) {
        margin-right: 2px;
    }

    a {
        cursor: pointer;
        color: #777 !important;
        transition: 1s;
    }

    a:hover, a:hover ~ a, .rated, .rated ~ a {
        color: #f2b600 !important;
    }

    .rated, .rated ~ a {
        transform: rotate(360deg);
    }
</style>