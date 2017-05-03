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

		<div id="app" class="row">
			<div class="small-8 small-centered columns">
				<h1>ACME Music Search</h1>

				{[message]}

				<form>
					<input type="text" placeholder="enter artist" v-model="txtArtist">
					<button id="search" class="button expanded" @click.prevent="search">Search</button>
				</form>

				<div id="results">

					<div class="row small-up-2 medium-up-3 large-up-3">
					  <div v-for="item in searchResults" class="column column-block">
						<img :src="item.images[0].url" class="thumbnail" alt=""><br>
						<p>Artist: {[item.name]}<br>
						Popularity: {[item.popularity]}</p>
					  </div>
					</div>

				</div>
			</div>
		</div>


		<script>
			var app = new Vue({
				el: '#app',
				data: {
					message: "hello, world",
					txtArtist:null,
					searchResults:null
				},
				methods: {
					search: function(event){
						var that = this;

						$.get("/api/artists?q="+this.txtArtist, function(data){
							//console.log(data);
							that.searchResults = data.artists.items;

							for(var i=0; i < that.searchResults.length; i++){
								if(that.searchResults[i].images.length == 0){
									that.searchResults[i].images = [{url: "//placehold.it/600x600"}];
								}
							}

							console.log(that.searchResults);
						});

					}
				},
				delimiters: ["{[", "]}"]
			});



		</script>
	</body>
</html>
