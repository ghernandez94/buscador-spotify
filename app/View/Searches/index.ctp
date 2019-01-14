<div id="appSearch" class="mt-4">
    <div v-cloak>
        <audio id="audio-player" type="audio/mpeg" v-on:ended="audioEnded"></audio>

        <div class="mb-3">
            <label for="input-textSearched" class="h5">
                Busca en Spotify (canciones, artistas y álbumes)
            </label>
            <input id="input-textSearched" v-model="textSearched" class="form-control" placeholder="Ej.: Calvin Harris" autofocus>
            <h6 class="mt-2">{{textStatus}}</h5>
        </div>
        <div class="row">
            <div class="col-lg-6">
                <div class="my-3">
                    <h5 v-if="artists.items && artists.items.length > 0">Artistas</h5>
                    <ul class="list-unstyled">
                        <artist-item v-for="artist in artists.items"
                                    v-bind:artist="artist"
                                    v-bind:key="artist.id">
                        </artist-item>
                        <button v-if="artists.next" v-on:click="seeMore('artist')" class="btn btn-link">Ver más</button>
                    </ul>
                    <br>
                    <h5 v-if="albums.items && albums.items.length > 0">Álbumes</h5>
                    <ul class="list-unstyled">
                        <album-item v-for="album in albums.items"
                                    v-bind:album="album"
                                    v-bind:key="album.id">
                        </album-item>
                        <button v-if="albums.next" v-on:click="seeMore('album')" class="btn btn-link">Ver más</button>
                    </ul>
                    <br>
                    <h5 v-if="tracks.items && tracks.items.length > 0">Canciones</h5>
                    <ul class="list-unstyled">
                        <track-item v-for="track in tracks.items"
                                    v-bind:track="track"
                                    v-bind:key="track.id">
                        </track-item>
                        <button v-if="tracks.next" v-on:click="seeMore('track')" class="btn btn-link">Ver más</button>
                    </ul>
                </div>
            </div>
            <div class="offset-lg-2 col-lg-4 border-left">
                <div id="artist">
                    <div class="text-center mb-3">
                        <img v-if="artist.images && artist.images.length > 0" :src="artist.images[0].url" class="img-thumbnail" height="200" width="200">
                        <span class="h5 my-2 d-block">{{artist.name}}</span>
                        <small class="d-block" v-if="artist_albums.length > 0">Álbumes de <strong>{{artist.name}}</strong></small>
                    </div>
                    <ul class="list-unstyled">
                        <album-item v-for="album in artist_albums"
                                    v-bind:album="album"
                                    v-bind:key="album.id">
                        </album-item>
                    </ul>
                </div>
                <div id="album">
                    <div class="text-center mb-3">
                        <img v-if="album.images && album.images.length > 0" :src="album.images[0].url" class="img-thumbnail" height="200" width="200">
                        <span class="h5 my-2 d-block">{{album.name}}</span>
                        <small class="d-block" v-if="album.artists">Álbum de <strong>{{album.artists[0].name}}</strong></small>
                    </div>
                    <ul class="list-unstyled">
                        <album-track-item v-for="track in album_tracks"
                                    v-bind:track="track"
                                    v-bind:key="track.id">
                        </album-track-item>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<?php 
    echo $this->Html->script('vue.min');
    echo $this->Html->script('lodash.min');
    echo $this->Html->script('axios.min');
    echo $this->Html->script('search');

    echo $this->fetch('script');
?>