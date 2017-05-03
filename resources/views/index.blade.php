<html>
	<head>
		<script
		  src="https://code.jquery.com/jquery-3.2.1.min.js"
		  integrity="sha256-hwg4gsxgFZhOsEEamdOYGBf13FyQuiTwlAQgxVSNgt4="
		  crossorigin="anonymous"></script>
		<!-- Compressed CSS -->
		<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/foundation/6.3.1/css/foundation.min.css" integrity="sha256-itWEYdFWzZPBG78bJOOiQIn06QCgN/F0wMDcC4nOhxY=" crossorigin="anonymous" />

		<!-- Compressed JavaScript -->
		<script src="https://cdnjs.cloudflare.com/ajax/libs/foundation/6.3.1/js/foundation.min.js" integrity="sha256-Nd2xznOkrE9HkrAMi4xWy/hXkQraXioBg9iYsBrcFrs=" crossorigin="anonymous"></script>

		<script src="https://unpkg.com/vue"></script>

	</head>

	<body>

		<style type="text/css">
			#results p span {font-weight:bold;}
			.artist-thumb {cursor:pointer;}
		</style>


		<div id="app" class="row">
			<div class="small-8 small-centered columns">
				<h1>ACME Music Search</h1>

				<form>
					<input type="text" placeholder="enter artist" v-model="txtArtist">
					<button id="search" class="button expanded" @click.prevent="search">Search</button>
				</form>

				<div id="results">

					<h3>{[resultsHeadline]}</h3>

					<div class="row small-up-2 medium-up-3 large-up-3">
					  <div v-for="item in searchResults" v-on:click="get_similar(item.id, item.name)" class="column column-block artist-thumb">
						<img :src="item.images[0].url" class="thumbnail" alt=""><br>
						<p><span>Artist:</span> {[item.name]}<br>
						<span>Popularity:</span> {[item.popularity]}</p>
					  </div>
					</div>
				</div>
			</div>
		</div>


		<script>
			var app = new Vue({
				el: '#app',
				data: {
					txtArtist:null,
					searchResults:null,
					resultsHeadline:null
				},
				methods: {
					get_similar: function(id,name){
						var that = this;

						$.get("/api/similar_to?spotify_id="+id, function(data){
							that.searchResults = data.artists.items;
							that.resultsHeadline = "Artists similar to "+name;

							for(var i=0; i < that.searchResults.length; i++){
								if(that.searchResults[i].images.length == 0){
									that.searchResults[i].images = [{url: "//placehold.it/600x600"}];
								}
							}

						});
					},
					search: function(event){
						var that = this;

						$.get("/api/artists?q="+this.txtArtist, function(data){
							that.searchResults = data.artists.items;
							that.resultsHeadline = "Search results for: "+that.txtArtist;

							for(var i=0; i < that.searchResults.length; i++){
								if(that.searchResults[i].images.length == 0){
									that.searchResults[i].images = [{url: "//placehold.it/600x600"}];
								}
							}

						});

					}
				},
				delimiters: ["{[", "]}"]
			});



		</script>
	</body>
</html>
