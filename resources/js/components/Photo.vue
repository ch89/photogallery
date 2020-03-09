<template>
	<div class="col-md-6 photo">
        <div class="card">
            <div class="position-relative">
                <!-- <img class="card-img-top" :src="`https://placeimg.com/640/480/any?${photo.id}`" alt="Photo"> -->
                <img :src="photo.image" :alt="photo.title" class="card-img-top">
                <div class="caption" v-text="photo.description"></div>
                <h5 class="card-title" v-text="photo.title" @click="$emit('remove', photo)"></h5>
                <!-- <button class="btn channel" :class="`btn-${photo.channel.color}`" v-text="photo.channel.title" @click="$emit('channel', photo.channel_id)"></button> -->
                <button class="btn channel" :style="style" v-text="photo.channel.title" @click="$emit('channel', photo.channel_id)"></button>
            </div>
            <div class="card-body">
                <!-- <p class="card-text" v-text="photo.description"></p> -->
                <!-- <button v-if="photo.can.delete" class="btn btn-danger btn-sm" @click="$emit('remove', photo)">Remove</button> -->
                <!-- <small class="text-muted" @click="$emit('edit', photo)">{{ photo.user.name }} on {{ photo.created_at }}</small> -->

                <small class="text-muted" @click="show ? close() : edit()">{{ photo.user.name }} on {{ photo.created_at }}</small>

                <popover title="Edit" v-show="show">
                    <form @submit.prevent="update">
                        <div class="form-group">
                            <input type="text" placeholder="Title" class="form-control form-control-sm" :class="{ 'is-invalid': errors.title }" v-model="cache.title">
                            <span v-if="errors.title" v-text="errors.title[0]" class="invalid-feedback"></span>
                        </div>
                        <div class="form-group">
                            <input type="text" placeholder="Description" class="form-control form-control-sm" :class="{ 'is-invalid': errors.description }" v-model="cache.description">
                            <span v-if="errors.description" v-text="errors.description[0]" class="invalid-feedback"></span>
                        </div>
                        <div class="form-group">
                            <select class="form-control form-control-sm mb-2" v-model="cache.channel_id">
                                <option v-for="channel in channels" :value="channel.id" v-text="channel.title"></option>
                            </select>
                        </div>
                        <div class="form-group">
                            <div class="form-check" v-for="tag in tags">
                                <input class="form-check-input" type="checkbox" :value="tag.id" v-model="cache.tag_ids">
                                <label class="form-check-label" v-text="tag.title"></label>
                            </div>
                        </div>

                        <button class="btn btn-primary btn-sm" :class="{ loading }" :disabled="loading">Update</button>
                        <button type="button" class="btn btn-secondary btn-sm" @click="close">Close</button>
                    </form>
                </popover>

                <div class="flex">
                    <div class="icons">
                        <like :photo="photo"></like>
                        <comments :photo="photo" @comment="$emit('comment')"></comments>
                        <!-- <tags :photo="photo"></tags> -->

                        <div class="d-inline-block">
                            <tags :photo="photo" @mouseenter.native="showTags = true" @mouseleave.native="showTags = false"></tags>

                            <popover title="Tags" v-show="showTags && photo.tags.length">
                                <ul class="list-unstyled">
                                    <li v-for="tag in photo.tags" v-text="tag.title"></li>
                                </ul>
                            </popover>
                        </div>
                    </div>
                    <star-rating :photo="photo"></star-rating>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import Like from "./Like"
    import Comments from "./Comments"
    import Tags from "./Tags"
    import StarRating from "./StarRating"

	export default {
        components: { Like, Comments, Tags, StarRating },
		props: ["photo", "channels", "tags"],
        data() {
            return {
                cache: {},
                show: false,
                showTags: false,
                loading: false,
                errors: {}
            }
        },
        computed: {
            style() {
                return {
                    "background-color": this.photo.channel.color,
                    "border-color": this.photo.channel.color
                }
            }
        },
        methods: {
            edit() {
                this.show = true
                Object.assign(this.cache, this.photo)
                this.cache.tag_ids = this.photo.tags.map(tag => tag.id)
            },
            close() {
                this.show = false
                this.errors = {}
            },
            update() {
                this.loading = true

                axios.patch(`/photos/${this.photo.id}`, this.cache)
                    .then(response => {
                        Object.assign(this.photo, response.data)
                        this.close()
                        flash.success("Photo successfully updated!")
                    })
                    .catch(errors => this.errors = errors.response.data.errors)
                    .finally(() => this.loading = false)
            }
        }
	}
</script>

<style scoped>
    .photo {
        transition: 1s;
        padding-bottom: 30px;
    }
    
	.card {
        border: none;
        box-shadow: 0 3px 6px rgba(0, 0, 0, 0.16), 0 3px 6px rgba(0, 0, 0, 0.23);
        /*transition: 1s;*/
    }

    /*.card:hover {
        transform: scale(1.05);
        box-shadow: 0 14px 28px rgba(0, 0, 0, 0.25), 0 10px 10px rgba(0, 0, 0, 0.22);
    }*/

    .card-title {
        position: absolute;
        bottom: 0;
        color: #fff;
        margin-bottom: 0;
        padding: 1.25rem;
        text-shadow: 0 1px 2px rgba(0, 0, 0, 0.8);
        cursor: pointer;
    }

    small {
        display: block;
        margin-bottom: 10px;
    }

    .caption {
        position: absolute;
        top: 0;
        color: #fff;
        background-color: rgba(0, 0, 0, .5);
        width: 100%;
        height: 100%;
        display: flex;
        justify-content: center;
        align-items: center;
        opacity: 0;
        transition: opacity 1s;
    }

    .caption:hover {
        opacity: 1;
    }

    .channel {
        position: absolute;
        right: 1.25rem;
        transform: translateY(-50%);
        box-shadow: 0 3px 6px rgba(0, 0, 0, 0.16), 0 3px 6px rgba(0, 0, 0, 0.23);
        color: #fff;

        display: block;
    }

    .photos-enter, .photos-leave-to {
        opacity: 0;
        transform: scale(1.1);
    }

    .icons > :not(:last-child) {
        margin-right: 3px;
    }

    /*.photos-enter-active {
        animation: bounce 1s;
    }

    .photos-leave-active {
        animation: bounce 1s reverse;
    }

    @keyframes bounce {
        0% {
            transform: scale(0);
        }
        50% {
            transform: scale(1.1);
        }
        100% {
            transform: scale(1);
        }
    }*/
</style>