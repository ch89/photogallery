<template>
	<nav v-if="photos.last_page > 1">
  		<ul class="pagination">
	    	<li class="page-item" :class="{ disabled: photos.current_page == 1 }">
	    		<a class="page-link" href="#" @click="$emit('change', photos.current_page - 1)">Previous</a>
	    	</li>
	    	<li class="page-item" :class="{ disabled: photos.current_page == photos.last_page }">
	    		<a class="page-link" href="#" @click="$emit('change', photos.current_page + 1)">Next</a>
	    	</li>
  		</ul>
	</nav>
</template>

<script>
	export default {
		props: ["photos"]
	}
</script>