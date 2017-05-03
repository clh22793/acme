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

					<div v-for="item in searchResults" v-on:click="get_similar(item.id, item.name)" class="column column-block artist-thumb">
						<div class="row">
							<div class="small-3 column">
								<img :src="item.images[0].url" class="thumbnail" alt=""><br>
							</div>

							<div class="small-9 column">
								<p><span>Artist:</span> {[item.name]}<br>
								<span>Popularity:</span> {[item.popularity]}</p>
							</div>
						</div>

						<div class="row">
							<div class="similar-artists" :data-similar-to="item.id"></div>
						</div>
					</div>

				</div>
			</div>
		</div>


		<script>
			var fill_similar_artists = function(selector, data){
				var table_rows = [];

				for(var i=0; i < data.artists.items.length; i++){
					if(data.artists.items[i].images.length == 0){
						data.artists.items[i].images = [{url: "//placehold.it/600x600"}];
					}
					table_rows.push("<tr><td>"+data.artists.items[i].name+"</td></tr>");
				}

				var html = "<h5>Similar Artists</h5><table>"+table_rows.join('');+"</table>";

				html = (data.artists.items.length == 0) ? html + "0 results." : html;

				$(selector).html(html);
			};


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
							fill_similar_artists('.similar-artists[data-similar-to="'+id+'"]', data);
						});
					},
					search: function(event){
						var that = this;
						$('.similar-artists').empty();

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
