<template>
	<transition name="vue-popover">
        <div class="vue-popover shadow">
            <div class="vue-popover-header bg-dark" v-text="title"></div>
            <div class="vue-popover-body">
            	<slot></slot>
            </div>
        </div>
    </transition>
</template>

<script>
	export default {
		props: ["title"]
	}
</script>

<style scoped>
    .vue-popover {
        position: absolute;
        z-index: 100;
        border-radius: .25rem;
    }

    .vue-popover-header {
        padding: 10px;
        color: #fff;
        border-top-left-radius: .25rem;
        border-top-right-radius: .25rem;
    }

    .vue-popover-body {
        padding: 10px;
        background-color: #fff;
        border-bottom-left-radius: .25rem;
        border-bottom-right-radius: .25rem;
    }

    .vue-popover-enter-active, .vue-popover-leave-active {
        transition: .5s;
    }

    .vue-popover-enter, .vue-popover-leave-to {
        opacity: 0;
    }
</style>