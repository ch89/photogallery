<template>
	<li class="nav-item dropdown">
		<a class="nav-link" href="#" @click="show = ! show">
      		<i class="fas fa-bell"></i>
      		<span class="badge badge-danger" v-if="notifications.length" v-text="notifications.length"></span>
        </a>
    	<transition name="notifications">
    		<div class="dropdown-menu shadow" v-show="show">
	    		<div class="notification-header bg-dark flex">
	    			<span>Notifications</span>
	    			<a href="#" @click="markAsRead">Mark all as read</a>
	    		</div>
				<div class="notification-item" v-for="notification in notifications">
					<img :src="`https://placeimg.com/50/50/any?${notification.id}`" alt="Avatar">
					<div>
						<component :is="notification.type" :notificaton="notification"></component>
						<small class="text-muted">2 hours ago</small>
					</div>
				</div>
			</div>
		</transition>
	</li>
</template>

<script>
	import PhotoLiked from "./notifications/PhotoLiked"
	import PhotoRated from "./notifications/PhotoRated"

	export default {
		components: { PhotoLiked, PhotoRated },
		data() {
			return {
				notifications: [],
				show: false
			}
		},
		created() {
			axios.get("/notifications")
				.then(response => {
					this.notifications = response.data

					this.show = false
				})
		},
		methods: {
			markAsRead() {
				axios.patch("/notifications")
					.then(response => this.notifications = [])
			}
		}
	}
</script>

<style scoped>
	.dropdown-menu {
		display: block;
		padding: 0;
		border: none;
	}

	.notification-header {
		padding: 15px;
		color: #fff;
	}

	.notification-header a {
		color: #fff;
	}

	.notification-item {
		display: flex;
		padding: 15px;
		white-space: nowrap;
	}

	.notification-item:nth-child(odd) {
		background-color: #f8f9fa;
	}

	img {
		margin-right: 15px;
		border-radius: 50%;
	}

	p {
		margin-bottom: 0;
	}

	.notifications-enter-active, .notifications-leave-active {
		transition: 1s;
	}

	.notifications-enter, .notifications-leave-to {
		opacity: 0;
		/*transform: scale(1.1);*/
		transform: translateY(-100%);
	}
</style>