import Vue from "vue"
import axios from "axios"
import Pusher from "pusher-js"
import Echo from "laravel-echo"
import Navbar from "./components/Navbar"
import Modal from "./components/Modal"
import Popover from "./components/Popover"
import Countdown from "./components/Countdown"
import Pagination from "./components/Pagination"
import Sort from "./components/Sort"
import Photo from "./components/Photo"
import Flash from "./components/Flash"

window.axios = axios

axios.defaults.headers.common["X-Requested-With"] = "XMLHttpRequest"

let token = document.head.querySelector("meta[name='csrf-token']")
axios.defaults.headers.common["X-CSRF-TOKEN"] = token.content

window.Echo = new Echo({
    broadcaster: "pusher",
    key: process.env.MIX_PUSHER_APP_KEY,
    cluster: process.env.MIX_PUSHER_APP_CLUSTER,
    encrypted: true
})

Array.prototype.remove = function(item) {
    this.splice(this.indexOf(item), 1)
}

window.bus = new Vue
window.flash = {
    success(text) {
        this.message(text, "success")
    },
    danger(text) {
        this.message(text, "danger")
    },
    message(text, type) {
        bus.$emit("flash", { text, type })
    }
}

Vue.component("navbar", Navbar)
Vue.component("modal", Modal)
Vue.component("popover", Popover)
Vue.component("countdown", Countdown)
Vue.component("pagination", Pagination)
Vue.component("sort", Sort)
Vue.component("flash", Flash)

let app = new Vue({
    el: "#app",
    components: { Photo },
    data: {
    	photos: { data: [] },
    	photo: { tag_ids: [] },
        active: {},
        commentedPhoto: null,
        comment: {},
        channels: [],
        channel: {
            color: "#ff0000"
        },
        users: [],
        tags: [],
    	show: false,
        showChannelModal: false,
    	errors: null,
        loading: false,
        params: { tag_ids: [] },
        countdowns: [],
        countdown: {}
    },
    created() {
        this.getPhotos()
        this.getChannels()
        this.getUsers()
        this.getTags()
        this.getCountdowns()

        // window.Echo.channel("photos")
        //     .listen("NewPhoto", e => this.photos.data.push(e.photo))
        //     .listen("RemovePhoto", e => this.photos.data.remove(e.photo))
    },
    methods: {
        getPhotos() {
            axios.get("photos", { params: this.params })
                .then(response => this.photos = response.data)
        },
        getChannels() {
            axios.get("channels")
                .then(response => {
                    this.channels = response.data

                    this.photo.channel_id = this.channels[0].id
                })
        },
        getUsers() {
            axios.get("users")
                .then(response => this.users = response.data)
        },
        getTags() {
            axios.get("tags").then(response => this.tags = response.data)
        },
        getCountdowns() {
            axios.get("countdowns").then(response => this.countdowns = response.data)
        },
        all() {
            this.params = { tag_ids: [] }

            this.getPhotos()
        },
        favorites() {
            this.params.favorites = true

            this.getPhotos()
        },
        popular(column) {
            // this.params.sort = "likes_count"
            this.params.sort = column
            delete this.params.direction

            this.getPhotos()
        },
        getPhotosByChannel(channel_id) {
            this.params.channel_id = channel_id

            this.getPhotos()
        },
        change(page) {
            this.params.page = page

            this.getPhotos()
        },
    	add() {
            this.loading = true

    		axios.post("photos", this.photo)
    			.then(response => {
    				this.photos.data.push(response.data)

    				this.close()
    			})
    			.catch(errors => this.errors = errors.response.data.errors)
                .finally(() => this.loading = false)
    	},
        async addWithImage(e) {
            let data = new FormData(e.target)

            let response = await axios.post("/photos", data)
            
            this.photos.data.push(response.data)

            this.close()
        },
    	close() {
    		this.show = false
    		this.photo = { 
                channel_id: this.channels[0].id,
                tag_ids: [] 
            }
            this.active = {}
    		this.errors = null
    	},
        edit(photo) {
            this.show = true
            this.active = photo
            Object.assign(this.photo, photo)
            this.photo.tag_ids = photo.tags.map(tag => tag.id)
        },
        update() {
            this.loading = true

            axios.patch(`/photos/${this.photo.id}`, this.photo)
                .then(response => {
                    // Version 1
                    Object.assign(this.active, response.data)

                    // Version 2
                    // Object.assign(this.active, this.photo)
                    // this.active.channel = this.channels.find(channel => channel.id == this.photo.channel_id)

                    // Version 3
                    // this.getPhotos()

                    this.close()
                })
                .catch(error => this.errors = error.response.data.errors)
                .finally(() => this.loading = false)
        },
    	remove(photo) {
    		axios.delete(`/photos/${photo.id}`)
    			.then(response => this.photos.data.remove(photo))
    	},
        sort(key) {
            this.params.direction = this.params.sort == key && this.params.direction == "asc" ? "desc" : "asc";

            this.params.sort = key

            this.getPhotos()
        },
        follow(user) {
            axios.post(`/users/${user.id}/follow`)
                .then(response => {
                    user.followed = ! user.followed

                    this.getPhotos()
                })
        },
        addComment() {
            axios.post(`/photos/${this.commentedPhoto.id}/comments`, this.comment)
                .then(response => this.commentedPhoto.comments.push(response.data))
        },
        removeComment(comment) {
            axios.delete(`/comments/${comment.id}`)
                .then(response => this.commentedPhoto.comments.remove(comment))
        },
        addCountdown() {
            axios.post("countdowns", this.countdown)
                .then(response => {
                    this.countdowns.push(response.data)
                    this.countdown = {}
                })
        },
        async addChannel() {
            let response = await axios.post("/channels", this.channel)

            this.channels.push(response.data)

            this.closeChannelModal()  
        },
        closeChannelModal() {
            this.showChannelModal = false

            this.channel = {
                color: "#ff0000"
            }
        }
    }
})