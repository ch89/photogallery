<template>
    <nav class="navbar navbar-expand-md navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="#">App</a>
            <button class="navbar-toggler" @click="show = ! show">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" :class="{ show }">
                <ul class="navbar-nav mr-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="/" @click.prevent="$emit('all')">Photos</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/" @click.prevent="$emit('favorites')">Favorites</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/" @click.prevent="$emit('popular', 'likes_count')">Popular</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/" @click.prevent="$emit('popular', 'rating')">Rating</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/" @click.prevent="$emit('create-photo')">Create Photo</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/" @click.prevent="$emit('create-channel')">Create Channel</a>
                    </li>
                </ul>

                <ul class="navbar-nav ml-auto">
                    <template v-if="user">
                        <notifications></notifications>
                        
                        <li class="nav-item" v-if="user">
                            <a href="#" class="nav-link" v-text="user.name"></a>
                        </li>
                    </template>
                    <template v-else>
                        <li class="nav-item">
                            <a href="#" class="nav-link">Login</a>
                        </li>
                        <li class="nav-item">
                            <a href="#" class="nav-link">Register</a>
                        </li>
                    </template>
                </ul>
            </div>
        </div>
    </nav>
</template>

<script>
    import Notifications from "./Notifications"

    export default {
        components: { Notifications },
        props: ["user"],
        data() {
            return {
                show: false
            }
        }
    }
</script>