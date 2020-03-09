<template>
	<transition name="modal">
		<div class="modal">
	  		<div class="modal-dialog">
	    		<div class="modal-content">
		      		<div class="modal-header">
		        		<h5 class="modal-title" v-text="title"></h5>
		        		<button type="button" class="close" @click="$emit('close')">&times;</button>
		      		</div>
		      		<div class="modal-body">
		        		<slot></slot>
		      		</div>
		      		<div class="modal-footer">
		        		<slot name="footer"></slot>
		        		<button type="button" class="btn btn-secondary" @click="$emit('close')">Close</button>
		      		</div>
	    		</div>
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
	.modal {
		display: block;
		background-color: rgba(0, 0, 0, .5);
		transition: opacity 1s;
	}

	.modal-content {
		transition: transform 1s;
	}

	.modal-enter, .modal-leave-to {
		opacity: 0
	}

	.modal-enter .modal-content, .modal-leave-to .modal-content {
		transform: scale(1.1);
	}
</style>